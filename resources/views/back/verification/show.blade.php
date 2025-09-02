@extends('back.layouts.master')

@section('content')
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
          <div class="breadcrumb-wrapper col-12">
            <ol class="breadcrumb no-border">
              <li class="breadcrumb-item">مدیریت</li>
              <li class="breadcrumb-item"><a href="{{ route('admin.back.verification.index') }}">درخواست‌ها</a></li>
              <li class="breadcrumb-item active">جزئیات درخواست #{{ $verification->id }}</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div class="content-body">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <section class="card">
        <div class="card-header">
          <h4 class="card-title">جزئیات درخواست شماره {{ $verification->id }}</h4>
        </div>
        <div class="card-content">
          <div class="card-body">

            <div class="row mb-4">
              <div class="col-md-4 text-center">
                <h5>کارت ملی</h5>
                <img src="{{ asset($verification->national_card) }}" class="img-fluid img-thumbnail">
              </div>
              <div class="col-md-4 text-center">
                <h5>شناسنامه</h5>
                <img src="{{ asset($verification->birth_certificate) }}" class="img-fluid img-thumbnail">
              </div>
              <div class="col-md-4 text-center">
                <h5>گواهی فوت</h5>
                <img src="{{ asset($verification->death_cerification) }}" class="img-fluid img-thumbnail">
              </div>
            </div>

            <form action="{{ route('admin.back.verification.update', $verification) }}"
                  method="POST">
              @csrf

              <div class="form-group">
                <label>انتخاب وضعیت</label>
                <select name="status" class="form-control">
                  <option value="pending"  {{ $verification->status=='pending'  ? 'selected':'' }}>در حال بررسی</option>
                  <option value="approved" {{ $verification->status=='approved' ? 'selected':'' }}>قبول شده</option>
                  <option value="rejected" {{ $verification->status=='rejected' ? 'selected':'' }}>رد شده</option>
                </select>
                @error('status') <small class="text-danger">{{ $message }}</small> @enderror
              </div>

              <div class="form-group">
                <label>توضیحات مدیر (برای رد)</label>
                <textarea name="admin_note" class="form-control" rows="3">{{ old('admin_note', $verification->admin_note) }}</textarea>
                @error('admin_note') <small class="text-danger">{{ $message }}</small> @enderror
              </div>

              <button type="submit" class="btn btn-success">
                ذخیره تغییرات
              </button>
              <a href="{{ route('admin.back.verification.index') }}" class="btn btn-secondary">بازگشت</a>
            </form>

          </div>
        </div>
      </section>
    </div>
  </div>
</div>
@endsection
