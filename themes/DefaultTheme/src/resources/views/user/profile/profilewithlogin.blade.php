@extends('front::user.layouts.masterprofile')

@push('styles')
    <link rel="stylesheet" href="{{ theme_asset('css/UserProfile/profile-main.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('css/UserProfile/fonts/remixicon.css') }}">
@endpush

@section('user-post')
    <div class="profile-container my-3">
        <div class="profile-header">

            <div class="top-bar d-flex justify-content-between align-items-center flex-row-reverse">
                @if (!empty($weatherData))
                    <div class="weather-stat rounded">
                        <div class="d-flex justify-content-between align-items-center flex-row-reverse">
                            <span class="fs-5">
                                {!! $weatherData['icon'] ?? '' !!}
                            </span>
                            <div class="text-end ">
                                <h4 class="mb-0 fs-5 d-flex justify-content-between align-items-center flex-row-reverse">
                                    {{ round($weatherData['temperature']) }}
                                    <p class="fs-6 m-0">°C</p>
                                </h4>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="ps-3 username d-flex align-items-center justify-content-center flex-row gap-2" id="username">
                    {{ $user->username ?? 'کاربر نمونه' }}
                    @if ($user->is_verified == 1)
                        <span style="width: 22px; height: 22px;display: flex;padding: 0;margin: 0;color: var(--bs-success);"
                            class="badge rounded-circle d-flex justify-content-center align-items-center fs-5">
                            <i style="width: 100%;height: 100%;display: flex;align-items: center;justify-content: center;"
                                class="mdi mdi-check-decagram"></i>
                        </span>
                    @endif
                </div>

            </div>

            <div class="profile-header-main justify-content-center flex-column">

				<div class="profile-stats justify-content-start gap-3 flex-wrap w-auto mw-auto">
                    {{-- <div class="stat">
                        <span class="number">{{ $postCount }}</span>
                <span class="label">پست ها</span>
                      </div> --}}
                    <div class="stat d-flex align-items-center justify-content-end flex-column">
                        <span class="number">{{ $followersCount }}</span>
                        <span class="label">دنبال کنندگان</span>
                    </div>
                  {{--  <div class="stat d-flex align-items-center justify-content-end flex-column">
                        <span class="number">{{ $followingCount }}</span>
                        <span class="label">دنبال شوندگان</span>
                    </div> --}}
					@if($user->plaque_id)
                    <div class="stat d-flex align-items-center justify-content-end flex-column">
                        <span class="number">{{ $user->plaque_id }}</span>
                        <span class="label">شماره پلاک</span>
                    </div>
					@endif

                </div>
                <div class="profile-picture overflow-hidden">
                    <img class="profile-picture-inner object-fit-cover" id="profilePicture" src="{{ $profileImage }}"
                        alt="Profile Picture">
                </div>

				<div class="d-flex align-items-center justify-content-center flex-column gap-1">
				
				
				<div class="profile-name d-flex align-items-center justify-content-center w-100 fs-5" id="fullName">{{ $fullName }}</div>
				
				<div class="d-flex align-items-center justify-content-center flex-row-reverse gap-1">
					<span class="birth-year">
  {{ $user->birth ? explode('-', $user->birth)[0] : '' }}
</span>

					-
					<span class="death-year">
  {{ $user->death ? explode('-', $user->death)[0] : '' }}
					</span>
				</div>	
				
				
				</div>


            </div>

            <div class="profile-info">
 <div class="profile-bio" id="profileBio">{{ $bio }}</div>
{{--                <div class="profile-info-user">
                   
                    @if ($hasQrCodeProduct)
					
                        <img src="data:image/png;base64,{{ $qrCodeBase64 }}" alt="QR Code" class="qr_code_own">
                    @else
                        <div class="qr_code_box">
                            <p style="font-size: 10px;">
                                برای داشتن Qrcode

                                لطفا اول آن را تهیه کنید!!
                            </p>
                        </div>
                    @endif
                </div> --}}
            </div>
			
			<div class="seprating-line my-2"></div>
           
			<div class="informations d-flex align-items-center justify-content-center gap-5 flex-row-reverse">
				
                <div class="information birth-place-date  flex-row gap-2 fs-6 text-dark">
                     {{ $user->birth }} {{ $user->birthCity->name ?? '-' }}
                </div>

                <div class="information death-place-date flex-row gap-2 fs-6 text-dark">
                    {{ $user->death }} {{ $user->deathCity->name ?? '-' }}
                </div>

            </div>
            @php
                use App\Models\Follow;

                // اگر کاربر لاگین‌شده، خودش است که دارد پروفایل را می‌بیند
                $isMe = auth()->id() === $user->id;

                // رکورد فالو
                $follow = Follow::where('follower_id', auth()->id())
                    ->where('following_id', $user->id)
                    ->first();

                $canSeePosts = !$user->is_private || ($follow && $follow->status === 'قبول شده') || $isMe;
                // dd($canSeePosts)
            @endphp

			@if($canSeePosts)
            <div class="FeatureGrid w-100" id="featureGrid">
                <!-- Loading state -->
                <div class="FeatureLoader">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading features...</p>
                </div>
            </div>
			@endif


            <div class="button-section" id="buttonSection">
                @if ($isMe)
                    {{-- دکمه‌های ویرایش پروفایل و ایجاد پست برای خودِ کاربر --}}
                    <button class="profile-btn" data-action="edit">ویرایش پروفایل</button>
                    <button class="profile-btn" data-action="create-post">افزودن پست</button>
                @else
                    {{-- کاربر دیگر است: نمایش وضعیت فالو --}}
                    @if (!$follow)
                        {{-- هنوز هیچ درخواستی ارسال نکرده --}}
                        <form method="POST" action="{{ url('/follows/' . $user->id) }}" class="w-100 d-flex align-items-center justify-content-center  ">
                            @csrf
                            <button type="submit" class="profile-btn bg-info text-white w-100 h-100 py-2 d-flex align-items-center justify-content-center">دنبال کردن</button>
                        </form>
                    @elseif($follow->status === 'درحال پردازش')
                        {{-- منتظر تأیید --}}
                        <button class="profile-btn d-flex align-items-center justify-content-center w-100 h-100 py-2" disabled>درحال پردازش</button>
                    @elseif($follow->status === 'قبول شده')
                        {{-- اگر دنبال شده، دکمه آنفالو نمایش داده شود --}}
                        <form method="POST" action="{{ url('/unfollow/' . $user->id) }}" class="w-100 d-flex align-items-center justify-content-center ">
                            @csrf
                            <button type="submit" class="profile-btn  py-2  w-100 h-100 d-flex align-items-center justify-content-center">آنفالو</button>
                        </form>
                    @elseif($follow->status === 'رد شده')
                        {{-- اگر درخواست رد شده، می‌تواند دوباره درخواست بفرستد --}}
                        <form method="POST" action="{{ url('/follows/' . $user->id) }}" class="w-100 d-flex align-items-center justify-content-center ">
                            @csrf
                            <button type="submit" class="profile-btn bg-info w-100 h-100  py-2 d-flex align-items-center justify-content-center">دنبال کردن</button>
                        </form>
                    @endif
                @endif
            </div>


        </div>
    </div>



    @if (!$canSeePosts && !$isMe)
        <div class="alert alert-info mt-3">
            این کاربر خصوصی است. برای مشاهده پست‌ها باید ابتدا درخواست دنبال کردن را ارسال کرده و منتظر تأیید باشید.
        </div>
    @endif
    @if ($canSeePosts)

               


    <div class="gallery-container">
        <!-- Dynamic posts grid - will be populated by JavaScript -->
        <div class="gallery-posts-grid" id="gallery-posts-grid">
            <!-- Posts will be dynamically inserted here -->
        </div>
    </div>

                        








        <div class="modal-overlay" id="editModal">
            <div class="modal-content EditProfileModal">
                <div class="modal-header EditProfileModal-header p-3">
                    <h2>ویرایش پروفایل</h2>
                    <button class="edit-modal-close-btn btn fs-4 d-flex align-items-center justify-content-center p-0"
                        id="closeModal">
                        <i class="ri-close-fill"></i>
                    </button>
                </div>
                <div class="modal-body EditProfileModal-body justify-content-start p-2">

                    <div class="form-group EditProfileModal-form-group">
                        <label for="edit_first_name">نام </label>
                        <input type="text" id="edit_first_name" placeholder="نام خود را وارد کنید">
                    </div>

                    <div class="form-group EditProfileModal-form-group">
                        <label for="edit_last_name">نام خانوادگی</label>
                        <input type="text" id="edit_last_name" placeholder="نام خانوادگی خود را وارد کنید">
                    </div>

                    <div class="form-group EditProfileModal-form-group">
                        <label for="edit_national_code">کد ملی</label>
                        <input type="text" id="edit_national_code" placeholder="کد ملی خود را وارد کنید">
                    </div>

                    <div class="form-group EditProfileModal-form-group">
                        <label for="edit_birth">تاریخ تولد</label>
                        <input type="text" id="edit_birth" placeholder="نام خانوادگی خود را وارد کنید">
                    </div>

                    <div class="form-group EditProfileModal-form-group">
                        <label for="edit_death">تاریخ وفات</label>
                        <input type="text" id="edit_death" placeholder="نام خانوادگی خود را وارد کنید">
                    </div>
                   {{-- <div class="form-group EditProfileModal-form-group">
                        <label for="edit_birth_city_id">شهر محل تولد</label>
                        <input type="text" id="edit_birth_city_id" placeholder="نام خانوادگی خود را وارد کنید">
                    </div> 
                    <div class="form-group EditProfileModal-form-group">
                        <label for="edit_death_city_id">شهر محل وفات</label>
                        <input type="text" id="edit_death_city_id" placeholder="نام خانوادگی خود را وارد کنید">
                    </div> --}}

                    <div class="form-group EditProfileModal-form-group bio_group">
                        <label for="editBio">توضیحات</label>
                        <textarea id="editBio" placeholder="یه چیزی درباره‌ی خودت بنویس..." rows="3">
						
						</textarea>
						<small class="js-bio-counter"></small>
                    </div>

                    <div class="form-group EditProfileModal-form-group">
                        <label for="profilePictureInput">تصویر پروفایل</label>
                        <div class="picture-upload-area">
                            <input type="file" id="profilePictureInput" accept="image/*" style="display: none;">
                            <div class="current-picture">
                                <img id="previewPicture" src="" alt="">

                            </div>
                        </div>
                        <small class="help-text"> تصویری با حداقل ابعاد ۱۵۰ در ۱۵۰ پیکسل</small>
                    </div>
                    <div class="form-group EditProfileModal-form-group">
                        <span class="fr-lable">وضعیت پروفایل</span>
                        <div class="order-toggle-wrapper d-flex align-items-center gap-3 w-100">
                            <span class="order-toggle-label">خصوصی</span>
                            <div class="order-custom-toggle">
                                <input type="checkbox" id="newCustomerToggle" class="order-toggle-input">
                                <label for="newCustomerToggle" class="order-toggle-slider"></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer EditProfileModal-footer">
                    <button class="btn-cancel" id="cancelEdit">لغو</button>
                    <button class="btn-save" id="saveProfile">اعمال تغییرات</button>
                </div>
            </div>
        </div>

        <div class="modal-overlay" id="createPostModal">
            <div class="modal-content create-post-modal">
                <div class="modal-header createPostModal-header">
                    <h2 class="m-0"><i class="fas fa-plus-circle me-2"></i>ایجاد پست جدید</h2>
                    <button class="close-btn btn" id="closeCreatePostModal">&times;</button>
                </div>
                <div class="modal-body createPostModal-body">
                    <form id="postForm" class="createPostModal-postform">
                        <div class="form-group createPostModal-form-group">
                            <label for="postDescription" class="form-label">
                                <i class="fas fa-align-left me-2"></i>توضیحات
                            </label>
                            <textarea id="postDescription" name="description" class="form-control" placeholder="توضیحات خود را اینجا بنویسید..."
                                rows="4"></textarea>
                        </div>
                        <div class="form-group createPostModal-form-group">
                            <label class="form-label">عکس‌ها و ویدیوها</label>
                            <div id="mediaPreviewContainer" class="media-preview-container w-100" style="display: none;">
                            </div>
                            <div class="upload-area" id="uploadArea">
                                <div class="upload-content">
                                    <i class="ri-upload-cloud-fill fa-3x"></i>
                                    <h5 class="text-muted">عکس‌ها و ویدیوها را اینجا بکشید</h5>
                                    <p class="text-muted mb-3">یا کلیک کنید تا از کامپیوتر خود انتخاب کنید</p>
                                    <button type="button" class="btn btn-outline-primary" id="selectMediaBtn">
                                        <i class="ri-folder-open-line "></i>انتخاب فایل‌های رسانه‌ای
                                    </button>
                                    <p class="text-muted mt-2 small">فرمت‌های پشتیبانی شده: JPG، PNG، GIF، MP4، WebM،
                                        MOV</p>
                                </div>
                            </div>
                            <input type="file" id="fileInput" multiple accept="image/*,video/*"
                                style="display: none;">
                            <div class="media-count" id="mediaCount" style="display: none;"></div>
                        </div>

                        <div class="form-group createPostModal-form-group modal-footer">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                ایجاد پست
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Single Dynamic Modal - Only ONE modal that changes content dynamically -->
        <div class="modal fade" id="featureModal" tabindex="-1" aria-labelledby="featureModalLabel"
            aria-hidden="true">
            <div class="featureModal-inner modal-dialog modal-lg d-flex align-items-center flex-column">
                <div class="modal-content FeatureModal">
                    <div class="modal-header FeatureModalHeader">
                        <h5 class="modal-title FeatureModalTitle" id="featureModalLabel">Feature Title
                        </h5>
                        <button type="button" class="btn-close FeatureCloseButton m-0" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body FeatureModalBody align-items-start">
                        <!-- <div class="FeatureModalImage mb-3">
                                                    <img src="/placeholder.svg" alt="" class="img-fluid rounded" id="modalImage" style="display: none;">
                                                </div> -->
                        <div class="FeatureModalContent w-100">
                            <div class="FeatureModalDescription mb-3">
                                <div id="modalDescription"></div>
                            </div>
                            <!-- <div class="FeatureModalDetails">
                                                        <div class="FeatureDetailsGrid" id="modalDetails"> -->
                            <!-- Dynamic details will be inserted here -->
                            <!-- </div>
                                                    </div>
                                                    <div class="FeatureModalTags mt-3" id="modalTags"> -->
                            <!-- Tags will be inserted here -->
                            <!-- </div>
                                                    <div class="FeatureModalMeta mt-3" id="modalMeta"> -->
                            <!-- Additional metadata will be inserted here -->
                            <!-- </div>
                                                </div> -->
                        </div>
                        <!-- <div class="modal-footer FeatureModalFooter">
                                                <div class="FeatureModalActions">
                                                    <div class="FeatureCirclePreview">
                                                        <span class="badge bg-primary" id="modalCircleId"></span>
                                                    </div>
                                                    <div class="FeatureModalButtons">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary FeatureActionButton" id="modalActionButton">Learn More</button>
                                                    </div>
                                                </div>
                                            </div> -->
                    </div>
                </div>
            </div>

        </div>

        <div class="gallery-modal" id="gallery-post-modal">
            <div class="gallery-modal-content">
                <div class="gallery-modal-media">
                    <button class="gallery-close-btn" onclick="closeModal()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>

                    <!-- Swiper -->
                    <div class="swiper gallery-modal-swiper" dir="rtl">
                        <div class="swiper-wrapper">
                            <!-- Slides will be inserted here dynamically -->
                        </div>

                        <!-- Navigation buttons -->
                        <div class="swiper-button-prev gallery-swiper-button-prev"></div>
                        <div class="swiper-button-next gallery-swiper-button-next"></div>

                        <!-- Pagination -->
                        <div class="swiper-pagination gallery-swiper-pagination"></div>
                    </div>
                </div>

                <div class="gallery-modal-sidebar">
                    <div class="gallery-modal-header">
                        <div class="gallery-user-info">
                            <div
                                class="gallery-user-avatar d-flex align-items-center justify-content-center p-0 overflow-hidden">
                                <img src="" class="w-100 h-100 object-fit-cover">
                            </div>
                            <div class="gallery-username">کاربر نمونه</div>
                        </div>

                    </div>

                    <div class="gallery-modal-description" id="gallery-modal-description-id">
                        این توضیحات پست است. می‌توانید این بخش را بر اساس پست واقعی تغییر
                        دهید.
                    </div>

                    <div class="gallery-modal-actions">
                        <div class="gallery-actions-row">
                            <button class="gallery-action-btn" id="gallery-like-btn">
                                <svg viewBox="0 0 24 24">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                    </path>
                                </svg>
                            </button>
                            <button class="gallery-action-btn" onclick="showComments()">
                                <svg viewBox="0 0 24 24">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                </svg>
                            </button>
                            <!-- NEW: Edit Post Button -->
                            <button class="gallery-action-btn" id="gallery-edit-post-btn" onclick="showedit()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9"></path>
                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="gallery-likes-count" id="gallery-likes-count-id">142 پسندیدن
                        </div>
                    </div>

                    <!-- Sliding Overlay -->
                </div>
                <div class="gallery-sliding-overlay" id="gallery-sliding-overlay-id">
                    <div class="gallery-overlay-header">
                        <div class="gallery-overlay-title" id="gallery-overlay-title-id">پسندیدن‌ها</div>
                        <button class="gallery-overlay-close" onclick="hideOverlay()">
                            <i class="ri-close-fill"></i>
                        </button>
                    </div>
                    <div class="gallery-overlay-content" id="gallery-overlay-content-id">
                        <!-- Content will be inserted here -->
                    </div>
                    <div class="gallery-comment-form" id="gallery-comment-form-id" style="display: none">

                        <input type="text" class="gallery-comment-input" id="gallery-comment-input-id"
                            placeholder="نظر خود را اضافه کنید..." oninput="toggleSubmitButton()" />
                        <button class="gallery-comment-submit" id="gallery-submit-btn" onclick="addComment()">
                            ارسال
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

@if($canSeePosts)
    @if ($userBanners->count())
        <div class="Option_Card_Title my-4">
            <div class="Option_Card_Right_Line"></div>
            <h2 class="Option_Card_Center_Line">بنرهای من</h2>
            <div class="Option_Card_Left_Line"></div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($userBanners as $banner)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 rounded-3 text-center">
                        <a href="{{ asset($banner->path) }}" target="_blank" class="d-block p-2">
                            <img src="{{ asset($banner->path) }}" alt="User Banner" class="img-fluid rounded"
                                style="max-height: 240px; object-fit: cover;">
                        </a>
                        <div class="card-footer bg-white border-0">
                            <small class="text-muted">{{ $banner->created_at->format('Y/m/d H:i') }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endif
@if($canSeePosts)
    <div class="Option_Card_Title my-4">
        <div class="Option_Card_Right_Line"></div>
        <h2 class="Option_Card_Center_Line">آرامگاه های مرتبط</h2>
        <div class="Option_Card_Left_Line"></div>
    </div>
    <div class="row hiii">
        <div class="col-xl-12 col-lg-12">
            <section class="slider-section dt-sl mb-3">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="section-title text-sm-title title-wide no-after-title-wide">
                            <h2>آرامگاه های مرتبط</h2>
                            <a href="/all-user">مشاهده همه</a>
                        </div>
                    </div>

                    <div class="col-12 px-res-0">
                        <div class="product-carousel carousel-md owl-carousel owl-theme">
                            @foreach ($related_users as $ru)
                                @include('front::partials.user-block', ['u' => $ru])

                            @endforeach

                        </div>

                    </div>
                </div>
            </section>
        </div>
    </div>
@endif
@endsection



@if($canSeePosts)
@push('scripts')
<script src="{{ theme_asset('js/pages/profile/gallery.js') }}"></script>
<script src="{{ theme_asset('js/pages/profile/biography.js') }}"></script>
    <script src="{{ theme_asset('js/pages/profile/profile.js') }}"></script>

@endpush
@endif
