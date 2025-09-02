@extends('front::user.layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ theme_asset('css/vendor/nice-select.css') }}">
@endpush

@section('user-content')
    <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12">
        <div class="px-3 px-res-0">
            <div class="section-title text-sm-title title-wide mb-1 no-after-title-wide dt-sl mb-2 px-res-1">
                <h2>جزئیات درخواست تسویه</h2>
            </div>
            <div class="form-ui additional-info dt-sl dt-sn pt-4">
                <form action="{{ route('front.settlements.cancel',$settlement->id) }}" class="setting_form" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-row-title">
                                <h3>نام صاحب حساب</h3>
                            </div>
                            <div class="form-row form-group">
                                <input type="text" class="input-ui pr-2" name="name" value="{{ $settlement->name }}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-row-title">
                                <h3>شماره کارت</h3>
                            </div>
                            <div class="form-row form-group">
                                <input type="text" class="input-ui pr-2" name="card_number" value="{{ $settlement->card_number }}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-4">
                            <div class="form-row-title">
                                <h3>شماره شبا</h3>
                            </div>
                            <div class="form-row form-group">
                                <input type="text" class="input-ui pl-2 text-left dir-ltr" name="shaba" value="{{ $settlement->shaba }}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-4">
                            <div class="form-row-title">
                                <h3>مبلغ</h3>
                            </div>
                            <div class="form-row form-group">
                                <input type="text" class="input-ui pl-2 text-left dir-ltr" name="amount" value="{{ $settlement->amount }}" readonly>
                            </div>
                        </div>
                    </div>
                    @if($settlement->status == \App\Models\Settlement::STATUS_PENDING)
                        <hr>
                        <div class="form-row mt-3 justify-content-center">
                            <button id="submit-btn" type="submit" class="btn-primary-cm btn-with-icon ml-2" style="background-color: #ef394e !important;">
                                <i class="mdi mdi-cancel"></i>
                                لغو درخواست
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ theme_asset('js/vendor/jquery.nice-select.min.js') }}"></script>
@endpush
