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
                                    <li class="breadcrumb-item">مدیریت متوفی
                                    </li>
                                    <li class="breadcrumb-item active">مدیریت ویژگی های متوفی
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <!-- Description -->
                <section id="main-card" class="card">
                    <div class="card-header">
                        <h4 class="card-title">ایجاد ویژگی های متوفی</h4>
                    </div>

                    <div id="main-card" class="card-content">
                        <div class="card-body">
                            <div class="col-12 col-md-10 offset-md-1">
                                <form class="form" id="user-create-form" action="{{ route('admin.users.info.store') }}"
                                      method="post">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>زندگینامه</label>
                                                    <input type="text" class="form-control" name="life_biography">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>وصیت نامه</label>
                                                    <input type="text" class="form-control" name="Will">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>دفتریادبود</label>
                                                    <input type="text" class="form-control" name="Memorial_book">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label></label>
                                                    <input type="text" class="form-control" name="Memorial_book">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>نوع کاربری</label>
                                                    <select id="level" class="form-control" name="level">
                                                        <option value="user">کاربر عادی</option>
                                                        <option value="admin">مدیر وبسایت</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <fieldset class="form-group">
                                                    <label>تصویر</label>
                                                    <div class="custom-file">
                                                        <input id="image" type="file" accept="image/*" name="image"
                                                               class="custom-file-input">
                                                        <label class="custom-file-label" for="image"></label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>

                                        <div id="roles-div" class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>انتخاب نقش ها</label>
                                                    <select id="roles" class="form-control" name="roles[]" multiple>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->id }}">{{ $role->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        @can('marketer')
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>کد بازاریاب(معرف)</label>
                                                        <input type="text" class="form-control" name="referral_code"
                                                               value="{{ old('referral_code') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>درصد فروش بازاریاب(معرف)</label>
                                                        <input type="number" class="form-control"
                                                               name="referral_percentage"
                                                               value="{{ old('referral_percentage') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endcan
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>گذرواژه</label>
                                                    <input type="password" id="password" class="form-control"
                                                           name="password">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>تکرار گذرواژه</label>
                                                    <input type="password" class="form-control user-link ltr"
                                                           name="password_confirmation">
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <fieldset class="checkbox">
                                                    <div class="vs-checkbox-con vs-checkbox-primary">
                                                        <input type="checkbox" name="verified_at">
                                                        <span class="vs-checkbox">
                                                            <span class="vs-checkbox--check">
                                                                <i class="vs-icon feather icon-check"></i>
                                                            </span>
                                                        </span>
                                                        <span>شماره تلفن تایید شده</span>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <div class="mb-1">درصدهای فروش برای دسته‌بندی‌ها</div>
                                            <div class="form-group border-bottom pb-1">
                                                <label>دسته‌بندی‌</label>
                                                <select id="referral_categories" class="form-control">
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" @if(is_array(old('referral_categories')) && array_key_exists($category->id,old('referral_categories'))) disabled @endif>{{ $category->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="row form-group js-referral-categories-items">
                                                @if(is_array(old('referral_categories')) && count(old('referral_categories')))
                                                    @foreach(old('referral_categories') as $key=>$referral_category)
                                                        <div class="col-12 col-md-3 js-referral-categories-item">
                                                            <label class="">{{ optional($referral_category)->offsetGet('title') }}</label>
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 mr-1">
                                                                    <input type="number" name="referral_categories[{{ $key }}][value]" value="{{ optional($referral_category)->offsetGet('value') }}" class="form-control">
                                                                    <input type="hidden" name="referral_categories[{{ $key }}][title]" value="{{ optional($referral_category)->offsetGet('title') }}">
                                                                </div>
                                                                <button class="bg-transparent border-0" data-id="{{ optional($referral_category)->offsetGet('id') }}">
                                                                    <i class="fa fa-close no-pointer-events"></i>
                                                                </button>
                                                            </div>
                                                            @error('referral_categories.'.$key)
                                                            <span class="text-danger text-xl-right">{{ $message }}</span>
                                                            @enderror
                                                            @error('referral_categories.'.$key.'.value')
                                                            <span class="text-danger text-xl-right">{{ $message }}</span>
                                                            @enderror
                                                            @error('referral_categories.'.$key.'.title')
                                                            <span class="text-danger text-xl-right">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="mb-1">درصدهای فروش برای محصولات</div>
                                            <div class="form-group border-bottom pb-1">
                                                <label>محصولات</label>
                                                <select id="referral_products" class="form-control">
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" @if(is_array(old('referral_products')) && array_key_exists($product->id,old('referral_products'))) disabled @endif>{{ $product->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="row form-group js-referral-products-items">
                                                @if(is_array(old('referral_products')) && count(old('referral_products')))
                                                    @foreach(old('referral_products') as $key=>$referral_product)
                                                        <div class="col-12 col-md-3 js-referral-products-item">
                                                            <label class="">{{ optional($referral_product)->offsetGet('title') }}</label>
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 mr-1">
                                                                    <input type="number" name="referral_products[{{ $key }}][value]" value="{{ optional($referral_product)->offsetGet('value') }}" class="form-control">
                                                                    <input type="hidden" name="referral_products[{{ $key }}][title]" value="{{ optional($referral_product)->offsetGet('title') }}">
                                                                </div>
                                                                <button class="bg-transparent border-0" data-id="{{ optional($referral_product)->offsetGet('id') }}">
                                                                    <i class="fa fa-close no-pointer-events"></i>
                                                                </button>
                                                            </div>
                                                            @error('referral_products.'.$key)
                                                            <span class="text-danger text-xl-right">{{ $message }}</span>
                                                            @enderror
                                                            @error('referral_products.'.$key.'.value')
                                                            <span class="text-danger text-xl-right">{{ $message }}</span>
                                                            @enderror
                                                            @error('referral_products.'.$key.'.title')
                                                            <span class="text-danger text-xl-right">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit"
                                                        class="btn btn-primary mr-1 mb-1 waves-effect waves-light">ایجاد
                                                    کاربر
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
                <!--/ Description -->

            </div>
        </div>
    </div>

@endsection

@include('back.partials.plugins', ['plugins' => ['jquery.validate']])

@php
    $help_videos = [
        config('general.video-helpes.users')
    ];
@endphp

@push('scripts')
    <script src="{{ asset('back/assets/js/pages/users/all.js') }}"></script>
    {{-- <script src="{{ asset('back/assets/js/pages/users/create.js') }}"></script> --}}

@endpush
