#!/usr/bin/env bash

# =============================================================================
# inicializar.sh — Bootstrap de proyecto Laravel + Sail (La Cuponera SV)
# Requisitos: Docker (con Docker Compose) instalado. NO necesita PHP ni Composer.
# Uso: bash inicializar.sh
# =============================================================================

set -e

# ---------------------------------------------------------------------------
# Colores para output
# ---------------------------------------------------------------------------
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
BOLD='\033[1m'
NC='\033[0m'

info()    { echo -e "${CYAN}[INFO]${NC}  $1"; }
success() { echo -e "${GREEN}[OK]${NC}    $1"; }
warn()    { echo -e "${YELLOW}[WARN]${NC}  $1"; }
error()   { echo -e "${RED}[ERROR]${NC} $1"; exit 1; }
step()    { echo -e "\n${BOLD}$1${NC}"; }

# ---------------------------------------------------------------------------
# Detección de sistema operativo
#   - En Git Bash / MSYS / Cygwin (Windows) necesitamos `pwd -W` para obtener
#     la ruta estilo Windows (C:/...) y la ruta del contenedor con doble barra
#     (//var/www/html) para evitar que MSYS la traduzca.
#   - En Linux y macOS usamos `pwd` normal, una sola barra y además pasamos
#     --user para que los archivos generados no queden como root.
# ---------------------------------------------------------------------------
case "$(uname -s)" in
    MINGW*|MSYS*|CYGWIN*)
        export MSYS_NO_PATHCONV=1
        HOST_PWD_CMD="pwd -W"
        CONTAINER_PATH="//var/www/html"
        DOCKER_USER_ARGS=()
        ;;
    *)
        HOST_PWD_CMD="pwd"
        CONTAINER_PATH="/var/www/html"
        DOCKER_USER_ARGS=(--user "$(id -u):$(id -g)")
        ;;
esac

# ---------------------------------------------------------------------------
# 1. Verificar que Docker esté disponible y corriendo
# ---------------------------------------------------------------------------
step "── 1. Verificando Docker ──────────────────────────────────"
command -v docker >/dev/null 2>&1 || error "Docker no encontrado. Instálalo desde https://www.docker.com"
docker info >/dev/null 2>&1       || error "Docker está instalado pero no está corriendo. Ábrelo e intenta de nuevo."
success "Docker disponible."

# ---------------------------------------------------------------------------
# 2. Moverse al directorio del proyecto
# ---------------------------------------------------------------------------
APP_DIR="${APP_DIR:-$(cd "$(dirname "$0")" && pwd)}"
cd "$APP_DIR"
info "Directorio: $APP_DIR"

# ---------------------------------------------------------------------------
# 3. Crear .env si no existe
# ---------------------------------------------------------------------------
step "── 2. Configuración de entorno ────────────────────────────"
if [ ! -f ".env" ]; then
    cp .env.example .env
    success ".env creado desde .env.example"
else
    info ".env ya existe, se omite."
fi

# ---------------------------------------------------------------------------
# 4. Instalar dependencias PHP con contenedor temporal (sin PHP local)
# ---------------------------------------------------------------------------
step "── 3. Instalando dependencias PHP ─────────────────────────"
if [ ! -d "vendor" ]; then
    info "Descargando imagen PHP+Composer (primera vez puede tardar)..."
    docker run --rm \
        --pull=missing \
        "${DOCKER_USER_ARGS[@]}" \
        -e HOME=/tmp \
        -e COMPOSER_HOME=/tmp/.composer \
        -v "$($HOST_PWD_CMD):$CONTAINER_PATH" \
        -w "$CONTAINER_PATH" \
        laravelsail/php84-composer:latest \
        composer install --ignore-platform-reqs --no-interaction --prefer-dist
    success "Dependencias instaladas."
else
    info "vendor/ ya existe, se omite composer install."
fi

# ---------------------------------------------------------------------------
# 5. Generar APP_KEY si está vacía
# ---------------------------------------------------------------------------
step "── 4. APP_KEY ──────────────────────────────────────────────"
APP_KEY_VALUE=$(grep -E '^APP_KEY=' .env | cut -d '=' -f2-)
if [ -z "$APP_KEY_VALUE" ]; then
    info "Generando APP_KEY..."
    docker run --rm \
        "${DOCKER_USER_ARGS[@]}" \
        -e HOME=/tmp \
        -e COMPOSER_HOME=/tmp/.composer \
        -v "$($HOST_PWD_CMD):$CONTAINER_PATH" \
        -w "$CONTAINER_PATH" \
        laravelsail/php84-composer:latest \
        php artisan key:generate --force
    success "APP_KEY generada."
else
    info "APP_KEY ya configurada, se omite."
fi

# ---------------------------------------------------------------------------
# 6. Levantar contenedores con docker compose
#    (sail no es compatible con Git Bash / MINGW64)
# ---------------------------------------------------------------------------
step "── 5. Levantando contenedores ─────────────────────────────"
info "Iniciando Laravel + PostgreSQL (primera vez puede tardar)..."
WWWUSER=0 WWWGROUP=0 docker compose up -d
success "Contenedores levantados."

# ---------------------------------------------------------------------------
# 7. Esperar a que PostgreSQL esté listo
# ---------------------------------------------------------------------------
step "── 6. Esperando a PostgreSQL ───────────────────────────────"
info "Esperando que la base de datos esté lista..."

DB_USER=$(grep -E '^DB_USERNAME=' .env | cut -d '=' -f2-)
DB_NAME=$(grep -E '^DB_DATABASE=' .env  | cut -d '=' -f2-)

RETRIES=20
until docker compose exec -T pgsql pg_isready -q -U "$DB_USER" -d "$DB_NAME" 2>/dev/null; do
    RETRIES=$((RETRIES - 1))
    if [ "$RETRIES" -le 0 ]; then
        error "PostgreSQL no respondió a tiempo. Revisa: docker compose logs pgsql"
    fi
    echo -n "."
    sleep 2
done
echo ""
success "PostgreSQL listo."

# ---------------------------------------------------------------------------
# 8. Ejecutar migraciones
# ---------------------------------------------------------------------------
step "── 7. Migraciones ──────────────────────────────────────────"
docker compose exec -T laravel.test php artisan migrate --force
success "Migraciones ejecutadas."

# ---------------------------------------------------------------------------
# 9. Ejecutar seeders (roles: Admin, Empresa, Cliente)
# ---------------------------------------------------------------------------
step "── 8. Seeders ──────────────────────────────────────────────"
docker compose exec -T laravel.test php artisan db:seed --force
success "Roles insertados (Admin, Empresa, Cliente)."

# ---------------------------------------------------------------------------
# 10. Resumen final
# ---------------------------------------------------------------------------
echo ""
echo -e "${GREEN}${BOLD}╔══════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}${BOLD}║   La Cuponera SV está lista                  ║${NC}"
echo -e "${GREEN}${BOLD}╚══════════════════════════════════════════════╝${NC}"
echo ""
echo -e "  ${BOLD}App:${NC}        http://localhost"
echo -e "  ${BOLD}Registro:${NC}   http://localhost/empresa/registro"
echo -e "  ${BOLD}Login:${NC}      http://localhost/login"
echo ""
echo -e "  ${BOLD}Comandos útiles:${NC}"
echo -e "    ${CYAN}docker compose stop${NC}                                    — Detener"
echo -e "    ${CYAN}WWWUSER=0 WWWGROUP=0 docker compose up -d${NC}             — Volver a levantar"
echo -e "    ${CYAN}docker compose exec laravel.test php artisan migrate${NC}   — Re-migrar"
echo -e "    ${CYAN}docker compose exec laravel.test php artisan tinker${NC}    — Consola interactiva"
echo -e "    ${CYAN}docker compose logs -f${NC}                                 — Ver logs"
echo -e "    ${CYAN}docker compose exec laravel.test bash${NC}                  — Shell del contenedor"
echo ""
echo -e "  ${YELLOW}Tip:${NC} Para aprobar tu empresa en la BD:"
echo -e "    ${CYAN}docker compose exec laravel.test php artisan tinker${NC}"
echo -e "    >>> App\Models\Empresa::first()->update(['estado_solicitud'=>'Aprobada']);"
echo ""
