@php

    $variables = get_widget($widget);
    $products = $variables['products'];
@endphp


@push('styles')
    <link rel="stylesheet" href="{{ theme_asset('css/vendor/all.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('css/swiper-bundle.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('css/counterCards.css') }}">
@endpush



@if ($products->count())
    <div class="Option_Card_Title my-3 container">
        <div class="Option_Card_Right_Line"></div>
        <h2 class="Option_Card_Center_Line">تخفیفات ویژه‌ی زمستانه</h2>
        <div class="Option_Card_Left_Line"></div>
    </div>

    <div class="main_parent my-4">


        <div class="counter-cards-box ">

            <div class="title-box">
                <div class="title-box-body">
                    <div class="titles">
                        <h3 class="title m-0">
                            {{ $widget->title }}
                        </h3>
                        <div class="title-icon">
                            <img src="{{ $widget->option('image') }}" alt="">
                        </div>

                    </div>
                    <div class="counter" id="counter">


                        <div class="time-unit" id="seconds-box">
                            <span id="seconds" class="time-value">00</span>
                            <span class="time-label">ثانیه</span>
                        </div>
                        <div class="time-unit" id="minutes-box">
                            <span id="minutes" class="time-value">00</span>
                            <span class="time-label">دقیقه</span>
                        </div>
                        <div class="time-unit" id="hours-box">
                            <span id="hours" class="time-value">00</span>
                            <span class="time-label">ساعت</span>
                        </div>
                        <div class="time-unit" id="days-box">
                            <span id="days" class="time-value">00</span>
                            <span class="time-label">روز</span>
                        </div>

                    </div>
                    <a href="{{ $widget->option('link') }}" class="show_all btn">
                        {{ $widget->option('link_title', 'مشاهده همه') }}
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                </div>

            </div>

            <div class="offer-box">

                <div class="offer_container swiper" dir="rtl" id="offer-container">

                    <div class="swiper-wrapper offer_wrapper">


                        @foreach ($products as $product)
                            <div class="product_slide swiper-slide ">

                                <div class="product_slide_child" target="_blank">

                                    <div class="{{$product->getLowestDiscount() ? '' : 'percent_hider'}} product_offer_percent">
                                        <span
                                            data-testid="price-discount-percent">{{ $product->discount ? $product->discount . '%' : '' }}</span>
                                    </div>

                                    <article class="product_card">

                                        <div class="product_card_child">

                                            <div>
                                                <div class="product_card_child_inner">
                                                    <div class="product_card_child_inner_in">
                                                        <div>
                                                            <a style="line-height: 0; text-decoration:none;"
															   href="{{ route('front.products.show', ['product' => $product]) }}">
                                                                <picture>
                                                                    <img class="product_card_child_inner_in_pic"
                                                                        data-src="{{ $product->image ? asset($product->image) : asset('/no-image-product.png') }}"
                                                                        src="{{ theme_asset('images/600-600.png') }}"
																		
                                                                        alt="{{ $product->title }}">
                                                                </picture>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product_details">
                                                <div>
                                                    <a
                                                        href="{{ route('front.products.show', ['product' => $product]) }}">
                                                        <h3 class="product_title"
                                                            style="height: 40px; line-height: 20px;">
                                                            {{ $product->title }}
                                                        </h3>

                                                    </a>
                                                    <a
                                                        href="{{ $product->category ? $product->category->link : '#' }}">
                                                        <h6 class="product_category  "
                                                            style="height: 24px; line-height: 20px;">
                                                            {{ $product->category ? $product->category->title : 'بدون دسته بندی' }}
                                                        </h6>

                                                    </a>

                                                </div>

                                                <div class="product_price_box">

                                                    <div class="product_price_box_final_child">

                                                        <div class="price_final_box">

                                                            <span data-testid="price-final"
                                                                class="price-final">{{ $product->getLowestPrice() }}</span>

                                                            <div class="d-flex">
                                                                <svg
                                                                    style="width: 16px; height: 16px; fill: var(--color-icon-high-emphasis);">
                                                                    <use xlink:href="#toman">
                                                                        <symbol id="toman"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            viewBox="0 0 14 14">
                                                                            <path fill-rule="evenodd"
                                                                                d="M3.057 1.742L3.821 1l.78.75-.776.741-.768-.749zm3.23 2.48c0 .622-.16 1.111-.478 1.467-.201.221-.462.39-.783.505a3.251 3.251 0 01-1.083.163h-.555c-.421 0-.801-.074-1.139-.223a2.045 2.045 0 01-.9-.738A2.238 2.238 0 011 4.148c0-.059.001-.117.004-.176.03-.55.204-1.158.525-1.827l1.095.484c-.257.532-.397 1-.419 1.403-.002.04-.004.08-.004.12 0 .252.055.458.166.618a.887.887 0 00.5.354c.085.028.178.048.278.06.079.01.16.014.243.014h.555c.458 0 .769-.081.933-.244.14-.139.21-.383.21-.731V2.02h1.2v2.202zm5.433 3.184l-.72-.7.709-.706.735.707-.724.7zm-2.856.308c.542 0 .973.19 1.293.569.297.346.445.777.445 1.293v.364h.18v-.004h.41c.221 0 .377-.028.467-.084.093-.055.14-.14.14-.258v-.069c.004-.243.017-1.044 0-1.115L13 8.05v1.574a1.4 1.4 0 01-.287.863c-.306.405-.804.607-1.495.607h-.627c-.061.733-.434 1.257-1.117 1.573-.267.122-.58.21-.937.265a5.845 5.845 0 01-.914.067v-1.159c.612 0 1.072-.082 1.38-.247.25-.132.376-.298.376-.499h-.515c-.436 0-.807-.113-1.113-.339-.367-.273-.55-.667-.55-1.18 0-.488.122-.901.367-1.24.296-.415.728-.622 1.296-.622zm.533 2.226v-.364c0-.217-.048-.389-.143-.516a.464.464 0 00-.39-.187.478.478 0 00-.396.187.705.705 0 00-.136.449.65.65 0 00.003.067c.008.125.066.22.177.283.093.054.21.08.352.08h.533zM9.5 6.707l.72.7.724-.7L10.209 6l-.709.707zm-6.694 4.888h.03c.433-.01.745-.106.937-.29.024.012.065.035.12.068l.074.039.081.042c.135.073.261.133.379.18.345.146.67.22.977.22a1.216 1.216 0 00.87-.34c.3-.285.449-.714.449-1.286a2.19 2.19 0 00-.335-1.145c-.299-.457-.732-.685-1.3-.685-.502 0-.916.192-1.242.575-.113.132-.21.284-.294.456-.032.062-.06.125-.084.191a.504.504 0 00-.03.078 1.67 1.67 0 00-.022.06c-.103.309-.171.485-.205.53-.072.09-.214.14-.427.147-.123-.005-.209-.03-.256-.076-.057-.054-.085-.153-.085-.297V7l-1.201-.5v3.562c0 .261.048.496.143.703.071.158.168.296.29.413.123.118.266.211.43.28.198.084.42.13.665.136v.001h.036zm2.752-1.014a.778.778 0 00.044-.353.868.868 0 00-.165-.47c-.1-.134-.217-.201-.35-.201-.18 0-.33.103-.447.31-.042.071-.08.158-.114.262a2.434 2.434 0 00-.04.12l-.015.053-.015.046c.142.118.323.216.544.293.18.062.325.092.433.092.044 0 .086-.05.125-.152z"
                                                                                clip-rule="evenodd">
                                                                            </path>
                                                                        </symbol>
                                                                    </use>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div
                                                        class="product_price_box_current_child ">
                                                        <div class="{{$product->getLowestDiscount() ? '' : 'price_current_hider'}} price_current_box">
                                                            <div class="price_current">
                                                                <span data-testid="price-no-discount"
                                                                    class="price-no-discount">
                                                                    {{ $product->getLowestDiscount() }}
                                                                </span>
                                                                <div class="d-flex">
                                                                    <svg
                                                                        style="width: 16px; height: 16px; fill: var(--color-icon-high-emphasis);">
                                                                        <use xlink:href="#toman">
                                                                            <symbol id="toman"
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                viewBox="0 0 14 14">
                                                                                <path fill-rule="evenodd"
                                                                                    d="M3.057 1.742L3.821 1l.78.75-.776.741-.768-.749zm3.23 2.48c0 .622-.16 1.111-.478 1.467-.201.221-.462.39-.783.505a3.251 3.251 0 01-1.083.163h-.555c-.421 0-.801-.074-1.139-.223a2.045 2.045 0 01-.9-.738A2.238 2.238 0 011 4.148c0-.059.001-.117.004-.176.03-.55.204-1.158.525-1.827l1.095.484c-.257.532-.397 1-.419 1.403-.002.04-.004.08-.004.12 0 .252.055.458.166.618a.887.887 0 00.5.354c.085.028.178.048.278.06.079.01.16.014.243.014h.555c.458 0 .769-.081.933-.244.14-.139.21-.383.21-.731V2.02h1.2v2.202zm5.433 3.184l-.72-.7.709-.706.735.707-.724.7zm-2.856.308c.542 0 .973.19 1.293.569.297.346.445.777.445 1.293v.364h.18v-.004h.41c.221 0 .377-.028.467-.084.093-.055.14-.14.14-.258v-.069c.004-.243.017-1.044 0-1.115L13 8.05v1.574a1.4 1.4 0 01-.287.863c-.306.405-.804.607-1.495.607h-.627c-.061.733-.434 1.257-1.117 1.573-.267.122-.58.21-.937.265a5.845 5.845 0 01-.914.067v-1.159c.612 0 1.072-.082 1.38-.247.25-.132.376-.298.376-.499h-.515c-.436 0-.807-.113-1.113-.339-.367-.273-.55-.667-.55-1.18 0-.488.122-.901.367-1.24.296-.415.728-.622 1.296-.622zm.533 2.226v-.364c0-.217-.048-.389-.143-.516a.464.464 0 00-.39-.187.478.478 0 00-.396.187.705.705 0 00-.136.449.65.65 0 00.003.067c.008.125.066.22.177.283.093.054.21.08.352.08h.533zM9.5 6.707l.72.7.724-.7L10.209 6l-.709.707zm-6.694 4.888h.03c.433-.01.745-.106.937-.29.024.012.065.035.12.068l.074.039.081.042c.135.073.261.133.379.18.345.146.67.22.977.22a1.216 1.216 0 00.87-.34c.3-.285.449-.714.449-1.286a2.19 2.19 0 00-.335-1.145c-.299-.457-.732-.685-1.3-.685-.502 0-.916.192-1.242.575-.113.132-.21.284-.294.456-.032.062-.06.125-.084.191a.504.504 0 00-.03.078 1.67 1.67 0 00-.022.06c-.103.309-.171.485-.205.53-.072.09-.214.14-.427.147-.123-.005-.209-.03-.256-.076-.057-.054-.085-.153-.085-.297V7l-1.201-.5v3.562c0 .261.048.496.143.703.071.158.168.296.29.413.123.118.266.211.43.28.198.084.42.13.665.136v.001h.036zm2.752-1.014a.778.778 0 00.044-.353.868.868 0 00-.165-.47c-.1-.134-.217-.201-.35-.201-.18 0-.33.103-.447.31-.042.071-.08.158-.114.262a2.434 2.434 0 00-.04.12l-.015.053-.015.046c.142.118.323.216.544.293.18.062.325.092.433.092.044 0 .086-.05.125-.152z"
                                                                                    clip-rule="evenodd">
                                                                                </path>
                                                                            </symbol>
                                                                        </use>
                                                                    </svg>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>





                                                </div>
                                            </div>
                                        </div>
                                        <a class="add_to_cart add-to-cart-single"
                                            data-action="{{ route('front.cart.store', ['product' => $product]) }}"
                                            href="javascript:void(0)">
                                            <i class="fa-solid fa-cart-shopping"></i>
                                        </a>
                                    </article>
                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>
            </div>


        </div>
    </div>
@endif

@push('scripts')
    <script src="{{ theme_asset('js/vendor/jquery.countdown.js') }}"></script>
    <script src="{{ theme_asset('js/vendor/jalaali.min.js') }}"></script>
    <script src="{{ theme_asset('js/vendor/swiper-bundle.min.js') }}"></script>
    <script src="{{ theme_asset('js/pages/bootstrap.bundle.js') }}"></script>
    <script src="{{ theme_asset('js/pages/counterCards.js') }}"></script>
@endpush
