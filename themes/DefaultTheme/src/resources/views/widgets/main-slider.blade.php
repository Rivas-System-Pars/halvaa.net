@php
    $variables      = get_widget($widget);
    $main_sliders   = $variables['main_sliders'];
    $mobile_sliders = $variables['mobile_sliders'];
    $index_slider_banners = $variables['index_slider_banners'];
@endphp

<!-- Start Main-Slider -->
<div class="row index-main-slider px-2 py-3 gap-2 gap-lg-0 d-flex justify-content-start {{ $widget->option('banner_position', 'left') == 'right' ? 'flex-row-reverse' : '' }}">
    
    @if ($index_slider_banners->count())
        <aside class="ps-lg-0 col-lg-4 order-2 m-0">
            <!-- Start banner -->
            <div class="sidebar-inner dt-sl">
                <div class="sidebar-banner m-0">
                    <div class="row g-2 g-lg-0 gap-lg-2">
                        @foreach ($index_slider_banners as $banner)
                            <div class="col-6 col-lg-12 ">
                                <div class="widget-banner p-0 px-lg-2">
                                    <a href="{{ $banner->link }}" class="w-100">
                                        <img src="{{ asset($banner->image) }}" class="w-100 h-100" alt="{{ $banner->title }}">
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- End banner -->
        </aside>
    @endif

    <div class="col-lg-8 col-md-12 order-1 m-0">
        <!-- Start main-slider -->
        @if ($main_sliders->count())
            <section id="main-slider" class="main-slider h-100 main-slider-cs m-0 carousel slide carousel-fade card " data-ride="carousel">
                <ol class="carousel-indicators">
                    @foreach ($main_sliders as $slider)
                        <li data-target="#main-slider" data-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"></li>
                    @endforeach
                </ol>
                <div class="carousel-inner h-100 w-100">
                    @foreach ($main_sliders as $slider)
                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                            <a class="main-slider-slide h-100 w-100" href="{{ $slider->link }}">
                                <img src="{{ asset($slider->image) }}" alt="{{ $slider->title }}" class="img-fluid h-100 w-100">
                            </a>
                        </div>
                    @endforeach

                </div>
                <a class="carousel-control-prev" href="#main-slider" role="button" data-slide="prev">
                    <i class="mdi mdi-chevron-right"></i>
                </a>
                <a class="carousel-control-next" href="#main-slider" data-slide="next">
                    <i class="mdi mdi-chevron-left"></i>
                </a>
            </section>
        @endif

        @if ($mobile_sliders->count())
            <section id="main-slider-res" class="main-slider carousel slide carousel-fade card d-none show-sm" data-ride="carousel">
                <ol class="carousel-indicators">
                    @foreach ($mobile_sliders as $slider)
                        <li data-target="#main-slider-res" data-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"></li>
                    @endforeach
                </ol>
                <div class="carousel-inner">
                    @foreach ($mobile_sliders as $slider)
                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                            <a class="main-slider-slide" href="{{ $slider->link }}">
                                <img src="{{ asset($slider->image) }}" alt="{{ $slider->title }}" class="img-fluid">
                            </a>
                        </div>
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#main-slider-res" role="button" data-slide="prev">
                    <i class="mdi mdi-chevron-right"></i>
                </a>
                <a class="carousel-control-next" href="#main-slider-res" data-slide="next">
                    <i class="mdi mdi-chevron-left"></i>
                </a>
            </section>
        @endif
        <!-- End main-slider -->
    </div>
</div>
