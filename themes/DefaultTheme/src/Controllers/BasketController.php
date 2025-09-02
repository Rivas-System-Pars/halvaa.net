<?php

namespace Themes\DefaultTheme\src\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Basket;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;

class BasketController extends Controller
{
    public function show(Request $request, $basket)
    {
        $basket = Basket::query()->findOrFail($basket);
        return view('front::basket.show', compact('basket'));
    }

    public function addToCart(Request $request, $basket)
    {
        $basket = Basket::query()->findOrFail($basket);
        $cart = $this->getCart($request);
        /** @var Collection $products */
        $products = $basket->products()->has('price')->with('price')->get();
        try {
            $cart->products()->syncWithoutDetaching($products->mapWithKeys(function ($item) use ($basket) {
                return [
                    $item->id => [
                        'quantity' => 1,
                        'price_id' => $item->price->id,
                        'basket_id' => $basket->id,
                    ]
                ];
            })->toArray());
            return redirect()->route('front.cart');
        } catch (\Throwable $e) {
            toastr()->error("خطا در انجام عملیات");
            return redirect()->back();
        }
    }

    private function getCart(Request $request)
    {
        $cart = null;

        if ($request->user()) {
            $cart = $request->user()->getCart();
        } else {

            $cart_id = $request->header('cart-id');

            if ($cart_id) {
                try {
                    $cart_id = Crypt::decryptString($cart_id);
                    $cart = Cart::find($cart_id);
                } catch (\Throwable $e) {
                    $cart_id = null;
                }
            }
        }

        if (!$cart) {
            $cart = Cart::create();
        }

        return $cart;
    }
}
