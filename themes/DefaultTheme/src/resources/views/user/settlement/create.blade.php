@extends('front::user.layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ theme_asset('css/vendor/nice-select.css') }}">
@endpush

@section('user-content')
    <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12">
        <div class="px-3 px-res-0">
            <div class="section-title text-sm-title title-wide mb-1 no-after-title-wide dt-sl mb-2 px-res-1">
                <h2>ثبت درخواست تسویه حساب</h2>
            </div>
            <div class="form-ui additional-info dt-sl dt-sn pt-4">
                <form action="{{ route('front.settlements.store') }}" class="setting_form" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-row-title">
                                <h3>نام صاحب حساب</h3>
                            </div>
                            <div class="form-row form-group">
                                <input type="text" class="input-ui pr-2" name="name" value="{{ old('name') }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-row-title">
                                <h3>شماره کارت</h3>
                            </div>
                            <div class="form-row form-group">
                                <input type="text" class="input-ui pr-2" name="card_number" value="{{ old('card_number') }}">
                                @error('card_number')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 mt-4">
                            <div class="form-row-title">
                                <h3>شماره شبا</h3>
                            </div>
                            <div class="form-row form-group">
                                <input type="text" class="input-ui pl-2 text-left dir-ltr" name="shaba" value="{{ old('shaba') }}">
                                @error('shaba')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 mt-4">
                            <div class="form-row-title">
                                <h3>مبلغ</h3>
                            </div>
                            <div class="form-row form-group">
                                <input type="text" class="input-ui pl-2 text-left dir-ltr" name="amount" value="{{ old('amount') }}">
                                @error('amount')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="form-row mt-3 justify-content-center">
                        <button id="submit-btn" type="submit" class="btn-primary-cm btn-with-icon ml-2">
                            <i class="mdi mdi-credit-card-plus"></i>
                            ثبت درخواست
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ theme_asset('js/vendor/jquery.nice-select.min.js') }}"></script>
@endpush
