<!--start .author-author__info-->
@if (auth()->check())
    <ul class="nav float-left nav_direction">
        <li class="nav-item account dropdown">
            <a class="nav-link" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="label-dropdown">حساب کاربری</span>
                <i class="mdi mdi-account-circle-outline"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-left">
                @if (auth()->user()->level == 'admin' || auth()->user()->level == 'creator')
                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                        پنل مدیریت
                    </a>
                @endif
				{{--
                <a class="dropdown-item" href="{{ route('front.user.profile') }}">
                    <i class="mdi mdi-account-card-details-outline"></i>پروفایل
                </a>
				--}}
                @if (auth()->user()->level != 'user' )
                <a class="dropdown-item" href="{{ route('front.user.showprofile', ['id' => auth()->user()->id]) }}">
                    <i class="mdi mdi-account-card-details-outline"></i>آرامگاه
                </a>
				@endif
                <a class="dropdown-item" href="{{ route('front.notification.notifications') }}">
                    <i class="mdi mdi-bell-alert-outline"></i>اعلان ها
                </a>
                <div class="dropdown-divider" role="presentation"></div>
                <a class="dropdown-item" href="{{ route('logout') }}">
                    <i class="mdi mdi-logout-variant"></i>خروج
                </a>
            </div>
        </li>
    </ul>
@else
    <ul class="nav float-left nav_direction">
        <li class="nav-item account dropdown">
            <a class="nav-link" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="label-dropdown">حساب کاربری</span>
                <i class="mdi mdi-account-circle-outline"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-left">
                <a class="dropdown-item" href="{{url('login-with-code')}}">
                    <i class="mdi mdi-account-card-details-outline"></i>ورود به سایت
                </a>
                <a class="dropdown-item" href="{{ route('register') }}">
                    <i class="mdi mdi-account-plus"></i>ثبت آرامگاه جدید 
                </a>
                <a class="dropdown-item" href="{{ url('user-register') }}">
                    <i class="mdi mdi mdi-account-plus-outline"></i>عضویت در سایت  
                </a>
            </div>
        </li>
    </ul>
@endif


<!--end /.author-author__info-->
