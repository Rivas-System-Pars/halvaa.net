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
                                <li class="breadcrumb-item active">ایجاد پیام</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <section id="main-card" class="card">
                <div class="card-header">
                    <h4 class="card-title">ایجاد پیام جدید</h4>
                </div>

                <div id="main-card" class="card-content">
                    <div class="card-body">
                        <form action="{{ route('admin.users.info.message.store') }}" method="POST" class="form">
                            @csrf
                            <div class="form-group">
                                <label>متن پیام</label>
                                <textarea name="content" rows="5" class="form-control">{{ old('content') }}</textarea>
                                @error('content')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary mt-2">ثبت پیام</button>
                        </form>
                    </div>
                </div>
            </section>

            {{-- جدول پیام‌ها --}}
            @if(isset($messages) && $messages->count())
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="card-title">پیام‌های ثبت شده</h4>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>متن پیام</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($messages as $msg)
                                    <tr>
                                        <td>{{ $msg->content }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.info.message.edit', $msg->id) }}" class="btn btn-warning btn-sm">ویرایش</a>
                                            <form action="{{ route('admin.users.info.message.destroy', $msg->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('آیا مطمئن هستید؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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

