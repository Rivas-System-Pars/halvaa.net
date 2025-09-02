@extends('back.layouts.master')

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb no-border">
                                    <li class="breadcrumb-item">مدیریت
                                    </li>
                                    <li class="breadcrumb-item">مدیریت تسویه حساب ها
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="content-body">
                <!-- filter start -->
                <!-- filter end -->

                <section id="main-card" class="card">
                    <div class="card-header">
                        <h4 class="card-title">جزئیات تسویه حساب</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form action="{{ route('admin.settlements.change-status',$settlement->id) }}" class="setting_form" method="POST">
                                @method('PUT')
                                @csrf
                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <label for="title">نام صاحب حساب</label>
                                        <input type="text" class="form-control" name="name" value="{{ $settlement->name }}" readonly>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="title">شماره کارت</label>
                                        <input type="text" class="form-control" name="card_number" value="{{ $settlement->card_number }}" readonly>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="title">شماره شبا</label>
                                        <input type="text" class="form-control" name="shaba" value="{{ $settlement->shaba }}" readonly>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="title">مبلغ</label>
                                        <input type="text" class="form-control" name="amount" value="{{ $settlement->amount }}" readonly>
                                    </div>
                                </div>
                                @if($settlement->status == \App\Models\Settlement::STATUS_PENDING)
                                    <div class="">
                                        <label for="title">وضعیت</label>
                                        <select name="status" class="form-control">
                                            @foreach(\App\Models\Settlement::STATUSES as $status)
                                                <option value="{{ $status }}" @if($status == $settlement->status) selected @endif>{{ trans('settlements_'.$status) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <hr>
                                    <div class="form-row mt-3 justify-content-center">
                                        <button id="submit-btn" type="submit" class="btn btn-primary">
                                            بروزرسانی
                                        </button>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label for="title">وضعیت</label>
                                        <input type="text" class="form-control" name="amount" value="{{ trans('settlements_'.$settlement->status) }}" readonly>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
