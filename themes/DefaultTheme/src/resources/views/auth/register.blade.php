@extends('front::auth.layouts.master', ['title' => 'ثبت نام در سایت'])



@section('content')
    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">

        <div class="container main-container">

            <div class="row">

                <div class="col-xl-4 col-lg-5 col-md-7 col-12 mx-auto">

                    <div class="form-ui dt-sl dt-sn pt-4">

                        <div class="section-title title-wide mb-1 no-after-title-wide">
                            <h2 class="font-weight-bold">ثبت آرامگاه جدید</h2>
                        </div>

                        <form id="register-form" action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="form-row-title">
                                <h3>نام</h3>
                            </div>
                            <div class="form-row form-group">
                                <input type="text" name="first_name" class="input-ui pr-2"
                                    placeholder="  نام متوفی را وارد نمایید">
                            </div>
                            <div class="form-row-title">
                                <h3>نام خانوادگی</h3>
                            </div>
                            <div class="form-row form-group">
                                <input type="text" name="last_name" class="input-ui pr-2"
                                    placeholder="  نام خانوادگی متوفی را وارد نمایید">
                            </div>

                            <div class="form-row-title">
                                <h3>عکس پروفایل</h3>
                            </div>

                            <div class="form-group text-center">
                                <label for="image-upload" style="cursor: pointer;">
                                    <img id="profile-preview" 
                                        style="width: 110px; height: 110px; border-radius: 50%; object-fit: cover; border: 2px solid #ccc;flex-shrink: 0;display: flex;align-items: center;justify-content: center;">
                                    <div class="mt-2 text-muted">برای آپلود کلیک کنید</div>
                                </label>
                                <input type="file" name="image" id="image-upload" style="display: none;">
                            </div>

                            <div class="form-row-title">
                                <h3>نام کاربری</h3>
                            </div>
                            <div class="form-row with-icon form-group">
                                <input type="text" name="username" class="input-ui pr-2"
                                    placeholder="  نام کاربری  متوفی را وارد نمایید">
                                <i class="mdi mdi-account-circle-outline"></i>
                            </div>

                            <div class="form-row-title">
                                <h3>تاریخ تولد</h3>
                            </div>
                            <div class="form-row with-icon form-group">

                                <input type="text" id="birthDatePicker" name="birth"
                                    placeholder="تاریخ تولد را انتخاب کنید" class="input-ui pr-2">

                                <i class="mdi mdi-calendar"></i>
                            </div>

                            {{-- محل تولد --}}
                            <div class="form-row-title">
                                <h3>کشور تولد</h3>
                            </div>
                            <div class="form-row form-group">
                                <select name="birth_country_id" id="birth-country" class="input-ui pr-2">
                                    <option value="IRAN_LOCAL">ایران</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- ایران: استان/شهر --}}
                            <div id="birth-iran-fields" class="mt-2">
                                <div class="form-row-title">
                                    <h3>استان تولد</h3>
                                </div>
                                <div class="form-row form-group">
                                    <select id="birth-province" class="input-ui pr-2">
                                        <option value="">انتخاب استان</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-row form-group">
                                    <select name="birth_city_id" id="birth-city" class="input-ui pr-2">
                                        <option value="">ابتدا استان را انتخاب کنید</option>
                                    </select>
                                </div>
                            </div>

                            {{-- خارج از ایران: شهر متنی --}}
                            <div id="birth-foreign-fields" class="mt-2" style="display:none;">
                                <div class="form-row-title">
                                    <h3>شهر تولد (خارج از ایران)</h3>
                                </div>
                                <div class="form-row form-group">
                                    <input type="text" name="birth_city_foreign" class="input-ui pr-2"
                                        placeholder="نام شهر را وارد کنید">
                                </div>
                            </div>


                            <div class="form-row-title">
                                <h3>تاریخ وفات</h3>
                            </div>
                            <div class="form-row with-icon form-group">

                                <input type="text" id="deathDatePicker" name="death"
                                    placeholder="تاریخ وفات را انتخاب کنید" class="input-ui pr-2">
                                <i class="mdi mdi-calendar"></i>
                            </div>


                            {{-- محل وفات --}}
                            <div class="form-row-title">
                                <h3>کشور وفات</h3>
                            </div>
                            <div class="form-row form-group">
                                <select name="death_country_id" id="death-country" class="input-ui pr-2">
                                    <option value="IRAN_LOCAL">ایران</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- ایران: استان/شهر --}}
                            <div id="death-iran-fields" class="mt-2">
                                <div class="form-row-title">
                                    <h3>استان وفات</h3>
                                </div>
                                <div class="form-row form-group">
                                    <select id="death-province" class="input-ui pr-2">
                                        <option value="">انتخاب استان</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-row form-group">
                                    <select name="death_city_id" id="death-city" class="input-ui pr-2">
                                        <option value="">ابتدا استان را انتخاب کنید</option>
                                    </select>
                                </div>
                            </div>

                            {{-- خارج از ایران: شهر متنی --}}
                            <div id="death-foreign-fields" class="mt-2" style="display:none;">
                                <div class="form-row-title">
                                    <h3>شهر وفات (خارج از ایران)</h3>
                                </div>
                                <div class="form-row form-group">
                                    <input type="text" name="death_city_foreign" class="input-ui pr-2"
                                        placeholder="نام شهر را وارد کنید">
                                </div>
                            </div>

                            <br>
                            <div class="form-row form-group">
                                <select name="death_city_id" id="death-city" class="input-ui pr-2">
                                    <option value="">ابتدا استان را انتخاب کنید</option>
                                </select>
                            </div>
                            <div class="form-row-title">
                                <h3>شماره موبایل</h3>
                            </div>
                            <div class="form-row with-icon form-group">
                                <input type="text" name="phone_number" class="input-ui pr-2"
                                    placeholder="  شماره موبایل  را وارد نمایید">
                                <i class="mdi mdi-phone"></i>
                            </div>
                            <div class="form-row-title d-none">
                                <h3>کد ملی</h3>
                            </div>
                            <div class="form-row with-icon form-group">
                                <input type="text" name="national_code" class="input-ui pr-2 d-none"
                                    placeholder="  کد ملی متوفی را وارد نمایید">
                                <i class="mdi mdi-account-circle-outline d-none"></i>
                            </div>
                            <div class="form-row-title">
                                <h3>رمز عبور</h3>
                            </div>
                            <div class="form-row with-icon form-group">
                                <input id="password" type="text" name="password" class="input-ui pr-2"
                                    placeholder="رمز عبور را وارد نمایید">
                                <i class="mdi mdi-lock-open-variant-outline"></i>
                            </div>

                            <div class="form-row-title">
                                <h3>تکرار رمز عبور</h3>
                            </div>
                            <div class="form-row form-group">
                                <input type="text" name="password_confirmation" class="input-ui pr-2"
                                    placeholder="تکرار رمز عبور را وارد نمایید">
                            </div>
                            <div class="form-row-title mt-3">
                                <h3> خصوصی بودن حساب متوفی </h3>
                            </div>
                            <div class="form-row form-group">
                                <label class="ui-checkbox">
                                    <input type="checkbox" name="is_private" value="1">
                                    <span class="ui-checkbox-check"></span>
                                    می‌خواهم حساب متوفی خصوصی باشد (فقط دنبال‌کننده‌ها پست‌ها را ببینند)
                                </label>
                            </div>

                            <input type="hidden" name="level" value="admin">


                            <div class="row p-0">
                                <div class="col-12">
                                    <h5>انتخاب محل آرامگاه</h5>
                                    <hr>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label class="mb-2">طول جغرافیایی</label>
                                            <input type="number" step="any" id="Longitude" name="longitude"
                                                class="form-control rounded-3"
                                                value="{{ option('info_Longitude', '46.28582686185837') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label class="mb-2">عرض جغرافیایی</label>
                                            <input type="number" step="any" id="latitude" name="latitude"
                                                class="form-control rounded-3"
                                                value="{{ option('info_latitude', '38.07709880960678') }}">
                                        </div>
                                    </div>
                                </div>



                                <div class="col-12 my-3">
                                    <div id="halvaaMap" style="height: 300px;" class="w-100 rounded-4 overflow-hidden">
                                    </div>
                                </div>


                            </div>

                            <div class="form-row mt-4 row g-0">
                                <div class="col-md-8 col-6 ps-0">
                                    <div class="form-group">
                                        <input type="text" class="input-ui pl-2 captcha" autocomplete="off"
                                            name="captcha" placeholder="کد امنیتی" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6 pe-0 ps-2">
                                    <img class="captcha w-100" src="{{ captcha_src('flat') }}" alt="captcha">
                                </div>
                            </div>

                            <div class="form-row form-group">
                                <label class="ui-checkbox">
                                    <input type="checkbox" name="policy" value="1" required>
                                    <span class="ui-checkbox-check"></span>
                                    با قوانین موافقم
                                </label>
                            </div>

                            <div class="form-row mt-3">
                                <button class="btn-primary-cm btn-with-icon mx-auto w-100">
                                    <i class="mdi mdi-account-circle-outline"></i>
                                    ثبت آرامگاه جدید
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </main>
    <!-- End main-content -->
@endsection

@include('back.partials.plugins', [
    'plugins' => ['jquery-tagsinput', 'jquery.validate'],
])


@push('scripts')
    <script>
        var redirect_url =
            '{{ request('
                                redirect ') ?:
                Redirect::intended()->getTargetUrl() }}';
    </script>


    <script src="{{ theme_asset('js/pages/register.js') }}?v=2"></script>

    <script>
        document.getElementById('image-upload').addEventListener('change', function(event) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('profile-preview').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        });

        const provinces = @json($provinces);

        function handleCityDropdown(provinceSelectId, citySelectId) {
            document.getElementById(provinceSelectId).addEventListener('change', function() {
                const selectedProvinceId = this.value;
                const citySelect = document.getElementById(citySelectId);
                citySelect.innerHTML = '<option value="">در حال بارگذاری شهرها...</option>';

                const selectedProvince = provinces.find(p => p.id == selectedProvinceId);

                if (selectedProvince && selectedProvince.cities.length) {
                    citySelect.innerHTML = '<option value="">شهر را انتخاب کنید</option>';
                    selectedProvince.cities.forEach(city => {
                        citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
                    });
                } else {
                    citySelect.innerHTML = '<option value="">هیچ شهری یافت نشد</option>';
                }
            });
        }

        handleCityDropdown('birth-province', 'birth-city');
        handleCityDropdown('death-province', 'death-city');

        //var info_latitude = "{{ option('info_latitude', '38.07709880960678') }}";
        //var info_Longitude = "{{ option('info_Longitude', '46.28582686185837') }}";
        /*
            var mapIrApiKey =
                'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjYwMTBjYWE1OWU4ZDAyYzM0YWI2MGFhZDE5MTBhNjM5ZTZkYTI0MzA1ZmMwNzQzY2NmMjRkZmQ2Y2FlMzFjOThmODg4MjExYWY4ZDkwMGE1In0.eyJhdWQiOiIxMjcxOSIsImp0aSI6IjYwMTBjYWE1OWU4ZDAyYzM0YWI2MGFhZDE5MTBhNjM5ZTZkYTI0MzA1ZmMwNzQzY2NmMjRkZmQ2Y2FlMzFjOThmODg4MjExYWY4ZDkwMGE1IiwiaWF0IjoxNjEyODY3Mjc2LCJuYmYiOjE2MTI4NjcyNzYsImV4cCI6MTYxNTM3Mjg3Niwic3ViIjoiIiwic2NvcGVzIjpbImJhc2ljIl19.QNujb2BIyM8mIMy2AhivkMTpVCRyanpUIifJguxoEe4hXB1MESD2CWnO0WPq854Bi6yQyfD2w-oqjOi5N1aZmX4prggmrYelHy_mC1JEwAhWien_6QviFAvkhGDC-aPW4zjFKG2REUkQzXaeL2em543P6-hWdjFaUVSibm1XL4_CUnjJiafQsMQ67ZJ5E7Cpk92L89nJ0LMaBocex56tRqz7_7wZQUAtDYjfal90h2XaGh3QZ2rMwl69ZfMTrOEeTM9O6YCynT3IoTpDnNSXExJeMDuGv4zCD37UYG1gpVtNfipwgvc2J_LzLMXS4rnVAV2ednLKEYu7-jUXr68psg';*/
    </script>
    {{--
<script src="{{ asset('back/assets/js/pages/settings/information.js') }}"></script>
<script src="{{ asset('back/app-assets/js/scripts/navs/navs.js') }}"></script>
--}}
    <script src="{{ theme_asset('js/jalali-moment.js') }}"></script>

    <script src="{{ theme_asset('js/persiandatepicker.js') }}"></script>

    <script>
        newCalendar(['birthDatePicker', 'deathDatePicker'], {
            dayTitleFull: true,
            darkMode: true,
            theme: "#86ac22",
            closeCalendar: false
        })
    </script><!-- بارگذاری SDK لیفلت-نشان -->
    <script src="https://static.neshan.org/sdk/leaflet/v1.9.4/neshan-sdk/v1.0.8/index.js"></script>

    <script>
        var info_latitude = "{{ option('info_latitude', '38.07709880960678') }}";
        var info_Longitude = "{{ option('info_Longitude', '46.28582686185837') }}";
    </script>
    <script>
        $(document).ready(function() {
            const container = document.getElementById('halvaaMap');
            // 1. خواندن اولیه و فیلتر NaN
            let defaultLat = parseFloat(container.dataset.lat);
            let defaultLng = parseFloat(container.dataset.lng);
            if (isNaN(defaultLat)) defaultLat = 37.28395; // ← مقدار پیش‌فرض
            if (isNaN(defaultLng)) defaultLng = 49.59075; // ← مقدار پیش‌فرض

            // 2. ایجاد نقشه با مرکز اولیه‌ی معتبر
            const halvaaMap = new L.Map("halvaaMap", {
                key: "web.e26efd0612d64f34a6fc1123c6ba5507",
                maptype: "neshan",
                poi: false,
                traffic: false,
                center: [defaultLat, defaultLng],
                zoom: 15,
            });

            let currentMarker = null;

            function updateInputs(lat, lng) {
                $('#latitude').val(lat);
                $('#Longitude').val(lng);
            }

            function addMapMarker(lat, lng, pan = true) {
                // اطمینان از معتبر بودن ورودی‌ها
                if (isNaN(lat) || isNaN(lng)) return;
                if (currentMarker) halvaaMap.removeLayer(currentMarker);
                currentMarker = L.marker([lat, lng], {
                        draggable: true,
                        opacity: 0.9
                    })
                    .addTo(halvaaMap)
                    .on('dragend', function(e) {
                        const pos = e.target.getLatLng();
                        updateInputs(pos.lat, pos.lng);
                    });
                updateInputs(lat, lng);
                if (pan) halvaaMap.panTo([lat, lng]);
            }

            // 3. درخواست موقعیت کاربر
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(pos) {
                        addMapMarker(pos.coords.latitude, pos.coords.longitude);
                    },
                    function() {
                        // در صورت خطا یا انکار کاربر:
                        addMapMarker(defaultLat, defaultLng);
                    }, {
                        enableHighAccuracy: true,
                        timeout: 5000
                    }
                );
            } else {
                addMapMarker(defaultLat, defaultLng);
            }

            // 4. کلیک روی نقشه
            halvaaMap.on('click', function(e) {
                addMapMarker(e.latlng.lat, e.latlng.lng);
            });

            // 5. تغییر دستی این‌پوت‌ها
            $('#latitude, #Longitude').on('change', function() {
                const lat = parseFloat($('#latitude').val());
                const lng = parseFloat($('#Longitude').val());
                // اگر عدد بودند، مارکر را جابه‌جا کن
                if (!isNaN(lat) && !isNaN(lng)) {
                    addMapMarker(lat, lng);
                }
            });
        });

    </script>

@endpush
