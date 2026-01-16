<?php

use App\Http\Controllers\Frontend\ChatController;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\IssueController;
use App\Http\Controllers\Frontend\LoginController;
use App\Http\Controllers\Frontend\MapController;
use App\Http\Controllers\Frontend\SafariOperator\AuthController as SafariOperatorAuthController;
use App\Http\Controllers\Frontend\SafariOperator\ListingController as SafariOperatorListingController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\Frontend\WalletController;
use App\Models\CurrencyExchangeRate;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('safari-listings', [HomeController::class, 'safariListings'])->name('safari-listings');
Route::get('safari-details/{slug}', [HomeController::class, 'safariDetails'])->name('safari-details');
Route::get('story', [HomeController::class, 'story'])->name('story');
Route::get('blogs', [HomeController::class, 'blog'])->name('blogs');
Route::get('blogs/category/{category}', [HomeController::class, 'blogByCategory'])->name('blogs.category');
Route::get('blog-details/{title}', [HomeController::class, 'blogDetails'])->name('blog-details');
Route::any('contact-us', [HomeController::class, 'contactUs'])->name('contact-us');
// Route::get('country-guides', [HomeController::class, 'countryGuide'])->name('country-guides');
Route::get('country-guides', [HomeController::class, 'countryGuide'])->name('country-guides');
Route::get('country-guide/{name}', [HomeController::class, 'countryGuideDetails'])->name('country-guide');
Route::get('safari-types', [HomeController::class, 'safariTypes'])->name('safari-types');
Route::get('operators', [HomeController::class, 'operators'])->name('operators');
Route::get('parks', [HomeController::class, 'parkReserves'])->name('national-park-reserves');
Route::any('destinations', [HomeController::class, 'destinations'])->name('destinations');
Route::get('national-park-details/{name}', [HomeController::class, 'parkDetails'])->name('national-park-details');
Route::get('terms-and-conditions', [HomeController::class, 'termsAndConditions'])->name('terms-and-conditions');
Route::get('privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');
Route::any('get-banner', [HomeController::class, 'getBanner'])->name('get-banner');
Route::any('get-footer', [HomeController::class, 'getFooter'])->name('get-footer');
Route::get('header-countries', [HomeController::class, 'headerCountries'])->name('header-countries');
Route::get('header-national-parks', [HomeController::class, 'headerNationalParks'])->name('header-national-parks');
Route::get('header-safari-types', [HomeController::class, 'headerSafariType'])->name('header-safari-types');
Route::get('header-blog-categories', [HomeController::class, 'headerBlogCategories'])->name('header-blog-categories');
Route::get('our-story', [HomeController::class, 'ourStory'])->name('our-story');
Route::get('how-it-works', [HomeController::class, 'howItWorks'])->name('how-it-works');
Route::get('why-its-different', [HomeController::class, 'whyItsDifferent'])->name('why-its-different');
Route::get('responsible-travel', [HomeController::class, 'responsibleTravel'])->name('responsible-travel');
Route::get('faqs', [HomeController::class, 'faqs'])->name('faqs');

/** Auth */
Route::any('login', [LoginController::class, 'login'])->name('login');
Route::any('register', [LoginController::class, 'register'])->name('register');
Route::any('safari-operator-register-step-one', [LoginController::class, 'safariOperatorRegisterOne'])->name('safari-operator-register-step-one');
Route::any('safari-operator-register-step-two', [LoginController::class, 'safariOperatorRegisterTwo'])->name('safari-operator-register-step-two');
Route::any('forgot-password', [LoginController::class, 'forgotPassword'])->name('forgot-password');
Route::any('otp-validation', [LoginController::class, 'otpValidation'])->name('otp-validation');
Route::any('reset-password', [LoginController::class, 'resetPassword'])->name('reset-password');

Route::middleware(['auth', 'frontend.auth:TRAVELLER'])->group(function () {
    Route::any('traveller-profile', [AuthController::class, 'travellerProfile'])->name('traveller-profile');
    Route::post('traveller-change-password', [AuthController::class, 'travellerChangePassword'])->name('traveller-change-password');
    Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('traveller-booking-details', [BookingController::class, 'travellerBookingDetails'])->name('traveller-booking-details');
    Route::get('saved-safaris', [UserController::class, 'savedSafaris'])->name('saved-safaris');
    Route::get('traveller-safari-details/{slug}', [UserController::class, 'travellerSafariDetails'])->name('traveller-safari-details');
    Route::get('payments', [UserController::class, 'payments'])->name('payments');
    Route::get('help-and-support', [HomeController::class, 'helpAndSupport'])->name('help-and-support');
    Route::post('toggle-favourite', [UserController::class, 'toggleFavourite'])->name('toggle-favourite');
    Route::post('safari-listing-review-store', [UserController::class, 'safariListingReview'])->name('safari-listing-review-store');
    Route::get('checkout', [BookingController::class, 'checkout'])->name('checkout');
    Route::get('user-cards', [BookingController::class, 'getPaymentMethods'])->name('user-cards');
    Route::post('create-setup-intent', [BookingController::class, 'createSetupIntent'])->name('create-setup-intent');
    Route::post('store-payment-method', [BookingController::class, 'storePaymentMethod'])->name('store-payment-method');
    Route::delete('delete-card/{card_id}', [BookingController::class, 'deleteCard'])->name('delete-card');
    Route::post('safari-payment', [BookingController::class, 'safariPayment'])->name('safari-payment');
    Route::post('payment-success', [BookingController::class, 'paymentSuccess'])->name('payment-success');
    Route::post('manual-payment', [BookingController::class, 'manualPayment'])->name('manual-payment');
    Route::post('trip-booking-review', [UserController::class, 'tripReview'])->name('trip-booking-review');
    Route::get('all-reviews', [UserController::class, 'allReviews'])->name('all-reviews');
    Route::get('view-review/{id}', [UserController::class, 'viewReview'])->name('view-review');
    Route::get('my-trips', [UserController::class, 'myTrips'])->name('my-trips');
    Route::post('cancel-booking', [BookingController::class, 'cancelBooking'])->name('cancel-booking');
});

Route::post('safari-booking', [BookingController::class, 'safariBooking'])->name('safari-booking');
Route::get('check-price-special-discount', [BookingController::class, 'checkPriceSpecialDiscount'])->name('check-price-special-discount');

Route::middleware(['auth', 'frontend.auth:SAFARI_OPERATOR'])->group(function () {
    Route::get('operator-booking-details', [SafariOperatorAuthController::class, 'operatorBookingDetails'])->name('operator-booking-details');
    Route::get('operator-dashboard', [SafariOperatorAuthController::class, 'operatorDashboard'])->name('operator-dashboard');
    Route::any('operator-my-profile', [SafariOperatorAuthController::class, 'operatorMyProfile'])->name('operator-my-profile');
    Route::post('operator-change-password', [SafariOperatorAuthController::class, 'operatorChangePassword'])->name('operator-change-password');
    Route::get('safari-manage-listing', [SafariOperatorListingController::class, 'safariManageListing'])->name('safari-manage-listing');
    Route::any('safari-create-listing', [SafariOperatorListingController::class, 'createListing'])->name('safari-create-listing');
    Route::any('safari-edit-listing/{safari_id}', [SafariOperatorListingController::class, 'editListing'])->name('safari-edit-listing');
    Route::delete('safari-delete-listing/{safari_id}', [SafariOperatorListingController::class, 'deleteListing'])->name('safari-delete-listing');
    Route::get('operator-safari-details/{slug}', [SafariOperatorListingController::class, 'operatorSafariDetails'])->name('operator-safari-details');
    Route::get('get-countries', [SafariOperatorListingController::class, 'getCountries'])->name('get-countries');
    Route::get('get-nation-park-locations', [SafariOperatorListingController::class, 'getNationalParkLocations'])->name('get-nation-park-locations');
    Route::get('operator-bookings', [SafariOperatorAuthController::class, 'operatorBookings'])->name('operator-bookings');
    Route::post('operator-booking-status-change', [SafariOperatorAuthController::class, 'operatorBookingStatusChange'])->name('operator-booking-status-change');
    Route::any('operator-earnings', [SafariOperatorAuthController::class, 'operatorEarnings'])->name('operator-earnings');
    Route::post('operator-upload-documents', [SafariOperatorAuthController::class, 'uploadDocuments'])->name('operator-upload-documents');
    Route::post('duplicate-safari', [SafariOperatorListingController::class, 'duplicateSafari'])->name('duplicate-safari');
    Route::any('transfer-money', [WalletController::class, 'transferMoney'])->name('transfer-money');
    Route::get('operator-all-reviews', [SafariOperatorAuthController::class, 'operatorAllReviews'])->name('operator-all-reviews');

});
// Route::post('stripe-connect', [StripeController::class, 'stripeConnect'])->name('stripe-connect');
// Route::get('stripe/refresh', [StripeController::class, 'stripeConnect']);
// Route::get('stripe/return', [StripeController::class, 'returnStripeAccount']);

Route::middleware(['auth', 'frontend.auth'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('notifications', [UserController::class, 'notification'])->name('notifications');
    Route::delete('notification/{id}', [UserController::class, 'deleteNotification'])->name('notification.delete');
    Route::get('read-count-notification', [UserController::class, 'readCountNotification'])->name('read-count-notification');

    /** Chat with operator */
    Route::get('chat-box', [ChatController::class, 'chatBox'])->name('chat-box');
    Route::get('chat-user-list', [ChatController::class, 'getUserList'])->name('chat-user-list');
    Route::post('delete-chat', [ChatController::class, 'deleteChat'])->name('delete-chat');
    Route::get('get-message', [ChatController::class, 'getMessage'])->name('get-message');
    Route::post('send-message', [ChatController::class, 'sendMessage'])->name('send-message');
    Route::get('all-users', [ChatController::class, 'allUsers'])->name('allusers');
    Route::post('create-group', [ChatController::class, 'createGroup'])->name('create-group');
    Route::get('group-members/{chat_room_id}', [ChatController::class, 'getGroupMemberList'])->name('get-group-member');
    Route::delete('delete-message/{id}', [ChatController::class, 'deleteMessage'])->name('delete-message');
    Route::any('messages', [ChatController::class, 'messages'])->name('messages');
    Route::post('user/last-seen', [ChatController::class, 'submitLastSeen'])->name('last-seen-submit');
    Route::get('get-chat-for-dashboard', [ChatController::class, 'getChatForDashboard'])->name('get-chat-for-dashboard');
    Route::post('something-did-not-work', [IssueController::class, 'somethingDidNotWork'])->name('something-did-not-work.store');
    Route::any('submit-rating-for-website/{rating_id?}', [IssueController::class, 'submitRatingForWebsite'])->name('submit-rating-for-website');
    Route::get('get-help-support', [HomeController::class, 'getHelpSupport'])->name('get-help-support');
    Route::get('search-help-support', [HomeController::class, 'searchHelpSupport'])->name('search-help-support');
    Route::post('report-issue', [IssueController::class, 'reportIssue'])->name('report-issue');
});
Route::get('locations', [MapController::class, 'getLocations']);
Route::get('generate-social-link', [HomeController::class, 'genLink'])->name('generate-social-link');
Route::get('get-users/{id}', [ChatController::class, 'getOnlineUsers'])->name('get-users');
Route::get('get-settings', [HomeController::class, 'getSettings'])->name('get-settings');

Route::get('/exchange-rates', function () {
    $currencyExchangeRate = CurrencyExchangeRate::first();
    return response()->json(json_decode($currencyExchangeRate?->rates));
});

