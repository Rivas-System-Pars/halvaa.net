<?php

use App\Http\Controllers\Back\ApikeyController;
use App\Http\Controllers\Back\BasketController;
use App\Http\Controllers\Back\FamillyTreeController;
use App\Http\Controllers\Back\InstallmentController;
use App\Http\Controllers\Back\ProductGiftController;
use App\Http\Controllers\Back\RelativesController;
use App\Http\Controllers\Back\RelicsController;
use App\Http\Controllers\Back\SettlementController;
use App\Http\Controllers\Back\UserBannerController;
use App\Http\Controllers\Back\UserVerficationController;
use App\Http\Controllers\Back\VerificationController;
use App\Http\Controllers\CloseUsersController;
use App\Http\Controllers\User_PostsController;
use App\Models\Permission;
use App\Models\Order;
use App\Models\User;
use App\Models\Settlement;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Back\ProvinceController;
use App\Http\Controllers\Back\MainController;
use App\Http\Controllers\Back\TelegramController;
use App\Http\Controllers\Back\UserController;
use App\Http\Controllers\Back\ProductController;
use App\Http\Controllers\Back\BrandController;
use App\Http\Controllers\Back\FilterController;
use App\Http\Controllers\Back\AttributeGroupController;
use App\Http\Controllers\Back\AttributeController;
use App\Http\Controllers\Back\BackupController;
use App\Http\Controllers\Back\SpecTypeController;
use App\Http\Controllers\Back\PostController;
use App\Http\Controllers\Back\CategoryController;
use App\Http\Controllers\Back\PageController;
use App\Http\Controllers\Back\MenuController;
use App\Http\Controllers\Back\OrderController;
use App\Http\Controllers\Back\TransactionController;
use App\Http\Controllers\Back\SliderController;
use App\Http\Controllers\Back\BannerController;
use App\Http\Controllers\Back\CarrierController;
use App\Http\Controllers\Back\CityController;
use App\Http\Controllers\Back\LinkController;
use App\Http\Controllers\Back\ContactController;
use App\Http\Controllers\Back\StockNotifyController;
use App\Http\Controllers\Back\CommentController;
use App\Http\Controllers\Back\CurrencyController;
use App\Http\Controllers\Back\DeveloperController;
use App\Http\Controllers\Back\DiscountController;
use App\Http\Controllers\Back\InstallController;
use App\Http\Controllers\Back\RoleController;
use App\Http\Controllers\Back\PermissionController;
use App\Http\Controllers\Back\SettingController;
use App\Http\Controllers\Back\SmsController;
use App\Http\Controllers\Back\StatisticsController;
use App\Http\Controllers\Back\TariffController;
use App\Http\Controllers\Back\ThemeController;
use App\Http\Controllers\Back\TicketController;
use App\Http\Controllers\Back\WalletController;
use App\Http\Controllers\Back\WalletHistoryController;
use App\Http\Controllers\Back\WidgetController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\Back\CareeropportunitiesController;
use App\Http\Controllers\Back\SalesAgencyController;
use App\Http\Controllers\Back\DemoRequestController;
use App\Http\Controllers\Back\CounselingFormController;
use Illuminate\Support\Facades\Schema;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;
use Illuminate\Support\Facades\Http;
use App\Jobs\SendInstallmentSms;
use App\Notifications\InstallmentNotification;
use Themes\DefaultTheme\src\Controllers\UserPostController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__ . '/auth.php';
Route::get('province/get-cities', [ProvinceController::class, 'getCities'])->name('provinces.get-cities');
Route::get('/migrate', function () {
    dd(Order::latest()->has('installment')->first()->installment->items->first()->orderInstallment->order->user);
    $user = User::first();
    $user->phone_number = "09115059242";
    $user->notify(new InstallmentNotification());

    SendInstallmentSms::dispatch(Order::latest()->has('installment')->first()->installment->items->first()->id);
    dd(is_numeric(trim(option('installment_before_day'))), is_numeric(trim(option('installment_day'))), is_numeric(trim(option('installment_after_day'))));
});
Route::group(['as' => 'admin.', 'prefix' => 'admin/' . admin_route_prefix(), 'middleware' => ['guest']], function () {
    Route::get('login', [MainController::class, 'login'])->middleware(['CheckUserExists'])->name('login');
    Route::get('register', [InstallController::class, 'showRegisterForm'])->name('register')->middleware(['CheckUserNotExists']);
    Route::post('register', [InstallController::class, 'register'])->middleware(['CheckUserNotExists']);

});

// ------------------ Admin Part Routes
Route::group(['as' => 'admin.', 'prefix' => 'admin/' . admin_route_prefix(), 'middleware' => ['auth', 'Admin', 'verified', 'CheckPasswordChange', 'password.confirm']], function () {

    Route::resource('sales-agency', SalesAgencyController::class)->only(['index', 'show', 'destroy']);
    Route::get('sales-agency/{id}/download', [SalesAgencyController::class, 'download'])->name('sales-agency.download');
    Route::resource('careeropportunities', CareeropportunitiesController::class)->only(['index', 'show', 'destroy']);
    Route::get('careeropportunities/{id}/download', [CareeropportunitiesController::class, 'download'])->name('careeropportunities.download');
    Route::resource('demo-request', DemoRequestController::class)->only(['index', 'show', 'destroy']);
    Route::resource('counseling-form', CounselingFormController::class)->only(['index', 'show']);

    Route::group(['prefix' => 'settlements'], function (Router $router) {
        $router->get('/', [SettlementController::class, 'index'])->name('settlements.index');
        $router->get('/{settlement}', [SettlementController::class, 'show'])->name('settlements.show');
        $router->put('/{settlement}/change-status', [SettlementController::class, 'changeStatus'])->name('settlements.change-status');
    });


    Route::get('/installments', [InstallmentController::class, 'index'])->name('installments.index');
    Route::post('/installments', [InstallmentController::class, 'store'])->name('installments.store');
    Route::get('/installments/create', [InstallmentController::class, 'create'])->name('installments.create');
    Route::get('/installments/{installment}/edit', [InstallmentController::class, 'edit'])->name('installments.edit');
    Route::put('/installments/{installment}', [InstallmentController::class, 'update'])->name('installments.update');
    Route::delete('/installments/{installment}', [InstallmentController::class, 'destroy'])->name('installments.destroy');

    // ------------------ MainController
    Route::get('/', [MainController::class, 'index'])->name('dashboard');
    Route::get('get-tags', [MainController::class, 'get_tags'])->name('get-tags');
    Route::get('get-labels', [MainController::class, 'getLabels'])->name('get-labels');

    Route::get('notifications', [MainController::class, 'notifications'])->name('notifications');

    Route::get('file-manager', [MainController::class, 'fileManager'])->name('file-manager');
    Route::get('file-manager-iframe', [MainController::class, 'fileManagerIframe'])->name('file-manager-iframe');

    // ------------------ backups
    Route::get('backups', [BackupController::class, 'index'])->name('backups.index');
    Route::post('backups/create', [BackupController::class, 'create'])->name('backups.create');
    Route::get('backups/{backup}/download', [BackupController::class, 'download'])->name('backups.download');
    Route::delete('backups/{backup}', [BackupController::class, 'destroy'])->name('backups.destroy');

    // ------------------ users
    Route::resource('users', UserController::class);
    Route::post('users/api/index', [UserController::class, 'apiIndex'])->name('users.apiIndex');
    Route::delete('users/api/multipleDestroy', [UserController::class, 'multipleDestroy'])->name('users.multipleDestroy');
    Route::get('users/export/create', [UserController::class, 'export'])->name('users.export');
    Route::get('users/{user}/views', [UserController::class, 'views'])->name('users.views');
    Route::get('user/profile', [UserController::class, 'showProfile'])->name('user.profile.show');
    Route::put('user/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');


    // life_biography
    Route::get('user/info/life_biography', [UserController::class, 'UserInfoLifeBiographyCreate'])->name('users.info.lifebiography.create');
    Route::post('user/info/life_biography', [UserController::class, 'UserInfoLifeBiographyStore'])->name('users.info.lifebiography.store');

    Route::get('user/info/life_calender', [UserController::class, 'UserInfoLifeCalenderCreate'])->name('users.info.lifecalender.create');
    Route::post('user/info/life_calender', [UserController::class, 'UserInfoLifeCalenderStore'])->name('users.info.lifecalender.store');
    Route::get('user/info/life_calender/edit/{id}', [UserController::class, 'UserInfoLifeCalenderEdit'])->name('users.info.lifecalender.edit');
    Route::post('user/info/life_calender/edit/{id}', [UserController::class, 'UserInfoLifeCalenderUpdate'])->name('users.info.lifecalender.update');
    Route::delete('user/info/life_calender/edit/{id}', [UserController::class, 'UserInfoLifeCalenderDelete'])->name('users.info.lifecalender.destroy');

    Route::get('user/info/will', [UserController::class, 'UserInfoWillCreate'])->name('users.info.will.create');
    Route::post('user/info/will', [UserController::class, 'UserInfoWillStore'])->name('users.info.will.store');
    Route::get('user/info/will/edit/{id}', [UserController::class, 'UserInfoWillEdit'])->name('users.info.will.edit');
    Route::post('user/info/will/edit/{id}', [UserController::class, 'UserInfoWillUpdate'])->name('users.info.will.update');
    Route::delete('user/info/will/edit/{id}', [UserController::class, 'UserInfoWillDelete'])->name('users.info.will.destroy');

    Route::resource('familytree', FamillyTreeController::class)->except('index');

    Route::resource('close-user', CloseUsersController::class)->except('index');

    Route::resource('relics', RelicsController::class)->except('index', 'show', 'update');
    Route::post('relics/update/{id}', [RelicsController::class, 'update'])->name('relics.update');




    // Route::get('user/info/memorial_book', [UserController::class, 'UserInfoMemorialBookCreate'])->name('users.info.memorial.create');
    // Route::post('user/info/memorial_book', [UserController::class, 'UserInfoMemorialBookStore'])->name('users.info.memorial.store');
    // Route::get('user/info/memorial_book/edit/{id}', [UserController::class, 'UserInfoMemorialBookEdit'])->name('users.info.memorial.edit');
    // Route::post('user/info/memorial_book/edit/{id}', [UserController::class, 'UserInfoMemorialBookUpdate'])->name('users.info.memorial.update');
    // Route::delete('user/info/memorial_book/edit/{id}', [UserController::class, 'UserInfoMemorialBookDelete'])->name('users.info.memorial.destroy');
    Route::get('/verification/create', [UserVerficationController::class, 'create'])->name('verification.create');
    Route::post('/verification', [UserVerficationController::class, 'store'])->name('verification.store');

    Route::middleware(['auth'])->prefix('back/verification')->group(function () {
        // لیست همه درخواست‌ها
        Route::get('/', [VerificationController::class, 'index'])->name('back.verification.index');
        // نمایش جزئیات یک درخواست
        Route::get('/{verification}', [VerificationController::class, 'show'])->name('back.verification.show');
        // عملیات تایید یا رد
        Route::post('/{verification}/action', [VerificationController::class, 'update'])->name('back.verification.update');
    });


    // announcement
    Route::get('user/info/announcement', [UserController::class, 'UserInfoAnnouncementCreate'])->name('users.info.announcement.create');
    Route::post('user/info/announcement', [UserController::class, 'UserInfoAnnouncementStore'])->name('users.info.announcement.store');
    Route::get('user/info/announcement/edit/{id}', [UserController::class, 'UserInfoAnnouncementEdit'])->name('users.info.announcement.edit');
    Route::post('user/info/announcement/edit/{id}', [UserController::class, 'UserInfoAnnouncementUpdate'])->name('users.info.announcement.update');
    Route::delete('user/info/announcement/edit/{id}', [UserController::class, 'UserInfoAnnouncementDelete'])->name('users.info.announcement.destroy');

    Route::get('user/info/notice', [UserController::class, 'UserInfoNoticeCreate'])->name('users.info.notice.create');
    Route::post('user/info/notice', [UserController::class, 'UserInfoNoticeStore'])->name('users.info.notice.store');
    Route::get('user/info/notice/edit/{id}', [UserController::class, 'UserInfoNoticeEdit'])->name('users.info.notice.edit');
    Route::post('user/info/notice/edit/{id}', [UserController::class, 'UserInfoNoticeUpdate'])->name('users.info.notice.update');
    Route::delete('user/info/notice/edit/{id}', [UserController::class, 'UserInfoNoticeDelete'])->name('users.info.notice.destroy');

    Route::get('user/info/message_management', [UserController::class, 'UserInfoMessageManagementCreate'])->name('users.info.message.create');
    Route::post('user/info/message_management', [UserController::class, 'UserInfoMessageManagementStore'])->name('users.info.message.store');
    Route::get('user/info/message_management/edit/{id}', [UserController::class, 'UserInfoMessageManagementEdit'])->name('users.info.message.edit');
    Route::post('user/info/message_management/edit/{id}', [UserController::class, 'UserInfoMessageManagementUpdate'])->name('users.info.message.update');
    Route::delete('user/info/message_management/edit/{id}', [UserController::class, 'UserInfoMessageManagementDelete'])->name('users.info.message.destroy');


    Route::get('user/banner', [UserBannerController::class, 'create'])->name('users.userbanner.create');
    Route::post('user/banner', [UserBannerController::class, 'store'])->name('users.userbanner.store');
    Route::delete('/user/banner/{id}', [UserBannerController::class, 'destroy'])->name('users.userbanner.destroy');
    // Route::get('user/info', [UserController::class, 'UserInfoShow'])->name('users.info.show');
    // Route::put('user/info/life_biography', [UserController::class, 'UserInfoUpdate'])->name('users.info.lifebiography.update');
    // Route::delete('user/info/{id}', [UserController::class, 'UserInfoDelete'])->name('users.info.delete');

    // ------------------ wallets
    Route::resource('wallets', WalletController::class)->only(['show', 'index']);
    Route::get('wallets/histories/{history}', [WalletController::class, 'history'])->name('wallets.history');
    Route::get('wallets/{wallet}/create', [WalletController::class, 'create'])->name('wallets.create');
    Route::post('wallets/{wallet}', [WalletController::class, 'store'])->name('wallets.store');

    Route::delete('product-gifts/{productGift}', [ProductGiftController::class, 'destroy'])->name('product-gifts.destroy');
    Route::delete('product-gifts/{productGift}/products/{product}', [ProductGiftController::class, 'detachProduct'])->name('product-gifts.products.detach');
    // ------------------ products
    Route::resource('products', ProductController::class)->except('show');
    Route::get('products/published', [ProductController::class, 'published'])->name('products.published');
    Route::post('products/api/index', [ProductController::class, 'apiIndex'])->name('products.apiIndex');
    Route::delete('products/api/multipleDestroy', [ProductController::class, 'multipleDestroy'])->name('products.multipleDestroy');
    Route::post('products/image-store', [ProductController::class, 'image_store']);
    Route::post('products/image-delete', [ProductController::class, 'image_delete']);
    Route::get('product/categories', [ProductController::class, 'categories'])->name('products.categories.index');
    Route::post('product/slug', [ProductController::class, 'generate_slug']);

    Route::get('products/export/create', [ProductController::class, 'export'])->name('products.export');

    Route::get('product/prices', [ProductController::class, 'indexPrices'])->name('product.prices.index');
    Route::put('product/prices', [ProductController::class, 'updatePrices'])->name('product.prices.update');

    // ------------------ discounts
    Route::resource('discounts', DiscountController::class)->except(['show']);

    // ------------------ provinces
    Route::resource('provinces', ProvinceController::class);
    Route::post('provinces/api/index', [ProvinceController::class, 'apiIndex'])->name('provinces.apiIndex');
    Route::delete('provinces/api/multipleDestroy', [ProvinceController::class, 'multipleDestroy'])->name('provinces.multipleDestroy');
    Route::post('provinces/api/sort', [ProvinceController::class, 'sort'])->name('provinces.sort');

    // ------------------ cities
    Route::resource('cities', CityController::class)->except(['index']);
    Route::post('cities/api/{province}/index', [CityController::class, 'apiIndex'])->name('cities.apiIndex');
    Route::delete('cities/api/multipleDestroy', [CityController::class, 'multipleDestroy'])->name('cities.multipleDestroy');
    Route::post('cities/api/sort', [CityController::class, 'sort'])->name('cities.sort');

    // ------------------ brands
    Route::resource('brands', BrandController::class)->except('show');
    Route::get('brands/ajax/get', [BrandController::class, 'ajax_get']);

    // ------------------ filters
    Route::resource('filters', FilterController::class)->except('show');

    // ------------------ attributeGroups
    Route::resource('attributeGroups', AttributeGroupController::class);
    Route::get('attributeGroups/{attributeGroup}/attributes', [AttributeGroupController::class, 'attributesIndex'])->name('attributes.index');
    Route::post('attributeGroup/sort', [AttributeGroupController::class, 'sort']);

    // ------------------ attributes
    Route::resource('attributes', AttributeController::class)->except(['index', 'show']);

    // ------------------ spec types
    Route::get('spectypes/spec-type-data', [SpecTypeController::class, 'getData'])->name('spectypes.getdata');
    Route::get('spectypes/ajax/get', [SpecTypeController::class, 'ajax_get']);
    Route::resource('spectypes', SpecTypeController::class)->except(['show', 'create']);


    // ------------------ posts
    Route::resource('posts', PostController::class)->except(['show']);
    Route::get('post/categories', [PostController::class, 'categories'])->name('posts.categories.index');
    Route::post('post/slug', [PostController::class, 'generate_slug']);

    //------------------ reletives
    Route::resource('relatives', RelativesController::class);



    // ------------------ categories
    Route::resource('categories', CategoryController::class)->only(['update', 'destroy', 'store', 'edit']);
    Route::post('categories/sort', [CategoryController::class, 'sort']);
    Route::post('category/slug', [CategoryController::class, 'generate_slug']);


    // ------------------ pages
    Route::resource('pages', PageController::class)->except(['show']);

    // ------------------ apikeys
    Route::resource('apikeys', ApikeyController::class)->except(['show']);

    // ------------------ tickets
    Route::resource('tickets', TicketController::class)->except(['edit']);
    Route::post('tickets/file/store', [TicketController::class, 'storeFile'])->name('tickets.file.store');
    Route::delete('tickets/file/destroy', [TicketController::class, 'destoryFile'])->name('tickets.file.destroy');

    // ------------------ menus
    Route::resource('menus', MenuController::class)->except(['edit']);
    Route::post('menus/sort', [MenuController::class, 'sort']);

    // ------------------ orders
    Route::resource('orders', OrderController::class)->only(['index', 'show', 'destroy']);
    Route::post('orders/{order}/shipping-status', [OrderController::class, 'shipping_status'])->name('orders.shipping-status');
    Route::get('orders/{order}/print', [OrderController::class, 'print'])->name('orders.print');
    Route::get('orders/{order}/shipping-form', [OrderController::class, 'shippingForm'])->name('orders.shipping-form');
    Route::post('orders/api/index', [OrderController::class, 'apiIndex'])->name('orders.apiIndex');
    Route::delete('orders/api/multipleDestroy', [OrderController::class, 'multipleDestroy'])->name('orders.multipleDestroy');
    Route::get('order/not-completed/products', [OrderController::class, 'notCompleted'])->name('orders.notCompleted');

    Route::get('orders/export/create', [OrderController::class, 'export'])->name('orders.export');

    // ------------------ carriers
    Route::resource('carriers', CarrierController::class);
    Route::get('carriers/{carrier}/cities', [CarrierController::class, 'cities'])->name('carriers.cities');

    // ------------------ tariffs
    Route::resource('tariffs', TariffController::class);

    // ------------------ transactions
    Route::resource('transactions', TransactionController::class)->only(['index', 'show', 'destroy']);
    Route::post('transactions/api/index', [TransactionController::class, 'apiIndex'])->name('transactions.apiIndex');
    Route::delete('transactions/api/multipleDestroy', [TransactionController::class, 'multipleDestroy'])->name('transactions.multipleDestroy');

    // ------------------ wallet-histories
    Route::resource('wallet-histories', WalletHistoryController::class)->only(['index', 'show']);

    // ------------------ currencies
    Route::resource('currencies', CurrencyController::class)->except(['show']);

    // ------------------ sliders
    Route::resource('sliders', SliderController::class)->except(['show']);
    Route::post('sliders/sort', [SliderController::class, 'sort']);

    // ------------------ banners
    Route::resource('banners', BannerController::class)->except(['show']);
    Route::post('banners/sort', [BannerController::class, 'sort']);

    // ------------------ links
    Route::resource('links', LinkController::class)->except(['show']);
    Route::post('links/sort', [LinkController::class, 'sort']);
    Route::get('links/groups', [LinkController::class, 'groups'])->name('links.groups.index');
    Route::put('links/groups/update', [LinkController::class, 'updateGroups'])->name('links.groups.update');

    // ------------------ statistics
    Route::get('statistics/viewsList', [StatisticsController::class, 'viewsList'])->name('statistics.viewsList');
    Route::get('statistics/views', [StatisticsController::class, 'views'])->name('statistics.views');
    Route::get('statistics/viewCounts', [StatisticsController::class, 'viewCounts'])->name('statistics.viewCounts');
    Route::get('statistics/viewerCounts', [StatisticsController::class, 'viewerCounts'])->name('statistics.viewerCounts');
    Route::get('statistics/viewers', [StatisticsController::class, 'viewers'])->name('statistics.viewers');

    Route::get('statistics/orders', [StatisticsController::class, 'orders'])->name('statistics.orders');
    Route::get('statistics/orderValues', [StatisticsController::class, 'orderValues'])->name('statistics.orderValues');
    Route::get('statistics/orderCounts', [StatisticsController::class, 'orderCounts'])->name('statistics.orderCounts');
    Route::get('statistics/orderUsers', [StatisticsController::class, 'orderUsers'])->name('statistics.orderUsers');
    Route::get('statistics/orderProducts', [StatisticsController::class, 'orderProducts'])->name('statistics.orderProducts');

    Route::get('statistics/users', [StatisticsController::class, 'users'])->name('statistics.users');
    Route::get('statistics/userCounts', [StatisticsController::class, 'userCounts'])->name('statistics.userCounts');

    Route::get('statistics/smsLog', [StatisticsController::class, 'smsLog'])->name('statistics.smsLog');

    // ------------------ sms
    Route::resource('sms', SmsController::class)->only(['show']);

    // ------------------ contacts
    Route::resource('contacts', ContactController::class)->only(['index', 'show', 'destroy']);

    // ------------------ stock-notifies
    Route::resource('stock-notifies', StockNotifyController::class)->only(['index', 'show', 'destroy']);

    // ------------------ comments
    Route::resource('comments', CommentController::class)->only(['index', 'show', 'destroy', 'update']);

    // ------------------ roles
    Route::resource('roles', RoleController::class);

    // ------------------ permissions
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::put('permissions', [PermissionController::class, 'update'])->name('permissions.update');

    // ------------------ widgets
    Route::resource('widgets', WidgetController::class)->except(['show']);
    Route::get('widgets/{key}/template', [WidgetController::class, 'template'])->name('widgets.template');
    Route::post('widget/sort', [WidgetController::class, 'sort'])->name('widgets.sort');

    // ------------------ themes
    Route::resource('themes', ThemeController::class)->except(['edit']);

    Route::get('theme/settings', [ThemeController::class, 'showSettings'])->name('themes.settings');
    Route::post('theme/settings', [ThemeController::class, 'updateSettings']);

    // ------------------ settings
    Route::get('settings/information', [SettingController::class, 'showInformation'])->name('settings.information');
    Route::post('settings/information', [SettingController::class, 'updateInformation']);

    Route::get('settings/socials', [SettingController::class, 'showSocials'])->name('settings.socials');
    Route::post('settings/socials', [SettingController::class, 'updateSocials']);

    Route::get('settings/gateways', [SettingController::class, 'showGateways'])->name('settings.gateways');
    Route::post('settings/gateways', [SettingController::class, 'updateGateways']);

    Route::get('settings/others', [SettingController::class, 'showOthers'])->name('settings.others');
    Route::post('settings/others', [SettingController::class, 'updateOthers']);

    Route::get('settings/sms', [SettingController::class, 'showSms'])->name('settings.sms');
    Route::post('settings/sms', [SettingController::class, 'updateSms']);
    Route::get('/user-posts', [UserPostController::class, 'index']);
    Route::post('/user-posts', [UserPostController::class, 'store']);
    Route::get('/user-posts/{userPost}', [UserPostController::class, 'show']);
    Route::post('/user-posts/{userPost}/update', [UserPostController::class, 'update']);//✅
    Route::delete('/user-posts/{userPost}', [UserPostController::class, 'destroy']);


    /*
     * Basket routes
     * */
    Route::get('baskets', [BasketController::class, 'index'])->name('baskets.index');
    Route::post('baskets', [BasketController::class, 'store'])->name('baskets.store');
    Route::get('baskets/create', [BasketController::class, 'create'])->name('baskets.create');
    Route::get('baskets/{basket}/edit', [BasketController::class, 'edit'])->name('baskets.edit');
    Route::match(['put', 'patch'], 'baskets/{basket}', [BasketController::class, 'update'])->name('baskets.update');
    Route::delete('baskets/{basket}', [BasketController::class, 'destroy'])->name('baskets.destroy');
    Route::post('baskets/api/index', [BasketController::class, 'apiIndex'])->name('baskets.api-index');
    Route::delete('baskets/api/multipleDestroy', [BasketController::class, 'multipleDestroy'])->name('baskets.multipleDestroy');

    // ------------------ developer routes
    Route::group(['middleware' => 'CheckCreator'], function () {

        // ------------------ logs
        Route::get('logs', [LogViewerController::class, 'index'])->name('logs.index');

        // ------------------ settings
        Route::get('developer/settings', [DeveloperController::class, 'showSettings'])->name('developer.settings');
        Route::put('developer/settings', [DeveloperController::class, 'updateSettings']);

        Route::post('developer/downApplication', [DeveloperController::class, 'downApplication'])->name('developer.downApplication');
        Route::post('developer/upApplication', [DeveloperController::class, 'upApplication'])->name('developer.upApplication');

        Route::post('developer/webpushNotification', [DeveloperController::class, 'webpushNotification'])->name('developer.webpushNotification');

        // ------------------ updater
        Route::get('developer/updater', [DeveloperController::class, 'showUpdater'])->name('developer.showUpdater');
        Route::post('developer/updater', [DeveloperController::class, 'updateApplication'])->name('developer.updateApplication');
        Route::post('developer/updaterAfter', [DeveloperController::class, 'updaterAfter'])->name('developer.updaterAfter');
    });
});

// Push Subscriptions
Route::post('subscriptions', [PushSubscriptionController::class, 'update']);
Route::post('subscriptions/delete', [PushSubscriptionController::class, 'destroy']);
Route::get('user/info/{user_id}', [UserController::class, 'UserInfoShow'])->name('users.info.show');



// Manifest file (optional if VAPID is used)
Route::get('manifest.json', function () {
    return [
        'name' => config('app.name'),
        'gcm_sender_id' => config('webpush.gcm.sender_id')
    ];
});

// refresh csrf token
Route::get('refresh-csrf', function () {
    return csrf_token();
})->name('csrf');

Route::get('test', function () {
    $resutls = App\Jobs\GetCustomers::dispatch();
    dd($resutls);
});

//Telegram Bots
// Route::get('/telegram', [TelegramController::class, 'show']);
// Route::post('/telegram/message', [TelegramController::class, 'send_message']);
// Route::post('/telegram/webhook', [TelegramController::class, 'handleWebhook'])->name('telegram.webhook');
// Route::get('/set-webhook', [TelegramController::class, 'setWebhook']);
// Route::get('/webhook', [TelegramController::class, 'show']);


// Route::get('getBotInformation/', [TelegramController::class ,'getBotInformation']);
// Route::post('sendMessage/', [TelegramController::class ,'sendMessage']);


Route::group(['prefix' => 'telegram/'], function () {

    Route::get('getBotInformation', [TelegramController::class, 'getBotInformation']);
    Route::post('sendMessage', [TelegramController::class, 'sendMessage']);
    // Route::post('/webhook', [TelegramController::class, 'handle']);

    // Route::get('test', [TelegramController::class ,'show']);

});
