@extends('front::layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ theme_asset('mapp/css/mapp.min.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('mapp/css/fa/style.css') }}">
@endpush

@push('meta')
    <link rel="canonical" href="{{ route('front.contact.index') }}" />
@endpush

@section('content')
    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">

            <div class="row">
                <div class="col-12">
                    <div class="page dt-sl dt-sn pt-3 pb-5 px-5">
							<form id="contact-form" action="{{ route('front.counseling-form.store') }}" method="POST">
								@csrf
								<h4 class="mb-5">فرم مشاوره خرید</h4>
                        <div class="row">
							@if(session('success'))
							<div class="col-lg-12">
								<div class="alert alert-success">{{ session('success') }}</div>
							</div>
							@endif
							<div class="col-12 mb-4">
	<p>در دنیای پیشرفته امروزی، انتخاب و خرید نرم‌افزار مناسب می‌تواند به یکی از چالش‌برانگیزترین تصمیم‌ها برای هر کسب‌وکار یا فرد تبدیل شود. با وجود گستردگی و تنوع بالای محصولات نرم‌افزاری در بازار، شناخت کامل تمامی گزینه‌ها و انتخاب بهترین آن‌ها بر اساس نیازها و اهداف خاص، نیازمند دانش و تخصصی است که ممکن است برای همه قابل دسترس نباشد.

مشاوره خرید محصولات نرم‌افزاری می‌تواند راهکاری ایده‌آل برای رفع این چالش باشد. با استفاده از خدمات مشاوره، شما به کارشناسانی دسترسی پیدا می‌کنید که با بازار نرم‌افزارها به خوبی آشنا هستند و می‌توانند با درک نیازها و اولویت‌های شما، گزینه‌هایی سفارشی و مطابق با بودجه و اهداف‌تان پیشنهاد دهند.

اهمیت این موضوع زمانی بیشتر آشکار می‌شود که در نظر بگیریم انتخاب نادرست یک نرم‌افزار می‌تواند منجر به هزینه‌های اضافی، از دست دادن زمان و حتی کاهش بهره‌وری و کارایی کلی یک تیم یا سازمان شود. برعکس، یک انتخاب درست و مطلع نه تنها به افزایش کارایی و رضایت کاربران منجر می‌شود، بلکه می‌تواند در طولانی مدت به صرفه‌جویی قابل توجهی در منابع و هزینه‌ها کمک کند.

برای راهنمایی بیشتر و استفاده از خدمات مشاوره ما، لطفا با ما تماس حاصل فرمایید. کارشناسان ما آماده پاسخگویی به سوالات شما و راهنمایی در انتخاب بهترین گزینه‌های نرم‌افزاری بر اساس نیازهای دقیق شما هستند.</p>
                                            </div>
                            <div class="col-lg-6">
                                <div class="form-ui additional-info dt-sl">
                                    
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-row-title">
                                                    <h3>نام و نام خانوادگی</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="text" class="input-ui pr-2" name="name" value="{{ old('name') }}">
                                                </div>
												@error('name')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
											
											
											<div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h4>
                                                        شماره تماس
                                                    </h4>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="text" name="mobile" class="input-ui pr-2">{{ old('mobile') }}</input>
                                                </div>
												@error('mobile')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
											
											

                                        </div>

                                        
                                    
                                </div>
                            </div>
							
							<div class="col-lg-6">
                                <div class="form-ui additional-info dt-sl">
                                    
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-row-title">
                                                    <h3>استان</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="text" class="input-ui pr-2" name="province" value="{{ old('province') }}">
                                                </div>
												@error('province')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
											
											
											<div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h4>
                                                        شهر
                                                    </h4>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="text" name="city" class="input-ui pr-2">{{ old('city') }}</input>
                                                </div>
												@error('city')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
											
											

                                        </div>

                                        
                                    
                                </div>
                            </div>
							
							<div class="col-lg-6">
                                <div class="form-ui additional-info dt-sl">
                                    
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-row-title">
                                                    <h3>حوزه فعالیت</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="text" class="input-ui pr-2" name="activity" value="{{ old('activity') }}">
                                                </div>
												@error('activity')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
											
											
											<div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h4>
                                                        توضیحات تکمیلی
                                                    </h4>
                                                </div>
                                                <div class="form-row form-group">
                                                    <textarea rows="10" name="description" class="input-ui pr-2 text-right">{{ old('description') }}</textarea>
                                                </div>
												@error('description')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
											
											

                                        </div>

                                        
                                    
                                </div>
                            </div>
							
							
                           
                        </div><div class="form-row mt-3 justify-content-center">
                                            <button id="submit-btn" type="submit"
                                                class="btn-primary-cm btn-with-icon ml-2">
                                                <i class="mdi mdi-message"></i>
                                                ارسال
                                            </button>
                                        </div>
							</form>
                        <div class="row row-deck">
                            <div class="col-md-4">
                                <div class="contact_tile block">
                                    <span class="tiles__icon icon-location-pin"></span>
                                    <h6 class="tiles__title">آدرس </h6>
                                    <div class="tiles__content">
                                        <p>{{ option('info_address') }}</p>
                                    </div>
                                </div>
                            </div>
                            <!-- end /.col-md-4 -->

                            <div class="col-md-4">
                                <div class="contact_tile block">
                                    <span class="tiles__icon icon-earphones"></span>
                                    <h6 class="tiles__title">شماره تماس</h6>
                                    <div class="tiles__content">
                                        <p>{{ option('info_tel') }}</p>
                                    </div>
                                </div>
                                <!-- end /.contact_tile block -->
                            </div>
                            <!-- end /.col-md-4 -->

                            <div class="col-md-4">
                                <div class="contact_tile block">
                                    <span class="tiles__icon icon-envelope-open"></span>
                                    <h6 class="tiles__title">آدرس ایمیل</h6>
                                    <div class="tiles__content">
                                        <p>{{ option('info_email') }}</p>
                                    </div>
                                </div>
                                <!-- end /.contact_tile -->
                            </div>
                            <!-- end /.col-md-4 -->
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection
