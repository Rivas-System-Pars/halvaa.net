@extends('front::layouts.master')

@push('styles')

@endpush

@section('content')

<main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">

            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 search-card-res">
                    <!-- Start Content -->
                    <div class="title-breadcrumb-special dt-sl mb-3">
                        <div class="breadcrumb dt-sl">
                            <nav>
                                <a href="/">خانه</a>
                                <span>لیست آرامگاه ها</span>
                            </nav>
                        </div>
                    </div>
                    @if($users->count())
                        <div class="dt-sl dt-sn px-0 search-amazing-tab">
                            <div class="row mb-3 mx-0 px-res-0">

                                @foreach($users as $user)
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-12 px-10 mb-1 px-res-0">
                                        <div class="product-card mb-2 mx-res-0 category-index">
                                            <div class="product-card-body">
                                                <h5 class="product-title">
                                                    <a href="{{ route('admin.user.show', ['userPost' => $user->id]) }}">{{ $user->first_name }}</a>
                                                </h5>
                                            </div>
                                            
                                        </div>
                                    </div>
                                @endforeach

                            </div>

                        </div>

                    @endif
                </div>
                <!-- End Content -->
            </div>

        </div>
    </main>
@endsection

@push('scripts')


@endpush
