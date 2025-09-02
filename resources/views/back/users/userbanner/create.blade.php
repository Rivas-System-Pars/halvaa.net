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
                                <li class="breadcrumb-item">بنر کاربران</li>
                                <li class="breadcrumb-item active">آپلود بنر</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <section id="main-card" class="card">
                <div class="card-header">
                    <h4 class="card-title">آپلود بنر یا اعلامیه جدید</h4>
                </div>

                <div class="card-content">
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-bs-dismiss="alert" aria-label="بستن">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('admin.users.userbanner.store') }}" method="POST" enctype="multipart/form-data" id="bannerForm">
                            @csrf
                            <div class="form-group">
                                <label for="image">تصویر بنر</label>
                                <input type="file" name="image" id="image"
                                    class="form-control @error('image') is-invalid @enderror"
                                    accept="image/*" required>
                                @error('image')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- پیش‌نمایش تصویر --}}
                            <div class="form-group mt-3 text-center">
                                <img id="preview" src="#" alt="پیش‌نمایش تصویر"
                                     class="img-fluid rounded shadow-sm d-none"
                                     style="max-height: 250px;">
                            </div>

                            <button type="submit" class="btn btn-primary mt-2">
                                <i class="fa fa-upload me-1"></i> آپلود بنر
                            </button>
                        </form>
                    </div>
                </div>
            </section>

            {{-- نمایش همه بنرها --}}
            @if(isset($banners) && $banners->count())
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="card-title">لیست بنرهای شما</h4>
                    </div>
                    <div class="card-body row">
                        @foreach($banners as $banner)
                            <div class="col-md-4 mb-3 text-center">
                                <div class="border rounded p-2 shadow-sm">
                                    <img src="{{ asset($banner->path) }}" class="img-fluid rounded mb-2"
                                         style="max-height: 200px;" alt="User Banner">
                                    <small class="text-muted d-block">{{ $banner->created_at->format('Y/m/d H:i') }}</small>

                                    <form action="{{ route('admin.users.userbanner.destroy', $banner->id) }}" method="POST"
                                          onsubmit="return confirm('آیا مطمئن هستید که می‌خواهید این بنر را حذف کنید؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger mt-2">
                                            <i class="fa fa-trash me-1"></i> حذف بنر
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection

@include('back.partials.plugins', ['plugins' => ['jquery.validate']])

@push('scripts')
<script>
    $(document).ready(function () {
        // اعتبارسنجی فرم
        $('#bannerForm').validate({
            rules: {
                image: {
                    required: true,
                    extension: "jpg|jpeg|png|gif"
                }
            },
            messages: {
                image: {
                    required: "لطفاً یک فایل تصویر انتخاب کنید.",
                    extension: "فقط فرمت‌های jpg، jpeg، png، gif مجاز هستند."
                }
            }
        });

        // پیش‌نمایش تصویر
        $('#image').change(function () {
            const input = this;

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    $('#preview')
                        .attr('src', e.target.result)
                        .removeClass('d-none');
                };

                reader.readAsDataURL(input.files[0]);
            }
        });
    });
</script>
@endpush
