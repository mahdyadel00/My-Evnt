<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Frontend\Organization\AccountController;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\Frontend\{
    AuthController,
    Organization\SponserController,
    WebinarController,
    PaymobController,
    ContactController,
    EventController,
    HomeController,
    Organization\EventOrganizeController,
    Organization\OrganizationController,
    SocialiteController,
    AIEventController,
    FormServayController,
    FormServayHRController,
    InstaPayQrController
};
use App\Http\Controllers\BackEnd\AIEventController as BackEndAIEventController;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventReminderEmail;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Webinar;
use App\Models\Company;

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

Route::get('/', [HomeController::class, 'index'])->name('home');

// Route Login
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postLogin'])->name('post_login');
// Route Register
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'postRegister'])->name('post_register');
//get-sub-category-list
Route::get('get-sub-category-list', [AuthController::class, 'getSubCategoryList'])->name('get_sub_category_list');
// Route Forgot Password
Route::get('forgot-password', [AuthController::class, 'forgotPassword'])->name('forget_password');
Route::post('forgot-password', [AuthController::class, 'postForgotPassword'])->name('forgot.password');
// Route Reset Password
Route::get('reset-password/{token}', [AuthController::class, 'resetPassword'])->name('reset_password');
Route::post('reset-password', [AuthController::class, 'postResetPassword'])->name('post_reset_password');
//confirmation_email
Route::get('confirmation-email/{token}', [AuthController::class, 'confirmationEmail'])->name('confirmation_email');
Route::post('confirmation-email', [AuthController::class, 'postConfirmationEmail'])->name('post_confirmation_email');
// Route Profile
Route::get('profile', [AuthController::class, 'profile'])->name('profile');
Route::put('profile', [AuthController::class, 'updateProfile'])->name('update_profile');
// Route Change Password
Route::put('change-password', [AuthController::class, 'updatePassword'])->name('change_password');
// Route Logout
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
//Delete Account
Route::delete('delete-account', [AuthController::class, 'deleteAccount'])->name('delete_account');

//Route My Tickets
Route::get('my-tickets', [AuthController::class, 'myTickets'])->name('my_tickets');
//Route My Wishlist
Route::get('my-wishlist', [AuthController::class, 'myWishlist'])->name('my_wishlist');
//get_events_by_category
Route::get('get_events_by_category', [AuthController::class, 'getEventsByCategory'])->name('get_events_by_category');

//get all events
Route::get('events', [EventController::class, 'index'])->name('events');
//filteration events page (with advanced filters UI)
Route::get('events-filtration', [EventController::class, 'filteration'])->name('events.filteration');
//search events
Route::get('events/search', [EventController::class, 'search'])->name('events.search');
//ajax search events
Route::get('events/ajax-search', [EventController::class, 'ajaxSearch'])->name('events.ajax_search');
//route event by category
//new events
Route::get('events/new', [EventController::class, 'newEvents'])->name('new_events');
//Top events
Route::get('events/top', [EventController::class, 'topEvents'])->name('top_events');
//UpComing events
Route::get('events/upcoming', [EventController::class, 'upcomingEvents'])->name('upcoming_events');
//Plan of month
Route::get('events/plan-of-month', [EventController::class, 'planOfMonth'])->name('plan_of_month');
//Free events
Route::get('events/free', [EventController::class, 'freeEvents'])->name('free_events');
//Past events
Route::get('events/past', [EventController::class, 'pastEvents'])->name('past_events');
Route::get('events/category/{id}', [EventController::class, 'eventCategory'])->name('events_category');
//get events by city
Route::get('events/city/{id}', [EventController::class, 'eventsByCity'])->name('events_by_city');
//get single event
Route::get('event/{event:uuid}', [EventController::class, 'show'])->name('event');
//event QR code page
Route::get('event/{event:uuid}/qrcode', [EventController::class, 'showQrCode'])->name('event.qrcode');
//get events by category
Route::get('events/category', [EventController::class, 'category'])->name('getEventsByCategory');
//get events by type
Route::get('events/type', [EventController::class, 'type'])->name('getEventsByType');
//get events by format
Route::get('events/format', [EventController::class, 'format'])->name('getEventsByFormat');
//store guest survey
Route::post('guest-survey/store', [EventController::class, 'storeGuestSurvey'])->name('guest_survey.store');
//store carerha
Route::post('carerha/store', [EventController::class, 'storeCarerha'])->name('carerha.store');

//favourite event
Route::post('favourite', [EventController::class, 'favourite'])->name('favourite');
// event interested
Route::post('event/interested', [EventController::class, 'interested'])->name('event.interested');
//remove favourite event
Route::delete('event/unfavourite/{id}', [AuthController::class, 'unfavourite'])->name('unfavourite');

//terms and conditions
Route::get('terms-and-conditions', [HomeController::class, 'terms'])->name('terms');

//privacy policy
Route::get('privacy-policy', [HomeController::class, 'privacy'])->name('privacy');

//contact us
Route::get('contact-us', [ContactController::class, 'index'])->name('contacts');
Route::post('contact-us', [ContactController::class, 'store'])->name('contacts.store');

//subscribe
Route::post('subscribe', [HomeController::class, 'subscribe'])->name('subscribe');

//Route For Organization
Route::get('organization', [OrganizationController::class, 'index'])->name('organization');
//Route Login Organization
Route::get('organization/login', [OrganizationController::class, 'login'])->name('organization_login');
Route::post('organization/login', [OrganizationController::class, 'postLogin'])->name('post_organization_login');
//Route Register Organization
Route::get('organization/register', [OrganizationController::class, 'register'])->name('organization_register');
Route::post('organization/register', [OrganizationController::class, 'postRegister'])->name('post_organization_register');
//Route Forgot Password Organization
Route::get('organization/forgot-password', [OrganizationController::class, 'forgotPassword'])->name('organization_forgot_password');
Route::post('organization/forgot-password', [OrganizationController::class, 'postForgotPassword'])->name('organization_forgot_password');
//Route Reset Password Organization
Route::get('organization/reset-password/{token}', [OrganizationController::class, 'resetPassword'])->name('organization_reset_password');
Route::post('organization/reset-password', [OrganizationController::class, 'postResetPassword'])->name('post_organization_reset_password');
//Route Logout Organization
Route::get('organization/logout', [OrganizationController::class, 'logout'])->name('organization_logout');
//Route Settings Organization
Route::get('organization/settings', [OrganizationController::class, 'settings'])->name('organization_settings');
Route::put('organization/settings', [OrganizationController::class, 'updateSettings'])->name('organization_update_settings');
//get_cities
Route::get('get_cities', [EventOrganizeController::class, 'getCities'])->name('get_cities');
//Create Event
Route::get('organization/create-event', [EventOrganizeController::class, 'index'])->name('create_event');
Route::post('organization/create-event', [EventOrganizeController::class, 'store'])->name('organization.events.store');
//create event setup 2
Route::get('organization/create-event/setup-2', [EventOrganizeController::class, 'setup2'])->name('create_event_setup2');
Route::post('organization/create-event/setup-2', [EventOrganizeController::class, 'storeSetup2'])->name('event.store.setup2');
//create event setup 3
Route::get('organization/create-event/setup-3', [EventOrganizeController::class, 'setup3'])->name('create_event_setup3');
Route::post('organization/create-event/setup-3', [EventOrganizeController::class, 'storeSetup3'])->name('event.store.setup3');
//create event setup 4
Route::get('organization/create-event/setup-4/{id}', [EventOrganizeController::class, 'setup4'])->name('create_event_setup4');
Route::post('organization/create-event/setup-4', [EventOrganizeController::class, 'storeSetup4'])->name('event.store.setup4');
//Route Sponsor
Route::get('organization/create-event/sponsor', [SponserController::class, 'sponsor'])->name('create_event_sponsor');
Route::post('organization/create-event/sponsor', [SponserController::class, 'storeSponsor'])->name('event.store.sponsor');
Route::get('organization/callback', [SponserController::class, 'sponserCallback']);
//event.store.cache
Route::post('organization/create-event/cache', [EventOrganizeController::class, 'storeCache'])->name('event.store.cache');
//event.store.transfer bank
Route::post('organization/create-event/transfer-bank', [EventOrganizeController::class, 'storeTransferBank'])->name('event.store.transfer_bank');
//create event setup 5
Route::get('organization/create-event/setup-5', [EventOrganizeController::class, 'setup5'])->name('create_event_setup5');
//organization my events
Route::get('organization/my-events', [EventOrganizeController::class, 'myEvents'])->name('organization.my_events');
//organization orders
Route::get('organization/orders', [EventOrganizeController::class, 'orders'])->name('organization.orders');
Route::delete('organization/orders/{id}', [EventOrganizeController::class, 'deleteOrder'])->name('organization.orders.delete');
Route::get('organization/orders/export', [EventOrganizeController::class, 'exportOrders'])->name('organization.orders.export');
//organization.sorted_by
Route::get('organization/sorted-events', [AccountController::class, 'sortedBy'])->name('organization.sorted_events');
//Route Profile Organization
Route::get('organization/profile', [AccountController::class, 'profile'])->name('organization_profile');
Route::put('organization/profile', [AccountController::class, 'updateProfile'])->name('organization.profile.update');
//Route Change Password Organization
Route::put('organization/change-password', [AccountController::class, 'updatePassword'])->name('organization_change_password');
//Delete Account Organization
Route::delete('organization/delete-account', [OrganizationController::class, 'deleteAccount'])->name('organization_delete_account');
//organization.archived_events
Route::get('organization/archived-events', [AccountController::class, 'archivedEvents'])->name('organization.archived_events');
//unarchive_event
Route::get('organization/unarchive-event/{id}', [AccountController::class, 'unarchiveEvent'])->name('organization.unarchive_event');
//organization.event_details
Route::get('organization/event-details/{id}', [AccountController::class, 'eventDetails'])->name('organization.event_details');
//organization.add_archived_event
Route::get('organization/add-archived-event/{id}', [AccountController::class, 'addArchivedEvent'])->name('organization.add_archived_event');
//edit_event step 1
Route::get('organization/edit-event/{id}', [EventOrganizeController::class, 'editEvent'])->name('organization.edit_event');
Route::put('organization/edit-event/{id}', [EventOrganizeController::class, 'updateEvent'])->name('organization.update_event');
//edit_event step 2
Route::get('organization/edit-event/setup-2/{id}', [EventOrganizeController::class, 'editSetup2'])->name('organization.edit_setup2');
Route::put('organization/edit-event/setup-2/{id}', [EventOrganizeController::class, 'updateSetup2'])->name('organization.update_setup2');
//edit_event step 3
Route::get('organization/edit-event/setup-3/{id}', [EventOrganizeController::class, 'editSetup3'])->name('organization.edit_setup3');
Route::put('organization/edit-event/setup-3/{id}', [EventOrganizeController::class, 'updateSetup3'])->name('organization.update_setup3');
//edit_event step 4
Route::get('organization/edit-event/setup-4/{id}', [EventOrganizeController::class, 'editSetup4'])->name('organization.edit_setup4');
Route::put('organization/edit-event/setup-4/{id}', [EventOrganizeController::class, 'updateSetup4'])->name('organization.update_setup4');
//organization.edit_setup5
Route::get('organization/edit-event/setup-5/{id}', [EventOrganizeController::class, 'editSetup5'])->name('organization.edit_setup5');
//organization.edit_event_cache
Route::post('organization/edit-event/cache/{id}', [EventOrganizeController::class, 'editCache'])->name('organization.edit_cache');
//organization.edit_event_transfer_bank
Route::post('organization/edit-event/transfer-bank/{id}', [EventOrganizeController::class, 'editTransferBank'])->name('organization.edit_transfer_bank');
//faq
Route::get('faq', [HomeController::class, 'faq'])->name('faq');
//blogs
Route::get('blogs', [HomeController::class, 'blogs'])->name('blogs');
//blog
Route::get('blog/{id}', [HomeController::class, 'blogDetails'])->name('blog');
//company profile
Route::get('company-profile/{id}', [HomeController::class, 'companyProfile'])->name('company_profile');
//company.follow
Route::get('company/follow/{id}', [AccountController::class, 'follow'])->name('company.follow');
//company.unfollow
Route::get('company/unfollow/{id}', [AccountController::class, 'unfollow'])->name('company.unfollow');
//Route Checkout
Route::get('checkout/{id}', [EventController::class, 'checkout'])->name('checkout');
//Route checkout user
Route::get('checkout/user/{event_date_id}', [EventController::class, 'checkoutUser'])->name('checkout_user');
//checkout survey
Route::get('checkout/survey/{id}', [FormServayController::class, 'checkoutSurvey'])->name('checkout_survey');
//checkout survey
Route::post('checkout/survey', [FormServayController::class, 'checkoutSurveyPost'])->name('checkout_survey_post');

//checkout survey hr
Route::get('checkout/survey/hr/{id}', [FormServayHRController::class, 'checkoutSurveyHR'])->name('checkout_survey_hr');
//checkout survey hr
Route::post('checkout/survey/hr', [FormServayHRController::class, 'checkoutSurveyHRPost'])->name('checkout_survey_hr_post');
Route::get('api/mentorship-track-counts/{eventId}', [FormServayHRController::class, 'getMentorshipTrackCounts'])->name('api.mentorship_track_counts');


Route::get('/google/redirect', [SocialiteController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('auth/google/callback', [SocialiteController::class, 'handleGoogleCallback'])->name('google.callback');
Route::post('auth/google/callback', [SocialiteController::class, 'handleGoogleCallback'])->name('google.callback.post');
//facebook
Route::get('/facebook/redirect', [SocialiteController::class, 'redirectToFacebook'])->name('facebook.redirect');
Route::get('auth/facebook/callback', [SocialiteController::class, 'handleFacebookCallback'])->name('facebook.callback');
//Paymob Routes
Route::post('/credit', [PaymobController::class, 'credit'])->name('payment_checkout'); // this route send all functions data to paymob
Route::get('/callback', [PaymobController::class, 'callback'])->name('callback'); // this route get all reponse data to paymob
//filter_events
Route::get('filter_events', [EventController::class, 'filterEvents'])->name('filter_events');
//vent.ticket.download
Route::get('event/ticket/download/{id}', [EventController::class, 'downloadTicket'])->name('event.ticket.download');
//ticket_confirmation
Route::get('ticket_confirmation/{event_id}', [PaymobController::class, 'ticketConfirmation'])->name('ticket_confirmation');

// InstaPay manual QR for orders
Route::get('orders/{order}/instapay-qr', [InstaPayQrController::class, 'show'])
    ->middleware('auth')
    ->name('orders.instapay_qr');

//check_coupon
Route::post('check_coupon', [EventController::class, 'checkCoupon'])->name('check_coupon');
//apply_coupon
Route::post('apply_coupon/{event_id}', [EventController::class, 'applyCoupon'])->name('apply_coupon');
//check_email
Route::post('check_email', [EventController::class, 'checkEmail'])->name('check_email');
//organization.sub_categories
Route::get('organization/sub-categories', [EventOrganizeController::class, 'subCategories'])->name('organization.sub_categories');
//organization.events.my_events
Route::get('organization/events/my-events', [EventOrganizeController::class, 'myEvents'])->name('organization.events.my_events');
//upload image for event
Route::get('organization/upload-gallery/{id}', [EventOrganizeController::class, 'uploadGallery'])->name('upload_gallery');
Route::post('organization/upload-gallery/{id}', [EventOrganizeController::class, 'storeGallery'])->name('store_gallery');
//delete_gallery
Route::delete('delete-gallery/{id}', [EventOrganizeController::class, 'deleteGallery'])->name('delete_gallery');
//event_company_gallery
Route::get('event-company-gallery', [EventController::class, 'eventCompanyGallery'])->name('event_company_gallery');
//filter_sections
Route::post('filter_sections', [EventController::class, 'filterSections'])->name('filter_sections');

Route::post('events/process-mcp', [BackEndAIEventController::class, 'processMCP'])->name('admin.events.process-mcp');

// Webinar Routes
Route::get('/webinar/{slug}', [WebinarController::class, 'show'])->name('webinar.show');

// Test Reminder Email Command
Route::get('/test-reminder-email', function () {
    try {
        // Run the command in test mode
        Artisan::call('reminder:email', ['--test' => true]);
        $output = Artisan::output();
        
        return response()->json([
            'success' => true,
            'message' => 'Reminder email test executed successfully',
            'output' => $output
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
})->name('test.reminder.email');

// Preview Reminder Email
Route::get('/preview-reminder-email', function () {
    try {
        // Get a sample event with event dates
        $event = Event::with('eventDates')->whereHas('eventDates')->first();
        
        if (!$event) {
            return 'No events found. Please create an event with event dates first.';
        }

        // Get a sample user
        $user = User::whereNotNull('email')->where('email', '!=', '')->first();
        
        if (!$user) {
            return 'No users found. Please create a user first.';
        }

        // Get the first event date
        $eventDate = $event->eventDates->first()->start_date ?? now()->addDay();

        // Return the email view
        return view('emails.event_reminder', [
            'event' => $event,
            'user' => $user,
            'eventDate' => $eventDate
        ]);
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
})->name('preview.reminder.email');

Route::get('/test-email', function () {
    $event = Event::latest()->first();
    $user = User::where('type', 1)->first();
    $eventDate = $event->eventDates->first()->start_date;
    // dd($user , $event);
    // Mail::to($user->email)->send(new EventReminderEmail($event, $user, $event->eventDates->first()->start_date));

    return view('emails.survey_booking', compact('event', 'user', 'eventDate'));
});

Route::get('/sitemap.xml' , function(){
    $sitemap = Sitemap::create()
    ->add(Url::create('/')->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
    ->add(Url::create('/events')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
    ->add(Url::create('/events/new')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
    ->add(Url::create('/events/top')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
    ->add(Url::create('/events/upcoming')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
    ->add(Url::create('/events/plan-of-month')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
    ->add(Url::create('/events/free')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
    ->add(Url::create('/events/past')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
    ->add(Url::create('/events/category/{id}')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
    ->add(Url::create('/events/city/{id}')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
    ->add(Url::create('/event/{event:uuid}')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));

    foreach(Event::all() as $event){
        $sitemap->add(Url::create('/event/'.$event->uuid)->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
    }
    foreach(Webinar::all() as $webinar){
        $sitemap->add(Url::create('/webinar/'.$webinar->slug)->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
    }
    foreach(User::all() as $user){
        $sitemap->add(Url::create('/user/'.$user->id)->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
    }
    if(Company::count() > 0){
    foreach(Company::all() as $company){
            $sitemap->add(Url::create('/company-profile/'.$company->id)->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
        }
    }

    return response($sitemap->render(), 200)->header('Content-Type', 'application/xml');
    // return $sitemap-
});

