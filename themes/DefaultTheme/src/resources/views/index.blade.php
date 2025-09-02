@extends('front::layouts.master')

@push('meta')
    <meta name="description" content="{{ option('info_short_description') }}">
    <meta name="keywords" content="{{ option('info_tags') }}">
    <meta name="user-data" content='@json($date_time)'>

    <link rel="canonical" href="{{ url('/') }}" />

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebSite",
            "url": "{{ route('front.index') }}",
            "name": "{{ option('site_title') }}",
            "logo": "{{ option('info_logo') ? asset(option('info_logo')) : asset(config('front.asset_path') . 'img/logo.png') }}",
            "potentialAction": {
                "@type": "SearchAction",
                "target": "{{ route('front.products.search') }}/?q={search_term_string}",
                "query-input": "required name=search_term_string"
            }
        }

    </script>
@endpush

@section('content')
    <!-- Start main-content -->
    @push('styles')
        {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" /> --}}
        <link rel="stylesheet" href="{{ theme_asset('css/counterCards.css') }}">
    @endpush

    <div>
      <div class="parent-container">
      <!-- Child 1: Header Section -->

      <!-- Child 2: Main Content -->
      <div class="parent-container-inner">
        <div class="main-logo">halvaa.net</div>
        <div class="countries-text">206 کشور</div>
      </div>

      <!-- Child 3: Bottom Section -->
      <div class="bottom-section">
		  <a href="https://halvaa.net/register">
        <button class="armaghan-btn">آرامگاه نو ایجاد کنید</button>
		  </a>
      </div>
     </div>
        @foreach ($widgets as $widget)
            @switch($widget->key)
                @case('event')
                    @include('front::widgets.event')
                @break

                @case('products-slider-block')
                    @include('front::widgets.products-slider-block')
                @break

                @case('main-slider-fullpage')
                    @include('front::widgets.main-slider-fullpage')
                @break

                @case('performance')
                    @include('front::widgets.performance')
                @break

                @case('products-timer-card')
                    @include('front::widgets.products-timer-card')
                @break

                @case('banner-get-demo')
                    @include('front::widgets.banner-get-demo')
                @break

                @case('product-info')
                    @include('front::widgets.product-info')
                @break

                @case('counseling')
                    @include('front::widgets.counseling')
                @break

                @case('services-slider')
                    @include('front::widgets.services-slider')
                @break

                @case('coworker-sliders')
                    @include('front::widgets.coworker-sliders')
                @break

                @case('about-company')
                    @include('front::widgets.about-company')
                @break

                @case('why-us')
                    @include('front::widgets.why-us')
                @break

                @case('question-sort')
                    @include('front::widgets.question-sort')
                @break

                @case('main-slider')
                    @include('front::widgets.main-slider')
                @break

                @case('products-default-block')
                    @include('front::widgets.products-default-block')
                @break

                @case('products-colorful-block')
                    @include('front::widgets.products-colorful-block')
                @break

                @case('middle-banners')
                    @include('front::widgets.middle-banners')
                @break

                @case('sevices-sliders')
                    @include('front::widgets.sevices-sliders')
                @break

                @case('categories')
                    @include('front::widgets.categories')
                @break

                @case('posts')
                    @include('front::widgets.posts')
                @break
            @endswitch
        @endforeach
    </div>
    {{-- <main class="main-content dt-sl mb-3">
        <div class="container main-container">
            @foreach ($widgets as $widget)
                @switch($widget->key)
                    @case('main-slider')
                        @include('front::widgets.main-slider')
                    @break

                    @case('products-default-block')
                        @include('front::widgets.products-default-block')
                    @break

                    @case('products-colorful-block')
                        @include('front::widgets.products-colorful-block')
                    @break

                    @case('middle-banners')
                        @include('front::widgets.middle-banners')
                    @break

                    @case('coworker-sliders')
                        @include('front::widgets.coworker-sliders')
                    @break

                    @case('sevices-sliders')
                        @include('front::widgets.sevices-sliders')
                    @break

                    @case('categories')
                        @include('front::widgets.categories')
                    @break

                    @case('posts')
                        @include('front::widgets.posts')
                    @break
                @endswitch
            @endforeach
            @include('front::partials.products', [
                'products' => $syncedProducts,
                'title' => 'پیشنهاد برای شما',
            ])
            @include('front::partials.basket-list', ['items' => $basketList, 'title' => 'سبدهای خرید'])

        </div>

    </main> --}}
    <!-- End main-content -->
@endsection
@push('scripts')
    <script src="{{ theme_asset('js/pages/products-slider-block.js') }}"></script>
@endpush
