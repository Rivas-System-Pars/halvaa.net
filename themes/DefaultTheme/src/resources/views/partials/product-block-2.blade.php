<div class="item">
    <div class="product-card mb-3">
        <div class="product-head">



        </div>
        {{-- <a class="product-thumb" href="{{ route('front.products.show', ['product' => $product]) }}">
            <img data-src="{{ $product->image ? asset($product->image) : asset('/no-image-product.png') }}" src="{{ theme_asset('images/600-600.png') }}" alt="{{ $product->title }}">
        </a> --}}
        <a class="product-thumb" href="{{route('front.user.showprofile',['id'=>$user->id])}}">
            <img data-src="{{ $user->profileImage ? env('APP_URL') . '/' . $user->profileImage->image :  asset('/no-image-product.png') }}" src="{{ theme_asset('images/600-600.png') }}" alt="{{ $user->username }}">
        </a>
        <div class="product-card-body">
            <h5 class="product-title">
                {{-- <a href="{{ route('front.products.show', ['product' => $product]) }}">{{ $product->title }}</a> --}}
                <a href="{{route('front.user.showprofile',['id'=>$user->id])}}" class=" w-100 h-100">{{ $user->username }}</a>
            </h5>
            {{-- <a class="product-meta" href="{{ $product->category ? $product->category->link : '#' }}">{{ $product->category ? $product->category->title : 'بدون دسته بندی' }}</a> --}}


        </div>
    </div>
</div>
