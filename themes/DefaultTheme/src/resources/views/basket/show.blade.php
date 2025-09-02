@extends('front::layouts.master', ['title' => $basket->title])


@push('styles')
    <link rel="stylesheet" href="{{ theme_asset('css/vendor/fancybox.min.css') }}">
@endpush

@section('content')

    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">

            <!-- Start Product -->
            <div class="dt-sn mb-5 dt-sl">
                <div class="row">

                    @include('front::basket.partials.basket-info')
                </div>
            </div>
            @include('front::partials.products',['products'=>$basket->products,'title'=>"محصولات موجود در سبد"])
            @include('front::partials.products',['products'=>$basket->gifts,'title'=>"محصولات هدیه سبد"])
            <!-- End Product -->
        </div>
    </main>



@endsection

@push('scripts')
    <script src="{{ theme_asset('js/vendor/jquery.fancybox.min.js') }}"></script>
    <script src="{{ theme_asset('js/plugins/apexcharts/apexcharts.js') }}"></script>
    <script src="{{ theme_asset('js/pages/products/show.js') }}?v=9"></script>
    <script src="{{ theme_asset('js/pages/comments.js') }}"></script>
@endpush
