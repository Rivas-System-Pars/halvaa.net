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
                                    <li class="breadcrumb-item">لیست درخواست‌های نمایندگی فروش
                                    </li>
									<li class="breadcrumb-item">درخواست نمایندگی فروش
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
						<h4 class="card-title">درخواست نمایندگی فروش</h4>
					</div>
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="col-12 col-md-6 mb-2">
									<div>نام و نام خانوادگی</div>
									<div class="mt-1">{{ $salesAgencyItem->name }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>استان</div>
									<div class="mt-1">{{ $salesAgencyItem->province ? $salesAgencyItem->province : "-" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>شهر</div>
									<div class="mt-1">{{ $salesAgencyItem->city ? $salesAgencyItem->city : "-" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>آدرس</div>
									<div class="mt-1">{{ $salesAgencyItem->address ? $salesAgencyItem->address : "-" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>نام شرکت</div>
									<div class="mt-1">{{ $salesAgencyItem->company_name ? $salesAgencyItem->company_name : "-" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>شماره ثبت</div>
									<div class="mt-1">{{ $salesAgencyItem->registration_number ? $salesAgencyItem->registration_number : "-" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>تاریخ شروع فعالیت</div>
									<div class="mt-1">{{ $salesAgencyItem->start_activity_date ? jdate($salesAgencyItem->start_activity_date)->format('%d %B %Y') : null }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>شماره موبایل</div>
									<div class="mt-1">{{ $salesAgencyItem->mobile }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>میزان تحصیلات</div>
									<div class="mt-1">{{ $salesAgencyItem->level_of_education ? $salesAgencyItem->level_of_education : "-" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>موضوع فعالیت</div>
									<div class="mt-1">{{ $salesAgencyItem->activity_topic_description ? $salesAgencyItem->activity_topic_description : "-" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>وبسایت</div>
									<div class="mt-1">{{ $salesAgencyItem->website ? $salesAgencyItem->website : "-" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>فکس</div>
									<div class="mt-1">{{ $salesAgencyItem->fax ? $salesAgencyItem->fax : "-" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>شماره تلفن</div>
									<div class="mt-1">{{ $salesAgencyItem->phone_number ? $salesAgencyItem->phone_number : "-" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>پست الکترونیک</div>
									<div class="mt-1">{{ $salesAgencyItem->email ? $salesAgencyItem->email : "-" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>سوابق کاری</div>
									<div class="mt-1">{{ $salesAgencyItem->work_experience_description ? $salesAgencyItem->work_experience_description : "-" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>نحوه آشنایی با شرکت ریواس سیستم</div>
									<div class="mt-1">{{ $salesAgencyItem->method_of_introduction ? $salesAgencyItem->method_of_introduction : "-" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>ایا تاکنون در حوزه فروش محصولات نرم افزاری فعالیتی داشته اید؟</div>
									<div class="mt-1">{{ $salesAgencyItem->has_elling_software_products && $salesAgencyItem->has_elling_software_products == 2 ? "بله" : "خیر" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>عنوان قرارداد یا پروژه</div>
									<div class="mt-1">{{ $salesAgencyItem->project_title ? $salesAgencyItem->project_title : "-" }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>جزئیات</div>
									<div class="mt-1">{{ $salesAgencyItem->description ? $salesAgencyItem->description : "-" }}</div>

								</div>
								@if($salesAgencyItem->cv)
								<div class="col-12 col-md-6 mb-2">
									<div>رزومه</div>
									<div class="mt-1">
										<a href="{{ route('admin.sales-agency.download',$salesAgencyItem->id) }}" class="btn btn-primary">دانلود</a>
									</div>
								</div>
								@endif
								<div class="col-12 mb-2">
									<div>تمایل به فروش کدام یک از محصولات و نرم افزارها را دارید؟</div>
									<div class="mt-1">
										@if($salesAgencyItem->products->count())
											@foreach($salesAgencyItem->products as $product)
												<a href="{{ route('front.products.show',$product->slug) }}">{{ $product->title }}</a>
											@endforeach
										@else
										<span>محصولی انتخاب نشده است</span>
										@endif


										
									</div>
<form action="{{ route('admin.sales-agency.destroy', $salesAgencyItem->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger mr-1 waves-effect waves-light" onclick="return confirm('آیا مطمئن هستید که می‌خواهید حذف کنید؟');">حذف</button>
        </form>

								</div>
							</div>
						</div>

					</div>

				</section>
            </div>

        </div>
    </div>

@endsection
