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
							<form id="contact-form" action="{{ route('front.sales-agency.store') }}" method="POST" enctype="multipart/form-data">
								<h4 class="mb-5">درخواست نمایندگی فروش</h4>
								@csrf
                        <div class="row">
							@if(session('success'))
							<div class="col-lg-12">
								<div class="alert alert-success">{{ session('success') }}</div>
							</div>
							@endif
							@if(session('error'))
							<div class="col-lg-12">
								<div class="alert alert-danger">{{ session('error') }}</div>
							</div>
							@endif
							<div class="col-12 mb-4">
	<p>ما در شرکت ریواس سیستم، به دنبال شرکای تجاری متعهد و پویا هستیم تا شبکه فروش محصولاتمان را گسترش دهیم.این فرصت استثنایی برای کسانی ایجاد شده است که می‌خواهند در صنعت فروش نرم افزار، ردپایی ماندگار بر جای گذارند و از مزایای همکاری با یک برند پیشرو بهره‌مند شوند.
اگر مایل به همکاری به عنوان نماینده فروش هستید، فرم درخواست نمایندگی را تکمیل نمایید پس از بررسی اطلاعات شما، همکاران ما جهت ادامه روند و ارائه اطلاعات بیشتر با شما تماس خواهند گرفت.
بی‌صبرانه منتظر پیوستن شما به خانواده‌ی بزرگ ریواس سیستم هستیم و امیدواریم که با همکاری مشترک، به موفقیت‌های بزرگ‌تری دست یابیم.

</p>
                                            </div>
                            <div class="col-lg-6">
                                <div class="form-ui additional-info dt-sl">
                                    
                                        <div class="row">
											
											<div class="col-lg-12">
                                                <div class="form-row-title">
                                                    <h3>متقضی اخذ نمایندگی در کدام شهر یا استان هستید؟</h3>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-row-title">
                                                    <h3>استان</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="text" class="input-ui pl-2" name="province" value="{{ old('province') }}">
                                                </div>
												@error('province')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
                                            <div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h3>شهر</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="text" class="input-ui pl-2" name="city" value="{{ old('city') }}">
                                                </div>
												@error('city')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
											<div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h3>
														نام شرکت</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="text" class="input-ui pl-2 text-left dir-ltr"
                                                        name="company_name" value="{{ old('company_name') }}">
                                                </div>
												@error('company_name')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
											<div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h3>
														شماره ثبت</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="text" class="input-ui pl-2 text-left dir-ltr"
                                                        name="registration_number" value="{{ old('registration_number') }}">
                                                </div>
												@error('registration_number')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
											<div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h3>
														تاریخ شروع فعالیت</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="text" class="input-ui pl-2 text-left dir-ltr persianDate"
                                                        name="start_activity_date">
                                                </div>
												@error('start_activity_date')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
											<div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h3>
														موضوع فعالیت</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="text" class="input-ui pl-2 text-left dir-ltr"
                                                        name="activity_topic_description" value="{{ old('activity_topic_description') }}">
                                                </div>
												@error('activity_topic_description')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
											<div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h3>
														وب سایت</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="text" class="input-ui pl-2 text-left dir-ltr"
                                                        name="website" value="{{ old('website') }}">
                                                </div>
												@error('website')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
											<div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h3>
														فکس</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="number" class="input-ui pl-2 text-left dir-ltr"
                                                        name="fax" value="{{ old('fax') }}">
                                                </div>
												@error('fax')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
											<div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h3>
														شماره تلفن</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="number" class="input-ui pl-2 text-left dir-ltr"
                                                        name="phone_number" value="{{ old('phone_number') }}">
                                                </div>
												@error('phone_number')
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
											
											
<div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h3>آدرس</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="text" class="input-ui pl-2" name="address" value="{{ old('address') }}">
                                                </div>
												@error('address')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
                                        </div>
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="form-ui additional-info dt-sl">
                                   
                                        <div class="row">
											<div class="col-lg-12 mt-3">
                                                <div class="form-row-title">
                                                    <h3>
														نام و نام خانوادگی</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="text" class="input-ui pl-2 text-left dir-ltr"
                                                        name="name" value="{{ old('name') }}">
                                                </div>
												@error('name')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
                                            <div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h3>میزان تحصیلات</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="text" class="input-ui pr-2" name="level_of_education" value="{{ old('field_of_education') }}">
                                                </div>
												@error('level_of_education')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
                                            <div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h3>موبایل</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="text" class="input-ui pl-2 text-left dir-ltr"
                                                        name="mobile" value="{{ old('mobile') }}">
                                                </div>
												@error('mobile')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
                                            <div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h3>سوابق کاری</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="text" class="input-ui pl-2" name="work_experience_description" value="{{ old('work_experience_description') }}">
                                                </div>
												@error('work_experience_description')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
											
                                            <div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h3>نحوه آشنایی با شرکت ریواس سیستم </h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <select class="input-ui pl-2" name="method_of_introduction">
														<option value="توصیه دوستان">توصیه دوستان</option>
														<option value="جستجوی اینترنتی">جستجوی اینترنتی</option>
														<option value="فضای مجازی">فضای مجازی</option>
														<option value="پیامک های تبلیغاتی">پیامک های تبلیغاتی</option>
														<option value="تبلیغات محیطی">تبلیغات محیطی</option>
													</select>
                                                </div>
												@error('method_of_introduction')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
											<div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h3>ایا تاکنون در حوزه فروش محصولات نرم افزاری فعالیتی داشته اید؟ </h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <select class="input-ui pl-2" name="has_elling_software_products">
														<option value="1" @if(old('has_elling_software_products') == 1) selected @endif>خیر</option>
														<option value="2" @if(old('has_elling_software_products') == 2) selected @endif>بله</option>
													</select>
                                                </div>
												@error('has_elling_software_products')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
<div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h3>
														عنوان قرارداد یا پروژه</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="text" class="input-ui pl-2 text-left dir-ltr"
                                                        name="project_title" value="{{ old('project_title') }}">
                                                </div>
												@error('project_title')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div><div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h4>
                                                        جزئیات
                                                    </h4>
                                                </div>
                                                <div class="form-row form-group">
                                                    <textarea rows="10" name="description" class="input-ui pr-2 text-right">{{ old('description') }}</textarea>
                                                </div>
												@error('description')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
                                            
<div class="col-lg-12 mt-4 ">
                                                <div class="form-row-title">
                                                    <h3>اپلود رزومه</h3>
                                                </div>
	<style>input[type=file]::file-selector-button{
		height:100%;
		background:#9c27b0;
		color:#fff;
		border:none;
		outline:none;
		}</style>
                                                <div class="form-row form-group">
                                                    <input type="file" class="input-ui px-0" dir='ltr' name="cv">
                                                </div>
												@error('cv')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="form-row mt-4">
                                <div class="col-md-8 col-6">
                                    <div class="form-group">
                                        <input type="text" class="input-ui pl-2 captcha" autocomplete="off" name="captcha" placeholder="کد امنیتی" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6">
                                    <img class="captcha w-100" src="{{ captcha_src('flat') }}" alt="captcha">
                                </div>
                            </div>
									<div class="form-row mt-3 justify-content-center">
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
    <!-- End main-content -->

    <!-- end /.map -->

    <!--================================
            END BREADCRUMB AREA
        =================================-->
@endsection

@push('scripts')
    <!--<script src="{{ theme_asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ theme_asset('js/plugins/jquery-validation/localization/messages_fa.min.js') }}?v=2"></script>

    <script type="text/javascript" src="{{ theme_asset('mapp/js/mapp.env.js') }}"></script>
    <script type="text/javascript" src="{{ theme_asset('mapp/js/mapp.min.js?v=1') }}"></script>-->


    <!--<script>
        var info_map_type = "{{ option('info_map_type', 'google') }}"
        var info_latitude = "{{ option('info_latitude', '38.07709880960678') }}";
        var info_Longitude = "{{ option('info_Longitude', '46.28582686185837') }}";
        var info_site_title = "{{ option('info_site_title', 'داستار') }}";

        var mapIrApiKey =
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjYwMTBjYWE1OWU4ZDAyYzM0YWI2MGFhZDE5MTBhNjM5ZTZkYTI0MzA1ZmMwNzQzY2NmMjRkZmQ2Y2FlMzFjOThmODg4MjExYWY4ZDkwMGE1In0.eyJhdWQiOiIxMjcxOSIsImp0aSI6IjYwMTBjYWE1OWU4ZDAyYzM0YWI2MGFhZDE5MTBhNjM5ZTZkYTI0MzA1ZmMwNzQzY2NmMjRkZmQ2Y2FlMzFjOThmODg4MjExYWY4ZDkwMGE1IiwiaWF0IjoxNjEyODY3Mjc2LCJuYmYiOjE2MTI4NjcyNzYsImV4cCI6MTYxNTM3Mjg3Niwic3ViIjoiIiwic2NvcGVzIjpbImJhc2ljIl19.QNujb2BIyM8mIMy2AhivkMTpVCRyanpUIifJguxoEe4hXB1MESD2CWnO0WPq854Bi6yQyfD2w-oqjOi5N1aZmX4prggmrYelHy_mC1JEwAhWien_6QviFAvkhGDC-aPW4zjFKG2REUkQzXaeL2em543P6-hWdjFaUVSibm1XL4_CUnjJiafQsMQ67ZJ5E7Cpk92L89nJ0LMaBocex56tRqz7_7wZQUAtDYjfal90h2XaGh3QZ2rMwl69ZfMTrOEeTM9O6YCynT3IoTpDnNSXExJeMDuGv4zCD37UYG1gpVtNfipwgvc2J_LzLMXS4rnVAV2ednLKEYu7-jUXr68psg';
    </script>-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.0.0/dist/css/persian-datepicker.css">
<script src="
https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.min.js
"></script>
<script src="
https://cdn.jsdelivr.net/npm/persian-datepicker@1.0.0/dist/js/persian-datepicker.js
"></script>

<script>
$('.persianDate').persianDatepicker({
	initialValue: false,
	format: 'YYYY/MM/DD',
	viewMode: 'year'
});
</script>

{{-- <script src="{{ theme_asset('js/pages/contact.js?v=1') }}"></script> --}}
@endpush
