<?php

namespace Themes\DefaultTheme\src\Controllers;

use App\Models\ReturnedProduct;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReturnProductController extends \App\Http\Controllers\Controller
{
    public function show(Request $request, $order, $product)
    {
        $order = $request->user()->orderItems()->with(['returned' => function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        }])->where([
            'order_id' => $order,
            'product_id' => $product,
        ])->firstOrFail();
        return view('front::user.orders.return.return', compact('order'));
    }

    public function store(Request $request, $order, $product)
    {
        $order = $request->user()->orderItems()->whereDoesntHave('returned', function ($q)use ($request) {
            $q->where('user_id', $request->user()->id);
        })->where([
            'order_id' => $order,
            'product_id' => $product,
        ])->firstOrFail();
        $this->validate($request, [
            'type' => ['required', Rule::in(ReturnedProduct::TYPES)],
            'description' => ['nullable', 'string'],
        ]);
        ReturnedProduct::query()->create([
            'user_id' => $request->user()->id,
            'product_id' => $order->product_id,
            'order_id' => $order->order_id,
            'type' => $request->type,
            'description' => $request->description,
        ]);
        toastr()->success("ثبت مرجوعی با موفقیت انجام شد");
        return redirect()->route('front.orders.show', $order->order_id);
    }
}
