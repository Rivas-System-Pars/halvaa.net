<?php

namespace Themes\DefaultTheme\src\Controllers;

use App\Events\OrderCreated;
use App\Events\OrderPaid;
use App\Http\Controllers\Controller;
use App\Jobs\CancelOrder;
use App\Models\Basket;
use App\Models\Gateway;
use App\Models\Installment;
use App\Models\Order;
use App\Models\OrderInstallmentItem;
use App\Models\Product;
use App\Models\ProductGift;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Sina;
use App\Models\Wallet;
use App\Models\WalletHistory;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Shetabit\Payment\Facade\Payment;
use Shetabit\Multipay\Invoice;
use Themes\DefaultTheme\src\Requests\StoreOrderRequest;
use Throwable;
use App\Jobs\SendInstallmentSms;

class OrderController extends Controller
{
	public function prePayVerify(Request $request,Order $order){
		$transactionId = session()->get('transactionId');
        $amount = session()->get('amount');

        $transaction = Transaction::where('status', false)->where('transID', $transactionId)->firstOrFail();


        $order = $transaction->transactionable;
		$gateway = $request->gateway;
        $gateway_configs = get_gateway_configs($gateway);
		unset($gateway_configs['callbackUrl']);

        try {
            $receipt = Payment::via($gateway)->config($gateway_configs);

            if ($amount) {
                $receipt = $receipt->amount(intval($amount));
            }

            $receipt = $receipt->transactionId($transactionId)->verify();

            DB::table('transactions')->where('transID', (string)$transactionId)->update([
                'status' => 1,
                'traceNumber' => $receipt->getReferenceId(),
                'message' => $transaction->message . '<br>' . 'پرداخت موفق با درگاه ' . $gateway,
                'updated_at' => Carbon::now(),
            ]);
			$order->update([
				'status' => 'paid',
			]);

            return redirect()->route('front.orders.show', ['order' => $order])->with('message', 'ok');
        } catch (\Exception $exception) {

            DB::table('transactions')->where('transID', (string)$transactionId)->update([
                'message' => $transaction->message . '<br>' . $exception->getMessage(),
                "updated_at" => Carbon::now(),
            ]);

            return redirect()->route('front.orders.show', ['order' => $order])->with('transaction-error', $exception->getMessage());
        }
	}

	public function prePay(Order $order, Request $request)
    {
        if ($order->user_id != auth()->user()->id || is_null($order->installment)) {
            abort(404);
        }

        if ($order->status != 'unpaid') {
            return redirect()->route('front.orders.show', ['order' => $order])->with('error', 'سفارش شما لغو شده است یا قبلا پرداخت کرده اید');
        }

        if ($order->price == 0) {
            return $this->orderPaid($order);
        }

        $gateways = Gateway::active()->pluck('key')->toArray();

        $request->validate([
            'gateway' => 'required|in:wallet,' . implode(',', $gateways)
        ]);

        $gateway = $request->gateway;

        if ($gateway == 'wallet') {
            return $this->payUsingWallet($order);
        }
		$amount = $order->installment->prepayment;

        try {

            $gateway_configs = get_gateway_configs($gateway);
			$gateway_configs['callbackUrl']=route('front.orders.pre-pay-verify',[$order->id,'gateway'=>$gateway]);

            return Payment::via($gateway)->config($gateway_configs)->purchase(
                (new Invoice)->amount(intval($order->installment->prepayment)),
                function ($driver, $transactionId) use ($order, $gateway, $request,$amount) {
                    DB::table('transactions')->insert([
                        'status' => false,
                        'amount' => $amount,
                        'factorNumber' => $order->id,
                        'mobile' => auth()->user()->username,
                        'message' => 'پرداخت پیش قسط ' . $gateway,
                        'transID' => (string)$transactionId,
                        'token' => (string)$transactionId,
                        'user_id' => auth()->user()->id,
                        'transactionable_type' => Order::class,
                        'transactionable_id' => $order->id,
                        'gateway_id' => Gateway::where('key', $gateway)->first()->id,
                        "created_at" => Carbon::now(),
                        "updated_at" => Carbon::now(),
                    ]);
					$order->installment->update([
						'transID' => (string)$transactionId,
					]);

                    session()->put('transactionId', (string)$transactionId);
                    session()->put('amount', $amount);
                }
            )->pay()->render();
        } catch (Exception $e) {
            return redirect()
                ->route('front.orders.show', ['order' => $order])
                ->with('transaction-error', $e->getMessage())
                ->with('order_id', $order->id);
        }
    }

	public function verifyInstallment(Request $request,Order $order,$installment){
		$transactionId = session()->get('transactionId');
        $amount = session()->get('amount');

        $transaction = Transaction::where('status', false)->where('transID', $transactionId)->firstOrFail();

		$installment=$order->installment->items()->findOrFail($installment);
		$firstUnpaidItem = $order->installment->firstUnpaidItem;
		if ($order->user_id != auth()->user()->id || $installment->id != $firstUnpaidItem->id) {
            abort(404);
        }
		if ($installment->status == \App\Models\OrderInstallmentItem::STATUS_PAID) {
            return redirect()->route('front.orders.show', ['order' => $order])->with('error', 'قسط را قبلا پرداخت کرده اید');
        }

        $order = $transaction->transactionable;
		$gateway = $request->gateway;
        $gateway_configs = get_gateway_configs($gateway);
		unset($gateway_configs['callbackUrl']);

        try {
            $receipt = Payment::via($gateway)->config($gateway_configs);

            if ($amount) {
                $receipt = $receipt->amount(intval($amount));
            }

            $receipt = $receipt->transactionId($transactionId)->verify();

            DB::table('transactions')->where('transID', (string)$transactionId)->update([
                'status' => 1,
                'traceNumber' => $receipt->getReferenceId(),
                'message' => $transaction->message . '<br>' . 'پرداخت موفق با درگاه ' . $gateway,
                'updated_at' => Carbon::now(),
            ]);
			$installment->update([
				'status'=>\App\Models\OrderInstallmentItem::STATUS_PAID,
			]);

            return redirect()->route('front.orders.show', ['order' => $order])->with('message', 'ok');
        } catch (\Exception $exception) {

            DB::table('transactions')->where('transID', (string)$transactionId)->update([
                'message' => $transaction->message . '<br>' . $exception->getMessage(),
                "updated_at" => Carbon::now(),
            ]);

            return redirect()->route('front.orders.show', ['order' => $order])->with('transaction-error', $exception->getMessage());
        }
	}

	public function payInstallment(Request $request,Order $order,$installment){
		$installment=$order->installment->items()->findOrFail($installment);
		$firstUnpaidItem = $order->installment->firstUnpaidItem;
		if ($order->user_id != auth()->user()->id || $installment->id != $firstUnpaidItem->id) {
            abort(404);
        }
		if ($installment->status == \App\Models\OrderInstallmentItem::STATUS_PAID) {
            return redirect()->route('front.orders.show', ['order' => $order])->with('error', 'قسط را قبلا پرداخت کرده اید');
        }
		$gateways = Gateway::active()->pluck('key')->first();
		if (is_null($gateways)) {
            return redirect()->route('front.orders.show', ['order' => $order])->with('error', 'درگاه پرداخت فعالی وجود ندارد');
        }
		$gateway = $gateways;


		try {
			$gateway_configs = get_gateway_configs($gateway);
			$gateway_configs['callbackUrl']=route('front.orders.verify-installment',[$order->id,$installment->id,'gateway'=>$gateway]);
            return Payment::via($gateway)->config($gateway_configs)->purchase(
                (new Invoice)->amount(intval($installment->getTotalAmount())),
                function ($driver, $transactionId) use ($order, $gateway,$installment, $request) {
                    DB::table('transactions')->insert([
                        'status' => false,
                        'amount' => $installment->getTotalAmount(),
                        'factorNumber' => $order->id,
                        'mobile' => auth()->user()->username,
                        'message' => "پرداخت قسط",
                        'transID' => (string)$transactionId,
                        'token' => (string)$transactionId,
                        'user_id' => auth()->user()->id,
                        'installemnt_id' => $installment->id,
                        'transactionable_type' => Order::class,
                        'transactionable_id' => $order->id,
                        'gateway_id' => Gateway::where('key', $gateway)->first()->id,
                        "created_at" => Carbon::now(),
                        "updated_at" => Carbon::now(),
                    ]);

                    session()->put('transactionId', (string)$transactionId);
                    session()->put('amount', $installment->getTotalAmount());
                }
            )->pay()->render();
        } catch (Exception $e) {
            return redirect()
                ->route('front.orders.show', ['order' => $order])
                ->with('transaction-error', $e->getMessage())
                ->with('order_id', $order->id);
        }
	}

    public function index()
    {
        $orders = auth()->user()->orders()->latest()->paginate(10);

        return view('front::user.orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order)
    {
        if ($order->user_id != auth()->user()->id) {
            abort(404);
        }
        $gateways = Gateway::active()->get();
        $wallet = auth()->user()->getWallet();
        $order->load(['items' => function ($q) use ($request) {
            $q->with(['returned' => function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            }]);
        }]);
        return view('front::user.orders.show', compact(
            'order',
            'gateways',
            'wallet'
        ));
    }

    public function store(StoreOrderRequest $request)
    {
        $referralUser = null;
        if ($request->filled('referral_code')) {
            $referralUser = User::query()->where('referral_code', $request->referral_code)->first();
        }
        try {
            return DB::transaction(function() use($referralUser,$request){
					$user = auth()->user();

				$cart = $user->cart;

				if (!$cart || !$cart->products->count() || !check_cart_quantity()) {
					return redirect()->route('front.cart');
				}

				if (!check_cart_discount()['status']) {
					return redirect()->route('front.checkout');
				}

				$gateway = Gateway::where('key', $request->gateway)->first();
				$data = $request->validated();
				$final_price = $cart->finalPrice($request->city_id, $request->carrier_id);
				$discount_amount = $cart->totalDiscount();
				$products = $cart->products()->with(['gifts.products:id'])->get();
				$discountPriority = collect(json_decode(option('discount_priority'), true))->sort();

				$discount_factor_rows = collect(json_decode(Cache::get('options.discount_factor_rows'), true));
				if (Cache::get('options.discount_factor_rows')
					&& $discount_factor_rows->count()
					&& $discount_factor_row = $discount_factor_rows->sortByDesc('count')->first(function ($item) use ($products) {
						return $item['count'] <= $products->count();
					})) {
					if (array_key_exists('is_percent', $discount_factor_row) && $discount_factor_row['is_percent']) {
						$discount = ($final_price / 100) * $discount_factor_row['discount_amount'];
						$final_price = $final_price - $discount;
						$discount_amount = $discount_amount + $discount;
					} else {
						if ($discount_factor_row['discount_amount'] >= $final_price) {
							$final_price = 0;
						} else {
							$final_price = $final_price - $discount_factor_row['discount_amount'];
						}
						$discount_amount = $discount_amount + $discount_factor_row['discount_amount'];
					}
				}

				$cash_discounts = collect(json_decode(Cache::get('options.cash_discounts'), true));
				if ($request->settlement_type == Order::SETTLEMENT_TYPE_CASH
					&& Cache::get('options.cash_discounts')
					&& $cash_discounts->count()
					&& $cash_discount = $cash_discounts->sortByDesc('min_amount')->first(function ($item) use ($final_price) {
						return $item['min_amount'] <= $final_price;
					})) {
					if (array_key_exists('is_percent', $cash_discount) && $cash_discount['is_percent']) {
						$discount = ($final_price / 100) * $cash_discount['discount_amount'];
						$final_price = $final_price - $discount;
						$discount_amount = $discount_amount + $discount;
					} else {
						if ($cash_discount['discount_amount'] >= $final_price) {
							$final_price = 0;
						} else {
							$final_price = $final_price - $cash_discount['discount_amount'];
						}
						$discount_amount = $discount_amount + $cash_discount['discount_amount'];
					}
				}

				$per_purchases = collect(json_decode(Cache::get('options.per_purchases'), true));
				if (Cache::get('options.per_purchases')
					&& $per_purchases->count()
					&& $per_purchase = $per_purchases->sortByDesc('min_amount')->first(function ($item) use ($final_price) {
						return $item['min_amount'] <= $final_price;
					})) {
					if (array_key_exists('is_percent', $per_purchase) && $per_purchase['is_percent']) {
						$discount = ($final_price / 100) * $per_purchase['discount_amount'];
						$final_price = $final_price - $discount;
						$discount_amount = $discount_amount + $discount;
					} else {
						if ($per_purchase['discount_amount'] >= $final_price) {
							$final_price = 0;
						} else {
							$final_price = $final_price - $per_purchase['discount_amount'];
						}
						$discount_amount = $discount_amount + $per_purchase['discount_amount'];
					}
				}

				foreach ($products as $product) {
					$discount_per_purchase = collect($product->discount_per_purchase)->sortByDesc('quantity')->first(function ($i) use ($product) {
						return $i['quantity'] <= $product->pivot->quantity;
					});
				}

				$discount_per_purchase_discount = $products->mapWithKeys(function ($item) {
					$discount_per_purchase = collect($item->discount_per_purchase)->sortByDesc('quantity')->first(function ($i) use ($item) {
						return $i['quantity'] <= $item->pivot->quantity;
					});
					if ($item->discount_per_purchase && $discount_per_purchase) {
						$try = floor($item->pivot->quantity / $discount_per_purchase['quantity']);
						$sum = 0;
						foreach (range(0, $try) as $i) {
							$sum += $this->_calculateDiscountPerPurchaseProduct($discount_per_purchase, $item->prices()->find($item->pivot->price_id)->discount_price * $discount_per_purchase['quantity']);
						}
						return [$item->id => $sum];
					} else {
						return [$item->id => 0];
					}
				});
				$discount_per_purchase_discount_sum = $discount_per_purchase_discount->sum();

				if ($discount_per_purchase_discount_sum) {
					if ($discount_per_purchase_discount_sum >= $final_price) {
						$final_price = 0;
					} else {
						$final_price = $final_price - $discount_per_purchase_discount_sum;
					}
					$discount_amount = $discount_amount + $discount_per_purchase_discount_sum;
				}

				$data['shipping_cost'] = $cart->shippingCostAmount($request->city_id, $request->carrier_id);
				$data['price'] = $final_price;
				$data['status'] = 'unpaid';
				$data['discount_amount'] = $discount_amount;
				$data['discount_id'] = $cart->discount_id;
				$data['user_id'] = $user->id;



				if ($gateway) {
					$data['gateway_id'] = $gateway->id;
				}

				if ($referralUser) {
					$data['referral_id'] = $referralUser->id;
				}

				/*$carrier_result = $cart->canUseCarrier($request->carrier_id, $request->city_id);

				if ($cart->hasPhysicalProduct() && !$carrier_result['status']) {
					return redirect()->back()->withInput()->withErrors([
						'carrier_id' => $carrier_result['message'],
					]);
				}*/

				$sumBenefit = 0;
				$order = Order::create($data);

				if ($referralUser) {
					$records = auth()->user()->cart
						->products()
						->select(['products.id', 'products.category_id'])
						->with('price')
						->get()
						->groupBy('category_id')
						->filter(function ($item, $key) use ($referralUser) {
							return $referralUser->referralCategories->contains($key);
						})->map(function ($item, $key) use ($referralUser) {
							return (collect($item)->map(function ($item) {
										return $item->price->discountPrice();
									})->sum() / 100) * $referralUser->referralCategories->first(function ($item) use ($key) {
									return $item->id == $key;
								})->pivot->percentage;
						});
					$sumBenefit = $sumBenefit + $records->sum();
					foreach ($records as $key => $record) {
						$order->referralCategories()->create([
							'category_id' => $key,
							'referral_benefit' => $record,
						]);
					}
				}

				/** @var Installment $installment */
				$installmentFound=Installment::query()->active()->where(function($q)use($cart){
					$q->doesntHave('products')->orWhereHas('products',function($q)use($cart){
						foreach($cart->products as $product){
							$q->where('id',$product->id);
						}
					});
				})->find($request->settlement_type);

				if ($request->filled('settlement_type') && is_numeric($request->settlement_type) && $installmentFound) {
					$installmentItem = $order->installment()->create([
						'installment_id' => $installmentFound->id,
						'period' => $installmentFound->period,
						'prepayment' => $installmentFound->getPrepayment($final_price),
						'fee' => $installmentFound->getFeeTotal($final_price),
					]);
					if($installmentItem->fee == 0){
						$order->update([
							'status' => 'paid',
						]);
					}
					$start = $installmentItem->fee == 0 ? 0 : 1;
					$end=$installmentItem->fee == 0 ? ($installmentFound->installments_count - 1) : $installmentFound->installments_count;
					foreach (range($start, $end) as $i) {
						$date = $installmentItem->fee == 0 && $i == 0 ? jdate()->toCarbon() : jdate()->addMonths($installmentFound->period * $i)->toCarbon();
						$installmentItem->items()->create([
							'amount' => $installmentFound->getInstallment($final_price),
							'fee' => $installmentFound->getFee($final_price),
							'date' => $date,
							'status' => OrderInstallmentItem::STATUS_UNPAID,
						]);
						if(!$date->isToday()){
							if(is_numeric(trim(option('installment_before_day')))){
								SendInstallmentSms::dispatch($installmentItem,213977)->delay($date->subDays(option('installment_before_day'))->hour(9));
							}
							if(is_numeric(trim(option('installment_day')))){
								SendInstallmentSms::dispatch($installmentItem,213980)->delay($date->hour(9));
							}
							if(is_numeric(trim(option('installment_after_day')))){
								SendInstallmentSms::dispatch($installmentItem,213986)->delay($date->addDays(trim(option('installment_after_day')))->hour(9));
							}
						}
					}
				}

				$baskets = $products->pluck('pivot.basket_id')->unique();
				if ($baskets) {
					$items = Basket::query()->select(['id'])
						->whereIn('id', $baskets)
						->withCount('products')
						->get()
						->filter(function ($item) use ($products) {
							return $item['products_count'] == $products->where('pivot.basket_id', $item->id)->count();
						})->map(function ($i) {
							return $i->gifts->mapWithKeys(function ($item) use ($i) {
								return [
									$item->id => [
										'quantity' => $item->pivot->quantity,
										'basket_id' => $i->id,
									]
								];
							})->toArray();
						});
					foreach ($items as $item) {
						$order->productGifts()->attach($item);
					}
				}
				//add cart products to order
				foreach ($products as $product) {

					/** @var ProductGift $gift */
					if ($gift = $product->gifts->sortBy('quantity')
						->where('quantity', '<=', $product->pivot->quantity)
						->first()) {
						foreach ($gift->products as $p) {
							$order->productGifts()->attach($p->id, [
								'quantity' => $p->pivot->quantity,
								'product_id' => $product->id,
							]);
						}
					}
					$price = $product->prices()->find($product->pivot->price_id);

					if ($price) {
						$disocunt_diff = $price->tomanPrice() - $price->discountPrice();
						$referral_benefit = null;
						if ($referralUser && $referralUser->referralProducts->contains($product->id)) {
							$referralProduct = $referralUser->referralProducts->first(function ($i) use ($product) {
								return $i->id == $product->id;
							});
							$referral_benefit = ($price->discountPrice() / 100) * $referralProduct->pivot->percentage;
							$sumBenefit = $sumBenefit + $referral_benefit;
						}
						$order->items()->create([
							'product_id' => $product->id,
							'title' => $product->title,
							'price' => $price->discountPrice(),
							'real_price' => $price->tomanPrice(),
							'quantity' => $product->pivot->quantity,
							'discount' => $price->discount,
							'price_id' => $product->pivot->price_id,
							'referral_benefit' => $referral_benefit,
							'global_discount' => $discount_per_purchase_discount && $discount_per_purchase_discount->count() && array_key_exists($product->id, $discount_per_purchase_discount->toArray()) && $discount_per_purchase_discount->toArray()[$product->id] ? $discount_per_purchase_discount->toArray()[$product->id] + $disocunt_diff : $disocunt_diff,
						]);

						$price->update([
							'stock' => $price->stock - $product->pivot->quantity
						]);
					}
				}

				if ($referralUser && $sumBenefit) {
					Wallet::query()->updateOrCreate([
						'user_id' => $referralUser->id,
					], [
						'user_id' => $referralUser->id,
						'balance' => optional($referralUser->wallet)->balance + $sumBenefit,
					]);
				}

				// cancel order after $hour hours
				$hour = option('order_cancel', 1);
				CancelOrder::dispatch($order)->delay(now()->addHours($hour));

				event(new OrderCreated($order));
				$cart->delete();
				if ($request->settlement_type == Order::SETTLEMENT_TYPE_CASH) {
					return $this->pay($order, $request);
				} else {

					return redirect()->route('front.orders.show', ['order' => $order]);
				}
			});
        } catch (Throwable $e) {

            toastr()->error("خطا در انجام عملیات");

            return redirect()->route('front.checkout');
        }
    }

    public function _calculateDiscountPerPurchaseProduct($discount_per_purchase, $price)
    {
        if (array_key_exists('is_percent', $discount_per_purchase) && $discount_per_purchase['is_percent']) {
            return ($price / 100) * $discount_per_purchase['discount_amount'];
        } else {
            return $discount_per_purchase['discount_amount'];
        }
    }

    function closest($array, $price)
    {
        foreach ($array as $k => $v) {
            $diff[abs($v - $price)] = $k;
        }
        ksort($diff, SORT_NUMERIC);
        $closest_key = current($diff);
        return array($closest_key, $array[$closest_key]);
    }

    public function pay(Order $order, Request $request)
    {
        if ($order->user_id != auth()->user()->id) {
            abort(404);
        }

        if ($order->status != 'unpaid') {
            return redirect()->route('front.orders.show', ['order' => $order])->with('error', 'سفارش شما لغو شده است یا قبلا پرداخت کرده اید');
        }

        if ($order->price == 0) {
            return $this->orderPaid($order);
        }

        $gateways = Gateway::active()->pluck('key')->toArray();

        $request->validate([
            'gateway' => 'required|in:wallet,' . implode(',', $gateways)
        ]);

        $gateway = $request->gateway;

        if ($gateway == 'wallet') {
            return $this->payUsingWallet($order);
        }

        if($gateway != 'sina_bank'){
            try {
                $gateway_configs = get_gateway_configs($gateway);

                return Payment::via($gateway)->config($gateway_configs)->callbackUrl(route('front.orders.verify', ['gateway' => $gateway]))->purchase(
                    (new Invoice)->amount(intval($order->price)),
                    function ($driver, $transactionId) use ($order, $gateway, $request) {
                        DB::table('transactions')->insert([
                            'status' => false,
                            'amount' => $order->price,
                            'factorNumber' => $order->id,
                            'mobile' => auth()->user()->username,
                            'message' => 'تراکنش ایجاد شد برای درگاه ' . $gateway,
                            'transID' => (string)$transactionId,
                            'token' => (string)$transactionId,
                            'user_id' => auth()->user()->id,
                            'settlement_type' => $request->settlement_type,
                            'transactionable_type' => Order::class,
                            'transactionable_id' => $order->id,
                            'gateway_id' => Gateway::where('key', $gateway)->first()->id,
                            "created_at" => Carbon::now(),
                            "updated_at" => Carbon::now(),
                        ]);

                        session()->put('transactionId', (string)$transactionId);
                        session()->put('amount', $order->price);
                    }
                )->pay()->render();
            } catch (Exception $e) {
                return redirect()
                    ->route('front.orders.show', ['order' => $order])
                    ->with('transaction-error', $e->getMessage())
                    ->with('order_id', $order->id);
            }
        }

        $sina = new Sina();

        $sina->sendSalerequest($order->id,$order->price);
    }

    public function verify($gateway)
    {
        $transactionId = session()->get('transactionId');
        $amount = session()->get('amount');

        $transaction = Transaction::where('status', false)->where('transID', $transactionId)->firstOrFail();

        $order = $transaction->transactionable;

        $gateway_configs = get_gateway_configs($gateway);

        try {
            $receipt = Payment::via($gateway)->config($gateway_configs);

            if ($amount) {
                $receipt = $receipt->amount(intval($amount));
            }

            $receipt = $receipt->transactionId($transactionId)->verify();

            DB::table('transactions')->where('transID', (string)$transactionId)->update([
                'status' => 1,
                'amount' => $order->price,
                'factorNumber' => $order->id,
                'mobile' => $order->mobile,
                'traceNumber' => $receipt->getReferenceId(),
                'message' => $transaction->message . '<br>' . 'پرداخت موفق با درگاه ' . $gateway,
                'updated_at' => Carbon::now(),
            ]);
            $user = auth()->user();
            $cart = $user->cart;
            $cart->delete();

            return $this->orderPaid($order);
        } catch (\Exception $exception) {

            $this->orderUnpaidStock();

            DB::table('transactions')->where('transID', (string)$transactionId)->update([
                'message' => $transaction->message . '<br>' . $exception->getMessage(),
                "updated_at" => Carbon::now(),
            ]);

            return redirect()->route('front.orders.show', ['order' => $order])->with('transaction-error', $exception->getMessage());
        }
    }

    private function payUsingWallet(Order $order)
    {
        $wallet = $order->user->getWallet();
        $amount = intval($wallet->balance() - $order->price);

        if ($amount >= 0) {
            $result = $order->payUsingWallet();

            if ($result) {
                return $this->orderPaid($order);
            }
        }

        $gateway = Gateway::active()->orderBy('ordering')->first();
        $amount = abs($amount);

        if (!$gateway) {
            return redirect()->route('front.orders.show', ['order' => $order])
                ->with('transaction-error', 'درگاه فعالی برای پرداخت یافت نشد')
                ->with('order_id', $order->id);
        }

        $history = $wallet->histories()->create([
            'type' => 'deposit',
            'amount' => $amount,
            'description' => 'شارژ آنلاین کیف پول برای ثبت سفارش',
            'source' => 'user',
            'status' => 'fail',
            'order_id' => $order->id
        ]);

        try {
            $gateway = $gateway->key;
            $gateway_configs = get_gateway_configs($gateway);

            return Payment::via($gateway)->config($gateway_configs)->callbackUrl(route('front.wallet.verify', ['gateway' => $gateway]))->purchase(
                (new Invoice)->amount($amount),
                function ($driver, $transactionId) use ($history, $gateway, $amount) {
                    DB::table('transactions')->insert([
                        'status' => false,
                        'amount' => $amount,
                        'factorNumber' => $history->id,
                        'mobile' => auth()->user()->username,
                        'message' => 'تراکنش ایجاد شد برای درگاه ' . $gateway,
                        'transID' => $transactionId,
                        'token' => $transactionId,
                        'user_id' => auth()->user()->id,
                        'transactionable_type' => WalletHistory::class,
                        'transactionable_id' => $history->id,
                        'gateway_id' => Gateway::where('key', $gateway)->first()->id,
                        "created_at" => Carbon::now(),
                        "updated_at" => Carbon::now(),
                    ]);

                    session()->put('transactionId', $transactionId);
                    session()->put('amount', $amount);
                }
            )->pay()->render();
        } catch (Exception $e) {
            return redirect()->route('front.orders.show', ['order' => $order])
                ->with('transaction-error', $e->getMessage())
                ->with('order_id', $order->id);
        }
    }

    private function orderPaid(Order $order)
    {
        $order->update([
            'status' => 'paid',
        ]);

        event(new OrderPaid($order));

        return redirect()->route('front.orders.show', ['order' => $order])->with('message', 'ok');
    }

    public function orderUnpaidStock()
    {
        $user = auth()->user();
        $cart = $user->cart;

        $products = $cart->products()->with(['gifts.products:id'])->get();

        foreach ($products as $product) {

            $price = $product->prices()->find($product->pivot->price_id);

            $price->update([
                'stock' => $price->stock + $product->pivot->quantity
            ]);
        }
        return true;
    }
}
