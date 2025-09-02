@extends('front::layouts.master')



@push('meta')
<link rel="canonical" href="{{ route('front.contact.index') }}" />
@endpush

@section('content')
<!--Start main-content-->
<main class="main-content dt-sl mt-4 mb-3">
    <div class="container main-container">

        <div class="row">
            <div class="col-12">
                <div class="page dt-sl dt-sn pt-3 pb-5">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-ui additional-info dt-sl">
<h4>ارتباط با ما  <h4/>
							<h5>
ما ۵ نفر از جوانان فارغ التحصیل رشته های کامپیوتر، ادبیات و علوم اجتماعی برای تسهیل در انتقال احساسات و عواطف انسانی و بودن در کنار آنهایی که حس تنهایی دارند، تیم حلوا را تشکیل دادیم. حلوا را از ما بپذیرید.
<h5/>

                                <form id="contact-form" action="{{ route('front.contact.store') }}" method="POST">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-row-title">
                                                <h3>نام و نام خانوادگی</h3>
                                            </div>
                                            <div class="form-row form-group">
                                                <input type="text" class="input-ui pr-2" name="name">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mt-4">
                                            <div class="form-row-title">
                                                <h3>آدرس ایمیل</h3>
                                            </div>
                                            <div class="form-row form-group">
                                                <input type="email" class="input-ui pl-2 text-left dir-ltr"
                                                    name="email">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mt-4">
                                            <div class="form-row-title">
                                                <h3>نام شرکت یا موسسه</h3>
                                            </div>
                                            <div class="form-row form-group">
                                                <input type="text" class="input-ui pl-2" name="subject">
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mt-4">
                                            <div class="form-row-title">
                                                <h4>
                                                    متن پیام
                                                </h4>
                                            </div>
                                            <div class="form-row form-group">
                                                <textarea rows="10" name="message"
                                                    class="input-ui pr-2 text-right"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-4" style="direction: rtl; text-align: right;">
                                        <div class="form-row-title">
                                            <h3 style="margin:0 0 8px;">شماره موبایل</h3>
                                        </div>
                                        <div class="form-row form-group" style="margin-bottom: 10px;">
                                            <input type="tel" name="mobile" inputmode="tel" value="{{ old('mobile') }}"
                                                pattern="^(?:\+?98|0)?9\d{9}$" title="شماره موبایل نامعتبر است" style="
                width: 100%;
                box-sizing: border-box;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                padding: 10px 12px;
                height: 44px;
                outline: none;
                direction: ltr;
                text-align: left;
                background: #fff;
                box-shadow: inset 0 1px 2px rgba(0,0,0,0.03);
                transition: border-color .2s ease;
            " onfocus="this.style.borderColor='#9c27b0'" onblur="this.style.borderColor='#e0e0e0'">
                                            @error('mobile')
                                            <div style="color:#d32f2f; font-size:12px; margin-top:6px;">{{ $message }}
                                            </div>
                                            @enderror
                                            <div style="color:#757575; font-size:12px; margin-top:6px;">فرمت مجاز:
                                                09xxxxxxxxx</div>
                                        </div>
                                    </div>


                                    <div class="col-lg-12 mt-4" style="direction: rtl; text-align: right;">
                                        <div class="form-row-title">
                                            <h3 style="margin:0 0 8px;">آپلود تصویر</h3>
                                        </div>

                                        <div class="form-row form-group" style="margin-bottom: 10px;">
                                            <input type="file" name="image" id="imageInput" accept="image/*" style="
                display:block;
                width:100%;
                box-sizing:border-box;
                border:1px solid #e0e0e0;
                border-radius:8px;
                padding:8px;
                background:#fff;
                cursor:pointer;
                transition: border-color .2s ease;
            " onfocus="this.style.borderColor='#9c27b0'" onblur="this.style.borderColor='#e0e0e0'">
                                            @error('image')
                                            <div style="color:#d32f2f; font-size:12px; margin-top:6px;">{{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        {{-- preview box --}}
                                        <div id="previewWrapper" style="
        display:none;
        border:1px dashed #c7c7c7;
        border-radius:10px;
        padding:10px;
        background:#fafafa;
        text-align:center;
        margin-top:10px;
    ">
                                            <img id="imagePreview" src="" alt="preview" style="
            max-width:100%;
            height:auto;
            border-radius:8px;
            box-shadow:0 2px 6px rgba(0,0,0,0.08);
        ">
                                            <div style="margin-top:8px;">
                                                <button type="button" id="clearImageBtn" style="
                border:1px solid #e0e0e0;
                background:#fff;
                border-radius:8px;
                padding:6px 10px;
                font-size:13px;
                cursor:pointer;
            ">حذف تصویر</button>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-row mt-4">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" class="input-ui pl-2 captcha" autocomplete="off"
                                                    name="captcha" placeholder="کد امنیتی" required>
                                            </div>
											<br/>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <img class="captcha" src="{{ captcha_src('flat') }}" alt="captcha">
                                        </div>
                                    </div>

                                    <div class="form-row mt-3 justify-content-center">
                                        <button id="submit-btn" type="submit" class="btn-primary-cm btn-with-icon ml-2">
                                            <i class="mdi mdi-message"></i>
                                            ارسال پیام
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
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
                        <!-- end /.col-md-4-->

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
                            <!-- end /.contact_tile-->
                        </div>
                        <!-- end /.col-md-4-->
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


<script src="{{ theme_asset('js/pages/contact.js?v=1') }}"></script>

<script>
    (function () {
        var input = document.getElementById('imageInput');
        var previewWrapper = document.getElementById('previewWrapper');
        var previewImg = document.getElementById('imagePreview');
        var clearBtn = document.getElementById('clearImageBtn');

        if (input) {
            input.addEventListener('change', function (e) {
                var file = e.target.files && e.target.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function (ev) {
                        previewImg.src = ev.target.result;
                        previewWrapper.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewImg.src = '';
                    previewWrapper.style.display = 'none';
                }
            });
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                input.value = '';
                previewImg.src = '';
                previewWrapper.style.display = 'none';
            });
        }
    })();
</script>
@endpush
