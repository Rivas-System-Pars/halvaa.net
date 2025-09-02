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
                                    <li class="breadcrumb-item">لیست درخواست های مشاوره خرید
                                    </li>
									<li class="breadcrumb-item"> درخواست های مشاوره خرید
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
						<h4 class="card-title">لیست درخواست های مشاوره خرید</h4>
					</div>
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="col-12 col-md-6 mb-2">
									<div>نام و نام خانوادگی</div>
									<div class="mt-1">{{ $counselingFormItem->name }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>شماره موبایل</div>
									<div class="mt-1">{{ $counselingFormItem->mobile }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>استان</div>
									<div class="mt-1">{{ $counselingFormItem->province }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>شهر</div>
									<div class="mt-1">{{ $counselingFormItem->city }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>حوزه فعالیت</div>
									<div class="mt-1">{{ $counselingFormItem->activity }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>توضیحات تکمیلی موضوع مشاوره</div>
									<div class="mt-1">{{ $counselingFormItem->description }}</div>
								</div>
							</div>
						</div>
					</div>
				</section>
            </div>
        </div>
    </div>

@endsection
