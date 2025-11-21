<?php

use App\Http\Controllers\Admin\{AccomodationController, BlogController, CategoryController, CmsController, FaqController, HomeController, IssueController, MasterController, SafariController, SettingController, TestimonialController, UserController, VendorController, BannerController, BookingController, CollectionController, ContactUsController, CountryGuideController, HelpSupportController, NationalParkReserveController, PageController, SafariOperatorController, WebsiteRatingController, WithdrawalRequestController, AvailableTagController, OperatorReviewController, RegionController};
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Common\CommonController;
use App\Models\AvailableTag;

Route::any('forgot-password', [CommonController::class, 'forgotPassword'])->name('forgotPassword');
Route::any('otp-validations', [CommonController::class, 'otpValidations'])->name('otpValidations');
Route::any('reset-password', [CommonController::class, 'resetPassword'])->name('resetPassword');
Route::get('login', [HomeController::class, 'index'])->name('login');
Route::post('login', [HomeController::class, 'authenticate'])->name('login-submit');

Route::middleware(['auth', 'isAdmin'])->group(function () {
    /**Authentication */
    Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::any('admin-profile', [HomeController::class, 'adminProfile'])->name('profile');
    Route::post('admin-change-password', [HomeController::class, 'adminChangePassword'])->name('changePassword');
    Route::post('logout', [HomeController::class, 'logout'])->name('logout');

    /** User */
    Route::get('user', [UserController::class, 'userlist'])->name('users');
    Route::any('create-user', [UserController::class, 'createUser'])->name('createUser');
    Route::any('edit-user/{id}', [UserController::class, 'editUser'])->name('editUser');
    Route::delete('delete-user/{id}', [UserController::class, 'deleteUser'])->name('userDelete');
    Route::post('change-user-status', [UserController::class, 'changeUserStatus'])->name('changeuserstatus');

    /** Vendor */
    Route::get('vendor', [VendorController::class, 'vendorList'])->name('vendors');
    Route::any('create-vendor', [VendorController::class, 'createVendor'])->name('createVendor');
    Route::any('edit-vendor/{id}', [VendorController::class, 'editVendor'])->name('editVendor');
    Route::delete('delete-vendor/{id}', [VendorController::class, 'deleteVendor'])->name('deleteVendor');
    Route::post('change-vendor-status', [VendorController::class, 'changeVendorStatus'])->name('changeVendorStatus');
    Route::any('vendor-approval/{id}', [VendorController::class, 'vendorApproval'])->name('vendorApproval');

    /** Blog */
    Route::get('blogs', [BlogController::class, 'index'])->name('blog.index');
    Route::any('create-blog', [BlogController::class, 'create'])->name('blog.create');
    Route::any('edit-blog/{blog}/edit', [BlogController::class, 'edit'])->name('blog.edit');
    Route::delete('delete-blog/{id}', [BlogController::class, 'destroy'])->name('blog.destroy');
    Route::post('change-blog-status', [BlogController::class, 'changeStatus'])->name('blog.status');

    /** Category */
    Route::resource('category', CategoryController::class)->except(['update']);
    Route::post('category/{category}', [CategoryController::class, 'update'])->name('category.update');
    Route::post('change-category-status', [CategoryController::class, 'changeStatus'])->name('category.status');
    Route::get('category-re-order', [CategoryController::class, 'reOrder'])->name('category.re-order');

    //key experience

    Route::get('key-experience', [MasterController::class, 'index'])->name('key-experience.index');
    Route::any('create-key-experience', [MasterController::class, 'create'])->name('key-experience.create');
    Route::any('edit-key-experience/{id}/edit', [MasterController::class, 'edit'])->name('key-experience.edit');
    Route::delete('delete-key-experience/{id}', [MasterController::class, 'destroy'])->name('key-experience.destroy');

    /** Activity*/
    Route::get('activities', [MasterController::class, 'activityList'])->name('activity.index');
    Route::any('create-activity', [MasterController::class, 'activityCreate'])->name('activity.create');
    Route::any('edit-activity/{id}/edit', [MasterController::class, 'activityEdit'])->name('activity.edit');
    Route::delete('delete-activity/{id}', [MasterController::class, 'activityDestroy'])->name('activity.destroy');

    /** CMS */
    Route::resource('cms', CmsController::class)->except(['update', 'show']);
    Route::post('cms/{slug}', [CmsController::class, 'update']);

    /** Banner CMS */
    Route::get('banner-list', [BannerController::class, 'bannerList'])->name('banner_list');
    Route::any('create-banner', [BannerController::class, 'createBanner'])->name('create_banner');
    Route::post('update-banner/{banner_id}', [BannerController::class, 'updateBanner'])->name('update-banner');
    Route::get('edit-banner/{banner_id}', [BannerController::class, 'edit'])->name('banner.edit');
    Route::delete('delete-banner/{banner_id}', [BannerController::class, 'deleteBanner'])->name('banner.delete');

    /** Banner CMS */
    Route::get('bottom-banner-list', [BannerController::class, 'bottomBannerList'])->name('bottom-banner-list');
    Route::any('create-bottom-banner', [BannerController::class, 'createBottomBanner'])->name('create-bottom-banner');
    Route::post('update-bottom-banner/{banner_id}', [BannerController::class, 'updateBottomBanner'])->name('update-bottom-banner');
    Route::get('edit-bottom-banner/{banner_id}', [BannerController::class, 'editBottomBanner'])->name('edit-bottom-banner');
    Route::delete('delete-bottom-banner/{banner_id}', [BannerController::class, 'deleteBottomBanner'])->name('delete-bottom-banner');
    /** Accomodation */
    Route::get('accomodations', [AccomodationController::class, 'index'])->name('accomodations.index');
    Route::any('create-accomodation', [AccomodationController::class, 'create'])->name('accomodation.create');
    Route::any('edit-accomodation/{id}/edit', [AccomodationController::class, 'edit'])->name('accomodation.edit');
    Route::delete('delete-accomodation/{id}', [AccomodationController::class, 'destroy'])->name('accomodation.destroy');
    Route::post('change-accomodation-status', [AccomodationController::class, 'changeStatus'])->name('accomodation.status');

    /** Operator */
    Route::get('operator', [SafariOperatorController::class, 'operatorList'])->name('operators');
    Route::any('create-operator', [SafariOperatorController::class, 'createOperator'])->name('createOperator');
    Route::any('edit-operator/{id}', [SafariOperatorController::class, 'editOperator'])->name('editOperator');
    Route::delete('delete-operator/{id}', [SafariOperatorController::class, 'deleteOperator'])->name('operatorDelete');
    Route::post('change-operator-status', [SafariOperatorController::class, 'changeOperatorStatus'])->name('changeOperatorStatus');

    /** safaris */
    Route::get('safaris', [SafariController::class, 'index'])->name('safari.index');
    Route::any('create-safari', [SafariController::class, 'createSafari'])->name('safari.create');
    Route::any('edit-safari/{id}/edit', [SafariController::class, 'editSafari'])->name('safari.edit');
    Route::delete('delete-safari/{id}', [SafariController::class, 'destroySafari'])->name('safari.destroy');
    Route::post('change-safari-status', [SafariController::class, 'changeSafariStatus'])->name('safari.status');

    Route::post('safari-approval', [SafariController::class, 'safariApproval'])->name('safariApproval');
    Route::post('change-safari-featured-status', [SafariController::class, 'changeFeaturedStatus'])->name('safari.featured');
    Route::get('view-safari/{slug}', [SafariController::class, 'viewSafari'])->name('view-safari');
    Route::post('safari-review-create', [SafariController::class, 'createSafariReview'])->name('safari-review.create');

    /** national park & reserve */
    Route::get('national-park-reserves', [NationalParkReserveController::class, 'index'])->name('national-park-reserves.index');
    Route::any('create-national-park-reserves', [NationalParkReserveController::class, 'createPark'])->name('national-park-reserves.create');
    Route::any('edit-national-park-reserves/{id}/edit', [NationalParkReserveController::class, 'editPark'])->name('national-park-reserves.edit');
    Route::delete('delete-national-park-reserves/{id}', [NationalParkReserveController::class, 'destroyPark'])->name('national-park-reserves.destroy');
    Route::post('change-national-park-reserves-status', [NationalParkReserveController::class, 'changeParkStatus'])->name('national-park-reserves.status');
    Route::post('change-national-park-reserves-hidden-status', [NationalParkReserveController::class, 'changeHiddenStatus'])->name('national-park-reserves.hidden-status');
    Route::get('details-park/{id}', [NationalParkReserveController::class, 'detailsPark'])->name('details-park');

    /** CMS */
    Route::resource('cms', CmsController::class)->except(['update', 'show']);
    Route::post('cms/{slug}', [CmsController::class, 'update']);


    /** country guide */
    Route::get('country-guides', [CountryGuideController::class, 'index'])->name('country-guides');
    Route::any('create-country-guide', [CountryGuideController::class, 'create'])->name('country-guide.create');
    Route::any('edit-country-guide/{id}/edit', [CountryGuideController::class, 'edit'])->name('country-guide.edit');
    Route::delete('delete-country-guide/{id}', [CountryGuideController::class, 'destroy'])->name('country-guide.destroy');
    Route::post('change-country-guide-status', [CountryGuideController::class, 'changeStatus'])->name('country-guide.status');

    /** faq */
    Route::resource('faq', FaqController::class);
    Route::post('change-faq-status', [FaqController::class, 'changeFaqStatus']);

    /** Contact us */
    Route::get('contact-us', [ContactUsController::class, 'index'])->name('contact-us');
    Route::get('view-contact-us/{contactus}', [ContactUsController::class, 'viewContact'])->name('viewContactUs');
    Route::post('change-contact-status', [ContactUsController::class, 'changeContactStatus'])->name('change.contact.status');

    /**Contact info */
    Route::any('update-contact-info', [ContactUsController::class, 'updateContactInfo'])->name('update.contact-info');


    // Testimonial
    Route::get('testimonial', [TestimonialController::class, 'index'])->name('testimonial.list');
    Route::any('testimonial-create', [TestimonialController::class, 'testimonialCreate'])->name('testimonial.create');
    Route::any('testimonial-edit/{id}', [TestimonialController::class, 'testimonialEdit'])->name('testimonial.edit');
    Route::delete('delete-testimonial/{id}', [TestimonialController::class, 'testimonialDelete'])->name('testimonial.delete');
    Route::post('change-testimonial-status', [TestimonialController::class, 'testimonialStatusChange'])->name('testimonial.status');
    Route::get('view-testimonial/{testimonial_id}', [TestimonialController::class, 'viewTestimonial'])->name('view-testimonial');

    // Operator Review
    Route::get('operator-review', [OperatorReviewController::class, 'index'])->name('operator-review.list');
    Route::any('operator-review-create', [OperatorReviewController::class, 'create'])->name('operator-review.create');
    Route::any('operator-review-edit/{id}', [OperatorReviewController::class, 'edit'])->name('operator-review.edit');
    Route::delete('delete-operator-review/{id}', [OperatorReviewController::class, 'delete'])->name('operator-review.delete');
    Route::post('change-operator-review-status', [OperatorReviewController::class, 'changeStatus'])->name('operator-review.status');

    /** Pages */

    Route::any('create-edit-pages', [PageController::class, 'createEditPages'])->name('create-edit-pages');

    //safari types
    Route::get('safari-types', [MasterController::class, 'types'])->name('safari-type.index');
    Route::any('create-safari-type', [MasterController::class, 'typeCreate'])->name('safari-type.create');
    Route::any('edit-safari-type/{id}/edit', [MasterController::class, 'typeEdit'])->name('safari-type.edit');
    Route::delete('delete-safari-type/{id}', [MasterController::class, 'typeDestroy'])->name('safari-type.destroy');

    Route::get('wild-lives', [MasterController::class, 'wildLifeList'])->name('wild-lives');
    Route::any('create-wild-life', [MasterController::class, 'wildLifeCreate'])->name('wild-life.create');
    Route::any('edit-wild-life/{id}/edit', [MasterController::class, 'wildLifeEdit'])->name('wild-life.edit');
    Route::delete('delete-wild-life/{id}', [MasterController::class, 'wildLifeDestroy'])->name('wild-life.destroy');

    /** Region */
    Route::get('regions', [RegionController::class, 'index'])->name('region.index');
    Route::any('create-region', [RegionController::class, 'create'])->name('region.create');
    Route::any('edit-region/{id}/edit', [RegionController::class, 'edit'])->name('region.edit');
    Route::delete('delete-region/{id}', [RegionController::class, 'destroy'])->name('region.destroy');
    Route::post('change-region-status', [RegionController::class, 'changeStatus'])->name('region.status');

    /** Collection */
    Route::get('collections', [CollectionController::class, 'index'])->name('collection.index');
    Route::any('create-collection', [CollectionController::class, 'create'])->name('collection.create');
    Route::any('edit-collection/{id}/edit', [CollectionController::class, 'edit'])->name('collection.edit');
    Route::delete('delete-collection/{id}', [CollectionController::class, 'destroy'])->name('collection.destroy');
    Route::post('change-collection-status', [CollectionController::class, 'changeStatus'])->name('collection.status');
    Route::post('safari-collection', [CollectionController::class, 'safariCollection'])->name('safari-collection');

        /** available tags */
    Route::get('available-tags', [AvailableTagController::class, 'index'])->name('available-tags.index');
    Route::any('create-available-tags', [AvailableTagController::class, 'create'])->name('available-tags.create');
    Route::any('edit-available-tags/{id}/edit', [AvailableTagController::class, 'edit'])->name('available-tags.edit');
    Route::delete('delete-available-tags/{id}', [AvailableTagController::class, 'destroy'])->name('available-tags.destroy');
    Route::post('change-available-tags-status', [AvailableTagController::class, 'changeStatus'])->name('available-tags.status');
    Route::post('safari-available-tags', [AvailableTagController::class, 'safariAvailabletags'])->name('safari-available-tags');

    //help & support 
    Route::get('help-support', [HelpSupportController::class, 'index'])->name('help-support.index');
    Route::any('create-help-support', [HelpSupportController::class, 'create'])->name('help-support.create');
    Route::any('edit-help-support/{support}/edit', [HelpSupportController::class, 'edit'])->name('help-support.edit');
    Route::delete('delete-help-support/{id}', [HelpSupportController::class, 'destroy'])->name('help-support.destroy');
    Route::post('change-help-support-status', [HelpSupportController::class, 'changeStatus'])->name('help-support.status');

    //something did not work
    Route::get('something-did-not-work', [IssueController::class, 'somethingDidNotWorkIndex'])->name('something-did-not-work.index');
    Route::get('something-did-not-work/{issue}/view', [IssueController::class, 'somethingDidNotWorkView'])->name('something-did-not-work.view');
    Route::post('change-something-did-not-work-status', [IssueController::class, 'changeSomethingDidNotWorkStatus'])->name('something-did-not-work.status');

    //report issue
    Route::get('report-issue', [IssueController::class, 'reportIssueIndex'])->name('report-issue.index');
    Route::get('report-issue/{report}/view', [IssueController::class, 'reportIssueView'])->name('report-issue.view');
    Route::post('change-report-issue-status', [IssueController::class, 'changeReportIssueStatus'])->name('report-issue.status');

    //Booking management
    Route::get('bookings', [BookingController::class, 'index'])->name('booking.index');
    Route::get('view-booking/{booking_id}', [BookingController::class, 'viewBooking'])->name('view-booking');
    // Route::post('change-booking-status', [BookingManagementController::class, 'changeBookingStatus'])->name('change-booking-status');

    //Website Rating
    Route::get('website-rating', [WebsiteRatingController::class, 'index'])->name('website-rating.list');
    Route::get('view-website-rating/{rating_id}', [WebsiteRatingController::class, 'viewWebsiteRating'])->name('view-website-rating');
    Route::delete('delete-website-rating/{id}', [WebsiteRatingController::class, 'websiteRatingDelete'])->name('website-rating.delete');

    //Withdrawal Requests
    Route::get('withdrawal-requests', [WithdrawalRequestController::class, 'index'])->name('withdrawal-requests.index');
    Route::post('withdrawal-requests/{withdrawalRequest}/update-status', [WithdrawalRequestController::class, 'updateStatus'])->name('withdrawal-requests.update-status');

    //Settings
    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
});
