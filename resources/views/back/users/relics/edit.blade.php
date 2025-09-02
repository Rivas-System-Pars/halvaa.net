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
                                    <li class="breadcrumb-item">مدیریت آثار متوفی ‌ها</li>
                                    <li class="breadcrumb-item active">ویرایش آثار متوفی </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <section id="main-card" class="card">
                    <div class="card-header">
                        <h4 class="card-title">ویرایش آثار متوفی </h4>
                    </div>

                    <div id="main-card" class="card-content">
                        <div class="card-body">
                            <div class="col-12 col-md-10 offset-md-1">
                                <form class="form" id="relics-form"
                                    action="{{ route('admin.relics.update', $relics->id) }}"method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-body">
                                        <div class="row">
                                            {{-- عنوان آثار متوفی  --}}
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>عنوان</label>
                                                    <input type="text" class="form-control" name="title"
                                                        value="{{ old('title', $relics->title) }}">
                                                    @error('title')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- تصویر آثار متوفی  --}}
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>تصویر آثار متوفی  (اختیاری)</label>
                                                    <input type="file" class="form-control-file" name="image" accept="image/*">
                                                    @error('image')
                                                        <span class="text-danger d-block">{{ $message }}</span>
                                                    @enderror

                                                    @if ($relics->image)
                                                        <div class="mt-2">
                                                            <img src="{{ asset($relics->image) }}" alt="تصویر آثار متوفی " width="100">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- متن آثار متوفی  --}}
                                            <div class="col-md-12 col-12">
                                                <div class="form-group">
                                                    <label>متن آثار متوفی </label>
                                                    <textarea class="form-control" name="content" rows="6">{{ old('content', $relics->content) }}</textarea>
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
                                                    به‌روزرسانی آثار متوفی
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
