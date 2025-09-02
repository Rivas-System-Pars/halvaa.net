@if (collect($products)->count())
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <section class="slider-section dt-sl mb-3">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="section-title text-sm-title title-wide no-after-title-wide">
                            <h2>{{ $title }}</h2>
                        </div>
                    </div>

                    <div class="col-12 px-res-0">
                        <div class="product-carousel carousel-md owl-carousel owl-theme">
                            @foreach ($products as $product)
                                @include('front::partials.product-block')
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endif
