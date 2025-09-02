@extends('front::user.layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ theme_asset('css/vendor/nice-select.css') }}">
@endpush

@section('user-content')
    <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12">
        <div class="px-3 px-res-0">
            <div class="section-title text-sm-title title-wide mb-1 no-after-title-wide dt-sl mb-2 px-res-1">
                <h2>مرجوع محصول "{{ $order->title }}"</h2>
            </div>
            <div class="form-ui additional-info dt-sl dt-sn pt-4">
                <form @unless($order->returned) id="profile-form" action="{{ route('front.orders.return-products.store',[$order->order_id,$order->product_id]) }}" method="POST" @endunless class="setting_form">
                    @csrf
                    <div class="row">
                        @if($order->returned)
                        <div class="col-12 mb-4">
                            زمان ثبت: <span>{{ \Morilog\Jalali\Jalalian::fromCarbon($order->returned->created_at)->format('H:i:s Y/m/d') }}</span>
                        </div>
                        @endif
                        <div class="col-12 mb-4">
                            <div class="form-row-title">
                                <h3>نوع مرجوعی</h3>
                            </div>
                            <div class="form-row form-group">
                                <div class="custom-select-ui">
                                    <select class="right" name="type" autocomplete="off" @if($order->returned) disabled @endif>
                                        <option value="" selected disabled>انتخاب کنید</option>
                                        @foreach(\App\Models\ReturnedProduct::TYPES as $type)
                                            <option value="{{ $type }}" @if($order->returned && $order->returned->type == $type) selected @endif>{{ trans('enums.'.\App\Models\ReturnedProduct::class.'.'.$type) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-row-title">
                                <h4>
                                    توضیحات
                                </h4>
                            </div>
                            <div class="form-row form-group">
                                <textarea name="description" class="input-ui pr-2 text-right" placeholder="توضیحات" @if($order->returned) disabled @endif>{{ optional($order->returned)->description }}</textarea>
                            </div>
                        </div>
                    </div>
                    @unless($order->returned)
                    <hr>
                    <div class="form-row mt-3 justify-content-center">
                        <button id="submit-btn" type="submit" class="btn-primary-cm btn-with-icon ml-2">
                            <i class="mdi mdi-cart-off"></i>
                            ثبت اطلاعات
                        </button>
                    </div>
                    @endunless
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ theme_asset('js/vendor/jquery.nice-select.min.js') }}"></script>
    <script src="{{ theme_asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ theme_asset('js/plugins/jquery-validation/localization/messages_fa.min.js') }}?v=2"></script>
@endpush
