<button class="btn-menu col-1">
    <div class="align align__justify">
        <span></span>
        <span></span>
        <span></span>
    </div>
</button>
<div class="side-menu">
	<button class="btn side-menu-close-btn">
	<span class="mdi mdi-close"></span>
	</button>
    <div class="logo-nav-res dt-sl text-center">
        <a href="#">
            <img data-src="{{ option('info_logo', theme_asset('img/logo.png')) }}"
                alt="{{ option('info_site_title', 'داستار') }}">
        </a>
    </div>
    <div class="search-box-side-menu dt-sl text-center mt-2 mb-3">
        <form action="{{ route('front.products.search') }}" method="GET">
            <input type="text" name="q" placeholder="جستجو کنید...">
            <i class="mdi mdi-magnify"></i>
        </form>
    </div>
    <ul class="navbar-nav dt-sl">
        @foreach ($menus as $menu)
            @include('front::partials.mobile-menu.child-menu')
        @endforeach
    </ul>
</div>

<div class="overlay-side-menu"></div>
