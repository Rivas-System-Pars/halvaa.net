@extends('front::user.layouts.masterprofile')

@push('styles')
    <link rel="stylesheet" href="{{ theme_asset('css/UserProfile/profile.css') }}">
@endpush

@section('user-post')
    <div class="profile-container">
        <div class="profile-header">
            <div class="top-bar">
                <span class="back-icon">&#8592;</span>
                <div class="username" id="username">{{ $user->username ?? 'کاربر مهمان' }}</div>
            </div>

            <div class="header-main">
                <img class="profile-picture" id="profilePicture" src="{{ asset('storage/' . $user->image) }}"
                    alt="Profile Picture">
                <div class="profile-stats">
                    <div class="stat">
                        <span class="number">{{ $postCount }}</span>
                        <span class="label">پست ها</span>
                    </div>
                    <div class="stat">
                        <span class="number">{{ $followingCount ?? '0' }}</span>
                        <span class="label">دنبال کنندگان</span>
                    </div>
                    <div class="stat">
                        <span class="number">{{ $followersCount ?? '0' }}</span>
                        <span class="label">دنبال شوندگان</span>
                    </div>
                </div>
            </div>

            <div class="profile-info">
                <div class="first_name" id="first_name">
                    {{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}
                </div>
                <div class="bio" id="bio">
                    {{-- @dd($user->bio ) --}}
                    {{ $user->bio ?? 'بیوگرافی خود را اضافه کنید...' }}
                </div>

                <div class="birth" id="birth">
                    تاریخ تولد: {{ $user->birth ?? 'ندارد' }}
                </div>
                <div class="death" id="death">
                    تاریخ فوت: {{ $user->death ?? 'ندارد' }}
                </div>

            </div>

            <!-- Button Section -->
            <div class="button-section" id="button-Section">
                @auth
                    @php
                        $isOwner = Auth::check() && Auth::id() === $user->id;
                    @endphp

                    @if ($isOwner)
                    <button class="profile-btn" data-action="edit">
                        ویرایش پروفایل
                   </button>
                   <button class="profile-btn" data-action="create-post">
                        افزودن پست
                   </button>
                    @else
                        <form action="{{ route('user.follow', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="profile-btn follow">دنبال کردن</button>
                        </form>
                    @endif
                @else
                @endauth
            </div>
            dfhsdfygfsdyffsdyfasyfsdbfr7fg
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ theme_asset('js/pages/profile/jquery-3.7.1.min.js') }}?v=3"></script>
    <script src="{{ theme_asset('js/pages/profile/profilewithoutlogin.js') }}?v=3"></script>
@endpush
