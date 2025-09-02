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
                                    <li class="breadcrumb-item active">ایجاد/ویرایش وصیت‌نامه</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <section id="main-card" class="card">
                    <div class="card-header">
                        <h4 class="card-title">ایجاد/ویرایش وصیت‌نامه</h4>
                    </div>

                    <div id="main-card" class="card-content">
                        <div class="card-body">
                            <div class="col-12 col-md-10 offset-md-1">
                                <form class="form" id="will-form"
                                    action="{{ route('admin.users.info.will.store') }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-body">
                                        <div class="row">
                                            {{-- عنوان وصیت --}}
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>عنوان</label>
                                                    <input type="text" class="form-control" name="title"
                                                        value="{{ old('title', $will->title ?? '') }}">
                                                    @error('title')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- تصویر --}}
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>تصویر وصیت‌نامه (اختیاری)</label>
                                                    <input type="file" class="form-control-file" name="image" accept="image/*">
                                                    @error('image')
                                                        <span class="text-danger d-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- متن وصیت --}}
                                            <div class="col-md-12 col-12">
                                                <div class="form-group">
                                                    <label>متن وصیت‌نامه</label>
                                                    <textarea class="form-control" name="content" rows="6">{{ old('content', $will->content ?? '') }}</textarea>
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
                                                    ثبت وصیت‌نامه
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- جدول نمایش وصیت‌نامه‌ها --}}
                @if(isset($wills) && count($wills))
                    <div class="card mt-3">
                        <div class="card-header">
                            <h4 class="card-title">وصیت‌نامه‌های ثبت‌شده</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th>عنوان</th>
                                            <th>متن</th>
                                            <th>تصویر</th>
                                            <th>عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($wills as $item)
                                            <tr>
                                                <td>{{ $item->title }}</td>
                                                <td>{{ Str::limit($item->content, 100) }}</td>
                                                <td>
                                                    @if($item->image)
                                                        <img src="{{ asset($item->image) }}" alt="image" width="80">
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.users.info.will.edit', $item->id) }}"
                                                       class="btn btn-sm btn-warning">ویرایش</a>
                                                    <form action="{{ route('admin.users.info.will.destroy', $item->id) }}"
                                                          method="POST"
                                                          style="display:inline-block;"
                                                          onsubmit="return confirm('آیا مطمئن هستید که می‌خواهید حذف کنید؟');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

@include('back.partials.plugins', ['plugins' => ['jquery.validate']])

@push('scripts')
    <script src="{{ asset('back/assets/js/pages/users/all.js') }}"></script>
@endpush
