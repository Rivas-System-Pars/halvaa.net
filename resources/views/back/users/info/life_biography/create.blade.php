@extends('back.layouts.master')

@section('content')

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb no-border">
                                    <li class="breadcrumb-item">مدیریت
                                    </li>
                                    <li class="breadcrumb-item">مدیریت متوفی
                                    </li>
                                    <li class="breadcrumb-item active">ایجاد/ویرایش زندگینامه متوفی
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <!-- Description -->
                <section id="main-card" class="card">
                    <div class="card-header">
                        <h4 class="card-title">ایجاد/ویرایش زندگینامه متوفی</h4>
                    </div>

                    <div id="main-card" class="card-content">
                        <div class="card-body">
                            <div class="col-12 col-md-10 offset-md-1">
                                <form class="form" id="user-create-form" action="{{ route('admin.users.info.lifebiography.store') }}"
                                      method="post">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>خلاصه زندگی نامه</label>
                                                    <input type="text" class="form-control" name="summerise_bio" value="{{$summerise_bio->summerise_bio ?? '' }}">
                                                    <label>زندگینامه</label>
                                                    <input type="text" class="form-control" name="life_biography" value="{{$user_biolife->life_biography ?? '' }}">

                                                </div>
                                            </div>


                                        </div>


                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit"
                                                        class="btn btn-primary mr-1 mb-1 waves-effect waves-light">ایجاد/ویرایش زندگینامه
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
                <!--/ Description -->

            </div>
        </div>
    </div>

@endsection

@include('back.partials.plugins', ['plugins' => ['jquery.validate']])

@php
    $help_videos = [
        config('general.video-helpes.users')
    ];
@endphp

@push('scripts')
    <script src="{{ asset('back/assets/js/pages/users/all.js') }}"></script>
    {{-- <script src="{{ asset('back/assets/js/pages/users/create.js') }}"></script> --}}

@endpush
