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
                                    <li class="breadcrumb-item">مدیریت</li>
                                    <li class="breadcrumb-item">مدیریت متوفی</li>
                                    <li class="breadcrumb-item active">ویرایش وصیت‌نامه</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <section id="main-card" class="card">
                    <div class="card-header">
                        <h4 class="card-title">ویرایش وصیت‌نامه</h4>
                    </div>

                    <div id="main-card" class="card-content">
                        <div class="card-body">
                            <div class="col-12 col-md-10 offset-md-1">
                                <form class="form" id="will-form"
                                      action="{{ route('admin.users.info.will.update', $will->id) }}"
                                      method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-body">
                                        <div class="row">
                                            {{-- عنوان --}}
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>عنوان</label>
                                                    <input type="text" class="form-control" name="title"
                                                           value="{{ old('title', $will->title) }}">
                                                    @error('title')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- تصویر جدید --}}
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>تصویر جدید (اختیاری)</label>
                                                    <input type="file" class="form-control-file" name="image" accept="image/*">
                                                    @error('image')
                                                        <span class="text-danger d-block">{{ $message }}</span>
                                                    @enderror

                                                    @if($will->image)
                                                        <div class="mt-2">
                                                            <img src="{{ asset($will->image) }}" alt="image" width="120">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- متن --}}
                                            <div class="col-md-12 col-12">
                                                <div class="form-group">
                                                    <label>متن وصیت‌نامه</label>
                                                    <textarea class="form-control" name="content" rows="6">{{ old('content', $will->content) }}</textarea>
                                                    @error('content')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit"
                                                        class="btn btn-primary mr-1 mb-1 waves-effect waves-light">
                                                    ذخیره تغییرات
                                                </button>
                                                <a href="{{ route('admin.users.info.will.create') }}"
                                                   class="btn btn-secondary">بازگشت</a>
                                            </div>
                                        </div>
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

@include('back.partials.plugins', ['plugins' => ['jquery.validate']])

@push('scripts')
    <script src="{{ asset('back/assets/js/pages/users/all.js') }}"></script>
@endpush
