<?php

namespace Themes\DefaultTheme\src\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Basket;
use App\Models\Carrier;
use App\Models\Gateway;
use App\Models\Installment;
use App\Models\Product;
use App\Models\WidgetOption;
use App\Models\Province;
use App\Models\User;
use App\Models\Widget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Shetabit\Payment\Facade\Payment;

class MainController extends Controller
{
    public function index(Request $request)
    {

        $date_time = [
            'date_widget' => optional(
                WidgetOption::where('widget_id', 43)->where('key', 'end_date')->first()
            )->value,

            'time_widget' => optional(
                WidgetOption::where('widget_id', 43)->where('key', 'end_time')->first()
            )->value,
        ];


        $widgets = Widget::with('options')
            ->where('theme', current_theme_name())
            ->where('is_active', true)
			->where('plugin',0)
            ->orderBy('ordering')
            ->get();
        if ($request->user()) {
            $label_ids = $request->user()
                ->orders()
                ->with('items.product.labels')
                ->get()
                ->pluck('items')
                ->flatten()
                ->unique('products')
                ->pluck('product')
                ->flatten()
                ->pluck('labels')
                ->flatten()
                ->pluck('id')
                ->toArray();
            if (count($label_ids)) {
                $syncedProducts = Product::query()
                    ->withCount(['labels' => function ($q) use ($label_ids) {
                        $q->whereIn('labels.id', $label_ids);
                    }])->whereHas('labels', function ($q) use ($label_ids) {
                        $q->whereIn('labels.id', $label_ids);
                    })->orderByDesc('labels_count')
                    ->limit(15)
                    ->published()
                    ->get();
            } else {
                $syncedProducts = [];
            }
        } else {
            $syncedProducts = [];
        }
        $basketList = Basket::query()
            ->limit(15)
            ->get();

$products = Product::whereNotNull('publish_in_index')
    ->orderBy('title_en', 'asc')
    ->get();

        return view('front::index', compact('widgets', 'basketList', 'syncedProducts','products','date_time'));
    }

    public function checkReferralCode(Request $request)
    {
        $this->validate($request, [
            'code' => ['required', 'string'],
        ]);
        $exists = User::query()->where('referral_code', $request->code)->exists();
        return response()->json(['is_success' => $exists , 'msg' => $exists ? "کد معرف معتبر میباشد" : "کد معرف معتبر نمی باشد"]);
    }

    public function checkout()
    {
        $cart = auth()->user()->cart;
        $gateways = Gateway::active()->orderBy('ordering')->get();

        if (!$cart || !$cart->products->count() || !check_cart_quantity()) {
            return redirect()->route('front.cart');
        }

        $discount_status = check_cart_discount();

        $provinces = Province::active()->orderBy('ordering')->get();
        $wallet = auth()->user()->getWallet();
        $city_id = auth()->user()->address ? auth()->user()->address->city_id : null;
        /*$carriers = Carrier::active()->latest()->get();*/
		$carriers = Carrier::get();

        $installments = Installment::query()->active()->where(function($q)use($cart){
			$q->doesntHave('products')->orWhereHas('products',function($q)use($cart){
				foreach($cart->products as $product){
					$q->where('id',$product->id);
				}
			});
		})->get();

        return view('front::checkout', compact(
            'provinces',
            'discount_status',
            'gateways',
            'wallet',
            'city_id',
            'installments',
            'carriers'
        ));
    }

    public function getPrices(Request $request)
    {
        $cart = auth()->user()->cart;

        if ($request->city_id) {
            $request->validate([
                'city_id' => 'required|exists:cities,id',
            ]);
        }

        if ($request->carrier_id) {
            $request->validate([
                'carrier_id' => 'required|exists:carriers,id',
            ]);
        }

        $carriers = Carrier::active()->latest()->get();

        return [
            'checkout_sidebar' => view('front::partials.checkout-sidebar', [
                'city_id' => $request->city_id,
                'carrier_id' => $request->carrier_id
            ])->render(),

            'carriers_container' => view('front::partials.carriers-container', [
                'city_id' => $request->city_id,
                'cart' => $cart,
                'carrier_id' => $request->carrier_id,
                'carriers' => $carriers
            ])->render(),
        ];
    }

    public function captcha()
    {
        return response(['captcha' => captcha_src('flat')]);
    }
}
