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
                                    <li class="breadcrumb-item">مدیریت</li>
                                    <li class="breadcrumb-item">مدیریت وابستگان</li>
                                    <li class="breadcrumb-item active">لیست وابستگان</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- اگه صفحه ایجاد داری --}}
                {{-- <div class="content-header-right col-md-3 col-12">
                    <a href="{{ route('admin.relatives.create') }}" class="btn btn-primary">ایجاد مورد جدید</a>
                </div> --}}
            </div>

            <div class="content-body">
                @if ($relatives->count())
                    <section class="card">
                        <div class="card-header">
                            <h4 class="card-title">لیست وابستگان</h4>
                        </div>
                        <div class="card-content" id="main-card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>کاربر</th>
                                                <th>کاربر انتخاب‌شده</th>
                                                <th>نام گزینه</th>
                                                {{-- <th>تاریخ</th> --}}
                                                <th class="text-center">عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($relatives as $relative)
                                                <tr id="relative-{{ $relative->id }}-tr">
                                                    <td>
                                                        {{ optional($relative->user)->fullname ?? (optional($relative->user)->name ?? '---') }}
                                                        {{-- اگر لینک پروفایل ادمین داری:
                                                        <a href="{{ route('admin.users.edit', optional($relative->user)->id) }}"><i class="feather icon-external-link"></i></a> --}}
                                                    </td>

                                                    <td>
                                                        {{ optional($relative->selectedUser)->fullname ?? (optional($relative->selectedUser)->name ?? '---') }}
                                                        {{-- <a href="{{ route('admin.users.edit', optional($relative->selectedUser)->id) }}"><i class="feather icon-external-link"></i></a> --}}
                                                    </td>

                                                    <td>
                                                        {{ $relative->option_name ?: '—' }}
                                                    </td>

                                                    {{-- <td>
                                                        {{ function_exists('jdate') ? jdate($relative->created_at)->format('Y/m/d H:i') : $relative->created_at->format('Y/m/d H:i') }}
                                                    </td> --}}

                                                    <td class="text-center">
                                                        {{-- ویرایش --}}
                                                        {{-- @can('relatives.update') --}}
                                                        <a href="{{ route('admin.relatives.edit', $relative) }}"
                                                            class="btn btn-success mr-1 waves-effect waves-light">ویرایش</a>
                                                        {{-- @endcan --}}

                                                        {{-- حذف --}}
                                                        {{-- @can('relatives.delete') --}}
                                                        <form action="{{ route('admin.relatives.destroy', $relative) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('حذف شود؟');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-danger waves-effect waves-light">حذف</button>
                                                        </form>

                                                        {{-- @endcan --}}
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
                            <h4 class="card-title">لیست وابستگان</h4>
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

                {{ $relatives->links() }}
            </div>
        </div>
    </div>

    {{-- delete modal --}}
    <div class="modal fade text-left" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">آیا مطمئن هستید؟</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    با حذف این مورد دیگر قادر به بازیابی آن نخواهید بود.
                </div>
                <div class="modal-footer">
                    <form action="#" id="relative-delete-form" method="POST">
                        @csrf
                        @method('delete')
                        <button type="button" class="btn btn-success waves-effect waves-light"
                            data-dismiss="modal">خیر</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light">بله حذف شود</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-delete');
            if (!btn) return;
            document.getElementById('relative-delete-form').setAttribute('action', btn.getAttribute('data-action'));
        }, false);
    </script>
@endpush
