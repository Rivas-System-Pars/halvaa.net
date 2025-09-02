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
                                    <li class="breadcrumb-item">مدیریت قسط ها
                                    </li>
                                    <li class="breadcrumb-item active">ایجاد قسط
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <!-- Description -->
                <section class="card">
                    <div class="card-header">
                        <h4 class="card-title">ایجاد قسط جدید</h4>
                    </div>

                    <div id="main-card" class="card-content overflow-hidden">
                        <div class="card-body">
                            @error('error')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="col-12 col-md-12">
                                <form class="form" action="{{ route('admin.installments.store') }}" method="post">
                                    @csrf

                                    <div class="d-block">
                                        <div class="form-group">
                                            <label for="title">عنوان</label>
                                            <input type="text" class="form-control" name="title" value="{{ old('title') }}" id="title">
                                            @error('title')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>{{ trans('validation.attributes.prepayment_percentage') }}</label>
                                                    <input type="number" class="form-control" name="prepayment_percentage" value="{{ old('prepayment_percentage') }}">
                                                    @error('prepayment_percentage')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>{{ trans('validation.attributes.fee_percentage') }}</label>
                                                    <input type="number" class="form-control" name="fee_percentage" value="{{ old('fee_percentage') }}">
                                                    @error('fee_percentage')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>{{ trans('validation.attributes.installments_count') }}</label>
                                                    <input type="number" class="form-control" name="installments_count" value="{{ old('installments_count') }}">
                                                    @error('installments_count')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
											<div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>{{ trans('validation.attributes.period') }}</label>
                                                    <input type="number" class="form-control" name="period" value="{{ old('period') }}">
                                                    @error('period')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
											<div class="col-md-12">
												<div class="form-group">
													<label>محصولات</label>
													<select class="form-control product-categories"
															name="products[]" multiple>
														@foreach ($products as $product_id=>$product)
														<option value="{{ $product_id }}" @if(old('products') && in_array($product_id,old('products'))) selected @endif>{{ $product }}</option>
														@endforeach
													</select>
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<label>وضعیت</label>
													<select class="form-control product-categories"
															name="is_active">
														<option value="1" @if(old('is_active') == 1) selected @endif>فعال</option>
														<option value="0" @if(old('is_active') == 0) selected @endif>غیرفعال</option>
													</select>
												</div>
											</div>
                                        </div>
                                        <div class="form-group">
                                            <label>توضیحات</label>
                                            <textarea class="form-control" rows="2" name="description">{{ old('description') }}</textarea>
                                            @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 text-right">
                                        <button type="submit" class="btn btn-primary mb-1 waves-effect waves-light">ثبت</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

@endsection
