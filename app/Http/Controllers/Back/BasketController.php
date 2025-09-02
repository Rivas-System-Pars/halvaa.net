<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Resources\Datatable\Basket\BasketCollection;
use App\Models\Basket;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BasketController extends Controller
{
    public function index(Request $request)
    {
        return view('back.basket.index');
    }

    public function create()
    {
        $products = Product::query()
            ->select(['id', 'title'])
            ->published()
            ->get();
        return view('back.basket.create', compact('products'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'requirements' => ['required', 'array', 'min:1'],
            'requirements.*' => ['exists:' . Product::class . ',id'],
            'gifts' => ['required', 'array', 'min:1'],
            'gifts.*' => ['required', 'array:product_id,quantity', 'min:1'],
            'gifts.*.product_id' => ['required', 'exists:' . Product::class . ',id'],
            'gifts.*.quantity' => ['required', 'numeric', 'min:1'],
        ]);
        try {
            return DB::transaction(function () use ($request) {
                /** @var Basket $basket */
                $basket = Basket::query()->create([
                    'user_id' => $request->user()->id,
                    'title' => $request->title,
                    'description' => $request->description,
                ]);
                $basket->products()->attach($request->requirements);
                $basket->gifts()->attach(collect($request->gifts)->mapWithKeys(function ($item) {
                    return [$item['product_id'] => [
                        'quantity' => $item['quantity']
                    ]];
                })->toArray());
                toastr()->success('سبد خرید با موفقیت ثبت شد.');
                return redirect()->route('admin.baskets.index');
            });
        } catch (\Throwable $e) {
            toastr()->error('خطا در انجام عملیات');
            return redirect()->back();
        }
    }

    public function update(Request $request, $basket)
    {
        $basket = Basket::query()->findOrFail($basket);
        $this->validate($request, [
            'title' => ['required', 'string'],
            'requirements' => ['required', 'array', 'min:1'],
            'description' => ['nullable', 'string'],
            'requirements.*' => ['exists:' . Product::class . ',id'],
            'gifts' => ['required', 'array', 'min:1'],
            'gifts.*' => ['required', 'array:product_id,quantity', 'min:1'],
            'gifts.*.product_id' => ['required', 'exists:' . Product::class . ',id'],
            'gifts.*.quantity' => ['required', 'numeric', 'min:1'],
        ]);
        try {
            return DB::transaction(function () use ($request,$basket) {
                /** @var Basket $basket */
                $basket->update([
                    'title' => $request->title,
                    'description' => $request->description,
                ]);
                $basket->products()->sync($request->requirements);
                $basket->gifts()->sync(collect($request->gifts)->mapWithKeys(function ($item) {
                    return [$item['product_id'] => [
                        'quantity' => $item['quantity']
                    ]];
                })->toArray());
                toastr()->success('سبد خرید با موفقیت ویرایش شد.');
                return redirect()->route('admin.baskets.index');
            });
        } catch (\Throwable $e) {
            toastr()->error('خطا در انجام عملیات');
            return redirect()->back();
        }
    }

    public function edit($basket)
    {
        $basket = Basket::query()->findOrFail($basket);
        $products = Product::query()
            ->select(['id', 'title'])
            ->published()
            ->get();
        return view('back.basket.edit', compact('basket', 'products'));
    }

    public function destroy(Request $request, $basket)
    {
        $basket = Basket::query()->findOrFail($basket);
        $basket->delete();
        return response("success");
    }

    public function apiIndex(Request $request)
    {
        $baskets = Basket::query();
        return new BasketCollection(datatable($request, $baskets));
    }

    public function multipleDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:' . Basket::class . ',id',
        ]);
        Basket::query()->whereIn('id', $request->ids)->delete();
        return response('success');
    }
}
