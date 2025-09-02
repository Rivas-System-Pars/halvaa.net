<!-- Product Info -->
<div class="col-12 product-info-block">
    <div class="product-info dt-sl">
        <div class="product-title">
            <h1>{{ $basket->title }}</h1>
        </div>

        <div class="row pt-4">
            <div class="col-md-6">
                @if ($basket->description)
                    <p class="little-des pt-0 mt-0">{!! nl2br($basket->description) !!}</p>
                @endif
                <form action="{{ route('front.baskets.add-to-cart',$basket->id) }}" method="post">
                    @csrf
                    <button type="submit" class="btn-primary-cm btn-with-icon add-to-cart">
                        <img data-src="{{ theme_asset('img/theme/shopping-cart.png') }}" alt="">
                        افزودن محصولات این سبد به سبد خرید
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
