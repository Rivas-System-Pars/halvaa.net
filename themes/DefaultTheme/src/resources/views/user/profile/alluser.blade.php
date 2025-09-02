@extends('front::layouts.master', ['title' => "لیست آرامگاه ها"])


@push('befor-styles')
    <link rel="stylesheet" href="{{ theme_asset('css/vendor/nouislider.min.css') }}">
@endpush

{{-- @php
    $has_filter = $category->getFilter();
@endphp --}}

@section('content')

    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">

            <div class="row">
                <!-- Start Content -->
                <div class="title-breadcrumb-special dt-sl mb-3">
                    <div class="breadcrumb dt-sl">
                        <nav>
                            <a href="/">خانه</a>
                            <a href="/">لیست آرامگاه ها</a>

                            {{-- @foreach ($category->parents() as $parent)
                                <a href="{{ route('front.products.category', ['category' => $parent]) }}">{{ $parent->title }}</a>
                            @endforeach --}}
                            {{-- <span>{{ $category->title }}</span> --}}
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                {{-- <div id="category-products-div" data-action="{{ route('front.products.category-products', ['category' => $category]) }}" class="{{ $category->getFilter() ? 'col-lg-9' : 'col-lg-12' }} col-md-12 col-sm-12"> --}}


                {{-- <div id="category-products-div" data-action="{{ route('front.products.category-products', ['category' => $category]) }}" class="{{ $category->getFilter() ? 'col-lg-9' : 'col-lg-12' }} col-md-12 col-sm-12"> --}}
                    @if($users->count())
                        <div class="dt-sl dt-sn px-0 search-amazing-tab">

                            {{-- <div class="row">
                                <div class="products-list-sort-type ah-tab-wrapper dt-sl">
                                    <div class="ah-tab dt-sl">
                                        <a class="ah-tab-item" data-sort="latest" {{ request('sort_type') == 'latest' || !request('sort_type') ? 'data-ah-tab-active=true' : '' }} href="#">جدید ترین</a>
                                        <a class="ah-tab-item" data-sort="view" {{ request('sort_type') == 'view' ? 'data-ah-tab-active=true' : '' }} href="#">پربازدید ترین</a>
                                        <a class="ah-tab-item" data-sort="sale" {{ request('sort_type') == 'sale' ? 'data-ah-tab-active=true' : '' }} href="#">پرفروش ترین</a>
                                        <a class="ah-tab-item" data-sort="cheapest" {{ request('sort_type') == 'cheapest' ? 'data-ah-tab-active=true' : '' }} href="#">ارزان ترین</a>
                                        <a class="ah-tab-item" data-sort="expensivest" {{ request('sort_type') == 'expensivest' ? 'data-ah-tab-active=true' : '' }} href="#">گران ترین</a>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="row mb-3 mx-0 px-res-0">
                                @foreach($users as $user)

                                    <div class="col-lg-3 col-md-4 col-sm-6 col-12 px-10 mb-1 px-res-0 category-product-div">
                                        @include('front::products.partials.product-card', ['user' => $user])
                                    </div>

                                @endforeach
                            </div>

                            {{-- {{ $products->appends(request()->all())->links('front::components.paginate') }} --}}
                        </div>
                    @else
                        @include('front::partials.empty')
                    @endif
                </div>
            </div>



        </div>
    </main>
    <!-- End main-content -->
@endsection

@push('scripts')



    <script src="{{ theme_asset('js/pages/products/category.js') }}?v=5"></script>
    <script src="{{ theme_asset('js/vendor/nouislider.min.js') }}"></script>
    <script src="{{ theme_asset('js/vendor/wNumb.js') }}"></script>
    <script src="{{ theme_asset('js/vendor/ResizeSensor.min.js') }}"></script>
@endpush
