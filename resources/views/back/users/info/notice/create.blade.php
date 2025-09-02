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
                                    <li class="breadcrumb-item">مدیریت متوفی</li>
                                    <li class="breadcrumb-item active">ایجاد/ویرایش اطلاعیه</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <section id="main-card" class="card">
                    <div class="card-header">
                        <h4 class="card-title">ایجاد اطلاعیه جدید</h4>
                    </div>

                    <div id="main-card" class="card-content">
                        <div class="card-body">
                            <div class="col-12 col-md-10 offset-md-1">
                                <form class="form" action="{{ route('admin.users.info.notice.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>عنوان اطلاعیه</label>
                                                    <input type="text" class="form-control" name="title" value="{{ old('title') }}">
                                                    @error('title')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>تصویر اطلاعیه</label>
                                                    <input type="file" class="form-control-file" name="image" accept="image/*">
                                                    @error('image')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-12 col-12">
                                                <div class="form-group">
                                                    <label>متن اطلاعیه</label>
                                                    <textarea class="form-control" name="content" rows="5">{{ old('content') }}</textarea>
                                                    @error('content')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">ثبت اطلاعیه</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>

                @if(isset($notices) && count($notices))
                    <div class="card mt-3">
                        <div class="card-header">
                            <h4 class="card-title">لیست اطلاعیه‌ها</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th>عنوان</th>
                                            <th>متن</th>
                                            <th>تصویر</th>
                                            <th>عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($notices as $notice)
                                            <tr>
                                                <td>{{ $notice->title }}</td>
                                                <td>{{ Str::limit($notice->content, 100) }}</td>
                                                <td>
                                                    @if($notice->image)
                                                        <img src="{{ asset($notice->image) }}" alt="image" width="80">
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.users.info.notice.edit', $notice->id) }}" class="btn btn-sm btn-warning">ویرایش</a>
                                                    <form action="{{ route('admin.users.info.notice.destroy', $notice->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('آیا مطمئن هستید؟');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
