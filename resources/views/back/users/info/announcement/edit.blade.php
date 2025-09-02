@extends('back.layouts.master')

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">
            <section class="card">
                <div class="card-header">
                    <h4 class="card-title">ویرایش اطلاعیه</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.info.announcement.update', $announcement->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>عنوان</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $announcement->title) }}" required>
                            @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>متن اطلاعیه (اختیاری)</label>
                            <textarea name="content" class="form-control" rows="5">{{ old('content', $announcement->content) }}</textarea>
                            @error('content') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>تصویر (اختیاری)</label>
                            <input type="file" name="image" class="form-control-file" accept="image/*">
                            @error('image') <span class="text-danger">{{ $message }}</span> @enderror

                            @if($announcement->image)
                                <div class="mt-2">
                                    <img src="{{ asset($announcement->image) }}" alt="تصویر اطلاعیه" width="120">
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
                        <a href="{{ route('admin.users.info.announcement.create') }}" class="btn btn-secondary">انصراف</a>
                    </form>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
