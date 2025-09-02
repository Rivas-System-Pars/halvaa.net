<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use themes\DefaultTheme\src\Controllers\FollowController;
use Themes\DefaultTheme\src\Controllers\MainController;
use Themes\DefaultTheme\src\Controllers\NotificationController;
use Themes\DefaultTheme\src\Controllers\PostCommentController;
use Themes\DefaultTheme\src\Controllers\PostController;
use Themes\DefaultTheme\src\Controllers\PostLikeController;
use Themes\DefaultTheme\src\Controllers\ProductController;
use Themes\DefaultTheme\src\Controllers\CartController;
use Themes\DefaultTheme\src\Controllers\ReturnProductController;
use Themes\DefaultTheme\src\Controllers\SettlementController;
use Themes\DefaultTheme\src\Controllers\StockNotifyController;
use Themes\DefaultTheme\src\Controllers\PageController;
use Themes\DefaultTheme\src\Controllers\BrandController;
use Themes\DefaultTheme\src\Controllers\OrderController;
use Themes\DefaultTheme\src\Controllers\UserController;
use Themes\DefaultTheme\src\Controllers\SitemapController;
use Themes\DefaultTheme\src\Controllers\ContactController;
use Themes\DefaultTheme\src\Controllers\FavoriteController;
use Themes\DefaultTheme\src\Controllers\TicketController;
use Themes\DefaultTheme\src\Controllers\UserPostController;
use Themes\DefaultTheme\src\Controllers\UserProfileController;
use Themes\DefaultTheme\src\Controllers\VerifyController;
use Themes\DefaultTheme\src\Controllers\DiscountController;
use Themes\DefaultTheme\src\Controllers\WalletController;

use Themes\DefaultTheme\src\Controllers\CareeropportunitiesController;
use Themes\DefaultTheme\src\Controllers\SalesAgencyController;
use Themes\DefaultTheme\src\Controllers\DemoRequestController;
use Themes\DefaultTheme\src\Controllers\CounselingFormController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Back\RatingController;

// ------------------ Front Part Routes

Route::group(['as' => 'front.'], function () {
    // ------------------ MainController
    Route::get('/', [MainController::class, 'index'])->name('index');
    Route::get('/careeropportunities', [CareeropportunitiesController::class, 'index'])->name('careeropportunities.index');
    Route::post('/careeropportunities', [CareeropportunitiesController::class, 'store'])->name('careeropportunities.store');
    Route::get('/sales-agency', [SalesAgencyController::class, 'index'])->name('sales-agency.index');
    Route::post('/sales-agency', [SalesAgencyController::class, 'store'])->name('sales-agency.store');
    Route::get('/demo-request', [DemoRequestController::class, 'index'])->name('demo-request.index');
    Route::post('/demo-request', [DemoRequestController::class, 'store'])->name('demo-request.store');
    Route::get('/counseling-form', [CounselingFormController::class, 'index'])->name('counseling-form.index');
    Route::post('/counseling-form', [CounselingFormController::class, 'store'])->name('counseling-form.store');
    Route::get('/get-new-captcha', [MainController::class, 'captcha']);
    Route::get('/users/getall', [UserController::class, 'AllUser']);

    Route::get('postof/{userId}', [UserPostController::class, 'userPosts']);

    Route::get('/baskets/{basket}', [\Themes\DefaultTheme\src\Controllers\BasketController::class, 'show'])->name('baskets.show');
    Route::post('/baskets/{basket}/add-to-cart', [\Themes\DefaultTheme\src\Controllers\BasketController::class, 'addToCart'])->name('baskets.add-to-cart');
    // ------------------ posts
    Route::resource('posts', PostController::class)->only(['index', 'show']);
    Route::get('posts/category/{category}', [PostController::class, 'category'])->name('posts.category');

    // ------------------ products
    Route::resource('products', ProductController::class)->only(['show', 'index']);
    Route::get('products/category/{category}', [ProductController::class, 'category'])->name('products.category');
    Route::get('products/category-products/{category}', [ProductController::class, 'categoryProducts'])->name('products.category-products');
    Route::get('products/category-specials/{category}', [ProductController::class, 'categorySpecials'])->name('products.category-specials');
    Route::get('search', [ProductController::class, 'search'])->name('products.search');
    Route::post('search', [ProductController::class, 'ajax_search'])->name('products.ajax_search');
    Route::get('product/specials', [ProductController::class, 'specials'])->name('products.specials');
    Route::get('product/discount', [ProductController::class, 'discount'])->name('products.discount');
    Route::get('product/{product}/prices', [ProductController::class, 'prices'])->name('products.prices');
    Route::get('product/compare/{product1}/{product2?}/{product3?}', [ProductController::class, 'compare'])->name('products.compare');
    Route::post('product/compare', [ProductController::class, 'similarCompare'])->name('products.similar-compare');
    Route::get('products/{price}/priceChart', [ProductController::class, 'priceChart'])->name('products.priceChart');
    Route::post('products/ratings', [RatingController::class, 'store']);



    // ------------------ cart
    Route::get('cart', [CartController::class, 'show'])->name('cart');
    Route::post('cart/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::put('cart', [CartController::class, 'update']);
    Route::delete('cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::post('stock-notify', [StockNotifyController::class, 'store']);


    // ------------------ pages
    Route::get('pages/{page}', [PageController::class, 'show'])->name('pages.show');

    // ------------------ brands
    Route::get('brands/{brand}', [BrandController::class, 'show'])->name('brands.show');

    // ------------------ sitemap
    Route::get('sitemap', [SitemapController::class, 'index']);
    Route::get('sitemap-posts', [SitemapController::class, 'posts']);
    Route::get('sitemap-pages', [SitemapController::class, 'pages']);
    Route::get('sitemap-products', [SitemapController::class, 'products']);

    // ------------------ contacts
    Route::resource('contact', ContactController::class)->only(['index', 'store']);

    // ------------------ orders
    Route::any('orders/payment/callback/{gateway}', [OrderController::class, 'verify'])->name('orders.verify');

    // ------------------ wallet
    Route::any('wallet/payment/callback/{gateway}', [WalletController::class, 'verify'])->name('wallet.verify');

    // ------------------ authentication required
    Route::group(['middleware' => ['auth', 'verified', 'CheckPasswordChange']], function () {

        Route::group(['prefix' => 'settlements'], function (Router $router) {
            $router->get('/', [SettlementController::class, 'index'])->name('settlements.index');
            $router->get('/create', [SettlementController::class, 'create'])->name('settlements.create');
            $router->post('/store', [SettlementController::class, 'store'])->name('settlements.store');
            $router->get('/{settlement}', [SettlementController::class, 'show'])->name('settlements.show');
            $router->post('/{settlement}/cancel', [SettlementController::class, 'cancel'])->name('settlements.cancel');
        });


        Route::post('/user-posts', [UserPostController::class, 'store']);
        Route::get('/user-posts/{userPost}', [UserPostController::class, 'show']);
        Route::post('/user-posts/{userPost}/update', [UserPostController::class, 'update']); //✅
        Route::delete('/user-posts/{userPost}', [UserPostController::class, 'destroy']);
        Route::post('/userprofile/update', [UserProfileController::class, 'updateProfile'])->name('user.profile.update');
        //Like & Comments
        Route::post('/user-posts/{userPost}/like', [PostLikeController::class, 'toggle']); //✅
        Route::post('/user-posts/{userPost}/comment', [PostCommentController::class, 'store']); //✅
        Route::delete('/user-posts/{userPost}/comment/{comment}', [PostCommentController::class, 'destroy']); //✅


        // ------------------ UserProfile Auth Required
        // Route::group(['middleware' => ['auth', 'verified', 'CheckPasswordChange']], function () {

        //     // Route::group(['prefix' => 'users'], function () {

        //         Route::get('/profile/{id}', [UserProfileController::class, 'showProfile'])->name('showProfile');


        //     // });
        // });

        // ------------------ MainController
        Route::get('checkout', [MainController::class, 'checkout'])->name('checkout');
        Route::post('checkout/check-referral-code', [MainController::class, 'checkReferralCode'])->name('check-referral-code');
        Route::get('checkout-prices', [MainController::class, 'getPrices'])->name('checkout.prices');

        // ------------------ discount
        Route::post('discount', [DiscountController::class, 'store'])->name('discount.store');
        Route::delete('discount', [DiscountController::class, 'destroy'])->name('discount.destroy');

        // ------------------ orders
        Route::resource('orders', OrderController::class);
        Route::get('orders/{order}/return-products/{product}', [ReturnProductController::class, 'show'])->name('orders.return-products.show');
        Route::post('orders/{order}/return-products/{product}', [ReturnProductController::class, 'store'])->name('orders.return-products.store');
        Route::get('orders/pay/{order}', [OrderController::class, 'pay'])->name('orders.pay');
        Route::get('orders/pay/{order}/pre-pay', [OrderController::class, 'prePay'])->name('orders.pre-pay');
        Route::get('orders/pay/{order}/installments/{installment}', [OrderController::class, 'payInstallment'])->name('orders.pay-installment');
        Route::match(['get', 'post'], 'orders/pay/{order}/installments/{installment}/verify', [OrderController::class, 'verifyInstallment'])->name('orders.verify-installment');
        Route::match(['get', 'post'], 'orders/pay/{order}/pre-pay/verify', [OrderController::class, 'prePayVerify'])->name('orders.pre-pay-verify');

        // ------------------ wallet
        Route::resource('wallet', WalletController::class)->only(['index', 'show', 'create', 'store']);

        // ------------------ user
        Route::get('user/profile', [UserController::class, 'profile'])->name('user.profile');
        Route::get('user/comments', [UserController::class, 'comments'])->name('user.comments');
        Route::get('user/edit-profile', [UserController::class, 'editProfile'])->name('user.profile.edit');
        //Route::put('user/profile', [UserController::class, 'update_profile'])->name('user.profile.update');
        Route::get('user/change-password', [UserController::class, 'changePassword'])->name('user.password');
        Route::put('user/change-password', [UserController::class, 'updatePassword'])->name('user.password.update');


        //Follow
        Route::post('/follows/{userToFollow}', [FollowController::class, 'follow']); //✅
        Route::post('/unfollow/{userToUnfollow}', [FollowController::class, 'unfollow'])->name('unfollow');
        Route::post('/follow/respond/{follower}', [FollowController::class, 'respondToRequest']); //✅
        Route::get('/user/follow/requests', [FollowController::class, 'followRequests'])->name('notification.notifications');




        Route::group(['middleware' => ['EnsureForceChange']], function () {
            Route::get('user/force-change-password', [UserController::class, 'forceChangePassword'])->name('user.force-change-password');
            Route::post('user/force-change-password', [UserController::class, 'forceUpdatePassword'])->name('user.force-update-password');
        });

        // ------------------ products
        Route::get('products/{price}/download', [ProductController::class, 'download'])->name('products.download');
        Route::post('products/{product}/comments', [ProductController::class, 'comments'])->name('product.comments');

        // ------------------ posts
        Route::post('posts/{post}/comments', [PostController::class, 'comments'])->name('post.comments');

        // ------------------ favorites
        Route::resource('favorites', FavoriteController::class)->only(['index', 'store', 'destroy']);

        // ------------------ tickets
        Route::resource('tickets', TicketController::class)->except(['destroy']);
    });

    // ------------------ verify user routes
    Route::group(['middleware' => ['auth', 'notVerified', 'CheckPasswordChange']], function () {
        Route::get('verify', [VerifyController::class, 'showVerify'])->name('verify.showVerify');
        Route::post('verify', [VerifyController::class, 'verifyCode'])->name('verify.verifyCode')->middleware('throttle:15,1');
        Route::get('change-username', [VerifyController::class, 'showChangeUsername'])->name('verify.showChangeUsername');
        Route::post('change-username', [VerifyController::class, 'changeUsername'])->name('verify.changeUsername');
    });

    // Route::group(['middleware' => ['auth', 'notVerified', 'CheckPasswordChange']], function () {


    Route::get('user/profile/{id}', [UserProfileController::class, 'showprofile'])->name('user.showprofile');


    Route::get('user/{id}', [UserController::class, 'user']);

    Route::get('user/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('user/comments', [UserController::class, 'comments'])->name('user.comments');


    Route::get('/user-posts/{userPost}/like_exist', [PostLikeController::class, 'likeExist']); //✅


    //Route::put('/user-posts/{userPost}', [UserPostController::class, 'update']);

    // });
    Route::get('/user-posts', [UserPostController::class, 'index']);


    Route::get('/all-user', [UserProfileController::class, 'alluser'])->name('user.getall');
});

//user memorials ---------------------------------------------------------------
Route::prefix('memorials')->group(function () {
    Route::get('/user/{target_user}', [UserProfileController::class, 'indexmemorials']);
    Route::post('/user/{target_user}', [UserProfileController::class, 'storememorials']);
    Route::get('/{id}', [UserProfileController::class, 'showmemorials']);
    Route::delete('/{id}', [UserProfileController::class, 'destroymemorials']);
});
 //user comments ----------------------------------------------------------------
Route::prefix('usercomments')->group(function () {
    Route::get('/user/{target_user}', [UserProfileController::class, 'indexusercomments']);
    Route::post('/user/{target_user}', [UserProfileController::class, 'storeusercomments']);
    // Route::get('/{id}', [UserProfileController::class, 'showusercomments']);
    Route::delete('/{id}', [UserProfileController::class, 'destroyusercomments']);
});


// get auth user in 404 page
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
