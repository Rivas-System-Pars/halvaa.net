@extends('front::user.layouts.master')

@section('user-content')
    <!-- Start Content -->
    <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12">
        <div class="mb-2">
            <a href="{{ route('front.settlements.create') }}" class="btn btn-primary">ثبت درخواست</a>
        </div>
        @if($settlements->count())

            <div class="row">
                <div class="col-12">
                    <div
                        class="section-title text-sm-title title-wide mb-1 no-after-title-wide dt-sl mb-2 px-res-1">
                        <h2>همه تسویه حساب‌ها</h2>
                    </div>
                    <div class="dt-sl">
                        <div class="table-responsive">
                            <table class="table table-order">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>نام صاحب حساب</th>
                                    <th>شماره کارت</th>
                                    <th>تاریخ ثبت</th>
                                    <th>وضعیت پرداخت</th>
                                    <th>جزییات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($settlements as $settlement)
                                    <tr>
                                        <td>{{ $loop->iteration}}</td>
                                        <td class="text-info">{{ $settlement->name }}</td>
                                        <td>{{ wordwrap($settlement->card_number , 4 , '-' , true ) }}</td>
                                        <td>{{ jdate($settlement->created_at)->format('%d %B %Y') }}</td>
                                        <td>
                                            @if($settlement->status == \App\Models\Settlement::STATUS_PENDING)
                                                <span class="text-warning">در حال بررسی</span>
                                            @elseif($settlement->status == \App\Models\Settlement::STATUS_REJECTED)
                                                <span class="text-danger">رد شده</span>
                                            @elseif($settlement->status == \App\Models\Settlement::STATUS_CANCELED)
                                                <span class="text-danger">لغو شده</span>
                                            @elseif($settlement->status == \App\Models\Settlement::STATUS_DONE)
                                                <span class="text-success">انجام شده</span>
                                            @endif
                                        </td>
                                        <td class="details-link">
                                            <a href="{{ route('front.settlements.show', ['settlement' => $settlement]) }}">
                                                <i class="mdi mdi-chevron-left"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <div class="row">
                <div class="col-12">
                    <div class="page dt-sl dt-sn pt-3">
                        <p>چیزی برای نمایش وجود ندارد</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-3">
            {{ $settlements->links('front::components.paginate') }}
        </div>

    </div>
    <!-- End Content -->
@endsection
@push('scripts')
    <script>
        @if(session()->has('error'))
        toastr.error('', "{{ session('error') }}", {
            positionClass: 'toast-bottom-left',
            containerId: 'toast-bottom-left'
        });
        @endif
    </script>
@endpush
