@php
    $variables      = get_widget($widget);
@endphp

<div class="Option_Card_Title mb-2 container">
        <div class="Option_Card_Right_Line"></div>
        <h2 class="Option_Card_Center_Line">{{$widget->option('title')}}</h2>
        <div class="Option_Card_Left_Line"></div>
</div>


<section class="product_home w-100 d-flex align-items-center justify-content-center py-3">

            <div class="product_swiper swiper w-100">
				
                <div class="w-100 swiper-wrapper h-100">
					
					@foreach($products as $product)
					

                    <article class="product_article swiper-slide vajar_color w-100 h-100 ">

                        <div class="product_panel-1"></div>
                        <div class="product_panel-2"></div>

                        <div class="product_info-button-container  ">
                            <a href="{{env('APP_URL').'products/'.$product->slug}}" class="product_info-button ">
                                <span>مشاهده محصول</span>
                                <i class="fa-solid fa-arrow-left-long"></i>
                            </a>
                        </div>

                        <div class="product_content container h-100">

                            <div
                                class="product_data  d-grid d-md-flex align-items-start align-items-xl-start justify-content-center row h-100">

                                <div
                                    class="product_titles col-12 d-flex align-items-center justify-content-center justify-content-xl-start row ">
                                    <h3 class="product_subtitle justify-content-xl-start">
                                        {{$product->title}}
                                    </h3>
                                </div>






                                <div class="product_info col-12 col-md-6 col-xl-12 ">
                                    <p class="product_info-child">
										{{$product->short_description}}
                                    </p>
                                </div>



                                <div
                                    class="product_image col-12 col-md-6 col-xl-12 d-flex align-items-center justify-content-center">
                                    <img src="{{env('APP_URL').$product->image}}" alt="{{$product->title}}" class="product_img">
                                </div>



                            </div>

                        </div>

                    </article>
					@endforeach
                    {{-- <article class="product_article swiper-slide vajar_color w-100 h-100 ">

                        <div class="product_panel-1"></div>
                        <div class="product_panel-2"></div>

                        <div class="product_info-button-container  ">
                            <a href="" class="product_info-button ">
                                <span>مشاهده محصول</span>
                                <i class="fa-solid fa-arrow-left-long"></i>
                            </a>
                        </div>

                        <div class="product_content container  h-100">

                            <div
                                class="product_data  d-grid d-md-flex align-items-start align-items-xl-center justify-content-center row h-100">

                                <div
                                    class="product_titles col-12 d-flex align-items-center justify-content-center justify-content-xl-start row ">
                                    <h3 class="product_subtitle justify-content-xl-start">
                                        سامانه مدیریت منو و سفارشات آنلاین رستوران‌ها ویشتا (پلن حرفه ای)
                                    </h3>
                                </div>






                                <div class="product_info col-12 col-md-6 col-xl-12 ">
                                    <p class="product_info-child">
                                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از
                                        طراحان گرافیک است، چاپگرها و.لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم
                                        از
                                        صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و.
                                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از
                                        طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که
                                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از
                                        طراحان گرافیک است، چاپگرها و.لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم
                                        از
                                        صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و.
                                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از
                                        طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که

                                    </p>
                                </div>



                                <div
                                    class="product_image col-12 col-md-6 col-xl-12 d-flex align-items-center justify-content-center">
                                    <img src="/media/ویشتا حرفه ای.png" alt="ویشتا" class="product_img">
                                </div>



                            </div>

                        </div>

                    </article>

                    <article class="product_article swiper-slide vajar_color w-100 h-100 ">

                        <div class="product_panel-1"></div>
                        <div class="product_panel-2"></div>

                        <div class="product_info-button-container  ">
                            <a href="" class="product_info-button ">
                                <span>مشاهده محصول</span>
                                <i class="fa-solid fa-arrow-left-long"></i>
                            </a>
                        </div>

                        <div class="product_content container  h-100">

                            <div
                                class="product_data  d-grid d-md-flex align-items-start align-items-xl-center justify-content-center row h-100">

                                <div
                                    class="product_titles col-12 d-flex align-items-center justify-content-center justify-content-xl-start row ">
                                    <h3 class="product_subtitle justify-content-xl-start">
                                        سامانه فروشگاه ساز داستار (پلن حرفه ای)
                                    </h3>
                                </div>






                                <div class="product_info col-12 col-md-6 col-xl-12 ">
                                    <p class="product_info-child">
                                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از
                                        طراحان گرافیک است، چاپگرها و.لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم
                                        از
                                        صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و.
                                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از
                                        طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که
                                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از
                                        طراحان گرافیک است، چاپگرها و.لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم
                                        از
                                        صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و.
                                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از
                                        طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که

                                    </p>
                                </div>



                                <div
                                    class="product_image col-12 col-md-6 col-xl-12 d-flex align-items-center justify-content-center">
                                    <img src="/media/داستار- حرفه ای.png" alt="داستار" class="product_img">
                                </div>



                            </div>

                        </div>

                    </article>
					--}}


                </div>

                <div class="swiper-pagination product_pagination"></div>
            </div>




        </section>