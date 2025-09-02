@php
    $variables = get_widget($widget);
    $index_middle_banners = $variables['index_middle_banners'];
@endphp

<!-- Start Banner -->
@if ($index_middle_banners->count())
    <div class="Option_Card_Title my-2">
        <div class="Option_Card_Right_Line"></div>
        <h2 class="Option_Card_Center_Line">تبلیغات</h2>
        <div class="Option_Card_Left_Line"></div>
    </div>
    <div class="row py-2 d-flex align-items-center justify-content-center g-0">
        @foreach ($index_middle_banners as $banner)
            <div class="col-sm-6 col-12 mb-2 d-flex align-items-center justify-content-center p-2 ">
                <div class="widget-banner">
                    <a href="{{ $banner->link }}">
                        <img data-src="{{ $banner->image }}" alt="{{ $banner->title }}">
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endif
<!-- End Banner -->
