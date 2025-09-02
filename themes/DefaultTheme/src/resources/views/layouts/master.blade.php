<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">

    <!-- viewport meta -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="DmEUwudExUhesjlLTnriV92fuoiXlX3SeP5vThJ24WU" />

    @stack('meta')

    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ option('info_icon', theme_asset('images/favicon-32x32.png')) }}">

    <title>
        @isset($title)
            {{ $title }} |
        @endisset

        {{ option('info_site_title', 'داستار') }}
    </title>

    <!-- Font Icon -->

	<link rel="stylesheet" href="{{ theme_asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('css/vendor/materialdesignicons.min.css') }}">

    @stack('befor-styles')

    <!-- theme color file -->
    <link rel="stylesheet"
        href="{{ theme_asset('css/colors/' . option('dt_theme_color', 'default') . '.css') }}?v={{ time() }}">

    @if (config('app.debug'))
        <!-- inject:css -->
        {{-- <link rel="stylesheet" href="{{ theme_asset('css/vendor/bootstrap.min.css') }}"> --}}
        <link rel="stylesheet" href="{{ theme_asset('css/arya/all.min.css') }}">
        <link rel="stylesheet" href="{{ theme_asset('js/arya/bootstrap-5.3.3/dist/css/bootstrap.rtl.min.css') }}">
        <link rel="stylesheet" href="{{ theme_asset('css/arya/swiper-bundle.min.css') }}">
        {{-- <link rel="stylesheet" href="{{ theme_asset('css/vendor/swiper-bundle.min.css')}}"> --}}

        <!-- Plugins -->
        <link rel="stylesheet" href="{{ theme_asset('css/vendor/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ theme_asset('css/vendor/jquery.horizontalmenu.css') }}">
        <link rel="stylesheet" href="{{ theme_asset('css/vendor/persian-datepicker.min.css') }}">
        <link rel="stylesheet" href="{{ theme_asset('js/plugins/toastr/toastr.css') }}">

        <!-- Main CSS File -->
	    <link rel="stylesheet" href="https://static.neshan.org/sdk/leaflet/v1.9.4/neshan-sdk/v1.0.8/index.css">
        <link rel="stylesheet" href="{{ theme_asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ theme_asset('css/main.css') }}?v=15">
        <link rel="stylesheet" href="{{ theme_asset('css/styles.css') }}?v=22">
        <link rel="stylesheet" href="{{ theme_asset('css/custom.css') }}?v=1">
        <link href="{{ theme_asset('css/vendor/fontawesome/css/all.min.css') }}" rel="stylesheet">
        <link href="{{ theme_asset('imageIcons/fonts/remixicon.css') }}" rel="stylesheet">

        <!-- endinject -->
    @else
        <!-- All Css Files -->
        <link rel="stylesheet" href="{{ mix('css/all.css', config('front.mainfest_path')) }}">
    @endif

    @stack('styles')

</head>



<body>

    <div class="wrapper @yield('wrapper-classes')">


        <!-- Start header -->
        <header class="main-header dt-sl py-3 py-lg-0 w-100">

            <!-- Start topbar -->
            <div class="container main-container">
                <div class="topbar dt-sl d-none d-lg-block">
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-6 the_logo_height">
                            <div class="logo-area float-right w-100 h-100 d-flex align-items-center justify-content-start my-0">
                                <a href="{{ route('front.index') }}" class="d-flex align-items-center justify-content-center h-100">
                                    <img data-src="{{ option('info_logo', theme_asset('img/logo.png')) }}"
                                        alt="{{ option('info_site_title', 'داستار') }}" class=" mh-100" >
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-5 d-none d-md-flex align-items-start">
                            <div class="search-area dt-sl">
                                <form id="search-form" action="{{ route('front.products.search') }}" class="search">
                                    <input type="text" name="q" value="{{ request('q') }}" id="search-input"
                                        autocomplete="off" placeholder="نام متوفی مورد نظر خود را جستجو کنید…">
                                    <button type="submit"><img data-src="{{ theme_asset('img/theme/search.png') }}"
                                            alt="search button"></button>
                                    <button id="close-search-result" class="close-search-result" type="button"><i
                                            class="mdi mdi-close"></i></button>
                                    <div class="search-result p-0" id="search-result">
                                        <ul>

                                        </ul>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 topbar-left">
                            @include('front::partials.user-menu')
                        </div>
                    </div>
                </div>
            </div>
            <!-- End topbar -->

            <!-- Start bottom-header -->
            <div class="bottom-header dt-sl mb-sm-bottom-header">
                <div class="container main-container">
                    <!-- Start Main-Menu -->
                    @include('front::partials.menu.menu')
                    <!-- End Main-Menu -->
                </div>
            </div>
            <!-- End bottom-header -->
        </header>
        <!-- End header -->


        @yield('content')

        @include('front::partials.footer')
    </div>

    <script>
        var BASE_URL = "{{ route('front.index') }}";
    </script>

    @if (config('app.debug'))
        <!-- Core JS Files -->
        <script src="{{ theme_asset('js/vendor/jquery-3.4.1.min.js') }}"></script>
        <script src="{{ theme_asset('js/vendor/popper.min.js') }}"></script>
        <script src="{{ theme_asset('js/vendor/bootstrap.min.js') }}"></script>
        <!-- Plugins -->
        <script src="{{ theme_asset('js/vendor/owl.carousel.min.js') }}"></script>
        <script src="{{ theme_asset('js/vendor/jquery.horizontalmenu.js') }}"></script>
        <script src="{{ theme_asset('js/vendor/theia-sticky-sidebar.min.js') }}"></script>
      <script src="{{ theme_asset('js/vendor/jquery.lazyloadxt.min.js') }}"></script>

        <script src="{{ theme_asset('js/plugins/jquery.blockUI.js') }}"></script>
        <script src="{{ theme_asset('js/plugins/sweetalert2.all.min.js') }}"></script>
        <script src="{{ theme_asset('js/plugins/toastr/toastr.min.js') }}"></script>

        <!-- Main JS File -->
        <script src="{{ theme_asset('js/main.js') }}?v=3"></script>
        <script src="{{ theme_asset('js/pages/gsap.min.js') }}"></script>
        <script src="{{ theme_asset('js/scripts.js') }}?v=10"></script>
        <script src="{{ theme_asset('js/arya/bootstrap-5.3.3/dist/js/bootstrap.bundle.min.js') }}?v=10"></script>
        {{-- <script src="{{ theme_asset('js/vendor/swiper-bundle.min.js') }}?v=10"></script> --}}
        <script src="{{ theme_asset('js/arya/swiper-bundle.min.js') }}"></script>
        <script src="{{ theme_asset('js/custom.js') }}"></script>
        <script src="{{ theme_asset('js/arya/slider.js') }}"></script>
        <script src="{{ theme_asset('js/arya/service-slider.js') }}"></script>
        <script src="{{ theme_asset('js/arya/product_ltr.js') }}"></script>
	    <script src="https://static.neshan.org/sdk/leaflet/v1.9.4/neshan-sdk/v1.0.8/index.js"></script>


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new Swiper('.TwoCard-swiper', {
                    slidesPerView: 1,
                    spaceBetween: 50,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    loop: true,
                    breakpoints: {
                        768: {
                            slidesPerView: 2,
                        },
                        1380: {
                            slidesPerView: 3,
                        },
                    },
                });
            });
        </script>

        <script>
            const customersSlider = new Swiper('.customers-slider', {
                slidesPerView: 5,
                spaceBetween: 10,
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    320: {
                        slidesPerView: 2,
                        spaceBetween: 5,
                    },
                    480: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    768: {
                        slidesPerView: 4,
                        spaceBetween: 10,
                    },
                    1024: {
                        slidesPerView: 5,
                        spaceBetween: 10,
                    },
                    1200: {
                        slidesPerView: 5,
                        spaceBetween: 10,
                    }
                },
                freeMode: true,
                grabCursor: true,
            });
        </script>


    @else
        <!-- All JS Files -->
        <script src="{{ mix('js/all.js', config('front.mainfest_path')) }}"></script>
    @endif

    @stack('scripts')

    @toastr_render

    {!! option('info_scripts') !!}
    <!-- endinject -->
</body>

</html>
