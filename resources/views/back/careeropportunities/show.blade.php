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
                                    <li class="breadcrumb-item">مدیریت
                                    </li>
                                    <li class="breadcrumb-item">لیست فرصت‌های شغلی
                                    </li>
									<li class="breadcrumb-item">فرصت‌ شغلی
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
				<section class="card">
					<div class="card-header">
						<h4 class="card-title">لیست فرصت‌ شغلی</h4>
					</div>
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="col-12 col-md-6 mb-2">
									<div>نام و نام خانوادگی</div>
									<div class="mt-1">{{ $careeropportunitiesItem->name }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>شماره موبایل</div>
									<div class="mt-1">{{ $careeropportunitiesItem->mobile }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>تاریخ تولد</div>
									<div class="mt-1">{{ $careeropportunitiesItem->birth_of_date ? jdate($careeropportunitiesItem->birth_of_date)->format('%d %B %Y') : null }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>وضعیت تاهل</div>
									<div class="mt-1">{{ $careeropportunitiesItem->is_married ? "متاهل" : "مجرد" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>وضعیت نظام وظیفه</div>
									<div class="mt-1">{{ $careeropportunitiesItem->military_status ? $careeropportunitiesItem->military_status : "-" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>شماره همراه</div>
									<div class="mt-1">{{ $careeropportunitiesItem->mobile }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>شماره تلفن</div>
									<div class="mt-1">{{ $careeropportunitiesItem->phone_number ? $careeropportunitiesItem->phone_number : "-" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>پست الکترونیک</div>
									<div class="mt-1">{{ $careeropportunitiesItem->email ? $careeropportunitiesItem->email : "-" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>استان</div>
									<div class="mt-1">{{ $careeropportunitiesItem->province ? $careeropportunitiesItem->province : "-" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>شهر</div>
									<div class="mt-1">{{ $careeropportunitiesItem->city ? $careeropportunitiesItem->city : "-" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>آدرس</div>
									<div class="mt-1">{{ $careeropportunitiesItem->address ? $careeropportunitiesItem->address : "-" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>میزان تحصیلات</div>
									<div class="mt-1">{{ $careeropportunitiesItem->level_of_education ? $careeropportunitiesItem->level_of_education : "-" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>رشته تحصیلی</div>
									<div class="mt-1">{{ $careeropportunitiesItem->field_of_education ? $careeropportunitiesItem->field_of_education : "-" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>محل تحصیل</div>
									<div class="mt-1">{{ $careeropportunitiesItem->education_place ? $careeropportunitiesItem->education_place : "-" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>ایا سابقه کار دارید؟</div>
									<div class="mt-1">{{ $careeropportunitiesItem->has_work_experience ? "بله" : "خیر" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>سوابق کاری</div>

									<div class="mt-1">{{ $careeropportunitiesItem->work_experience_description ? $careeropportunitiesItem->work_experience_description : "-" }}</div>

								</div>
<form action="{{ route('admin.careeropportunities.destroy', $careeropportunitiesItem->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger mr-1 waves-effect waves-light" onclick="return confirm('آیا مطمئن هستید که می‌خواهید حذف کنید؟');">حذف</button>
        </form>
								@if($careeropportunitiesItem->cv)
								<div class="col-12 col-md-6 mb-2">
									<div>رزومه</div>
									<div class="mt-1">
										<a href="{{ route('admin.careeropportunities.download',$careeropportunitiesItem->id) }}" class="btn btn-primary">دانلود</a>
									</div>
								</div>
								@endif
							</div>
						</div>
					</div>
				</section>
            </div>
        </div>
    </div>

@endsection
