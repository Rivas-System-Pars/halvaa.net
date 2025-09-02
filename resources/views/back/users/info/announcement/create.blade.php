@extends('back.layouts.master')

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">
            <section class="card">
                <div class="card-header">
                    <h4 class="card-title">ایجاد اطلاعیه جدید</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.info.announcement.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>عنوان</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                            @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>متن اطلاعیه (اختیاری)</label>
                            <textarea name="content" class="form-control" rows="5">{{ old('content') }}</textarea>
                            @error('content') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>تصویر (اختیاری)</label>
                            <input type="file" name="image" class="form-control-file" accept="image/*">
                            @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">ثبت اطلاعیه</button>
                    </form>
                </div>
            </section>

            @if($announcements->count())
            <section class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title">اطلاعیه‌های ثبت‌شده</h4>
                </div>
                <div class="card-body table-responsive">
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
                            @foreach($announcements as $item)
                                <tr>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($item->content, 100) }}</td>
                                    <td>
                                        @if($item->image)
                                            <img src="{{ asset($item->image) }}" alt="image" width="80">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.info.announcement.edit', $item->id) }}" class="btn btn-sm btn-warning">ویرایش</a>
                                        <form action="{{ route('admin.users.info.announcement.destroy', $item->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('آیا مطمئن هستید؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">حذف</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
            @endif
        </div>
    </div>
</div>
@endsection
