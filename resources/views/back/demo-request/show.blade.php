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
                                    <li class="breadcrumb-item">لیست درخواست‌های دمو آنلاین
                                    </li>
									<li class="breadcrumb-item">درخواست دمو آنلاین
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
						<h4 class="card-title">درخواست دمو آنلاین</h4>
					</div>
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="col-12 col-md-6 mb-2">
									<div>نام و نام خانوادگی</div>
									<div class="mt-1">{{ $demoRequestItem->name }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>شماره موبایل</div>
									<div class="mt-1">{{ $demoRequestItem->mobile }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>پست الکترونیک</div>
									<div class="mt-1">{{ $demoRequestItem->email }}</div>
								</div>
								<div class="col-12 col-md-6 mb-2">
									<div>محصول</div>
									<div class="mt-1">
										<a href="{{ route('front.products.show',$demoRequestItem->product->slug) }}">{{ $demoRequestItem->product->title }}</a>
									</div>

								</div>
<form action="{{ route('admin.demo-request.destroy', $demoRequestItem->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger mr-1 waves-effect waves-light" onclick="return confirm('آیا مطمئن هستید که می‌خواهید حذف کنید؟');">حذف</button>
        </form>
							</div>
						</div>
					</div>
				</section>
            </div>
        </div>
    </div>

@endsection
