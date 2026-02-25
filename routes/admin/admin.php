<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BackEnd\{
    AdFeeController,
    CityController,
    Controller\BlogController,
    AIEventController,
    CountryController,
    FaqController,
    HomeController,
    OrganizationSliderController,
    UserController,
    AdminController,
    ArticleController,
    AuthController,
    CompanyController,
    CurrencyController,
    CustomerController,
    EventCategoryController,
    EventController,
    EventGalleryController,
    FeatureController,
    PackageController,
    PartnerController,
    RoleController,
    SettingController,
    SliderController,
    CouponController,
    SurveyFormController,
    SurveyFormHRController,
    NotificationController,
    SocialGalleryController,
    OrderController,
    ReportController,
    WebinarController,
    AboutWebinarController,
    WebinarSpeakerController,
    WebinarCardController,
};

/*
 *  Admin Routes
 * Here is where you can register Admin routes for your application. These
 * routes are loaded by the RouteServiceProvider and all of them will
 *  be assigned to the "admin" middleware group. Make something great!
 *
 */

// Public Payment Routes (no auth required)
Route::get('survey-payment/{id}', [SurveyFormController::class, 'showPayment'])->name('survey.payment.public');
Route::get('payment-iframe/{id}', [SurveyFormController::class, 'showPaymentIframe'])->name('payment.iframe.direct');
Route::post('payment-callback', [SurveyFormController::class, 'paymentCallback'])->name('payment.callback');
Route::get('check-payment-status/{id}', [SurveyFormController::class, 'checkPaymentStatus'])->name('payment.status.check');
Route::get('payment-completed/{id}', [SurveyFormController::class, 'showPaymentCompleted'])->name('payment.completed');

Route::prefix('admin')
    ->as('admin.')
    ->group(function () {
        // Route::group(['middleware' => ['auth', 'custom.authenticate']], function () {
        Route::get('login-show', [AuthController::class, 'login'])->name('login');
        Route::post('login', [AuthController::class, 'doLogin'])->name('do.login');
        // });

        Route::group(['middleware' => ['auth']], function () {
            //Rout logout
            Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

            //Route profile
            Route::get('/profile', [AuthController::class, 'profile'])->name('profile');

            Route::get('/', [HomeController::class, 'index'])->name('home');

            // Dashboard Search Route
            Route::get('/search', [HomeController::class, 'search'])->name('dashboard.search');

            Route::resource('/admins', AdminController::class);
            Route::resource('roles', RoleController::class);

            //Route Company
            Route::resource('companies', CompanyController::class);

            //Route Features
            Route::resource('packages', PackageController::class);
            //Route Pricing
            Route::resource('features', FeatureController::class);
            //Route Customers
            Route::resource('customers', CustomerController::class);
            //Route Blog
            Route::resource('blogs', BlogController::class);
            //Route Article
            Route::resource('articles', ArticleController::class);

            //Route Session Management
            Route::get('session-management', [SettingController::class, 'sessionManagement'])->name('session-management');
            Route::delete('sessions/{id}', [SettingController::class, 'destroySession'])->name('sessions.destroy');
            //faq
            Route::resource('faqs', FaqController::class);

            //Route Slider
            Route::resource('sliders', SliderController::class);
            //Route Organization Slider
            Route::get('organization-slider', [OrganizationSliderController::class, 'edit'])->name('organization_sliders.edit');
            Route::put('organization-slider', [OrganizationSliderController::class, 'update'])->name('organization_sliders.update');
            //Route Event Category
            Route::resource('event_categories', EventCategoryController::class);

            //Route Partners
            Route::resource('partners', PartnerController::class);

            //Route Event Report For Company
            Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
            Route::get('reports/filter', [ReportController::class, 'filter'])->name('reports.filter');
            Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
            //Route Event
            Route::resource('events', EventController::class);
            Route::post('events/filter', [EventController::class, 'filter'])->name('events.filter');
            Route::get('events/{id}/send-sms', [EventController::class, 'showSmsForm'])->name('events.send-sms-form');
            Route::post('events/send-sms-invitation', [EventController::class, 'sendSmsInvitation'])->name('events.send-sms-invitation');
            //get subcategories by category
            Route::get('get-subcategories-by-category', [EventController::class, 'getSubcategoriesByCategory'])->name('get.sub.category');

            // web.php

            Route::get('events/search', [EventController::class, 'search'])->name('events.search');
            Route::post('events/export', [EventController::class, 'export'])->name('events.export');
            Route::post('/events/gallery/upload', [EventController::class, 'uploadGallery'])->name('events.gallery.upload');
            //event active
            Route::post('events/toggle-format', [EventController::class, 'toggleFormat'])->name('events.toggle.format');
            Route::post('events/toggle-active', [EventController::class, 'toggleActive'])->name('events.toggleActive');
            //event sold out
            Route::get('sold_out', [EventController::class, 'soldOut'])->name('sold_out');

            //Route AdFee
            Route::resource('ad_fees', AdFeeController::class);
            // Route Setting
            Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
            Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
            //Route terms-condition
            Route::get('terms-condition', [SettingController::class, 'termsCondition'])->name('terms-condition.edit');
            Route::put('terms-condition', [SettingController::class, 'termsConditionUpdate'])->name('terms-condition.update');
            //Route Currency
            Route::resource('currencies', CurrencyController::class);

            //Route Subscribers
            Route::get('subscribers', [SettingController::class, 'subscribers'])->name('subscriptions');
            Route::delete('unsubscribe/{id}', [SettingController::class, 'unsubscribe'])->name('unsubscribe');
            Route::get('create-email', [SettingController::class, 'createEmail'])->name('create.email');
            Route::post('send-email', [SettingController::class, 'sendEmail'])->name('send.email');

            //Route Users
            // Important: Put specific routes BEFORE resource routes to avoid conflicts
            Route::get('user/import', [UserController::class, 'import'])->name('user.import');
            Route::post('users/import', [UserController::class, 'importStore'])->name('users.import.store');
            Route::get('users/send-message', [UserController::class, 'showSendMessageForm'])->name('users.send-message');
            Route::post('users/send-message', [UserController::class, 'sendMessage'])->name('users.send-message.store');
            Route::get('users/send-email', [UserController::class, 'showSendEmailForm'])->name('users.send-email');
            Route::post('users/send-email', [UserController::class, 'sendEmail'])->name('users.send-email.store');
            Route::get('users/export', [UserController::class, 'export'])->name('users.export');
            Route::resource('users', UserController::class);
            //Route Get Cities By Country
            Route::get('get-cities-by-country', [UserController::class, 'getCitiesByCountry'])->name('get_cities');

            //Route Countries
            Route::resource('countries', CountryController::class);

            //Route Cities
            Route::resource('cities', CityController::class);

            //Route Contact
            Route::get('contacts', [SettingController::class, 'contacts'])->name('contacts.index');
            Route::delete('contacts/{id}', [SettingController::class, 'destroyContact'])->name('contacts.destroy');
            //Route Coupon
            Route::resource('coupons', CouponController::class);

            // Survey Forms
            Route::get('surveys', [SurveyFormController::class, 'index'])->name('surveys.index');
            Route::get('surveys/export', [SurveyFormController::class, 'export'])->name('surveys.export');
            Route::post('surveys/update-status', [SurveyFormController::class, 'updateStatus'])->name('surveys.updateStatus');
            Route::get('surveys/payment/{id}', [SurveyFormController::class, 'showPayment'])->name('surveys.payment');
            Route::get('surveys/payment-completed/{id}', [SurveyFormController::class, 'showPaymentCompleted'])->name('surveys.payment.completed');
            Route::get('payment-status-check/{id}', [SurveyFormController::class, 'checkPaymentStatus'])->name('payment.status.check');

            // Route Survey Forms HR
            Route::get('surveys/hr', [SurveyFormHRController::class, 'index'])->name('surveys.hr');
            Route::get('surveys/hr/export', [SurveyFormHRController::class, 'export'])->name('surveys.hr.export');
            Route::post('surveys/hr/update-status', [SurveyFormHRController::class, 'updateStatus'])->name('surveys.hr.updateStatus');
            Route::post('surveys/hr/send-email', [SurveyFormHRController::class, 'sendEmail'])->name('surveys.hr.sendEmail');

            // Route Social Gallery
            Route::resource('social_galleries', SocialGalleryController::class);
            // Route for deleting media (images) from social gallery
            Route::delete('social_galleries/media/{id}', [SocialGalleryController::class, 'destroyMedia'])->name('social_galleries.media.destroy');

            // Event Gallery Routes
            Route::prefix('events/{eventId}/gallery')->name('events.gallery.')->group(function () {
                Route::get('/', [EventGalleryController::class, 'index'])->name('index');
                Route::post('/', [EventGalleryController::class, 'store'])->name('store');
                Route::get('/{mediaId}', [EventGalleryController::class, 'show'])->name('show');
                Route::put('/{mediaId}', [EventGalleryController::class, 'update'])->name('update');
                Route::post('/{mediaId}', [EventGalleryController::class, 'update'])->name('update.post'); // For file uploads
                Route::delete('/{mediaId}', [EventGalleryController::class, 'destroy'])->name('destroy');
                Route::post('/reorder', [EventGalleryController::class, 'reorder'])->name('reorder');
                Route::post('/{mediaId}/set-main', [EventGalleryController::class, 'setMain'])->name('set-main');
            });

            //Route Webinars
            Route::resource('webinars', WebinarController::class);
            //Route Webinar Sold Out
            Route::get('sold_out_webinar', [WebinarController::class, 'soldOut'])->name('sold_out_webinar');
            //Route About Webinar
            Route::resource('about_webinars', AboutWebinarController::class);
            //Route Webinar Speakers
            Route::resource('webinar_speakers', WebinarSpeakerController::class);
            //Route Webinar Cards
            Route::resource('webinar_cards', WebinarCardController::class);
            //Route Order
            Route::resource('orders', OrderController::class);
            // Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');

            //delete_notification
            Route::delete('delete-notification/{id}', [NotificationController::class, 'deleteNotification'])->name('delete_notification');

            //Route Send Whatsapp
            Route::get('send-whatsapp', [SettingController::class, 'sendWhatsapp'])->name('send.whatsapp');
            Route::post('send-whatsapp', [SettingController::class, 'sendWhatsappPost'])->name('send.whatsapp.post');

            //Route Visitor Sessions
            // Route::get('visitor-sessions', [VisitorSessionController::class, 'index'])->name('visitor_sessions.index');

            // Test Reminder Email Route
            Route::get('test-reminder-email', function () {
                try {
                    \Illuminate\Support\Facades\Artisan::call('reminder:email', ['--test' => true]);
                    $output = \Illuminate\Support\Facades\Artisan::output();
                    
                    return redirect()->back()->with('success', 'Reminder email test executed successfully. Check the output below.')->with('test_output', $output);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
                }
            })->name('test.reminder.email');
        });
    });
