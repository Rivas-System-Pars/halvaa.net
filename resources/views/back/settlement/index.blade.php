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
                                    <li class="breadcrumb-item">مدیریت تسویه حساب ها
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="content-body">
                <!-- filter start -->
                <!-- filter end -->

                <section id="main-card" class="card">
                    <div class="card-header">
                        <h4 class="card-title">لیست تسویه حساب ها</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form action="" class="row">
                                <div class="form-group col-md-3">
                                    <input type="text" class="form-control" name="search"
                                           value="{{ request('search') }}" placeholder="جستجو...">
                                </div>
                            </form>
                            @if($settlements->isNotEmpty())
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>نام صاحب حساب</td>
                                        <td>شمارت کارت</td>
                                        <td>تاریخ</td>
                                        <td>مبلغ</td>
                                        <td>وضعیت</td>
                                        <td>عملیات</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($settlements as $settlement)
                                        <tr>
                                            <td>{{ ($settlements->currentpage()-1) * $settlements->perpage() + $loop->index + 1 }}</td>
                                            <td>{{ $settlement->name }}</td>
                                            <td>{{ wordwrap($settlement->card_number , 4 , '-' , true ) }}</td>
                                            <td>{{ jdate($settlement->created_at)->format('%d %B %Y') }}</td>
                                            <td>{{ number_format($settlement->amount)." تومان" }}</td>
                                            <td>
                                                @if($settlement->status == \App\Models\Settlement::STATUS_PENDING)
                                                    <span
                                                        class="text-warning">{{ trans('settlements_'.$settlement->status) }}</span>
                                                @elseif($settlement->status == \App\Models\Settlement::STATUS_REJECTED)
                                                    <span
                                                        class="text-danger">{{ trans('settlements_'.$settlement->status) }}</span>
                                                @elseif($settlement->status == \App\Models\Settlement::STATUS_CANCELED)
                                                    <span
                                                        class="text-danger">{{ trans('settlements_'.$settlement->status) }}</span>
                                                @elseif($settlement->status == \App\Models\Settlement::STATUS_DONE)
                                                    <span
                                                        class="text-success">{{ trans('settlements_'.$settlement->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.settlements.show',$settlement->id) }}"
                                                   class="btn btn-primary">مشاهده</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="row">
                                    <div class="col-12">
                                        <div class="page dt-sl dt-sn pt-3">
                                            <p>چیزی برای نمایش وجود ندارد</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {{ $settlements->appends(request()->all())->links() }}
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
