<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Rol::insert([
            ['nombre' => 'Admin'],
            ['nombre' => 'Empresa'],
            ['nombre' => 'Cliente'],
        ]);
    }
}
