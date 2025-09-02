@extends('front::layouts.master')

@section('content')

    <!-- Start main-content -->
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container w-100">
            <div class="d-flex flex-column">

                <!-- Start Sidebar -->

                <!-- End Sidebar -->

                @yield('user-post')

            </div>



        </div>
    </main>
    <!-- End main-content -->

@endsection
