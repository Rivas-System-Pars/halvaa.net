<div class="container-fluid" style="margin-top: 10px;" data-aos="fade-up" data-aos-duration="1000">
    <div class="Option_Card_Title container">
        <div class="Option_Card_Right_Line"></div>
        <h2 class="Option_Card_Center_Line">{{$widget->option('title')}}</h2>
        <div class="Option_Card_Left_Line"></div>
    </div>

    <div class="row Product_LTR mt-4">

        @foreach ($products as $product)
        <!-- ################################ Backend[ FOR ]  Product Div ################################# -->
        <div class="col-md-12">
            <div class="product-container">
                <div class="product-image">
                    <a href="{{ env('APP_URL').'products/'. $product->slug}}"><img src="{{ env('APP_URL').$product->image }}" alt="..."></a>
                </div>
                <div class="product-details">
                    <a href="{{ env('APP_URL').'products/'. $product->slug}}"><h5 class="product-title">{{$product->title}}</h5></a>
                    <p class="product-description">{{$product->short_description}}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
