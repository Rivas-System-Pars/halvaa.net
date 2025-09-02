@extends('back.layouts.master')

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">
            <section class="card">
                <div class="card-header">
                    <h4 class="card-title">ویرایش یادبود</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.info.memorial.update', $memorial->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>عنوان</label>
                                <input type="text" class="form-control" name="title" value="{{ old('title', $memorial->title) }}" required>
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label>تصویر (اختیاری)</label>
                                <input type="file" class="form-control-file" name="image">
                                @error('image')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                @enderror

                                @if($memorial->image)
                                    <div class="mt-2">
                                        <img src="{{ asset($memorial->image) }}" alt="تصویر یادبود" width="120">
                                    </div>
                                @endif
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
                                <a href="{{ route('admin.users.info.memorial.create') }}" class="btn btn-secondary">انصراف</a>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
