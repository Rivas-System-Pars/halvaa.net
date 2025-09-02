{{-- resources/views/back/users/info/familytree/create.blade.php --}}
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
                                    <li class="breadcrumb-item">کاربران</li>
                                    <li class="breadcrumb-item active">شجره نامه کاربر</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <section id="main-card" class="card">
                    <div class="card-header">
                        <h4 class="card-title">ایجاد/ویرایش تصویر شجره نامه</h4>
                        <div class="card-subtitle">
                        </div>
                    </div>

                    <div id="main-card" class="card-content">
                        <div class="card-body">
                            <div class="col-12 col-md-10 offset-md-1">

                                {{-- Flash messages --}}
                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif

                                    <form class="form" id="family-tree-form"
                                        action="{{ route('admin.familytree.store', $user->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <div class="form-body">
                                            <div class="row">
                                                {{-- فایل تصویر --}}
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>تصویر شجره نامه</label>
                                                        <input type="file" class="form-control-file" name="familly_tree"
                                                            accept=".jpg,.jpeg,.png,.webp" required>
                                                        <small class="text-muted d-block mt-50">
                                                            فرمت‌های مجاز: JPG, JPEG, PNG, WEBP — حداکثر 5 مگابایت
                                                        </small>
                                                        @error('familly_tree')
                                                            <span class="text-danger d-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                @php
                                                    $current = optional($user->familyTree);
                                                @endphp

                                                {{-- پیش‌نمایش تصویر فعلی (در صورت وجود) --}}
                                                <div class="col-md-6 col-12">
                                                    <label>پیش‌نمایش تصویر فعلی</label>
                                                    <div class="border rounded p-1 d-flex align-items-center justify-content-center"
                                                        style="min-height: 180px;">
                                                        @if ($current && $current->image)
                                                            <img src="{{ asset($current->image) }}" alt="Family Tree"
                                                                style="max-width:100%; height:auto;">
                                                        @else
                                                            <span class="text-muted">تصویری ثبت نشده است.</span>
                                                        @endif
                                                    </div>
                                                    {{-- @if ($current && $current->image)
                                                        <small class="d-block mt-50">
                                                            مسیر فعلی: <code>{{ $current->image }}</code>
                                                        </small>
                                                    @endif --}}
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                <div class="col-12">
                                                    <button type="submit"
                                                        class="btn btn-primary mr-1 mb-1 waves-effect waves-light">
                                                        ذخیره تصویر
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                    {{-- فرم حذف جداگانه --}}
                                    @if ($current && $current->image)
                                        <form action="{{ route('admin.familytree.destroy', $user->id) }}" method="POST"
                                            style="margin-top:10px;"
                                            onsubmit="return confirm('آیا از حذف تصویر مطمئن هستید؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                حذف تصویر
                                            </button>
                                        </form>
                                    @endif
                                    {{-- (اختیاری) باکس راهنما --}}
                                    <div class="card mt-2">
                                        <div class="card-body">
                                            <ul class="mb-0">
                                                <li>پس از بارگذاری، تصویر قبلی کاربر حذف می‌شود و تنها یک تصویر به‌عنوان «شجره
                                                    نامه» نگهداری
                                                    می‌شود.</li>
                                                {{-- <li>برای نمایش در صفحات دیگر از مسیر ذخیره‌شده در جدول <code>gallery</code> استفاده کنید.</li> --}}
                                            </ul>
                                        </div>
                                    </div>


                            </div>
                        </div>
                    </div>
                </section>


            </div>
        </div>
    </div>
@endsection

{{-- اگر نیاز به پلاگین خاصی دارید، اینجا اضافه کنید --}}
@include('back.partials.plugins', ['plugins' => ['jquery.validate']])

@push('scripts')
    <script>
        // نمونه ساده ولیدیشن فرانت (اختیاری)
        (function() {
            const form = document.getElementById('family-tree-form');
            form?.addEventListener('submit', function(e) {
                const input = form.querySelector('input[name="familly_tree"]');
                if (!input?.files?.length) {
                    e.preventDefault();
                    alert('لطفاً یک تصویر انتخاب کنید.');
                }
            }, false);
        })();
    </script>
@endpush
