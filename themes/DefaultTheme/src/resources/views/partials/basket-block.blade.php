<div class="item">
    <div class="product-card">
        <a class="product-thumb" href="{{ route('front.baskets.show',$item->id) }}">
            <img data-src="{{ asset('/no-image-product.png')  }}" src="{{ theme_asset('images/600-600.png') }}" alt="">
        </a>
        <div class="product-card-body">
            <h5 class="product-title">
                <a href="{{ route('front.baskets.show',$item->id) }}">{{ $item->title }}</a>
            </h5>
            <a class="product-meta" href="{{ route('front.baskets.show',$item->id) }}">{{ $item->description }}</a>
        </div>
    </div>
</div>
