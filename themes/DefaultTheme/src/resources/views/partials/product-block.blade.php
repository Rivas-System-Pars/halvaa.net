
<div class="item h-100">
    <div class="product-card h-100">
        <div class="product-head">

        </div>
        {{-- <a class="product-thumb" href="{{ route('front.products.show', ['product' => $product]) }}">
            <img data-src="{{ $product->image ? asset($product->image) : asset('/no-image-product.png') }}" src="{{ theme_asset('images/600-600.png') }}" alt="{{ $product->title }}">
        </a> --}}
        <a class="product-thumb" href="{{route('front.user.showprofile',['id'=>$user->id])}}">
            <img data-src="{{ $user->profileImage ? env('APP_URL') . '/' . $user->profileImage->image :  asset('/no-image-product.png') }}" src="{{ theme_asset('images/600-600.png') }}" alt="{{ $user->username }}">
        </a>
        <div class="product-card-body">

            <h5 class="product-title product-title_child">

                {{-- <a href="{{ route('front.products.show', ['product' => $product]) }}" class=" w-100 h-100">{{ $user->username }}</a> --}}
                                <a href="{{route('front.user.showprofile',['id'=>$user->id])}}" class=" w-100 h-100">{{ $user->username }}</a>

            </h5>
            {{-- <a class="product-meta product-meta_child " href="{{ $product->category ? $product->category->link : '#' }}">{{ $product->category ? $product->category->title : 'بدون دسته بندی' }}</a> --}}
            {{-- <div class="price-index-h">
                <div class="product-prices-div">
                    <span class="product-price">{{ $product->getLowestPrice() }}</span>

                    @if($product->getLowestDiscount())
                        <del class="product-price-del">{{ $product->getLowestDiscount() }}</del>
                    @endif
                </div>
            </div> --}}

            {{-- @if ($product->isSinglePrice())
                <div class="cart">
                    <a data-action="{{ route('front.cart.store', ['product' => $product]) }}" class="d-flex align-items-center add-to-cart-single" href="javascript:void(0)"><i class="mdi mdi-plus px-2"></i>
                        <span>افزودن به سبد</span>
                    </a>
                </div>
            @endif --}}
        </div>
    </div>
</div>
