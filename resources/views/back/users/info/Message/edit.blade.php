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
                                <li class="breadcrumb-item">مدیریت پیام‌ها</li>
                                <li class="breadcrumb-item active">ویرایش پیام</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <section id="main-card" class="card">
                <div class="card-header">
                    <h4 class="card-title">ویرایش پیام</h4>
                </div>

                <div id="main-card" class="card-content">
                    <div class="card-body">
                        <form action="{{ route('admin.users.info.message.update', $message->id) }}" method="POST" class="form">
                            @csrf
                            <div class="form-group">
                                <label>متن پیام</label>
                                <textarea name="content" rows="5" class="form-control">{{ old('content', $message->content) }}</textarea>
                                @error('content')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary mt-2">بروزرسانی پیام</button>
                            <a href="{{ route('admin.users.info.message.create') }}" class="btn btn-secondary mt-2">بازگشت</a>
                        </form>
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
