@extends('back.layouts.master')

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">
            <section class="card">
                <div class="card-header">
                    <h4 class="card-title">ثبت یادبود جدید</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.info.memorial.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>عنوان</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>تصویر</label>
                                <input type="file" class="form-control-file" name="image">
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary">ثبت یادبود</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

            @if($memorials->count())
                <section class="card mt-2">
                    <div class="card-header">
                        <h4 class="card-title">لیست یادبودها</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>عنوان</th>
                                    <th>تصویر</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($memorials as $memorial)
                                    <tr>
                                        <td>{{ $memorial->title }}</td>
                                        <td>
                                            @if($memorial->image)
                                                <img src="{{ asset($memorial->image) }}" width="80">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.users.info.memorial.edit', $memorial->id) }}" class="btn btn-warning btn-sm">ویرایش</a>
                                            <form action="{{ route('admin.users.info.memorial.destroy', $memorial->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('آیا مطمئن هستید؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">حذف</button>
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
