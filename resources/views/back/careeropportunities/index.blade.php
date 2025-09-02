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
                                    <li class="breadcrumb-item">لیست فرصت‌های شغلی
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">

                @if($careeropportunities->count())
                    <section class="card">
                        <div class="card-header">
                            <h4 class="card-title">لیست فرصت‌های شغلی</h4>
                        </div>
                        <div class="card-content" id="main-card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>نام و نام خانوادگی</th>
												<th>شماره موبایل</th>
												<th>تاریخ ثبت</th>
												<th>مشاهده در</th>
                                                <th class="text-center">عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($careeropportunities as $item)
											<tr id="brand-{{ $item->id }}-tr">
                                                    <td>{{ $item->name }}</td>
													<td>{{ $item->mobile }}</td>
													<td>{{ jdate($item->created_at)->format('%d %B %Y') }}</td>
													<td>{{ $item->viewed_at ? jdate($item->viewed_at)->format('%d %B %Y') : "مشاهده نشده" }}</td>
                                                    <td class="text-center">
		<a href="{{ route('admin.careeropportunities.show',$item->id) }}" class="btn btn-success mr-1 waves-effect waves-light">مشاهده جزئیات</a>
{{--
			<form action="{{ route('admin.careeropportunities.destroy', $item->id) }}" method="POST" style="display: inline;">
   					 @csrf
   						 @method('DELETE')
  							  <button type="submit" class="btn btn-danger mr-1 waves-effect waves-light">حذف</button>
										</form>
--}}
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
                            <h4 class="card-title">لیست فرصت‌های شغلی</h4>
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
                {{ $careeropportunities->links() }}

            </div>
        </div>
    </div>

    {{-- delete brand modal --}}
    <div class="modal fade text-left" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel19" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel19">آیا مطمئن هستید؟</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    با حذف برند دیگر قادر به بازیابی آن نخواهید بود
                </div>
                <div class="modal-footer">
                    <form action="#" id="brand-delete-form">
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
    <script src="{{ asset('back/assets/js/pages/brands/index.js') }}"></script>
@endpush
