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
                                    <li class="breadcrumb-item">مدیریت اطلاعیه‌ها</li>
                                    <li class="breadcrumb-item active">ویرایش اطلاعیه</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <section id="main-card" class="card">
                    <div class="card-header">
                        <h4 class="card-title">ویرایش اطلاعیه</h4>
                    </div>

                    <div id="main-card" class="card-content">
                        <div class="card-body">
                            <div class="col-12 col-md-10 offset-md-1">
                                <form class="form" id="notice-form"
                                    action="{{ route('admin.users.info.notice.update', $notice->id) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-body">
                                        <div class="row">
                                            {{-- عنوان اطلاعیه --}}
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>عنوان</label>
                                                    <input type="text" class="form-control" name="title"
                                                        value="{{ old('title', $notice->title) }}">
                                                    @error('title')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- تصویر اطلاعیه --}}
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>تصویر اطلاعیه (اختیاری)</label>
                                                    <input type="file" class="form-control-file" name="image" accept="image/*">
                                                    @error('image')
                                                        <span class="text-danger d-block">{{ $message }}</span>
                                                    @enderror

                                                    @if ($notice->image)
                                                        <div class="mt-2">
                                                            <img src="{{ asset($notice->image) }}" alt="تصویر اطلاعیه" width="100">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- متن اطلاعیه --}}
                                            <div class="col-md-12 col-12">
                                                <div class="form-group">
                                                    <label>متن اطلاعیه</label>
                                                    <textarea class="form-control" name="content" rows="6">{{ old('content', $notice->content) }}</textarea>
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
                                                    به‌روزرسانی اطلاعیه
                                                </button>
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
