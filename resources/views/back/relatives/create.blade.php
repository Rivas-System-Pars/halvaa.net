{{-- resources/views/back/relatives/create.blade.php --}}
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
                                    <li class="breadcrumb-item">مدیریت بستگان</li>
                                    <li class="breadcrumb-item active">ایجاد وابسته جدید</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right col-md-3 col-12 text-right">
                    <a href="{{ route('admin.relatives.index') }}" class="btn btn-outline-secondary">
                        بازگشت به لیست
                    </a>
                </div>
            </div>

            <div class="content-body">
                <section class="card">
                    <div class="card-header">
                        <h4 class="card-title">فرم ایجاد بستگان</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">

                            {{-- خطاهای اعتبارسنجی --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('admin.relatives.store') }}" method="POST">
                                @csrf

                                {{-- گزینه: کاربر انتخاب‌شده (option_value) --}}
                                <div class="form-group">
                                    <label for="option_value">انتخاب کاربر</label>

                                    <select name="option_value" id="option_value" class="form-control" required>
                                        <option></option> {{-- برای placeholder --}}
                                        @foreach ($users as $u)
                                            <option value="{{ $u->id }}">
                                                {{ 'نام :' . $u->fullname . ' - ' . '  نام کاربری:' . $u->username }}
                                            </option>
                                        @endforeach
                                    </select>


                                    @error('option_value')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- نام گزینه فقط با select ارسال می‌شود --}}
                                <div class="form-group">
                                    <label for="option_name">نام گزینه</label>
                                    <select name="option_name" id="option_name" class="custom-select" required>
                                        <option value="" disabled {{ old('option_name') ? '' : 'selected' }}>— انتخاب
                                            کنید —</option>
                                        @foreach ($relationTypes as $t)
                                            <option value="{{ $t->title }}"
                                                {{ old('option_name') === $t->title ? 'selected' : '' }}>
                                                {{ $t->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('option_name')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="d-flex">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">ثبت</button>
                                    <a href="{{ route('admin.relatives.index') }}" class="btn btn-light mr-1">انصراف</a>
                                </div>
                            </form>

                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('back/app-assets/vendors/css/forms/select/select2.min.css') }}">
<style>
  /* فیکس دیده‌شدن متن‌ها در select2 و تم‌های تیره/روشن */
  .select2-container--default .select2-selection--single .select2-selection__rendered { color:#212529 !important; }
  .select2-container--default .select2-selection--single { background:#fff !important; height:auto; min-height: calc(1.5em + .75rem + 2px); }
  .select2-dropdown, .select2-results__options { background:#fff !important; }
  .select2-results__option { color:#212529 !important; }
  .select2-results__option--highlighted[aria-selected] { background:#e9ecef !important; color:#212529 !important; }

  /* اگر لایوت تیره داری */
  .dark-layout .select2-container--default .select2-selection--single .select2-selection__rendered,
  .dark-layout .select2-results__option { color:#212529 !important; }
</style>
@endpush

@push('scripts')
<script src="{{ asset('back/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // فعال‌سازی select2 روی هر دو فیلد
  var $user = $('#option_value');
  if ($user.length) {
    $user.select2({
      dir: 'rtl',
      width: '100%',
      placeholder: 'یک کاربر را انتخاب کنید…'
    });
  }

  var $name = $('#option_name');
  if ($name.length) {
    $name.select2({
      dir: 'rtl',
      width: '100%',
      placeholder: '— انتخاب کنید —'
      // اگر داخل modal هست: dropdownParent: $('#ID-OF-MODAL')
    });
  }
});
</script>
@endpush
