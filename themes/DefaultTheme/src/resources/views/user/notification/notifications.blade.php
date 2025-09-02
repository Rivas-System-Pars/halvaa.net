@extends('front::user.layouts.master')
@section('content')
    <div class="container mt-5">

        <h4>درخواست ها</h4>
        <hr>

        @if ($followedUsersWithMessages->isNotEmpty())
            <div class="mt-5">
                <h4>پیام‌های مدیران آرامگاه</h4>
                <hr>
                @foreach ($followedUsersWithMessages as $admin)
                    <div class="card mb-4">
                        <div class="card-header">
                            <strong>{{ $admin->username ?? $admin->name }}</strong>
                        </div>
                        <div class="card-body">
                            @foreach ($admin->messages as $msg)
                                <div class="alert alert-light mb-2">
                                    <p class="mb-1">{{ $msg->content }}</p>
                                    <small class="text-muted">{{ $msg->created_at->diffForHumans() }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        @if (session('follow') && session('status_message'))
            <div class="alert alert-info d-flex justify-content-between align-items-center" role="alert">
                <div>
                    شما درخواست دنبال کردن
                    <strong>{{ session('follow')->follower->username }}</strong>
                    را به
                    <strong>{{ session('status_message') }}</strong> تغییر دادید.
                </div>
                <button type="button" class="btn-close" onclick="this.parentElement.remove()" aria-label="Close"></button>
            </div>
        @endif


        @forelse($requests as $follow)
            <div class="card mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $follow->follower->username ?? $follow->follower->first_name ." ". $follow->follower->last_name  }}</strong>
                        <p class="mb-0">درخواست دنبال کردن شما را دارد</p>
                    </div>
                    <div>
                        <form method="POST" action="{{ url('/follow/respond/' . $follow->follower->id) }}"
                            class="d-inline-block">
                            @csrf
                            <input type="hidden" name="status" value="قبول شده">
                            <button type="submit" class="btn btn-success btn-sm">قبول</button>
                        </form>

                        <form method="POST" action="{{ url('/follow/respond/' . $follow->follower->id) }}"
                            class="d-inline-block">
                            @csrf
                            <input type="hidden" name="status" value="رد شده">
                            <button type="submit" class="btn btn-danger btn-sm">رد</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p>درخواستی برای نمایش وجود ندارد.</p>
        @endforelse

        <div class="mt-3">
            {{ $requests->links() }}
        </div>
    </div>
@endsection
