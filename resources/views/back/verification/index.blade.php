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
              <li class="breadcrumb-item active">درخواست‌های تأیید هویت متوفی</li>
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
          <h4 class="card-title">لیست درخواست‌ها</h4>
        </div>
        <div class="card-content">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>کاربر</th>
                    <th>وضعیت فعلی</th>
                    <th>تاریخ ارسال</th>
                    <th>عملیات</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($verifications as $item)
                    <tr>
                      <td>{{ $item->id }}</td>
                      <td>{{ $item->user->first_name}} {{$item->user->last_name}}</td>
                      <td>
                        @switch($item->status)
                          @case('pending') در حال بررسی @break
                          @case('approved') قبول شده @break
                          @case('rejected') رد شده @break
                          @default {{ $item->status }}
                        @endswitch
                      </td>
                      <td>{{ $item->created_at->format('Y/m/d H:i') }}</td>
                      <td>
                        <a href="{{ route('admin.back.verification.show', $item) }}"
                           class="btn btn-sm btn-primary">
                          مشاهده جزئیات
                        </a>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="text-center">درخواستی وجود ندارد.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>
@endsection
