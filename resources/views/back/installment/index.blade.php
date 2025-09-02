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
                                    <li class="breadcrumb-item active">لیست اقساط ها
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">

                @if($installments->count())
                    <section class="card">
                        <div class="card-header">
                            <h4 class="card-title">لیست اقساط ها</h4>
                            <a href="{{ route('admin.installments.create') }}" class="btn btn-primary">ایجاد</a>
                        </div>
                        <div class="card-content" id="main-card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>عنوان</th>
                                                <th>{{ trans('validation.attributes.prepayment_percentage') }}</th>
                                                <th>{{ trans('validation.attributes.fee_percentage') }}</th>
                                                <th>{{ trans('validation.attributes.installments_count') }}</th>
                                                <th>تاریخ ایجاد</th>
												<th>وضعیت</th>
                                                <th class="text-center">عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($installments as $installment)
                                                <tr id="discount-{{ $installment->id }}-tr">
                                                    <td class="text-center">
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>{{ $installment->title }}</td>
                                                    <td>{{ $installment->prepayment_percentage."%" }}</td>
                                                    <td>{{ $installment->fee_percentage."%" }}</td>
                                                    <td>{{ $installment->installments_count }}</td>
                                                    <td>{{ jdate($installment->created_at)->format('%d %B %Y') }}</td>
													<td>{{ $installment->is_active ? "فعال" : "غیرفعال" }}</td>
                                                    <td class="text-center">
                                                        <a class="btn btn-warning waves-effect waves-light" href="{{ route('admin.installments.edit', ['installment' => $installment]) }}">ویرایش</a>
                                                        <button data-discount="{{ $installment->id }}" data-action="{{ route('admin.installments.destroy', [$installment]) }}" type="button" class="btn btn-danger waves-effect waves-light btn-delete"  data-toggle="modal" data-target="#delete-modal">حذف</button>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>

                @else
                    <section class="card">
                        <div class="card-header">
                            <h4 class="card-title">لیست اقساط ها</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="card-text">
                                    <p>چیزی برای نمایش وجود ندارد!</p>
                                </div>
                            </div>
                        </div>
                    </section>
                @endif
                {{ $installments->links() }}


            </div>
        </div>
    </div>

    {{-- delete post modal --}}
    <div class="modal fade text-left" id="delete-modal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel19">آیا مطمئن هستید؟</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    با حذف کد تخفیف دیگر قادر به بازیابی آن نخواهید بود
                </div>
                <div class="modal-footer">
                    <form action="#" id="discount-delete-form">
                        @csrf
                        @method('delete')
                        <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal">خیر</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light">بله حذف شود</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('back/assets/js/pages/discounts/index.js') }}"></script>
@endpush
