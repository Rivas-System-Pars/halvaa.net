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
							<form id="contact-form" action="{{ route('front.demo-request.store') }}" method="POST">
								@csrf
								<h4 class="mb-5">درخواست دمو آنلاین</h4>
                        <div class="row">
							@if(session('success'))
							<div class="col-lg-12">
								<div class="alert alert-success">{{ session('success') }}</div>
							</div>
							@endif
							<div class="col-12 mb-4">
	<p>در شرکت ریواس سیستم پارس، ما به ارزش‌های نوآوری و رضایت مشتری پایبند هستیم. با درک اینکه هر کسب‌وکاری نیازمند راه‌حل‌هایی منحصر به فرد است، ما ارائه دموی آنلاین محصولات نرم‌افزاری خود را به شما پیشنهاد می‌دهیم تا بتوانید قبل از تصمیم‌گیری برای خرید، عملکرد و ویژگی‌های محصولات ما را به خوبی بررسی کنید.</p>
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
                                                    <h3>محصول مورد نظر</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <select class="input-ui pl-2" name="product">
														@foreach($products as $product_id=>$title)
														<option value="{{ $product_id }}" @if(old('product') == $product_id) seleceted @endif>{{ $title }}</option>
														@endforeach
													</select>
                                                </div>
												@error('product')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
											
											

                                        </div>

                                        
                                    
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-ui additional-info dt-sl">
                                   
                                        <div class="row">
											<div class="col-lg-12 ">
                                                <div class="form-row-title">
                                                    <h3>
														شماره همراه</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="number" class="input-ui pl-2 text-left dir-ltr"
                                                        name="mobile" value="{{ old('mobile') }}">
                                                </div>
												@error('mobile')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
<div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h3>
														پست الکترونیک</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="email" class="input-ui pl-2 text-left dir-ltr"
                                                        name="email" value="{{ old('email') }}">
                                                </div>
												@error('email')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
                                            
                                        

                                    </div>
                                </div>
                            </div>
                        </div>
								<div class="form-row mt-3 justify-content-center">
                                            <button id="submit-btn" type="submit"
                                                class="btn-primary-cm btn-with-icon ml-2">
                                                <i class="mdi mdi-message"></i>
                                                ارسال
                                            </button>
                                        </div>
<br/>

 <div class="col-md-8 col-6">
                                    <div class="form-group">
                                        <input type="text" class="input-ui pl-2 captcha" autocomplete="off" name="captcha" placeholder="کد امنیتی" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6">
                                    <img class="captcha w-100" src="{{ captcha_src('flat') }}" alt="captcha">
                                </div>
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
