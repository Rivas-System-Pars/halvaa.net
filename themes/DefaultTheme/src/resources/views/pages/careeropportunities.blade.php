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
							<form id="contact-form" action="{{ route('front.careeropportunities.store') }}" method="POST" enctype="multipart/form-data">
								<h4 class="mb-5">شاغل شوید</h4>
								@csrf
                        <div class="row">
							@if(session('success'))
							<div class="col-lg-12">
								<div class="alert alert-success">{{ session('success') }}</div>
							</div>
							@endif
							<div class="col-12 mb-4">
	<p>ما در شرکت ریواس سیستم همواره به دنبال  کارشناسان متعهد و توانمند هستیم.اگر به دنبال یک جایگاه شغلی مناسب و همکاری طولانی مدت هستید جای شما در شرکت ما خالیست.</p>
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
                                                    <h3>
														تاریخ تولد</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="text" class="input-ui pl-2 text-left dir-ltr persianDate"
                                                        name="birth_of_date" @if(old('birth_of_date')) value="{{ old('birth_of_date') }}" @endif>
                                                </div>
												@error('birth_of_date')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
                                            <div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h3>وضعیت تاهل</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <select class="input-ui pl-2" name="is_married">
														<option value="1" @if(old('is_married') == 1) selected @endif>مجرد</option>
														<option value="2" @if(old('is_married') == 2) selected @endif>متاهل</option>
													</select>
                                                </div>
												@error('is_married')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
<div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h3>وضعیت نظام وظیفه</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <select class="input-ui pl-2" name="military_status">
														<option value="معاف">معاف</option>
														<option value="دارای کارت پایان خدمت">دارای کارت پایان خدمت</option>
													</select>
                                                </div>
												@error('military_status')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
											<div class="col-lg-12 mt-4">
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
                                        
                                    
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-ui additional-info dt-sl">
                                   
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-row-title">
                                                    <h3>میزان تحصیلات</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="text" class="input-ui pr-2" name="level_of_education" value="{{ old('level_of_education') }}">
                                                </div>
												@error('level_of_education')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
                                            <div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h3>رشته تحصیلی</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="text" class="input-ui pl-2 text-left dir-ltr"
                                                        name="field_of_education" value="{{ old('field_of_education') }}">
                                                </div>
												@error('field_of_education')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
                                            <div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h3>محل تحصیل</h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <input type="text" class="input-ui pl-2" name="education_place" value="{{ old('education_place') }}">
                                                </div>
												@error('education_place')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
											
                                            <div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h3>ایا سابقه کار دارید؟ </h3>
                                                </div>
                                                <div class="form-row form-group">
                                                    <select class="input-ui pl-2" name="has_work_experience">
														<option value="2" @if(old('has_work_experience') == 2) selected @endif>خیر</option>
														<option value="1" @if(old('has_work_experience') == 1) selected @endif>بله</option>
													</select>
                                                </div>
												@error('has_work_experience')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
                                            <div class="col-lg-12 mt-4">
                                                <div class="form-row-title">
                                                    <h4>
                                                        سوابق کاری خود را با ذکر نام و مدت آن ذکر نمایید
                                                    </h4>
                                                </div>
                                                <div class="form-row form-group">
                                                    <textarea rows="10" name="work_experience_description" class="input-ui pr-2 text-right">{{ old('work_experience_description') }}</textarea>
                                                </div>
												@error('work_experience_description')
												<span class="text-sm text-danger">{{ $message }}</span>
												@enderror
                                            </div>
                                        
										
<div class="col-lg-12 mt-4">
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
    <!-- End main-content -->

    <!-- end /.map -->

    <!--================================
            END BREADCRUMB AREA
        =================================-->
@endsection

@push('scripts')
 <script src="{{ theme_asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script> 
    <script src="{{ theme_asset('js/plugins/jquery-validation/localization/messages_fa.min.js') }}?v=2"></script>

   {{-- <script type="text/javascript" src="{{ theme_asset('mapp/js/mapp.env.js') }}"></script>
 <script type="text/javascript" src="{{ theme_asset('mapp/js/mapp.min.js?v=1') }}"></script> 

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDdbCAXvJIl7CKZwfpTswAIHvqJmZTUPwQ"></script>

    <script>
        var info_map_type = "{{ option('info_map_type', 'google') }}"
        var info_latitude = "{{ option('info_latitude', '38.07709880960678') }}";
        var info_Longitude = "{{ option('info_Longitude', '46.28582686185837') }}";
        var info_site_title = "{{ option('info_site_title', 'داستار') }}";

        var mapIrApiKey =
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjYwMTBjYWE1OWU4ZDAyYzM0YWI2MGFhZDE5MTBhNjM5ZTZkYTI0MzA1ZmMwNzQzY2NmMjRkZmQ2Y2FlMzFjOThmODg4MjExYWY4ZDkwMGE1In0.eyJhdWQiOiIxMjcxOSIsImp0aSI6IjYwMTBjYWE1OWU4ZDAyYzM0YWI2MGFhZDE5MTBhNjM5ZTZkYTI0MzA1ZmMwNzQzY2NmMjRkZmQ2Y2FlMzFjOThmODg4MjExYWY4ZDkwMGE1IiwiaWF0IjoxNjEyODY3Mjc2LCJuYmYiOjE2MTI4NjcyNzYsImV4cCI6MTYxNTM3Mjg3Niwic3ViIjoiIiwic2NvcGVzIjpbImJhc2ljIl19.QNujb2BIyM8mIMy2AhivkMTpVCRyanpUIifJguxoEe4hXB1MESD2CWnO0WPq854Bi6yQyfD2w-oqjOi5N1aZmX4prggmrYelHy_mC1JEwAhWien_6QviFAvkhGDC-aPW4zjFKG2REUkQzXaeL2em543P6-hWdjFaUVSibm1XL4_CUnjJiafQsMQ67ZJ5E7Cpk92L89nJ0LMaBocex56tRqz7_7wZQUAtDYjfal90h2XaGh3QZ2rMwl69ZfMTrOEeTM9O6YCynT3IoTpDnNSXExJeMDuGv4zCD37UYG1gpVtNfipwgvc2J_LzLMXS4rnVAV2ednLKEYu7-jUXr68psg';
    </script> --}}
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
