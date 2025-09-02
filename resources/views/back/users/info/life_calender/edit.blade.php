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
                                <li class="breadcrumb-item">مدیریت متوفی</li>
                                <li class="breadcrumb-item active">ویرایش گاهشمار زندگی</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <section id="main-card" class="card">
                <div class="card-header">
                    <h4 class="card-title">ویرایش رویداد زندگی</h4>
                </div>

                <div class="card-content">
                    <div class="card-body">
                        <div class="col-12 col-md-10 offset-md-1">
                            <form class="form" action="{{ route('admin.users.info.lifecalender.update', $event->id) }}" method="POST">
                                @csrf
                                {{-- No @method('PUT') because route is POST --}}
                                <div class="form-body">
                                    <div class="row">
                                        <!-- عنوان -->
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>عنوان رویداد</label>
                                                <select class="form-control" name="subject">
                                                    <option value="">-- انتخاب کنید --</option>
                                                    @foreach([
                                                        'تولد', 'اعزام به سربازی', 'ازدواج با خانم', 'ازدواج با آقا',
                                                        'قبولی در دانشگاه', 'سفر به کشور', 'فوت پدر', 'فوت مادر',
                                                        'صاحب فرزند دختر', 'صاحب فرزند پسر'
                                                    ] as $subject)
                                                        <option value="{{ $subject }}" {{ $event->subject == $subject ? 'selected' : '' }}>
                                                            {{ $subject }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- روز -->
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>روز</label>
                                                <select class="form-control" name="day">
                                                    <option value="">-- انتخاب کنید --</option>
                                                    @foreach(['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'] as $day)
                                                        <option value="{{ $day }}" {{ $event->day == $day ? 'selected' : '' }}>
                                                            {{ $day }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- تاریخ -->
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>تاریخ</label>
                                                <input type="text" class="form-control" name="date" id="publish_date_picker" value="{{ $event->date }}">
                                            </div>
                                        </div>

                                        <!-- مقدار -->
                                        <div class="col-md-12 col-12">
                                            <div class="form-group">
                                                <label>مقدار</label>
                                                <textarea class="form-control" name="value" rows="5">{{ $event->value }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-success mr-1 mb-1 waves-effect waves-light">ذخیره تغییرات</button>
                                            <a href="{{ route('admin.users.info.lifecalender.create') }}" class="btn btn-secondary">بازگشت</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            <!-- جدول نمایش -->
            @if($user_life_calendar->count())
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="card-title">رویدادهای ثبت‌شده</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered text-center">
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
                                @foreach($user_life_calendar as $item)
                                    <tr>
                                        <td>{{ $item->subject }}</td>
                                        <td>{{ $item->day }}</td>
                                        <td>{{ $item->date }}</td>
                                        <td>{{ $item->value }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.info.lifecalender.edit', $item->id) }}" class="btn btn-sm btn-warning">ویرایش</a>
                                            <form action="{{ route('admin.users.info.lifecalender.destroy', $item->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('آیا مطمئن هستید؟');">
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
            @endif
        </div>
    </div>
</div>
@endsection
