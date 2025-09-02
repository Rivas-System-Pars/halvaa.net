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
                                    <li class="breadcrumb-item">مدیریت سبد محصولات
                                    </li>
                                    <li class="breadcrumb-item active">ایجاد سبد محصول
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div id="main-card" class="content-body">
                <form class="form" id="product-create-form" action="{{ route('admin.baskets.update',$basket->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="row match-height">
                        <div class="col-md-12">
                            <div class="card overflow-hidden">
                                <div class="card-header">
                                    <h4 class="card-title">ویرایش سبد خرید</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">

                                        <div class="tab-pane active" id="tabVerticalLeft1" role="tabpanel"
                                             aria-labelledby="baseVerticalLeft-tab1">
                                            <div class="col-md-12">
                                                <div class="form-body">

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>عنوان</label>
                                                                <input type="text" class="form-control"
                                                                       name="title"
                                                                       value="{{ $basket->title }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="first-name-vertical">توضیحات</label>
                                                                <textarea id="description" class="form-control"
                                                                          rows="3"
                                                                          name="description">{{ $basket->description }}</textarea>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="requirements">محصولات موجود در سبد</label>
                                                                <select id="requirements" name="requirements[]" multiple class="form-control" autocomplete="off">
                                                                    @foreach($products as $product)
                                                                        <option value="{{ $product->id }}" @if($basket->products->contains('id',$product->id)) selected @endif>{{ $product->title }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <label class="text-muted">این محصولات باید خریداری شوند</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-2">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="gifts">هدایای سبد خرید</label>
                                                                <select id="gifts" name="gifts" class="form-control" autocomplete="off">
                                                                    @foreach($products as $product)
                                                                        <option value="{{ $product->id }}">{{ $product->title }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="gift-products-list">
                                                                @if($basket->gifts->count())
                                                                    @foreach($basket->gifts as $gift)
                                                                        <div class="col-12 d-flex align-items-center" data-product="{{ $gift->id }}">
                                                                            <button type="button" class="remove-gift-products-item border-0 bg-transparent">
                                                                                <i class="vs-icon feather text-danger icon-x-circle"></i>
                                                                            </button>
                                                                            <input type="hidden" name="gifts[{{ $loop->iteration }}][product_id]" value="{{ $gift->id }}">
                                                                            <div class="flex-grow-1 row m-0">
                                                                                <div class="col-md-6">
                                                                                    <p class="m-0 font-size-xsmall">{{ $gift->title }}</p>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <div class="controls">
                                                                                            <label>تعداد</label>
                                                                                            <input type="text" name="gifts[{{ $loop->iteration }}][quantity]" class="form-control valid" value="{{ $gift->pivot->quantity }}">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-12 text-center">
                                                    <button type="submit"
                                                            class="btn btn-primary mr-1 mb-1 waves-effect waves-light">
                                                        ایجاد سبد محصول
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
                <div id="form-progress" class="progress progress-bar-success progress-xl" style="display: none;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                         style="width:0%">0%
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@include('back.partials.plugins', ['plugins' => ['ckeditor', 'jquery-tagsinput', 'jquery.validate', 'jquery-ui', 'jquery-ui-sortable', 'dropzone', 'persian-datepicker']])

@push('scripts')
    <script src="{{ asset('back/assets/js/pages/basket/all.js') }}?v=6"></script>
    <script src="{{ asset('back/assets/js/pages/basket/actions.js') }}?v=2"></script>
@endpush
