@php
    $variables          = get_widget($widget);
    $coworker_sliders   = $variables['coworker_sliders'];
@endphp

<div class="container" data-aos="fade-up" data-aos-duration="1000">
    <div class="Option_Card_Title mt-5">
        <div class="Option_Card_Right_Line"></div>
        <h2 class="Option_Card_Center_Line">{{$widget->option('title')}}</h2>
        <div class="Option_Card_Left_Line"></div>
    </div>
    <!-- اسلایدر -->
    <div class="customers-slider swiper-container mt-4 mt-lg-5">
        <div class="swiper-wrapper">
            @foreach ($coworker_sliders as $slider)
            <div class="swiper-slide">
                <img src="{{ $slider->image }}" alt="{{ $slider->title }}">
            </div>
            @endforeach
        </div>

        <!-- دکمه‌های ناوبری -->
        <div class="swiper-button-next customers-next"></div>
        <div class="swiper-button-prev customers-prev"></div>
    </div>
</div>
