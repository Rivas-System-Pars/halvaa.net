<div class="item h-100">
    <div class="product-card h-100 shadow-sm border rounded overflow-hidden">

        {{-- تصویر پروفایل --}}
        <a class="product-thumb d-block position-relative"
           href="{{ route('front.user.showprofile', ['id' => $u->id]) }}">
<img
  src="{{ optional($u->profileImage)->url() ?? asset('images/avatar-placeholder.png') }}"
  alt="{{ $u->first_name }} {{ $u->last_name }}"
  width="64"
/>

            {{-- نسبت --}}
            @if(!empty($u->relation_name))
                <span class="badge bg-secondary position-absolute top-0 start-0 m-2">
                    نسبت: {{ $u->relation_name }}
                </span>
            @endif
        </a>

        {{-- نام --}}
        <div class="product-card-body text-center p-3">
            <h5 class="product-title m-0">
                <a href="{{ route('front.user.showprofile', ['id' => $u->id]) }}"
                   class="text-decoration-none text-dark">
                    {{ $u->first_name.' '.$u->last_name }}
                </a>
            </h5>
        </div>
    </div>
</div>
