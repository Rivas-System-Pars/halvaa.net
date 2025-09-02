@extends('back.layouts.master')

@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-body">
                <section class="card">
                    <div class="card-header">
                        <h4 class="card-title">ارسال مدارک تایید هویت</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="col-12 col-md-10 offset-md-1">

                                {{-- پیام خطا یا موفقیت --}}
                                @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif
                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif

                                {{-- اگر دسترسی ندارد --}}
                                @unless ($hasAccess)
                                    <div class="alert alert-warning">
                                        <p> برای ارسال مدارک ابتدا محصول مورد نظر را تهیه کنید.
                                        </p>
                                        <a href="https://halvaplugin.rivasit.com"> برای خرید روی لینک کلیک کنید</a>
                                    </div>
                                @else
                                    {{-- نمایش وضعیت قبلی در صورت وجود --}}
                                    @if (session('status'))
                                        <div class="alert alert-info">
                                            <strong>وضعیت:</strong>
                                            @switch(session('status'))
                                                @case('pending')
                                                    در حال بررسی
                                                @break

                                                @case('approved')
                                                    قبول شده
                                                @break

                                                @case('rejected')
                                                    رد شده
                                                @break

                                                @default
                                                    {{ session('status') }}
                                            @endswitch
                                        </div>

                                        @if (session('admin_note') && session('status') === 'rejected')
                                            <div class="table-responsive mt-2">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>توضیحات ادمین</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>{{ session('admin_note') }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    @endif

                                    {{-- فرم آپلود --}}
                                    <form action="{{ route('admin.verification.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            {{-- کارت ملی --}}
                                            <div class="col-md-4">
                                                <label>تصویر کارت ملی</label>
                                                <input type="file" name="national_card" class="form-control"
                                                    accept="image/*">
                                                @error('national_card')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            {{-- شناسنامه --}}
                                            <div class="col-md-4">
                                                <label>تصویر شناسنامه</label>
                                                <input type="file" name="birth_certificate" class="form-control"
                                                    accept="image/*">
                                                @error('birth_certificate')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            {{-- گواهی فوت --}}
                                            <div class="col-md-4">
                                                <label>تصویر گواهی فوت</label>
                                                <input type="file" name="death_cerification" class="form-control"
                                                    accept="image/*">
                                                @error('death_cerification')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fa fa-upload ml-1"></i> ارسال مدارک جهت بررسی
                                            </button>
                                        </div>
                                    </form>
                                @endunless

                            </div>
                        </div>
                    </div>
@if($verifications->count())
    <h4>سوابق ارسال مدارک شما:</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>تاریخ ارسال</th>
                <th>وضعیت</th>
                <th>یادداشت مدیر</th>
            </tr>
        </thead>
        <tbody>
            @foreach($verifications as $ver)
                <tr>
                    <td>{{ jdate($ver->created_at)->format('Y/m/d H:i') }}</td>
                    <td>
                        @if($ver->status == 'pending')
                            <span class="text-warning">در حال بررسی</span>
                        @elseif($ver->status == 'approved')
                            <span class="text-success">تأیید شده</span>
                        @elseif($ver->status == 'rejected')
                            <span class="text-danger">رد شده</span>
                        @endif
                    </td>
                    <td>{{ $ver->admin_note ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
                </section>
            </div>
        </div>
    </div>
@endsection
