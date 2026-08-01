<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BloggerCategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CourierIntegrationController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DynamicTableController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\HomePageSettingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceCustomFormController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadsFormController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\NewsletterSubscriberController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OutletLocationController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductSpecificationController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\ShippingMethodController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Models\Promotion;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('oc', function() {
    \Artisan::call('optimize:clear');
});


Route::get("/link", function () {
    Artisan::call('storage:link');
    dd('Done.');
});


Route::get('/clear-all', function () {
    // Clear config cache
    Artisan::call('config:clear');

    // Clear route cache
    Artisan::call('route:clear');

    // Clear view cache
    Artisan::call('view:clear');

    // Clear application cache
    Artisan::call('cache:clear');

    // Clear compiled services & packages
    Artisan::call('optimize:clear');

    // Create storage symlink
    Artisan::call('storage:link');

    return 'All caches cleared & storage linked!';
});



// new routes for new theme
Route::get('/', [FrontController::class, 'home'])->name('home');
Route::get('shop-new', [FrontController::class, 'product_list'])->name('shop-new');
Route::get('theme/login', [FrontController::class, 'theme_login'])->name('theme-login');
Route::get('theme/register', [FrontController::class, 'theme_register'])->name('theme-register');
Route::get('outlets', [FrontController::class, 'outlets'])->name('outlets');
Route::get('theme-carts', [FrontController::class, 'theme_carts'])->name('theme-carts');
Route::patch('ajax/theme-carts/{cart}', [FrontController::class, 'update_theme_cart'])->name('theme-cart-update');
Route::delete('ajax/theme-carts/{cart}', [FrontController::class, 'remove_theme_cart'])->name('theme-cart-remove');
Route::get('ajax/side-cart', [FrontController::class, 'side_cart_data'])->name('side-cart-data');
Route::get('theme-checkout', [FrontController::class, 'theme_checkout'])->name('theme-checkout');
Route::get('product/{slug?}', [FrontController::class, 'single_product'])->name('single-product');
Route::get('thankyou-page', [FrontController::class, 'thankyou_page'])->name('thankyou-page');
Route::get('wishlist', [FrontController::class, 'wishlist'])->name('wishlist');
Route::get('order-tracking', [FrontController::class, 'order_tracking'])->name('order-tracking');
Route::post('order-tracking', [FrontController::class, 'track_order'])->name('order-tracking.search')->middleware('throttle:10,1');
Route::post('newsletter/subscribe', [NewsletterSubscriberController::class, 'store'])
    ->middleware('throttle:10,1')->name('newsletter.subscribe');


Route::get('/home/old', [FrontController::class, 'html'])->name('index');
Route::get('products', [FrontController::class, 'products'])->name('products');
Route::get('product-details/{slug}', [FrontController::class, 'product_details'])->name('product-details');
Route::get('contacts', [FrontController::class, 'contact_page'])->name('contact-us');
Route::get('abouts', [FrontController::class, 'about_page'])->name('about-us');

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::get('user-login', [AuthController::class, 'user_login'])->name('user-login');
Route::get('user-register', [AuthController::class, 'user_register'])->name('user-register');

Route::post('post_login', [AuthController::class, 'postLogin'])->middleware('throttle:10,1')->name('login.post');
Route::post('post_user_login', [AuthController::class, 'postUserLogin'])->name('user.login.post');
Route::post('post_user_register', [AuthController::class, 'postUserRegister'])->name('user.register.post');

Route::get('send-pending-email', [EmailController::class, 'sendPendingEmail'])->name('send-pending-email');
Route::get('product-category/{category}', [FrontController::class, 'product_category_wise'])->name('product-category-wise');
Route::get('product-brand/{brand}', [FrontController::class, 'product_brand_wise'])->name('product-brand-wise');
Route::get('track-your-order', [FrontController::class, 'track_your_order'])->name('track-your-order');
Route::post('track-your-order', [FrontController::class, 'post_track_your_order'])->name('post-track-your-order');
Route::get('all-products', [FrontController::class, 'all_products'])->name('all-products');
Route::get('user-carts', [FrontController::class, 'user_cart'])->name('user-carts');
Route::get('add-to-cart/{slug}', [FrontController::class, 'add_to_cart'])->name('add-to-cart');
Route::post('ajax/add-to-cart', [FrontController::class, 'add_to_cart_ajax'])->name('ajax-add-to-cart');
Route::get('add-to-cart-details', [FrontController::class, 'add_to_cart_details'])->name('add-to-cart-details');
Route::get('add-to-wishlist/{product_id}', [FrontController::class, 'add_to_wishlist'])->name('add-to-wishlist');
Route::get('my-wishlist', [FrontController::class, 'my_wishlist'])->name('my-wishlist');
Route::post('/wishlist/add', [FrontController::class, 'add_wishlist'])->name('wishlist.add');
Route::get('/ajax/wishlist', [FrontController::class, 'wishlist_data'])->name('wishlist.data');
Route::get('remove-wishlist/{id}', [FrontController::class, 'remove_wishlist'])->name('remove-wishlist');
Route::get('remove-from-cart/{id}', [FrontController::class, 'remove_from_cart'])->name('remove-from-cart');
Route::get('blogs', [FrontController::class, 'blogs'])->name('blogs');
Route::get('blog_details/{id}', [FrontController::class, 'blog_details'])->name('blog-details');

Route::get('careers', [FrontController::class, 'careers'])->name('careers');
Route::get('careers/{id}', [FrontController::class, 'career_details'])->name('career-details');
// Route::get('order-history', [FrontController::class, 'order_history'])->name('order-history')->middleware(['check-permission']);

Route::get('checkout', [FrontController::class, 'checkout_page'])->name('checkout');
Route::post('go-checkout', [FrontController::class, 'go_checkout'])->name('go-checkout');
Route::post('checkout', [FrontController::class, 'checkout_store'])->name('checkout-store');
Route::get('order-success', [FrontController::class, 'order_success'])->name('order-success');
Route::get('terms-and-conditions', [FrontController::class, 'terms_and_conditions'])->name('terms-and-conditions');
Route::get('return-policy', [FrontController::class, 'return_policy'])->name('return-policy');
Route::get('privacy-policy', [FrontController::class, 'privacy_policy'])->name('privacy-policy');
Route::get('faq', [FrontController::class, 'faq'])->name('faq');



Route::group(['middleware' => ['auth']], function () {
	Route::get('/reports/{report}', [ReportController::class, 'show'])
		->where('report', 'sales|product-sales|customers|inventory|tax|profit-loss|payments|best-selling-products')
		->name('reports.show');
	Route::get('/marketing', [MarketingController::class, 'edit'])->name('marketing.edit');
	Route::put('/marketing', [MarketingController::class, 'update'])->name('marketing.update');
	Route::get('/admin/faqs', [FaqController::class, 'index'])->name('admin-faqs.index');
	Route::post('/admin/faqs', [FaqController::class, 'store'])->name('admin-faqs.store');
	Route::put('/admin/faqs/{faq}', [FaqController::class, 'update'])->name('admin-faqs.update');
	Route::delete('/admin/faqs/{faq}', [FaqController::class, 'destroy'])->name('admin-faqs.destroy');
	Route::get('/courier-integrations', [CourierIntegrationController::class, 'index'])->name('courier-integrations.index');
	Route::post('/courier-integrations', [CourierIntegrationController::class, 'update'])->name('courier-integrations.update');
	Route::post('/courier-integrations/steadfast', [CourierIntegrationController::class, 'updateSteadfast'])->name('courier-integrations.steadfast.update');
	Route::delete('/courier-integrations/steadfast', [CourierIntegrationController::class, 'deleteSteadfast'])->name('courier-integrations.steadfast.delete');
	Route::get('/courier-integrations/steadfast/balance', [CourierIntegrationController::class, 'steadfastBalance'])->name('courier-integrations.steadfast.balance');
	Route::post('/orders/{id}/fraud-check', [CourierIntegrationController::class, 'check'])->name('orders-fraud-check');
	Route::post('/orders/{id}/steadfast/place', [CourierIntegrationController::class, 'placeSteadfastOrder'])->name('orders-steadfast-place');
	Route::post('/orders/{id}/steadfast/status', [CourierIntegrationController::class, 'checkSteadfastStatus'])->name('orders-steadfast-status');
	Route::post('/orders/courier/bulk-send', [CourierIntegrationController::class, 'bulkSend'])->name('orders-courier-bulk-send');


	// customer panel routes
	Route::get('customer-order-history', [FrontController::class, 'customer_order_history'])->name('customer-order-history');
	Route::get('customer-order-details/{orderid}', [FrontController::class, 'customer_order_details'])->name('customer-order-details');

	Route::get('customer-shipping-address', [FrontController::class, 'customer_shipping_address'])->name('customer-shipping-address');
	Route::get('add-shipping-address', [FrontController::class, 'add_shipping_address'])->name('add-shipping-address');
	Route::get('post-shipping-address', [FrontController::class, 'post_shipping_address'])->name('post-shipping-address');

	Route::get('customer-dashboard', [FrontController::class, 'customer_dashboard'])->name('customer-dashboard');
	Route::get('customer-profile', [FrontController::class, 'customer_profile'])->name('customer-profile');
	Route::post('customer-profile', [FrontController::class, 'post_customer_profile'])->name('post-customer-profile');
	Route::get('customer-logout', [FrontController::class, 'customer_logout'])->name('customer-logout');


	Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
	Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
	Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');

	// agents route
	Route::get('/agents', [AgentController::class, 'index'])->name('agents-index')->middleware(['check-permission']);
    Route::get('/agents/create', [AgentController::class, 'create'])->name('agents-create')->middleware(['check-permission']);
	Route::post('/agents', [AgentController::class, 'store'])->name('agents-store');
	Route::get('/agents/{id?}', [AgentController::class, 'show'])->name('agents-show')->middleware(['check-permission']);
	Route::get('/agents/{id?}/edit', [AgentController::class, 'edit'])->name('agents-edit')->middleware(['check-permission']);
	Route::put('/agents/{id?}', [AgentController::class, 'update'])->name('agents-update');
	Route::post('/agents/search', [AgentController::class, 'search'])->name('agents-search');
	Route::delete('/agents/{id?}', [AgentController::class, 'destroy'])->name('agents-destroy')->middleware(['check-permission']);
	// end agents






    //promotion route
	Route::get('/promotion', [PromotionController::class, 'index'])->name('promotion-index')->middleware(['check-permission']);
	Route::get('/promotion/create', [PromotionController::class, 'create'])->name('promotion-create')->middleware(['check-permission']);
	Route::post('/promotion', [PromotionController::class, 'store'])->name('promotion-store');
	Route::get('/promotion/{id?}', [PromotionController::class, 'show'])->name('promotion-show')->middleware(['check-permission']);
	Route::get('/promotion/{id?}/edit', [PromotionController::class, 'edit'])->name('promotion-edit')->middleware(['check-permission']);
	Route::put('/promotion/{id}', [PromotionController::class, 'update'])->name('promotion-update');
	Route::delete('/promotion/{id?}', [PromotionController::class, 'destroy'])->name('promotion-destroy');
	Route::post('/promotion/search', [PromotionController::class, 'search'])->name('promotion-search');




	
	// users route
    Route::get('user-list',        [UserController::class, 'index'])->name('users.index')->middleware(['check-permission']);
    Route::get('user-show/{id?}',        [UserController::class, 'show'])->name('user.show')->middleware(['check-permission']);
    Route::get('create-user',      [UserController::class, 'create'])->name('create-user')->middleware(['check-permission']);
    Route::post('create-user',      [UserController::class, 'store'])->name('store-user');
    Route::get('edit-user/{id?}',      [UserController::class, 'edit_form'])->name('user.edit')->middleware(['check-permission']);
    Route::post('user-update',      [UserController::class, 'update'])->name('user.update');
    Route::get('user-details/{id?}',     [UserController::class, 'show'])->middleware(['check-permission']);
    Route::delete('user-delete/{id?}',   [UserController::class, 'destroy'])->name('user.destroy')->middleware(['check-permission']);
	Route::get('user-profile/{id}',        [UserController::class, 'user_profile'])->name('user-profile');
	Route::get('/account-settings/{id?}/edit', [UserController::class, 'profile_edit'])->name('profile-edit');
	Route::put('/account-settings/{id}', [UserController::class, 'profile_update'])->name('profile-update');
	Route::post('/user/search', [UserController::class, 'search'])->name('user-search');
	Route::put('/user/{id}/update-profile-image', [UserController::class, 'updateProfileImage'])->name('update-profile-image');
	Route::get('app-settings', [UserController::class, 'app_settings'])->name('app-settings')->middleware(['check-permission']);
	Route::post('app-settings', [UserController::class, 'store_app_settings'])->name('save-app-settings');


    Route::get('permission-list',        [UserController::class, 'permission_index'])->name('permission.index')->middleware(['check-permission']);
    Route::get('permission-show/{id}',        [UserController::class, 'permission_show'])->name('permission.show')->middleware(['check-permission']);
    Route::get('create-permission',      [UserController::class, 'permission_create'])->name('create-permission')->middleware(['check-permission']);
    Route::post('create-permission',      [UserController::class, 'permission_store'])->name('store-permission')->middleware(['check-permission']);
    Route::get('edit-permission/{id}',      [UserController::class, 'permission_edit'])->name('permission.edit')->middleware(['check-permission']);
    Route::post('permission-update',      [UserController::class, 'permission_update'])->name('permission.update')->middleware(['check-permission']);
    Route::get('permission-details/{id}',     [UserController::class, 'permission_show'])->middleware(['check-permission']);
    Route::delete('permission-delete/{id}',   [UserController::class, 'permission_destroy'])->name('permission.destroy')->middleware(['check-permission']);
	Route::post('/permission/search', [UserController::class, 'permission_search'])->name('permission-search')->middleware(['check-permission']);

    Route::get('role-list',        [UserController::class, 'role_index'])->name('role-list')->middleware(['check-permission']);
    Route::get('role-show/{id}',        [UserController::class, 'role_show'])->name('role.show')->middleware(['check-permission']);
    Route::get('role-create',      [UserController::class, 'role_create'])->name('role-create')->middleware(['check-permission']);
    Route::post('role-create',      [UserController::class, 'role_store'])->name('role-store')->middleware(['check-permission']);
    Route::get('role-edit/{id}',      [UserController::class, 'role_edit'])->name('role-edit')->middleware(['check-permission']);
    Route::post('role-update',      [UserController::class, 'role_update'])->name('role-update')->middleware(['check-permission']);
    Route::delete('role-delete/{id}',   [UserController::class, 'role_destroy'])->name('role-destroy')->middleware(['check-permission']);
	Route::post('/role/search', [UserController::class, 'role_search'])->name('role-search')->middleware(['check-permission']);

	// Email template routes start
	Route::get('email-template', [EmailController::class, 'emailTemplateList'])->name('email-template')->middleware(['check-permission']);
	Route::get('email-template/create', [EmailController::class, 'templateCreate'])->name('email-template-create')->middleware(['check-permission']);
	Route::post('email-template/store', [EmailController::class, 'templateStore'])->name('email-template-store');
	Route::get('email-template/edit/{id?}', [EmailController::class, 'templateEdit'])->name('email-template-edit')->middleware(['check-permission']);
	Route::get('email-template/show/{id?}', [EmailController::class, 'templateShow'])->name('email-template-show')->middleware(['check-permission']);
	Route::put('email-template/update/{id}', [EmailController::class, 'templateUpdate'])->name('email-template-update');
	Route::delete('email-template/delete/{id?}', [EmailController::class, 'templateDelete'])->name('email-template-delete')->middleware(['check-permission']);
	// Email template routes end

	// Send email routes start
	Route::get('send-email', [EmailController::class, 'sendEmail'])->name('send-email')->middleware(['check-permission']);
	Route::post('send-email-process', [EmailController::class, 'sendEmailPro'])->name('send-email-process');

	Route::get('send-email-list', [EmailController::class, 'sendEmailList'])->name('send-email-list')->middleware(['check-permission']);
	Route::get('send-bulk-email', [EmailController::class, 'sendBulkEmail'])->name('send-bulk-email')->middleware(['check-permission']);
	Route::post('send-bulk-email-process', [EmailController::class, 'sendBulkEmailPro'])->name('send-bulk-email-process');
	Route::get('send-email/show/{id?}', [EmailController::class, 'getEmailSendById'])->name('send-email-show')->middleware(['check-permission']);

	// Send email routes end

	// Sms template routes start
	Route::get('sms-template', [SmsController::class, 'smsTemplateList'])->name('sms-template')->middleware(['check-permission']);
	Route::get('sms-template/create', [SmsController::class, 'templateCreate'])->name('sms-template-create')->middleware(['check-permission']);
	Route::post('sms-template/store', [SmsController::class, 'templateStore'])->name('sms-template-store');

	Route::get('sms-template/edit/{id?}', [SmsController::class, 'templateEdit'])->name('sms-template-edit')->middleware(['check-permission']);

	Route::get('sms-template/show/{id?}', [SmsController::class, 'templateShow'])->name('sms-template-show')->middleware(['check-permission']);
	Route::put('sms-template/update/{id}', [SmsController::class, 'templateUpdate'])->name('sms-template-update');
	Route::delete('sms-template/delete/{id?}', [SmsController::class, 'templateDelete'])->name('sms-template-delete')->middleware(['check-permission']);
	// SMS template routes end

	// Send SMS routes start
	Route::get('send-sms', [smsController::class, 'sendSms'])->name('send-sms')->middleware(['check-permission']);
	Route::post('send-sms-process', [smsController::class, 'sendSmsPro'])->name('send-sms-pro');
	Route::get('send-sms-list', [smsController::class, 'sendSmsList'])->name('send-sms-list')->middleware(['check-permission']);
	Route::get('send-bulk-sms', [smsController::class, 'sendBulkSms'])->name('send-bulk-sms')->middleware(['check-permission']);
	Route::post('send-bulk-sms-process', [smsController::class, 'sendBulkSmsPro'])->name('send-bulk-sms-pro');
	Route::get('send-sms/show/{id?}', [SmsController::class, 'getSmsSendById'])->name('send-sms-show')->middleware(['check-permission']);

	// Send SMS routes end



	// Product routes start
	Route::get('product-stock-report', [ProductController::class, 'product_stock_report'])->name('product-stock-report')->middleware(['check-permission']);
	Route::get('product-list', [ProductController::class, 'productList'])->name('product-list')->middleware(['check-permission']);
	Route::get('add-product', [ProductController::class, 'productCreate'])->name('add-product')->middleware(['check-permission']);
	Route::post('add-product-pro', [ProductController::class, 'productStore'])->name('add-product-pro');
	Route::delete('product-delete/{id?}', [ProductController::class, 'productDelete'])->name('product-delete')->middleware(['check-permission']);
	Route::get('product-show/{id?}', [ProductController::class, 'productShow'])->name('product-show')->middleware(['check-permission']);
	Route::get('product-edit/{id?}', [ProductController::class, 'productEdit'])->name('product-edit')->middleware(['check-permission']);
	Route::put('product-update-pro/{id}', [ProductController::class, 'productUpdate'])->name('product-update-pro');
	// Product routes end

	// Product color and size routes
	Route::get('product-colors', [ProductAttributeController::class, 'colors'])->name('product-color-list');
	Route::post('product-colors', [ProductAttributeController::class, 'storeColor'])->name('product-color-store');
	Route::get('product-colors/{color}/edit', [ProductAttributeController::class, 'editColor'])->name('product-color-edit');
	Route::put('product-colors/{color}', [ProductAttributeController::class, 'updateColor'])->name('product-color-update');
	Route::delete('product-colors/{color}', [ProductAttributeController::class, 'destroyColor'])->name('product-color-destroy');
	Route::get('product-sizes', [ProductAttributeController::class, 'sizes'])->name('product-size-list');
	Route::post('product-sizes', [ProductAttributeController::class, 'storeSize'])->name('product-size-store');
	Route::get('product-sizes/{size}/edit', [ProductAttributeController::class, 'editSize'])->name('product-size-edit');
	Route::put('product-sizes/{size}', [ProductAttributeController::class, 'updateSize'])->name('product-size-update');
	Route::delete('product-sizes/{size}', [ProductAttributeController::class, 'destroySize'])->name('product-size-destroy');

	// Shipping method routes
	Route::get('shipping-methods', [ShippingMethodController::class, 'index'])->name('shipping-method-list');
	Route::post('shipping-methods', [ShippingMethodController::class, 'store'])->name('shipping-method-store');
	Route::get('shipping-methods/{shippingMethod}/edit', [ShippingMethodController::class, 'edit'])->name('shipping-method-edit');
	Route::put('shipping-methods/{shippingMethod}', [ShippingMethodController::class, 'update'])->name('shipping-method-update');
	Route::delete('shipping-methods/{shippingMethod}', [ShippingMethodController::class, 'destroy'])->name('shipping-method-destroy');

	// Outlet location routes
	Route::get('outlet-locations', [OutletLocationController::class, 'index'])->name('outlet-location-list');
	Route::get('outlet-locations/create', [OutletLocationController::class, 'create'])->name('outlet-location-create');
	Route::post('outlet-locations', [OutletLocationController::class, 'store'])->name('outlet-location-store');
	Route::get('outlet-locations/{outletLocation}/edit', [OutletLocationController::class, 'edit'])->name('outlet-location-edit');
	Route::put('outlet-locations/{outletLocation}', [OutletLocationController::class, 'update'])->name('outlet-location-update');
	Route::delete('outlet-locations/{outletLocation}', [OutletLocationController::class, 'destroy'])->name('outlet-location-destroy');
	Route::post('outlet-locations/banner', [OutletLocationController::class, 'updateBanner'])->name('outlet-location-banner');

	// Home page setting routes
	Route::get('home-page-setting', [HomePageSettingController::class, 'edit'])->name('home-page-setting-edit');
	Route::put('home-page-setting', [HomePageSettingController::class, 'update'])->name('home-page-setting-update');
	Route::delete('home-page-setting/partner-logo', [HomePageSettingController::class, 'deletePartnerLogo'])->name('home-page-setting-partner-logo-delete');




	// Country routes start
	Route::get('country-list', [countryController::class, 'countryList'])->name('country-list');
	Route::get('add-country', [countryController::class, 'countryCreate'])->name('add-country');
	Route::post('add-country-pro', [countryController::class, 'countryStore'])->name('add-country-pro');
	Route::delete('country-delete/{id?}', [countryController::class, 'countryDelete'])->name('country-delete');

	// Country routes end

	// Currency routes start
	Route::get('currency-list', [CurrencyController::class, 'currencyList'])->name('currency-list');
	Route::get('add-currency', [CurrencyController::class, 'currencyCreate'])->name('add-currency');
	Route::post('add-currency-pro', [CurrencyController::class, 'currencyStore'])->name('add-currency-pro');
	Route::delete('currency-delete/{id?}', [CurrencyController::class, 'currencyDelete'])->name('currency-delete');
	// Currency routes end



	// Proposal routes end

	// Log
	Route::get('log-list', [LogController::class, 'getLogList'])->name('log-list')->middleware(['check-permission']);

	// Customer routes start
	Route::get('customers', [CustomerController::class, 'index'])->name('customers')->middleware(['check-permission']);
	Route::get('add-customer/{leadid?}', [CustomerController::class, 'add_customer'])->name('add-customer')->middleware(['check-permission']);
	Route::post('add-customer', [CustomerController::class, 'save_customer'])->name('post-add-customer');

	// slider routes
	Route::get('sliders', [SliderController::class, 'index'])->name('slider-list')->middleware(['check-permission']);
    Route::get('slider/create', [SliderController::class, 'create'])->name('slider-create')->middleware(['check-permission']);
	Route::post('slider', [SliderController::class, 'store'])->name('slider-store');
	Route::get('slider/{id?}', [SliderController::class, 'show'])->name('slider-show')->middleware(['check-permission']);
	Route::get('slider/{id?}/edit', [SliderController::class, 'edit'])->name('slider-edit')->middleware(['check-permission']);
	Route::put('slider/{id?}', [SliderController::class, 'update'])->name('slider-update');
	Route::post('slider/search', [SliderController::class, 'search'])->name('slider-search');
	Route::delete('slider/{id?}', [SliderController::class, 'destroy'])->name('slider-destroy')->middleware(['check-permission']);
	Route::put('slider/{id}/update-slider-image', [SliderController::class, 'updateSliderImage'])->name('update-slider-image');

	// brand routes
	Route::get('brands', [BrandController::class, 'index'])->name('brand-list')->middleware(['check-permission']);
    Route::get('brand/create', [BrandController::class, 'create'])->name('brand-create')->middleware(['check-permission']);
	Route::post('brand', [BrandController::class, 'store'])->name('brand-store');
	Route::get('brand/{id?}', [BrandController::class, 'show'])->name('brand-show')->middleware(['check-permission']);
	Route::get('brand/{id?}/edit', [BrandController::class, 'edit'])->name('brand-edit')->middleware(['check-permission']);
	Route::put('brand/{id?}', [BrandController::class, 'update'])->name('brand-update');
	Route::post('brand/search', [BrandController::class, 'search'])->name('brand-search');
	Route::delete('brand/{id?}', [BrandController::class, 'destroy'])->name('brand-destroy')->middleware(['check-permission']);
	Route::put('brand/{id}/update-brand-image', [BrandController::class, 'updatebrandImage'])->name('update-brand-image');

	// category routes
	Route::get('categories', [CategoryController::class, 'index'])->name('category-list')->middleware(['check-permission']);
    Route::get('category/create', [CategoryController::class, 'create'])->name('category-create')->middleware(['check-permission']);
	Route::post('category', [CategoryController::class, 'store'])->name('category-store');
	Route::get('category/{id?}', [CategoryController::class, 'show'])->name('category-show')->middleware(['check-permission']);
	Route::get('category/{id?}/edit', [CategoryController::class, 'edit'])->name('category-edit')->middleware(['check-permission']);
	Route::put('category/{id?}', [CategoryController::class, 'update'])->name('category-update');
	Route::post('category/search', [CategoryController::class, 'search'])->name('category-search');
	Route::delete('category/{id?}', [CategoryController::class, 'destroy'])->name('category-destroy')->middleware(['check-permission']);
	Route::put('category/{id}/update-category-image', [CategoryController::class, 'updatecategoryImage'])->name('update-category-image');
	Route::patch('category/{id}/toggle-home-promo', [CategoryController::class, 'toggleHomePromo'])->name('category-toggle-home-promo');
	Route::get('newsletter-subscribers', [NewsletterSubscriberController::class, 'index'])->name('newsletter-subscribers.index');
	Route::delete('newsletter-subscribers/{newsletterSubscriber}', [NewsletterSubscriberController::class, 'destroy'])->name('newsletter-subscribers.destroy');


	// Orders Routes
	Route::get('/orders', [OrderController::class, 'index'])->name('orders-index')->middleware(['check-permission']);
	Route::get('/orders/create', [OrderController::class, 'create'])->name('orders-create')->middleware(['check-permission']);
	Route::post('/orders', [OrderController::class, 'store'])->name('orders-store');
	Route::get('/orders/{id}/invoice', [OrderController::class, 'invoice'])->name('orders-invoice');
	Route::get('/orders/{id?}', [OrderController::class, 'show'])->name('orders-show')->middleware(['check-permission']);
	Route::get('/orders/{id?}/edit', [OrderController::class, 'edit'])->name('orders-edit')->middleware(['check-permission']);
	Route::post('/orders/{id}/customer-delivery', [OrderController::class, 'updateCustomerDelivery'])->name('orders-customer-delivery-update');
	Route::post('/orders/{id}/items', [OrderController::class, 'addOrderItem'])->name('orders-items-add');
	Route::patch('/orders/{id}/items/{detailId}', [OrderController::class, 'updateOrderItem'])->name('orders-items-update');
	Route::delete('/orders/{id}/items/{detailId}', [OrderController::class, 'deleteOrderItem'])->name('orders-items-delete');
	Route::post('/orders/assign-agent', [OrderController::class, 'assignAgent'])->name('orders-assign-agent');
	Route::post('/orders/{id?}', [OrderController::class, 'update'])->name('orders-update');
	Route::post('/orders/search', [OrderController::class, 'search'])->name('orders-search');
	Route::delete('/orders/{id?}', [OrderController::class, 'destroy'])->name('orders-destroy')->middleware(['check-permission']);
	
	
	// blogger category routes
	Route::get('blog-category-list', [BloggerCategoryController::class, 'index'])->name('blogger-category-list')->middleware(['check-permission']);
	Route::get('blog-category/create', [BloggerCategoryController::class, 'create'])->name('blogger-category-create')->middleware(['check-permission']);
	Route::post('blogger-category', [BloggerCategoryController::class, 'store'])->name('blogger-category-store');
	Route::get('blog-category/{id?}', [BloggerCategoryController::class, 'show'])->name('blogger-category-show')->middleware(['check-permission']);
	Route::get('blog-category/{id?}/edit', [BloggerCategoryController::class, 'edit'])->name('blogger-category-edit')->middleware(['check-permission']);
	Route::put('blogger-category/{id?}', [BloggerCategoryController::class, 'update'])->name('blogger-category-update');
	Route::post('blogger-category/search', [BloggerCategoryController::class, 'search'])->name('blogger-category-search');
	Route::delete('blogger-category/{id?}', [BloggerCategoryController::class, 'destroy'])->name('blogger-category-destroy')->middleware(['check-permission']);
	Route::put('blogger-category/{id}/update-blogger-category-image', [BloggerCategoryController::class, 'updatebloggercategoryImage'])->name('update-blogger-category-image');

	// blog routes
	Route::get('blog-list', [BlogController::class, 'index'])->name('blog-list')->middleware(['check-permission']);
	Route::get('blog/create', [BlogController::class, 'create'])->name('create-blog')->middleware(['check-permission']);
	Route::post('blog-create', [BlogController::class, 'store'])->name('blog-store');
	Route::get('blog/{id?}', [BlogController::class, 'show'])->name('blog-show')->middleware(['check-permission']);
	Route::get('blog/{id?}/edit', [BlogController::class, 'edit'])->name('blog-edit')->middleware(['check-permission']);
	Route::put('blog-update/{id?}', [BlogController::class, 'update'])->name('blog-update');
	Route::post('blog/search', [BlogController::class, 'search'])->name('blog-search');
	Route::delete('blog-delete/{id?}', [BlogController::class, 'destroy'])->name('blog-delete')->middleware(['check-permission']);
	Route::put('blog/{id}/update-blog-image', [BlogController::class, 'update_blog_image'])->name('update-blog-image');
	
	// careers routes
	Route::get('career-list', [CareerController::class, 'index'])->name('career-list')->middleware(['check-permission']);
	Route::get('career-create', [CareerController::class, 'create'])->name('create-career')->middleware(['check-permission']);
	Route::post('career-create', [CareerController::class, 'store'])->name('career-store');
	Route::get('career/{id?}', [CareerController::class, 'show'])->name('career-show')->middleware(['check-permission']);
	Route::get('career/{id?}/edit', [CareerController::class, 'edit'])->name('career-edit')->middleware(['check-permission']);
	Route::put('career-update/{id?}', [CareerController::class, 'update'])->name('career-update');
	Route::post('career-search', [CareerController::class, 'search'])->name('career-search');
	Route::delete('career-delete/{id?}', [CareerController::class, 'destroy'])->name('career-delete')->middleware(['check-permission']);
	Route::put('career/{id}/update-career-image', [CareerController::class, 'update_career_image'])->name('update-career-image');



	Route::get('address-list', [CustomerController::class, 'address_list'])->name('address-list')->middleware(['check-permission']);
	Route::get('address/create', [BloggerCategoryController::class, 'address_create'])->name('address-create')->middleware(['check-permission']);
	Route::post('address-store', [BloggerCategoryController::class, 'address_store'])->name('address-store');
	Route::get('address/{id?}/edit', [BloggerCategoryController::class, 'address_edit'])->name('address-edit')->middleware(['check-permission']);
	Route::put('address/{id?}', [BloggerCategoryController::class, 'address_update'])->name('address-update');
	Route::delete('address/{id?}', [BloggerCategoryController::class, 'address_destroy'])->name('address-destroy')->middleware(['check-permission']);




});
