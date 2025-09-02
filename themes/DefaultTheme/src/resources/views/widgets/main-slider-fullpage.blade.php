@php
    $variables = get_widget($widget);
    $main_sliders = $variables['main_sliders'];
    // $mobile_sliders = $variables['mobile_sliders'];
    // $index_slider_banners = $variables['index_slider_banners'];
@endphp





@if ($main_sliders->count())
    <div class="swiper mySwiper2 main_swiper_full" id="newSwiper">
        <div class="swiper-wrapper">
            @foreach ($main_sliders as $slider)
                <div class="swiper-slide">
                    <div class="swiperImg" style="width: 100%; height:100%">
                        <a href="{{ $slider->link }}" style="width: 100%; height:100%">
                            <img style="width: 100%; height: 100%" src="{{ asset($slider->image) }}"
                                alt="{{ $slider->title }}">
                        </a>

                    </div>
                </div>
            @endforeach


        </div>
        <div class="swiper-button-next main_swiper_btn "></div>
        <div class="swiper-button-prev main_swiper_btn "></div>
        <div class="swiper-pagination swiper_pagination_color "></div>
    </div>



    {{-- <div class="carousel slide" id="carouselExampleAutoplaying" data-bs-ride="carousel" data-aos="fade-up" data-aos-duration="1000">
        <div class="carousel-indicators">
            <button class="active" data-bs-slide-to="0" data-bs-target="#carouselExampleAutoplaying" type="button"
                aria-current="true" aria-label="Slide 1"></button>
            <button data-bs-slide-to="1" data-bs-target="#carouselExampleAutoplaying" type="button"
                aria-label="Slide 2"></button>
            <button data-bs-slide-to="2" data-bs-target="#carouselExampleAutoplaying" type="button"
                aria-label="Slide 3"></button>
            <button data-bs-slide-to="3" data-bs-target="#carouselExampleAutoplaying" type="button"
                aria-label="Slide 4"></button>
        </div>
        <div class="carousel-inner pb-5 pb-md-4">
            @foreach ($main_sliders as $slider)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <a href="{{ $slider->link }}">
                        <img class="d-block w-100" src="{{ asset($slider->image) }}" alt="{{ $slider->title }}">
                    </a>
                </div>
            @endforeach
        </div>
    </div> --}}
@endif
