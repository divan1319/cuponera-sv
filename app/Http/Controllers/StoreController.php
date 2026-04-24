<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    // 1. Catálogo de Ofertas
    public function catalog()
    {
        $offers = Offer::where('expires_at', '>', now())
                      ->where('stock', '>', 0)
                      ->get();
        return response()->json($offers);
    }

    // 2. Agregar al Carrito (Simulación en Sesión)
    public function addToCart(Request $request, $id)
    {
        $offer = Offer::findOrFail($id);
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "title" => $offer->title,
                "quantity" => 1,
                "price" => $offer->price
            ];
        }

        session()->put('cart', $cart);
        return response()->json(['message' => 'Agregado al carrito', 'cart' => $cart]);
    }

    // 3. Simulación de Compra
    public function checkout()
    {
        $cart = session()->get('cart');

        if (!$cart) {
            return response()->json(['error' => 'El carrito está vacío'], 400);
        }

        // Simulación: Reducir stock y limpiar sesión
        foreach ($cart as $id => $details) {
            $offer = Offer::find($id);
            if ($offer && $offer->stock >= $details['quantity']) {
                $offer->decrement('stock', $details['quantity']);
            }
        }

        session()->forget('cart');

        return response()->json(['message' => 'Compra realizada con éxito (Simulación)']);
    }
}