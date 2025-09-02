@extends('front::layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ theme_asset('css/vendor/nouislider.min.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('css/vendor/nice-select.css') }}">
@endpush

@section('wrapper-classes', 'shopping-page')
@section('content')
    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">

            <form id="checkout-form" action="{{ route('front.orders.store') }}" class="setting_form" method="POST">
                @csrf
                <div class="row">

                    <div class="cart-page-content col-xl-9 col-lg-8 col-12 px-0">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        @if(!$discount_status['status'])
                            <div class="alert alert-danger" role="alert">
                                <p>کد تخفیف وارد شده معتبر نیست.</p>
                                <span>{{ $discount_status['message'] }}</span>
                            </div>
                        @endif

                        <div class="section-title text-sm-title title-wide no-after-title-wide mb-0 px-res-1">
                            <h2>انتخاب آدرس تحویل سفارش</h2>
                        </div>
                        <section class="page-content dt-sl">
                            <div class="form-ui dt-sl pt-4 pb-4 checkout-div">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 mb-2">
                                        <div class="form-row-title">
                                            <h4>
                                                نام و نام خانوادگی <sup class="text-danger">*</sup>
                                            </h4>
                                        </div>
                                        <div class="form-row form-group">
                                            <input class="input-ui pr-2 text-right"
                                                   type="text"
                                                   name="name" value="{{ old('name') ?: auth()->user()->fullname }}"
                                                   placeholder="نام خود را وارد نمایید">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-2">
                                        <div class="form-row-title">
                                            <h4>
                                                شماره موبایل <sup class="text-danger">*</sup>
                                            </h4>
                                        </div>
                                        <div class="form-row form-group">
                                            <input
                                                class="input-ui pl-2 dir-ltr text-left"
                                                type="text"
                                                name="mobile" value="{{ old('mobile') ?? "09123456789" }}"
                                                placeholder="09xxxxxxxxx">
                                        </div>
                                    </div>

                                    @if ($cart->hasPhysicalProduct())

                                        <div class="col-md-6 col-sm-12 mb-2">
                                            <div class="form-row-title">
                                                <h4>
                                                    استان <sup class="text-danger">*</sup>
                                                </h4>
                                            </div>
                                            <div class="form-row form-group">
                                                <div class="custom-select-ui">
                                                    <select class="right" name="province_id" id="province">
                                                        <option value="">انتخاب کنید</option>

                                                        @foreach ($provinces as $province)
                                                            <option value="{{ $province->id }}"
                                                                    @if(auth()->user()->address && auth()->user()->address->province->id == $province->id) selected @endif>
                                                                {{ $province->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-2">
                                            <div class="form-row-title">
                                                <h4>
                                                    شهر <sup class="text-danger">*</sup>
                                                </h4>
                                            </div>
                                            <div class="form-row form-group">
                                                <div class="custom-select-ui ">
                                                    <select class="right" name="city_id" id="city"
                                                            data-action="{{ route('front.checkout.prices') }}">
                                                        <option value="">انتخاب کنید</option>

                                                        @if(auth()->user()->address)

                                                            @foreach (auth()->user()->address->province->cities()->active()->orderBy('ordering')->get() as $city)
                                                                <option value="{{ $city->id }}"
                                                                        @if($city->id == auth()->user()->address->city->id) selected @endif>{{ $city->name }}</option>
                                                            @endforeach

                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-row-title">
                                                <h4>
                                                    آدرس پستی <sup class="text-danger">*</sup>
                                                </h4>
                                            </div>
                                            <div class="form-row form-group">
                                                    <textarea
                                                        class="input-ui pr-2 text-right"
                                                        name="address"
                                                        placeholder=" آدرس تحویل گیرنده را وارد نمایید">{{ strlen(user_address('address')) ? user_address('address') : "خیابان اصلی کوچه فرعی" }}</textarea>
                                            </div>
                                        </div>

                                    @endif
                                    <div class="col-md-6 mb-2">
                                        <div class="form-row-title">
                                            <h4>
                                                توضیحات سفارش
                                            </h4>
                                        </div>
                                        <div class="form-row">
                                                <textarea
                                                    class="input-ui pr-2 text-right"
                                                    name="description">{{ old('description') }}</textarea>
                                        </div>
                                    </div>

                                    @if ($cart->hasPhysicalProduct())
                                        <div class="col-md-6 mb-2">
                                            <div class="form-row-title">
                                                <h4>
                                                    کد پستی <sup class="text-danger">*</sup>
                                                </h4>
                                            </div>
                                            <div class="form-row form-group">
                                                <input
                                                    class="input-ui pl-2 dir-ltr text-left placeholder-right"
                                                    type="text" pattern="\d*"
                                                    name="postal_code"
                                                    value="{{ strlen(user_address('postal_code')) ? user_address('postal_code') : '1234567890' }}"
                                                    placeholder=" کد پستی را بدون خط تیره بنویسید">
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-12 mb-2">
                                        <div class="checkout-invoice">
                                            <div class="checkout-invoice-headline">
                                                <div class="custom-control custom-checkbox pr-0 form-group d-flex align-items-center">
                                                    {{-- <input id="agreement" name="agreement" @if(old('agreement')) checked
                                                           @endif type="checkbox" value="2" class="custom-control-input custom-control-input2 "
                                                           required> --}}
													<input id="agreement" name="agreement" @if(old('agreement')) checked
                                                           @endif type="checkbox" value="2" class="custom-control-input2 "
                                                           required>
                                                    <label for="agreement" class="custom-control-label p-0 px-1 ">با قوانین سایت
                                                        موافقم</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <section class="page-content dt-sl pt-2">
                                <div class="section-title text-sm-title title-wide no-after-title-wide mb-0 px-res-1">
                                    <h2>انتخاب شیوه تسویه حساب</h2>
                                </div>

                                <div class="dt-sn pt-3 pb-3 mb-4">
                                    <div class="checkout-pack">
                                        <div class="row">
                                            <div class="checkout-time-table checkout-time-table-time">

                                                @foreach (\App\Models\Order::SETTLEMENT_TYPES as $settlement_type)

                                                    <div class="col-12">
                                                        <div class="radio-box custom-control custom-radio pl-0 pr-3 d-flex align-items-center">
                                                            <input type="radio" class="custom-control-input2"
                                                                   name="settlement_type" id="{{ $loop->iteration }}"
                                                                   value="{{ $settlement_type }}" {{ $loop->first ? 'checked' : '' }}>
                                                            <label for="{{ $loop->iteration }}"
                                                                   class="custom-control-label">
                                                                <div class="content-box">
                                                                    <div
                                                                        class="checkout-time-table-title-bar checkout-time-table-title-bar-city">
                                                                        {{ trans("enums.".\App\Models\Order::class.".".$settlement_type) }}
                                                                    </div>
                                                                    <ul class="checkout-time-table-subtitle-bar">
                                                                        <li>
                                                                            تسویه
                                                                            بصورت {{ trans("enums.".\App\Models\Order::class.".".$settlement_type) }}
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>

                                                @endforeach

                                                @if($installments->count())
                                                    @foreach($installments as $installment)
                                                        <div class="col-12">
                                                            <div
                                                                class="radio-box custom-control custom-radio pl-0 pr-3 d-flex align-items-center">
                                                                <input type="radio" class="custom-control-input2"
                                                                       name="settlement_type"
                                                                       id="installment-{{ $loop->iteration }}"
                                                                       value="{{ $installment->id }}">
                                                                <label for="installment-{{ $loop->iteration }}"
                                                                       class="custom-control-label">
                                                                    <div class="content-box">
                                                                        <div
                                                                            class="checkout-time-table-title-bar checkout-time-table-title-bar-city">{{ ($installment->title ? $installment->title." - " : "")."اقساط ".$installment->installments_count." ماهه با پیش پرداخت ".$installment->prepayment_percentage }}</div>
                                                                        <ul class="checkout-time-table-subtitle-bar">
                                                                            <li>{{ trans('validation.attributes.prepayment_percentage')."(".$installment->prepayment_percentage."%)".": ".number_format($installment->getPrepayment($cart->finalPrice($city_id, $carrier_id ?? '')))." تومان" }}</li>
                                                                            <li>{{ trans('validation.attributes.fee_percentage')."(".$installment->fee_percentage."%)".": ".number_format($installment->getFeeTotal($cart->finalPrice($city_id, $carrier_id ?? '')))." تومان" }}</li>
                                                                            <li>{{ trans('validation.attributes.installments_count').": ".$installment->installments_count }}</li>
                                                                            <li>{{ "مبلغ هر قسط: ".number_format($installment->getInstallment($cart->finalPrice($city_id, $carrier_id ?? ''),true))." تومان" }}</li>
                                                                        </ul>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif


                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </section>

                            {{--@if ($cart->hasPhysicalProduct())
                                <div class="section-title no-reletive text-sm-title title-wide no-after-title-wide mb-0 px-res-1">
                                    <h2 class="mt-2">انتخاب نحوه ارسال</h2>
                                </div>

                                @include('front::partials.carriers-container', ['cart' => $cart, 'city_id' => $city_id])
                            @endif--}}

                            <section class="page-content dt-sl pt-2">
                                <div class="section-title text-sm-title title-wide no-after-title-wide mb-0 px-res-1">
                                    <h2>انتخاب شیوه پرداخت</h2>
                                </div>

                                <div class="dt-sn pt-3 pb-3 mb-4">
                                    <div class="checkout-pack">
                                        <div class="row">
                                            <div class="checkout-time-table checkout-time-table-time">

                                                @if ($wallet->balance)
                                                    <div class="col-12 wallet-select">
                                                        <div class="radio-box custom-control custom-radio pl-0 pr-3">
                                                            <input type="radio" class="custom-control-input2"
                                                                   name="gateway" id="wallet" value="wallet">
                                                            <label for="wallet" class="custom-control-label">
                                                                <i class="mdi mdi-credit-card-multiple-outline checkout-additional-options-checkbox-image"></i>
                                                                <div class="content-box">
                                                                    <div
                                                                        class="checkout-time-table-title-bar checkout-time-table-title-bar-city">
                                                                        <span
                                                                            class="has-balance">پرداخت با کیف پول</span>
                                                                        <span class="increase-balance"
                                                                              style="display: none;">افزایش اعتبار و پرداخت با کیف پول</span>
                                                                    </div>
                                                                    <ul class="checkout-time-table-subtitle-bar">
                                                                        <li id="wallet-balance"
                                                                            data-value="{{ $wallet->balance }}">
                                                                            موجودی:{{ number_format($wallet->balance) }}
                                                                            تومان
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endif
                                                @foreach ($gateways as $gateway)

                                                    <div class="col-12">
                                                        <div class="radio-box custom-control checkout_direction custom-radio pl-0 pr-3">
                                                            <input type="radio" class="custom-control-input2"
                                                                   name="gateway" id="{{ $gateway->key }}"
                                                                   value="{{ $gateway->key }}" {{ $loop->first ? 'checked' : '' }}>
                                                            <label for="{{ $gateway->key }}"
                                                                   class="custom-control-label">
                                                                   <div class="checkout-icon-resizer">
                                                                       <i class="mdi mdi-credit-card-outline checkout-additional-options-checkbox-image"></i>
                                                                   </div>
                                                                <div class="content-box">
                                                                    <div
                                                                        class="checkout-time-table-title-bar checkout-time-table-title-bar-city">
                                                                        پرداخت اینترنتی {{ $gateway->name }}
                                                                    </div>
                                                                    <ul class="checkout-time-table-subtitle-bar">
                                                                        <li>
                                                                            آنلاین با تمامی کارت‌های بانکی
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>

                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </section>

                        </section>

                    </div>

                    @include('front::partials.checkout-sidebar', ['city_id' => $city_id])

                </div>

                <div class="row mt-3">
                    <div class="col-sm-6 col-12 px-0">
                        <div class="dt-sn pt-3 pb-3 px-res-1">
                            <div class="section-title text-sm-title title-wide no-after-title-wide mb-0">
                                <h2>کد معرف</h2>
                            </div>
                            <div class="form-ui">
                                <div class="px-2">
                                    <div class="row text-center">
                                        <div class="col-xl-8 col-lg-12">
                                            <div class="form-row">
                                                <input type="text" name="referral_code" class="input-ui pr-2"
                                                       placeholder="کد معرف را اینجا وارد کنید">
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-12">
                                            <button type="button"
                                                    class="btn btn-success mt-res-1 js-btn-check-referral-code">بررسی کد
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            @if ($cart->discount)
                <div class="row mt-3">
                    <div class="col-md-4 col-12 px-0">
                        <div class="dt-sn pt-3 pb-3 px-res-1">
                            <div class="section-title text-sm-title title-wide no-after-title-wide mb-0">
                                <h2>کد تخفیف ثبت شده </h2>
                            </div>
                            <div class="form-ui">
                                <form action="{{ route('front.discount.destroy') }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <div class="row text-center">
                                        <div class="col-xl-6">
                                            <h3>{{ $cart->discount->code }}</strong>
                                        </div>
                                        <div class="col-xl-6 text-left">
                                            <button type="submit" class="btn btn-danger mt-res-1">حذف کد تخفیف</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row mt-3">
                    <div class="col-sm-6 col-12 px-0">
                        <div class="dt-sn pt-3 pb-3 px-res-1">
                            <div class="section-title text-sm-title title-wide no-after-title-wide mb-0">
                                <h2>کد تخفیف دارید؟</h2>
                            </div>
                            <div class="form-ui">
                                <form id="discount-create-form" action="{{ route('front.discount.store') }}">
                                    @csrf
                                    <div class="row text-center">
                                        <div class="col-xl-8 col-lg-12">
                                            <div class="form-row">
                                                <input type="text" name="code" class="input-ui pr-2"
                                                       placeholder="کد تخفیف را اینجا وارد کنید" required>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-12">
                                            <button type="submit" class="btn btn-primary mt-res-1">ثبت کد تخفیف</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-5">
                <a href="{{ route('front.cart') }}" class="float-right border-bottom-dt"><i
                        class="mdi mdi-chevron-double-right"></i>بازگشت به سبد خرید</a>
            </div>
        </div>
    </main>
    <!-- End main-content -->
@endsection

@push('scripts')
    <script src="{{ theme_asset('js/vendor/wNumb.js') }}"></script>
    <script src="{{ theme_asset('js/vendor/ResizeSensor.min.js') }}"></script>
    <script src="{{ theme_asset('js/vendor/jquery.nice-select.min.js') }}"></script>
    <script src="{{ theme_asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ theme_asset('js/plugins/jquery-validation/localization/messages_fa.min.js') }}?v=2"></script>

    <script src="{{ theme_asset('js/pages/cart.js') }}?v=3"></script>
    <script src="{{ theme_asset('js/pages/checkout.js') }}?v=11"></script>
    <script>
        $('.js-btn-check-referral-code').click(function (e) {
            const val = $('input[name="referral_code"]').val().trim();
            if (val.length) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('front.check-referral-code') }}",
                    data: {'code': val},
                    type: 'POST',
                    dataType: 'json',
                    success: function (result) {
                        if(result.is_success){
                            toastr.success('', result.msg, {
                                positionClass: 'toast-bottom-left',
                                containerId: 'toast-bottom-left'
                            });
                        }else {
                            toastr.error('', result.msg, {
                                positionClass: 'toast-bottom-left',
                                containerId: 'toast-bottom-left'
                            });
                        }
                    }
                });
            }
        });
    </script>
@endpush
