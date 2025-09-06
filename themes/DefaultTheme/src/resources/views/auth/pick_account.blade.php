@extends('front::auth.layouts.master')

@section('content')
@php
    // اگر کنترلر دیتا نداد، از سشن بخوان (fallback)
    $phone = $phone ?? session('phone_number');
    $users = $users ?? \App\Models\User::where('phone_number', $phone)->get();
@endphp

<div class="container" style="max-width: 520px;">
    <div class="card shadow-sm mt-4">
        <div class="card-header">
            <h5 class="mb-0">انتخاب حساب کاربری</h5>
            <small class="text-muted d-block mt-1">شماره: {{ $phone }}</small>
        </div>

        <div class="card-body">
            @if(!$phone)
                <div class="alert alert-warning">
                    جلسه شما منقضی شده است. لطفاً دوباره شماره را وارد کنید.
                </div>
                <a href="{{ route('login') }}" class="btn btn-outline-secondary">بازگشت به ورود</a>
            @elseif($users->isEmpty())
                <div class="alert alert-danger">
                    هیچ حسابی با این شماره یافت نشد.
                </div>
                <a href="{{ route('login') }}" class="btn btn-outline-secondary">بازگشت</a>
            @else
                <form method="POST" action="">
                    @csrf

                    <div class="list-group mb-3">
                        @foreach($users as $u)
                            <label class="list-group-item d-flex align-items-start gap-3">
                                <input class="form-check-input mt-1" type="radio" name="user_id" value="{{ $u->id }}" required>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">
                                        {{ $u->username ?? ($u->name ?? trim(($u->first_name ?? '').' '.($u->last_name ?? ''))) ?: 'بدون نام' }}
                                    </div>
                                    <div class="text-muted small">
                                        {{-- @dd($u) --}}
                                        بایو: {{ $u->bio }}
                                        @if($u->email) • {{ $u->email }} @endif
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    @error('user_id')
                        <div class="text-danger mb-2">{{ $message }}</div>
                    @enderror

                    <button type="submit" class="btn btn-primary w-100">ادامه</button>
                </form>
            @endif
        </div>
    </div>


</div>
@endsection
