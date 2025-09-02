    @extends('back.layouts.master')

    @section('content')

        <div class="app-content content">
            <div class="content-overlay"></div>
            <div class="header-navbar-shadow"></div>
            <div class="content-wrapper">
            </div>
            <div class="container-fluid">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="mb-0">افراد نزدیک</h4>
                    <small>بر اساس افرادی که شمارا فالوو کرده اند</small>

                    {{-- سوییچ بین فالورها / کلوزها (همه روی همین create) --}}
                    <div class="btn-group" role="group" aria-label="switch">
                        <a href="{{ route('admin.close-user.create', ['type' => 'followers']) }}"
                            class="btn btn-sm {{ ($type ?? 'followers') === 'followers' ? 'btn-primary' : 'btn-outline-primary' }}">
                            فالورها
                        </a>
                        <a href="{{ route('admin.close-user.create', ['type' => 'close']) }}"
                            class="btn btn-sm {{ ($type ?? 'followers') === 'close' ? 'btn-primary' : 'btn-outline-primary' }}">
                            افراد نزدیک
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $e)
                            <div>{{ $e }}</div>
                        @endforeach
                    </div>
                @endif

                @if (($list->count() ?? 0) === 0)
                    <div class="card">
                        <div class="card-body text-center text-muted py-5">
                            موردی یافت نشد.
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @foreach ($list as $row)
                                    @php
                                        // اگر followers: $row = Follow و باید به User برسیم
                                        $userItem = $type === 'followers' ? $row->follower ?? null : $row; // close: خودش User است
                                        $avatar = null;
                                        if ($userItem && isset($userItem->profileImage) && $userItem->profileImage) {
                                            $avatar = url($userItem->profileImage->image);
                                        }
                                        if (!$avatar) {
                                            $avatar = asset('back/app-assets/images/portrait/small/default.jpg');
                                        }
                                        // dd($userItem->count());
                                    @endphp

                                    <li class="list-group-item d-flex align-items-center">
                                        <img src="{{ $avatar }}" alt="{{ $userItem->username ?? 'user' }}" width="40"
                                            height="40" class="rounded-circle me-2" style="object-fit:cover">

                                        <div class="me-auto">
                                            <div class="fw-bold">
                                                {{ $userItem->username ?? $userItem->first_name ." ".  $userItem->last_name  }}</div>
                                                <small style="margin-right:.5rem">
                                                    @if ($userItem-> level == 'user')
                                                    کاربر عادی 
                                                    @endif
                                                    @if ($userItem-> level == 'admin')
                                                    متوفی  
                                                    @endif
                                                </small>
                                                
                                            @if (isset($userItem->name))
                                                <small class="text-muted">{{ $userItem->name }}</small>
                                            @endif
                                            @if ($type === 'followers' && isset($row->created_at))
                                                <div class="text-muted small mt-1">از
                                                    {{ $row->created_at->format('Y/m/d H:i') }} فالو می‌کند</div>
                                            @elseif($type === 'close' && isset($userItem->pivot?->created_at))
                                                <div class="text-muted small mt-1">از
                                                    {{ $userItem->pivot->created_at->format('Y/m/d H:i') }} در افراد نزدیک
                                                </div>
                                            @endif
                                        </div>

                                        @if ($type === 'followers' && $userItem)
                                            <form method="POST" action="{{ route(name: 'admin.close-user.store') }}" class="ms-2">
                                                @csrf
                                                <input type="hidden" name="close_user_id" value="{{ $userItem->id }}">
                                                <button class="btn btn-sm btn-success">افزودن به افراد نزدیک</button>
                                            </form>
                                        @elseif($type === 'close' && $userItem)
                                            <form method="POST"
                                                action="{{ route('admin.close-user.destroy', $userItem->id) }}" class="ms-2">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">حذف از افراد نزدیک</button>
                                            </form>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="card-footer">
                            {{ $list->appends(['type' => $type])->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
        </div>
        </div>
        </div>

    @endsection

    @include('back.partials.plugins', ['plugins' => ['jquery.validate']])
