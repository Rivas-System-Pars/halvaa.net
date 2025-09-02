@extends('back.layouts.master')

@push('styles')
	<link rel="stylesheet" href="{{ theme_asset('css/persiandatepicker.css') }}">

@endpush

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
                                    <li class="breadcrumb-item">مدیریت متوفی</li>
                                    <li class="breadcrumb-item active">ایجاد/ویرایش گاهشمار زندگی متوفی</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <section id="main-card" class="card">
                    <div class="card-header">
                        <h4 class="card-title">ایجاد/ویرایش گاهشمار زندگی متوفی</h4>
                    </div>

                    <div id="main-card" class="card-content">
                        <div class="card-body">
                            <div class="col-12 col-md-10 offset-md-1">
                                <form class="form" id="life-calendar-form"
                                    action="{{ route('admin.users.info.lifecalender.store') }}" method="post">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>عنوان رویداد</label>
                                                    <select class="form-control" name="subject">
                                                        <option value="">-- انتخاب کنید --</option>
                                                        <option value="تولد در شهر ، روستای"
                                                            {{ old('subject') == 'تولد در شهر ، روستای' ? 'selected' : '' }}>تولد در شهر ، روستای</option>
                                                        <option value="اعزام به سربازی به پادگان"
                                                            {{ old('subject') == 'اعزام به سربازی به پادگان' ? 'selected' : '' }}>
                                                            اعزام به سربازی به پادگان</option>
                                                        <option value="ازدواج با خانم"
                                                            {{ old('subject') == 'ازدواج با خانم' ? 'selected' : '' }}>
                                                            ازدواج با خانم</option>
                                                        <option value="ازدواج با آقا"
                                                            {{ old('subject') == 'ازدواج با آقا' ? 'selected' : '' }}>ازدواج
                                                            با آقا</option>
                                                        <option value="قبولی در دانشگاه"
                                                            {{ old('subject') == 'قبولی در دانشگاه' ? 'selected' : '' }}>
                                                            قبولی در دانشگاه</option>
                                                        <option value="سفر به کشور"
                                                            {{ old('subject') == 'سفر به کشور' ? 'selected' : '' }}>سفر به
                                                            کشور</option>
                                                        <option value="فوت پدر ایشان به نام"
                                                            {{ old('subject') == 'فوت پدر' ? 'selected' : '' }}>فوت پدر ایشان به نام
                                                        </option>
                                                        <option value="فوت مادر ایشان به نام"
                                                            {{ old('subject') == 'فوت مادر' ? 'selected' : '' }}>فوت مادر ایشان به نام
                                                        </option>
                                                        <option value="صاحب فرزند دختر به نام"
                                                            {{ old('subject') == 'صاحب فرزند دختر' ? 'selected' : '' }}>صاحب
                                                            فرزند دختر به نام</option>
                                                        <option value="صاحب فرزند پسر به نام"
                                                            {{ old('subject') == 'صاحب فرزند پسر' ? 'selected' : '' }}>صاحب
                                                            فرزند پسر به نام</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>روز</label>
                                                    <select class="form-control" name="day">
                                                        <option value="">-- انتخاب کنید --</option>
                                                        <option value="شنبه"
                                                            {{ old('day') == 'شنبه' ? 'selected' : '' }}>شنبه</option>
                                                        <option value="یکشنبه"
                                                            {{ old('day') == 'یکشنبه' ? 'selected' : '' }}>یکشنبه</option>
                                                        <option value="دوشنبه"
                                                            {{ old('day') == 'دوشنبه' ? 'selected' : '' }}>دوشنبه</option>
                                                        <option value="سه‌شنبه"
                                                            {{ old('day') == 'سه‌شنبه' ? 'selected' : '' }}>سه‌شنبه
                                                        </option>
                                                        <option value="چهارشنبه"
                                                            {{ old('day') == 'چهارشنبه' ? 'selected' : '' }}>چهارشنبه
                                                        </option>
                                                        <option value="پنج‌شنبه"
                                                            {{ old('day') == 'پنج‌شنبه' ? 'selected' : '' }}>پنج‌شنبه
                                                        </option>
                                                        <option value="جمعه"
                                                            {{ old('day') == 'جمعه' ? 'selected' : '' }}>جمعه</option>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="col-md-6 col-12">
                                               
												<div class="form-row with-icon form-group">

                                <input type="text" id="publish_date_picker" name="date"
                                    placeholder="تاریخ" class="input-ui pr-2 form-control">

                                <i class="mdi mdi-calendar"></i>
                            </div>
												
												
												
											{{--	<div class="form-group">
                                                    <label>تاریخ</label>
                                                    <input type="text" class="form-control" name="date"
                                                        id="publish_date_picker" value="{{ old('date') }}">
                                                </div> --}}
                                            </div>
											
											

                                            <div class="col-md-12 col-12">
                                                <div class="form-group">
                                                    <label>مقدار</label>
                                                    <textarea class="form-control" name="value" rows="5">{{ old('value') }}</textarea>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit"
                                                    class="btn btn-primary mr-1 mb-1 waves-effect waves-light">اضافه کردن
                                                    رویداد</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>

                @if (isset($user_life_calendar) && $user_life_calendar->count())
                    <div class="card mt-3">
                        <div class="card-header">
                            <h4 class="card-title">رویدادهای ثبت‌شده</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th>عنوان</th>
                                            <th>روز</th>
                                            <th>تاریخ</th>
                                            <th>مقدار</th>
                                            <th>عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user_life_calendar as $event)
                                            <tr>
                                                <td>{{ $event->subject }}</td>
                                                <td>{{ $event->day }}</td>
                                                <td>{{ $event->date }}</td>
                                                <td>{{ $event->value }}</td>
                                                <td>
                                                    <a href="{{ route('admin.users.info.lifecalender.edit', $event->id) }}"
                                                        class="btn btn-sm btn-warning">ویرایش</a>
                                                    <form
                                                        action="{{ route('admin.users.info.lifecalender.destroy', $event->id) }}"
                                                        method="POST" style="display:inline-block;"
                                                        onsubmit="return confirm('آیا مطمئن هستید؟');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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
    <script src="{{ theme_asset('js/jalali-moment.js') }}"></script>

    <script src="{{ theme_asset('js/persiandatepicker.js') }}"></script>

    <script>
        newCalendar('publish_date_picker', {
            dayTitleFull: true,
            darkMode: true,
            theme: "#86ac22",
            closeCalendar: false
        })
    </script><!-- بارگذاری SDK لیفلت-نشان -->
@endpush
