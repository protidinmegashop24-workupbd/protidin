<?php

// TODO: Admin
use App\Models\BkashSetting;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\AboutUsController;
use App\Http\Controllers\Backend\SpinSettingController;
use App\Http\Controllers\Backend\ServiceController;
use App\Http\Controllers\Backend\GoogleAdController;
use App\Http\Controllers\Backend\InvestmentPackageController;
use App\Http\Controllers\Backend\WebScriptController;
use App\Http\Controllers\Backend\LotteryController;
use App\Http\Controllers\Backend\ServiceItemController;
use App\Http\Controllers\Backend\PolicyController;
use App\Http\Controllers\Backend\UserVerifyDocumentController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Backend\BoostChargeController;
use App\Http\Controllers\Backend\BoostCategoryController;
use App\Http\Controllers\Backend\BoostSubCategoryController;
use App\Http\Controllers\Backend\UserBoostPackageController;
use App\Http\Controllers\Backend\ContinentController;
use App\Http\Controllers\Backend\CountryController;
use App\Http\Controllers\Backend\LocationZoneController;
use App\Http\Controllers\Backend\LocationZoneCountryController;
use App\Http\Controllers\Backend\HeadlineController;
use App\Http\Controllers\Backend\AcceptTaskHeadlineController;
use App\Http\Controllers\Backend\CompleteTaskHeadlineController;
use App\Http\Controllers\Backend\DepositTaskHeadlineController;
use App\Http\Controllers\Backend\DepositDocumentController;
use App\Http\Controllers\Backend\WithdrawTaskHeadlineController;
use App\Http\Controllers\Backend\BoostPackageHeadlineController;
use App\Http\Controllers\Backend\TopDepositUserHeadlineController;
use App\Http\Controllers\Backend\TopEarningUserHeadlineController;
use App\Http\Controllers\Backend\TopReferralUserHeadlineController;
use App\Http\Controllers\Backend\UserMessageController;
use App\Http\Controllers\Backend\DefaultController;
use App\Http\Controllers\Backend\WelcomeBonusController;
use App\Http\Controllers\Backend\DollarRateController;
use App\Http\Controllers\Backend\DepositAccountController;
use App\Http\Controllers\Backend\WithdrawMethodController;
use App\Http\Controllers\Backend\WithdrawController;
use App\Http\Controllers\Backend\AdvertisementController;
use App\Http\Controllers\Backend\JobController;
use App\Http\Controllers\Backend\JobWorkController;
use App\Http\Controllers\Backend\ModuleController;
use App\Http\Controllers\Backend\PaidAdRateController;
use App\Http\Controllers\Backend\ContactUsTextController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\ClientController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\UserSettingController;
use App\Http\Controllers\Backend\WebsiteController;
use App\Http\Controllers\Backend\SupportTicketController;
use Illuminate\Support\Facades\Mail;

// TODO:: Common / Front
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReferUserController;
use App\Http\Controllers\WuServiceController;
use App\Http\Controllers\VerifyController;
use App\Http\Controllers\socialEarnController;

// TODO:: User
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserDepositCOntroller;
use App\Http\Controllers\User\UserAdvertisementController;
use App\Http\Controllers\User\UserWithdrawController;
use App\Http\Controllers\User\UserJobController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\User\UserJobWorkController;
use App\Http\Controllers\User\BoostPackageController;
use App\Http\Controllers\User\UserReferralController;
use App\Http\Controllers\User\UserSpinController;
use App\Http\Controllers\User\UserSupportTicketController;
use App\Http\Controllers\User\UserInvestmentController;
use App\Http\Controllers\User\UserLotteryController;
use App\Http\Controllers\User\UserServiceItemController;
use App\Http\Controllers\User\UserWebScriptController;

// TODO:: Laravel
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Route::get('/test-email', function () {
    try {
        Mail::raw('Hello! This is a test email from your Laravel application.', function ($message) {
            $message->to('sahababd4@gmail.com')
                    ->subject('Laravel SMTP Test');
        });
        
        return 'Success: Test email sent successfully to sahababd4@gmail.com!';
    } catch (\Exception $e) {
        return 'Error sending email: ' . $e->getMessage();
    }
});
Auth::routes(['verify' => true]);

/*
|--------------------------------------------------------------------------
| Public Front Routes
|--------------------------------------------------------------------------
*/
Route::get('/public-shared/{id?}', [socialEarnController::class, 'publicPostLink'])->name('publicPostLink');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']);
Route::get('/about', [HomeController::class, 'about_us'])->name('about-us');
Route::get('/service', [HomeController::class, 'service'])->name('service');
Route::get('/service/{slug}', [HomeController::class, 'service_details'])->name('service_details');
Route::get('/policy-details/{slug}', [HomeController::class, 'policy_details'])->name('policy-details');
Route::get('/photo-gallery', [HomeController::class, 'photo_gallery'])->name('photo-gallery');
Route::get('/contact', [HomeController::class, 'contact_us'])->name('contact-us');
Route::post('/contact-message', [ContactMessageController::class, 'store'])->name('contact_message.send');
Route::get('/career', [HomeController::class, 'career'])->name('career');

Route::get('my-captcha', 'HomeController@myCaptcha')->name('myCaptcha');
Route::post('my-captcha', 'HomeController@myCaptchaPost')->name('myCaptcha.post');
Route::get('refresh_captcha', 'HomeController@refreshCaptcha')->name('refresh_captcha');

Route::get('/job/{code}', [HomeController::class, 'job_details'])->name('job-details');

/*
|--------------------------------------------------------------------------
| One Register + Referral + OTP
|--------------------------------------------------------------------------
*/
Route::get('/refer-user/{code}', [ReferUserController::class, 'refer_user'])->name('refer-user');

Route::get('/register/{code}', function ($code) {
    return view('auth.register', ['code' => $code]);
})->name('register.with.code');

Route::post('/user-register', [HomeController::class, 'user_register'])->name('user-register');

Route::get('/verify-otp', [HomeController::class, 'showOtpForm'])->name('otp.verify');
Route::post('/verify-otp', [HomeController::class, 'verifyOtp'])->name('otp.verify.submit');

Route::post('/user-logout', [HomeController::class, 'user_logout'])->name('user-logout');
Route::get('/device-validation-error-for-registration', [HomeController::class, 'device_validation_error'])->name('device-validation-error-for-registration');

Route::get('/user-foreget-password', [HomeController::class, 'foreget_password'])->name('user-foreget-password');
Route::get('/forget-password', [HomeController::class, 'foreget_password'])->name('forget-password');
Route::post('/recover-password', [HomeController::class, 'recover_password'])->name('recover-password');

Route::get('/verify-reset-otp', [HomeController::class, 'show_verify_reset_otp'])->name('verify-reset-otp');
Route::post('/verify-reset-otp', [HomeController::class, 'verify_reset_otp'])->name('verify-reset-otp.post');

Route::get('/set-new-password', [HomeController::class, 'show_set_new_password'])->name('set-new-password');
Route::post('/set-new-password', [HomeController::class, 'set_new_password'])->name('set-new-password.post');

Route::get('/admin-login', [HomeController::class, 'admin_login']);
Route::get('/reload-captcha', [HomeController::class, 'reload_captcha']);

/*
|--------------------------------------------------------------------------
| Public Profile Routes
|--------------------------------------------------------------------------
*/
Route::get('/seller-profile/{id}', [UserProfileController::class, 'user_profile'])->name('seller.profile');
Route::get('/user-profile/{id}', [UserProfileController::class, 'user_profile'])->name('user.user-profile');

/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'super-admin', 'as' => 'admin.', 'middleware' => ['auth', 'superadmin']], function () {

    Route::get('/accounts', [AdminController::class, 'index'])->name('admin.accounts');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('bkash-settings', [App\Http\Controllers\Backend\BkashPaymentController::class, 'index'])->name('bkash_settings.index');
    Route::put('bkash-settings', [App\Http\Controllers\Backend\BkashPaymentController::class, 'update'])->name('bkash_settings.update');

    Route::get('community-rate-setup', [socialEarnController::class, 'communityRateSetup'])->name('communityRateSetup');
    Route::get('community-post-list', [socialEarnController::class, 'cummunityPostList'])->name('cummunityPostList');
    Route::post('community-post-delete', [socialEarnController::class, 'deleteFeedPost'])->name('deleteFeedPost');
    Route::post('community-post-approve', [socialEarnController::class, 'approveFeedPost'])->name('approveFeedPost');
    Route::post('community-rate-setup-store', [socialEarnController::class, 'communityRateSetupStore'])->name('communityRateSetupStore');

    Route::get('boost-charge', [BoostChargeController::class, 'index'])->name('boost-charge');
    Route::post('boost-charge-store', [BoostChargeController::class, 'store'])->name('boost-charge.store');
    Route::post('boost-charge-update-{id}', [BoostChargeController::class, 'update'])->name('boost-charge.update');
    Route::get('boost-charge-delete-{id}', [BoostChargeController::class, 'destroy'])->name('boost-charge.delete');

    Route::get('default-setup', [DefaultController::class, 'index'])->name('default-setup');
    Route::post('minimum-deposit/{id}', [DefaultController::class, 'minimum_deposit'])->name('minimum-deposit.update');
    Route::post('withdraw-fee/{id}', [DefaultController::class, 'withdraw_fee'])->name('withdraw-fee.update');
    Route::post('job-fee/{id}', [DefaultController::class, 'job_fee'])->name('job-fee.update');
    Route::post('welcome-bonus-update-{id}', [WelcomeBonusController::class, 'update'])->name('welcome-bonus.update');
    Route::post('dollar-rate-update-{id}', [DollarRateController::class, 'update'])->name('dollar-rate.update');
    Route::post('main-wallet-{id}', [DefaultController::class, 'main_wallet_update'])->name('main-wallet.update');
    Route::post('screenshot-charge-{id}', [DefaultController::class, 'screenshot_charge_update'])->name('screenshot-charge.update');
    Route::post('need-user-verification-{id}', [DefaultController::class, 'need_user_verification_update'])->name('need-user-verification.update');

    Route::post('advertisement-delete-{id}', [AdvertisementController::class, 'destroy'])->name('advertisement.delete');

    Route::get('deposit-account', [DepositAccountController::class, 'index'])->name('deposit-account');
    Route::post('deposit-account-store', [DepositAccountController::class, 'store'])->name('deposit-account.store');
    Route::get('deposit-account-edit/{id}', [DepositAccountController::class, 'edit'])->name('deposit-account.edit');
    Route::post('deposit-account-update-{id}', [DepositAccountController::class, 'update'])->name('deposit-account.update');
    Route::get('deposit-account-delete-{id}', [DepositAccountController::class, 'destroy'])->name('deposit-account.delete');

    Route::get('website', [WebsiteController::class, 'index'])->name('website');
    Route::post('website-update-{id}', [WebsiteController::class, 'update'])->name('website.update');
    Route::get('mail-configure', [WebsiteController::class, 'mail_configure'])->name('mail-configure');
    Route::post('mail-configure-update-{id}', [WebsiteController::class, 'mail_configure_update'])->name('mail-configure.update');

    Route::get('user-edit-{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::get('admin-user-edit-{id}', [UserController::class, 'edit'])->name('admin-user.edit');
    Route::post('user-update-{id}', [UserController::class, 'update'])->name('user.update');
    Route::post('user-balance-update-{id}', [UserController::class, 'user_balance'])->name('user-balance.update');
    Route::get('user-delete-{id}', [UserController::class, 'destroy'])->name('user.delete');

    Route::get('withdraw-method', [WithdrawMethodController::class, 'index'])->name('withdraw-method');
    Route::post('withdraw-method-store', [WithdrawMethodController::class, 'store'])->name('withdraw-method.store');
    Route::get('withdraw-method-edit/{id}', [WithdrawMethodController::class, 'edit'])->name('withdraw-method.edit');
    Route::post('withdraw-method-update-{id}', [WithdrawMethodController::class, 'update'])->name('withdraw-method.update');
    Route::get('withdraw-method-delete-{id}', [WithdrawMethodController::class, 'destroy'])->name('withdraw-method.delete');
    Route::get('withdraw-request-delete/{id}', [WithdrawController::class, 'destroy'])->name('withdraw-request-delete');

    Route::get('spin-setting', [SpinSettingController::class, 'index'])->name('spin-setting');
    Route::post('spin-setting-{id}', [SpinSettingController::class, 'update'])->name('spin-setting.update');

    Route::get('system-color-setup', [AboutUsController::class, 'system_color_setup'])->name('system-color-setup');
    Route::post('system-color-setup-update-{id}', [AboutUsController::class, 'system_color_setup_update'])->name('system-color-setup.update');

    Route::get('header-info', [AboutUsController::class, 'header_info'])->name('header-info');
    Route::post('header-info-update-{id}', [AboutUsController::class, 'header_info_update'])->name('header-info.update');

    Route::get('counter-info', [AboutUsController::class, 'counter_info'])->name('counter-info');
    Route::post('counter-info-update-{id}', [AboutUsController::class, 'counter_info_update'])->name('counter-info.update');

    Route::get('refer-info', [AboutUsController::class, 'refer_info'])->name('refer-info');
    Route::post('refer-info-update-{id}', [AboutUsController::class, 'refer_info_update'])->name('refer-info.update');

    Route::get('login-register-page-info', [AboutUsController::class, 'login_register_page_info'])->name('login-register-page-info');
    Route::post('login-register-page-info-update-{id}', [AboutUsController::class, 'login_register_page_info_update'])->name('login-register-page-info.update');

    Route::get('policy', [PolicyController::class, 'index'])->name('policy');
    Route::post('policy-store', [PolicyController::class, 'store'])->name('policy.store');
    Route::post('policy-update-{id}', [PolicyController::class, 'update'])->name('policy.update');
    Route::get('policy-delete-{id}', [PolicyController::class, 'destroy'])->name('policy.delete');

    Route::get('service', [ServiceController::class, 'index'])->name('service');
    Route::post('service-store', [ServiceController::class, 'store'])->name('service.store');
    Route::post('service-update-{id}', [ServiceController::class, 'update'])->name('service.update');
    Route::get('service-delete-{id}', [ServiceController::class, 'destroy'])->name('service.delete');

    Route::get('about', [AboutUsController::class, 'index'])->name('about_us');
    Route::post('about-update-{id}', [AboutUsController::class, 'update'])->name('about_us.update');

    Route::get('contact-info', [ContactUsTextController::class, 'index'])->name('contact_info');
    Route::post('contact-info-update-{id}', [ContactUsTextController::class, 'update'])->name('contact_info.update');
    Route::get('contact-message-list', [ContactUsTextController::class, 'contact_msg'])->name('contact_msg');

    Route::get('google-ad', [GoogleAdController::class, 'index'])->name('google-ad');
    Route::post('google-ad-store', [GoogleAdController::class, 'store'])->name('google-ad.store');
    Route::post('google-ad-update-{id}', [GoogleAdController::class, 'update'])->name('google-ad.update');
    Route::get('google-ad-delete-{id}', [GoogleAdController::class, 'destroy'])->name('google-ad.delete');

    Route::get('ads-rate', [PaidAdRateController::class, 'index'])->name('ads-rate');
    Route::post('ads-rate-store', [PaidAdRateController::class, 'store'])->name('ads-rate.store');
    Route::post('ads-rate-update-{id}', [PaidAdRateController::class, 'update'])->name('ads-rate.update');

    Route::get('job-edit/{id}', [JobController::class, 'edit'])->name('job-edit');
    Route::get('job-delete/{id}', [JobController::class, 'destroy'])->name('job-delete');

    // Marketplace Admin
    Route::get('wu-marketplace-services', [WuServiceController::class, 'adminIndex'])->name('wu-marketplace-services');
    Route::post('wu-marketplace-services-approve/{id}', [WuServiceController::class, 'adminApprove'])->name('wu-marketplace-services-approve');
    Route::post('wu-marketplace-services-reject/{id}', [WuServiceController::class, 'adminReject'])->name('wu-marketplace-services-reject');
    Route::get('wu-marketplace-services-delete/{id}', [WuServiceController::class, 'adminDelete'])->name('wu-marketplace-services-delete');

    Route::get('wu-marketplace-categories', [WuServiceController::class, 'adminCategoryIndex'])->name('wu-marketplace-categories');
    Route::post('wu-marketplace-categories-store', [WuServiceController::class, 'adminCategoryStore'])->name('wu-marketplace-categories-store');
    Route::post('wu-marketplace-categories-update/{id}', [WuServiceController::class, 'adminCategoryUpdate'])->name('wu-marketplace-categories-update');
    Route::get('wu-marketplace-categories-delete/{id}', [WuServiceController::class, 'adminCategoryDelete'])->name('wu-marketplace-categories-delete');

    // Surveys
    Route::get('/surveys', [\App\Http\Controllers\Admin\AdminSurveyController::class, 'index'])->name('surveys.index');
    Route::get('/surveys/create', [\App\Http\Controllers\Admin\AdminSurveyController::class, 'create'])->name('surveys.create');
    Route::post('/surveys', [\App\Http\Controllers\Admin\AdminSurveyController::class, 'store'])->name('surveys.store');
    Route::delete('/surveys/{survey}', [\App\Http\Controllers\Admin\AdminSurveyController::class, 'destroy'])->name('surveys.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::put('/admin/user/update-status/{id}', [UserController::class, 'updateStatus'])->name('admin.user.update.status');
Route::put('/admin/user/update-email-status/{id}', [App\Http\Controllers\Backend\UserController::class, 'updateEmailStatus'])->name('admin.user.update.emailStatus');

Route::post('user/update/{id}', [UserController::class, 'update'])->name('user.update');
Route::put('user/update/{id}', [UserController::class, 'update'])->name('user.update');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'admin']], function () {

    Route::get('/bkash/search/{trxID}', [App\Http\Controllers\BkashTokenizePaymentController::class, 'searchTnx'])->name('bkash-serach');
    Route::get('/bkash/refund', [App\Http\Controllers\BkashTokenizePaymentController::class, 'refund'])->name('bkash-refund');
    Route::get('/bkash/refund/status', [App\Http\Controllers\BkashTokenizePaymentController::class, 'refundStatus'])->name('bkash-refund-status');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/get-country', [DashboardController::class, 'get_country'])->name('get-country');
    Route::post('/get-sub-category', [DashboardController::class, 'get_sub_category'])->name('get-sub-category');
    Route::post('/get-boost-sub-category', [DashboardController::class, 'get_boost_sub_category'])->name('get-boost-sub-category');
    Route::post('/get-boost-sub-category-price', [DashboardController::class, 'get_boost_sub_category_price'])->name('get-boost-sub-category-price');

    Route::get('role', [RoleController::class, 'index'])->name('role');
    Route::post('role-store', [RoleController::class, 'store'])->name('role.store');
    Route::post('role-update-{id}', [RoleController::class, 'update'])->name('role.update');
    Route::get('role-permission/{slug}', [RoleController::class, 'role_permission'])->name('role-permission');
    Route::post('role-permission-update', [RoleController::class, 'role_permission_update'])->name('role-permission-update');

    Route::get('user', [UserController::class, 'index'])->name('user');
    Route::get('user-search', [UserController::class, 'user_search'])->name('user-search');
    Route::get('user-full-job-view/{id}/{viewType}', [UserController::class, 'user_full_job_view'])->name('user_full_job_view');
    Route::get('duplicate-users', [UserController::class, 'duplicate_users'])->name('duplicate-users');
    Route::get('kyc-verify-check', [UserController::class, 'kyc_verify_check'])->name('kyc-verify-check');
    Route::get('kyc-user-list', [UserController::class, 'kyc_user_list'])->name('kyc-user-list');
    Route::get('kyc-user-unapprove', [UserController::class, 'kyc_user_unapprove'])->name('kyc-user-unapprove');
    Route::post('kyc-verify-check-update', [UserController::class, 'kyc_verify_check_update'])->name('kyc-verify-check-update');
    Route::get('admin-user', [UserController::class, 'admin_user'])->name('admin-user');
    Route::post('user-store', [UserController::class, 'store'])->name('user.store');

    Route::post('user-activity-update-{id}', [UserController::class, 'user_activity'])->name('user-activity.update');
    Route::post('user-verify-{id}', [UserController::class, 'verify'])->name('user.verify');
    Route::get('reactive-user-account-{id}', [UserController::class, 'reactive_user_account'])->name('reactive-user-account');
    Route::post('user-suspend-{id}', [UserController::class, 'user_suspend'])->name('user-suspend');
    Route::post('user-ban-{id}', [UserController::class, 'user_ban'])->name('user-ban');

    Route::get('support-ticket', [SupportTicketController::class, 'index'])->name('support-ticket');
    Route::get('pending-support-ticket', [SupportTicketController::class, 'pending'])->name('pending-support-ticket');
    Route::get('answered-support-ticket', [SupportTicketController::class, 'answered'])->name('answered-support-ticket');
    Route::get('closed-support-ticket', [SupportTicketController::class, 'closed'])->name('closed-support-ticket');
    Route::post('support-ticket-store', [SupportTicketController::class, 'store'])->name('support-ticket.store');
    Route::get('show-support-ticket/{id}', [SupportTicketController::class, 'show'])->name('show-support-ticket');
    Route::get('close-support-ticket/{id}', [SupportTicketController::class, 'close'])->name('close-support-ticket');
    Route::post('support-ticket-update-{id}', [SupportTicketController::class, 'update'])->name('support-ticket.update');
    Route::get('support-ticket-delete-{id}', [SupportTicketController::class, 'destroy'])->name('support-ticket.delete');
    Route::post('support-ticket-replay-store', [SupportTicketController::class, 'replay_store'])->name('support-ticket-replay.store');
    Route::get('support-ticket-data-delete-{id}', [SupportTicketController::class, 'destroy_data'])->name('support-ticket-data.delete');

    Route::get('web-script', [WebScriptController::class, 'index'])->name('web-script');
    Route::post('web-script-store', [WebScriptController::class, 'store'])->name('web-script.store');
    Route::post('web-script-update-{id}', [WebScriptController::class, 'update'])->name('web-script.update');
    Route::get('web-script-delete-{id}', [WebScriptController::class, 'destroy'])->name('web-script.delete');

    Route::get('lottery', [LotteryController::class, 'index'])->name('lottery');
    Route::post('lottery-store', [LotteryController::class, 'store'])->name('lottery.store');
    Route::post('lottery-update-{id}', [LotteryController::class, 'update'])->name('lottery.update');
    Route::get('lottery-delete-{id}', [LotteryController::class, 'destroy'])->name('lottery.delete');
    Route::get('lottery-active-{id}', [LotteryController::class, 'lottery_active'])->name('lottery.active');
    Route::get('lottery-deactive-{id}', [LotteryController::class, 'lottery_deactive'])->name('lottery.deactive');
    Route::get('lottery-bought-user-{id}', [LotteryController::class, 'lottery_bought_user'])->name('lottery-bought-user');

    Route::get('service-item', [ServiceItemController::class, 'index'])->name('service-item');
    Route::post('service-item-store', [ServiceItemController::class, 'store'])->name('service-item.store');
    Route::post('service-item-update-{id}', [ServiceItemController::class, 'update'])->name('service-item.update');
    Route::get('service-item-delete-{id}', [ServiceItemController::class, 'destroy'])->name('service-item.delete');
    Route::get('service-item-booking-list', [ServiceItemController::class, 'service_item_booking_list'])->name('service-item-booking-list');
    Route::get('pending-service-item-booking', [ServiceItemController::class, 'pending_service_item_booking'])->name('pending-service-item-booking');
    Route::post('deposit-service-item-booking/{id}', [ServiceItemController::class, 'service_item_booking_approved'])->name('service-item-booking-approved');
    Route::get('service-item-booking-delete/{id}', [ServiceItemController::class, 'service_item_booking_delete'])->name('service-item-booking-delete');

    Route::get('job', [JobController::class, 'index'])->name('job');
    Route::get('pending-job', [JobController::class, 'pending_job'])->name('pending-job');
    Route::get('complete-job', [JobController::class, 'complete_job'])->name('complete-job');
    Route::get('rejected-job', [JobController::class, 'rejected_job'])->name('rejected-job');
    Route::get('delete-requested-job', [JobController::class, 'delete_requested_job'])->name('delete-requested-job');
    Route::post('job-store', [JobController::class, 'store'])->name('job.store');
    Route::post('job-update-{id}', [JobController::class, 'update'])->name('job.update');
    Route::get('job-approve/{id}', [JobController::class, 'job_approve'])->name('job-approve');
    Route::post('reject-job/{id}', [JobController::class, 'reject_job'])->name('reject-job');

    Route::get('job-work', [JobWorkController::class, 'index'])->name('job-work');
    Route::get('reject-request-job-work', [JobWorkController::class, 'reject_request'])->name('reject-request-job-work');
    Route::get('rejected-job-work', [JobWorkController::class, 'rejected_work'])->name('rejected-job-work');
    Route::get('job-work-delete/{id}', [JobWorkController::class, 'destroy'])->name('job-work-delete');
    Route::get('job-work-final-reject/{id}', [JobWorkController::class, 'job_work_final_reject'])->name('job-work-final-reject');
    Route::get('job-work-approve/{id}', [JobWorkController::class, 'job_work_approve'])->name('job-work-approve');

    Route::get('ptc-job-running-admin', [JobWorkController::class, 'ptcRunningAdmin'])->name('ptcRunningAdmin');
    Route::get('ptc-job-expired-admin', [JobWorkController::class, 'ptcExpiredAdmin'])->name('ptcExpiredAdmin');
    Route::get('ptc-job-admin-strong-pending', [JobWorkController::class, 'ptcAdminPending'])->name('ptcAdminPending');
    Route::get('ptc-job-deleted-admin', [JobWorkController::class, 'ptcDeleteList'])->name('ptcDeleteList');
    Route::get('ptc-job-delete-request', [JobWorkController::class, 'ptcDeleteRequest'])->name('ptcDeleteRequest');
    Route::get('ptc-job-rejected-request', [JobWorkController::class, 'ptcRejectList'])->name('ptcRejectList');
    Route::get('ptc-job-history-admin', [JobWorkController::class, 'ptcJobHistoryAdmin'])->name('ptcJobHistoryAdmin');
    Route::post('ptc-job-running-admin-store', [JobWorkController::class, 'ptcRunningAdminStore'])->name('ptcRunningAdminStore');

    Route::get('deposit-list', [DepositAccountController::class, 'deposit_list'])->name('deposit-list');
    Route::get('pending-deposit', [DepositAccountController::class, 'pending_deposit'])->name('pending-deposit');
    Route::post('deposit-approved/{id}', [DepositAccountController::class, 'deposit_approved'])->name('deposit-approved');
    Route::get('deposit-delete/{id}', [DepositAccountController::class, 'deposit_delete'])->name('deposit-delete');

    Route::get('module', [ModuleController::class, 'index'])->name('module');
    Route::post('module-update-{id}', [ModuleController::class, 'update'])->name('module.update');

    Route::get('withdraw-request', [WithdrawController::class, 'index'])->name('withdraw-request');
    Route::get('pending-withdraw-request', [WithdrawController::class, 'pending_withdraw_request'])->name('pending-withdraw-request');
    Route::post('withdraw-request-approved/{id}', [WithdrawController::class, 'withdraw_request_approved'])->name('withdraw-request-approved');

    Route::get('advertisement', [AdvertisementController::class, 'index'])->name('advertisement');
    Route::get('pending-advertisement', [AdvertisementController::class, 'pending_advertisement'])->name('pending-advertisement');
    Route::get('expired-advertisement', [AdvertisementController::class, 'expired_advertisement'])->name('expired-advertisement');
    Route::post('advertisement-store', [AdvertisementController::class, 'store'])->name('advertisement.store');
    Route::post('advertisement-update-{id}', [AdvertisementController::class, 'update'])->name('advertisement.update');
    Route::post('advertisement-approve-{id}', [AdvertisementController::class, 'approve'])->name('advertisement.approve');
    Route::post('advertisement-exp-date-{id}', [AdvertisementController::class, 'exp_dade_update'])->name('advertisement-exp-dade.update');

    Route::get('verify-document', [UserVerifyDocumentController::class, 'index'])->name('verify-document');
    Route::post('verify-document-store', [UserVerifyDocumentController::class, 'store'])->name('verify-document.store');
    Route::post('verify-document-update-{id}', [UserVerifyDocumentController::class, 'update'])->name('verify-document.update');
    Route::post('verify-document-delete-{id}', [UserVerifyDocumentController::class, 'destroy'])->name('verify-document.delete');

    Route::get('job-category', [CategoryController::class, 'index'])->name('job-category');
    Route::post('job-category-store', [CategoryController::class, 'store'])->name('job-category.store');
    Route::post('job-category-update-{id}', [CategoryController::class, 'update'])->name('job-category.update');

    Route::get('job-sub-category', [SubCategoryController::class, 'index'])->name('job-sub-category');
    Route::post('job-sub-category-store', [SubCategoryController::class, 'store'])->name('job-sub-category.store');
    Route::post('job-sub-category-update-{id}', [SubCategoryController::class, 'update'])->name('job-sub-category.update');

    Route::get('/boost-package', [BoostPackageController::class, 'index'])->name('boost-package');
    Route::get('boost-category', [BoostCategoryController::class, 'index'])->name('boost-category');
    Route::post('boost-category-store', [BoostCategoryController::class, 'store'])->name('boost-category.store');
    Route::post('boost-category-update-{id}', [BoostCategoryController::class, 'update'])->name('boost-category.update');
    Route::get('boost-sub-category', [BoostSubCategoryController::class, 'index'])->name('boost-sub-category');
    Route::post('boost-sub-category-store', [BoostSubCategoryController::class, 'store'])->name('boost-sub-category.store');
    Route::post('boost-sub-category-update-{id}', [BoostSubCategoryController::class, 'update'])->name('boost-sub-category.update');
    Route::post('/boost-package-reject/{id}', [BoostPackageController::class, 'reject'])->name('boost-package-reject');

    Route::get('boost-package', [UserBoostPackageController::class, 'index'])->name('boost-package');
    Route::get('boost-package-process/{id}', [UserBoostPackageController::class, 'process'])->name('boost-package-process');
    Route::get('boost-package-inprocess/{id}', [UserBoostPackageController::class, 'inprocess'])->name('boost-package-inprocess');
    Route::get('boost-package-complete/{id}', [UserBoostPackageController::class, 'complete'])->name('boost-package-complete');

    Route::get('continent', [ContinentController::class, 'index'])->name('continent');
    Route::post('continent-store', [ContinentController::class, 'store'])->name('continent.store');
    Route::post('continent-update-{id}', [ContinentController::class, 'update'])->name('continent.update');

    Route::get('country', [CountryController::class, 'index'])->name('country');
    Route::post('country-store', [CountryController::class, 'store'])->name('country.store');
    Route::post('country-update-{id}', [CountryController::class, 'update'])->name('country.update');

    Route::get('zone', [LocationZoneController::class, 'index'])->name('zone');
    Route::post('zone-store', [LocationZoneController::class, 'store'])->name('zone.store');
    Route::post('zone-update-{id}', [LocationZoneController::class, 'update'])->name('zone.update');

    Route::get('zone-country/{id}', [LocationZoneCountryController::class, 'index'])->name('zone-country');
    Route::post('zone-country-store', [LocationZoneCountryController::class, 'store'])->name('zone-country.store');
    Route::post('zone-country-delete-{id}', [LocationZoneCountryController::class, 'destroy'])->name('zone-country.delete');

    Route::get('headline', [HeadlineController::class, 'index'])->name('headline');
    Route::post('headline-store', [HeadlineController::class, 'store'])->name('headline.store');
    Route::post('headline-delete-{id}', [HeadlineController::class, 'destroy'])->name('headline.delete');

    Route::get('accept-task-headline', [AcceptTaskHeadlineController::class, 'index'])->name('accept-task-headline');
    Route::post('accept-task-headline-store', [AcceptTaskHeadlineController::class, 'store'])->name('accept-task-headline.store');
    Route::post('accept-task-headline-delete-{id}', [AcceptTaskHeadlineController::class, 'destroy'])->name('accept-task-headline.delete');

    Route::get('complete-task-headline', [CompleteTaskHeadlineController::class, 'index'])->name('complete-task-headline');
    Route::post('complete-task-headline-store', [CompleteTaskHeadlineController::class, 'store'])->name('complete-task-headline.store');
    Route::post('complete-task-headline-delete-{id}', [CompleteTaskHeadlineController::class, 'destroy'])->name('complete-task-headline.delete');

    Route::get('deposit-headline', [DepositTaskHeadlineController::class, 'index'])->name('deposit-headline');
    Route::post('deposit-headline-store', [DepositTaskHeadlineController::class, 'store'])->name('deposit-headline.store');
    Route::post('deposit-headline-delete-{id}', [DepositTaskHeadlineController::class, 'destroy'])->name('deposit-headline.delete');

    Route::get('deposit-document', [DepositDocumentController::class, 'index'])->name('deposit-document');
    Route::post('deposit-document-store', [DepositDocumentController::class, 'store'])->name('deposit-document.store');
    Route::post('deposit-document-delete-{id}', [DepositDocumentController::class, 'destroy'])->name('deposit-document.delete');

    Route::get('withdraw-headline', [WithdrawTaskHeadlineController::class, 'index'])->name('withdraw-headline');
    Route::post('withdraw-headline-store', [WithdrawTaskHeadlineController::class, 'store'])->name('withdraw-headline.store');
    Route::post('withdraw-headline-delete-{id}', [WithdrawTaskHeadlineController::class, 'destroy'])->name('withdraw-headline.delete');

    Route::get('boost-package-headline', [BoostPackageHeadlineController::class, 'index'])->name('boost-package-headline');
    Route::post('boost-package-headline-store', [BoostPackageHeadlineController::class, 'store'])->name('boost-package-headline.store');
    Route::post('boost-package-headline-delete-{id}', [BoostPackageHeadlineController::class, 'destroy'])->name('boost-package-headline.delete');

    Route::get('top-deposit-user-headline', [TopDepositUserHeadlineController::class, 'index'])->name('top-deposit-user-headline');
    Route::post('top-deposit-user-headline-store', [TopDepositUserHeadlineController::class, 'store'])->name('top-deposit-user-headline.store');
    Route::post('top-deposit-user-headline-delete-{id}', [TopDepositUserHeadlineController::class, 'destroy'])->name('top-deposit-user-headline.delete');

    Route::get('top-earning-user-headline', [TopEarningUserHeadlineController::class, 'index'])->name('top-earning-user-headline');
    Route::post('top-earning-user-headline-store', [TopEarningUserHeadlineController::class, 'store'])->name('top-earning-user-headline.store');
    Route::post('top-earning-user-headline-delete-{id}', [TopEarningUserHeadlineController::class, 'destroy'])->name('top-earning-user-headline.delete');

    Route::get('top-referral-user-headline', [TopReferralUserHeadlineController::class, 'index'])->name('top-referral-user-headline');
    Route::post('top-referral-user-headline-store', [TopReferralUserHeadlineController::class, 'store'])->name('top-referral-user-headline.store');
    Route::post('top-referral-user-headline-delete-{id}', [TopReferralUserHeadlineController::class, 'destroy'])->name('top-referral-user-headline.delete');

    Route::get('user-message', [UserMessageController::class, 'index'])->name('user-message');
    Route::post('user-message-store', [UserMessageController::class, 'store'])->name('user-message.store');
    Route::post('user-message-delete-{id}', [UserMessageController::class, 'destroy'])->name('user-message.delete');

    Route::get('client', [ClientController::class, 'index'])->name('client');
    Route::post('client-store', [ClientController::class, 'store'])->name('client.store');
    Route::get('client-delete-{id}', [ClientController::class, 'destroy'])->name('client.delete');

    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('profile-update-{id}', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('setting', [UserSettingController::class, 'index'])->name('setting');
    Route::post('setting-update-{id}', [UserSettingController::class, 'update'])->name('setting.update');

    Route::post('/get-sub-category-price', [UserDashboardController::class, 'get_sub_category_price'])->name('get-sub-category-price');
    Route::post('/get-new-task-complete-area', [UserDashboardController::class, 'get_new_task_complete_area'])->name('get-new-task-complete-area');
});

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => ['auth', 'user']], function () {

    Route::get('/instant-deposit', function () {
        $bkashSettings = BkashSetting::first();

        if ($bkashSettings && $bkashSettings->status == 1) {
            return view('user.pages.instant-deposit');
        }

        return redirect()->back()->with('error', 'bKash payment is currently disabled. Please try again later.');
    });

    Route::get('/bkash/payment', [App\Http\Controllers\BkashTokenizePaymentController::class, 'index']);
    Route::get('/bkash/create-payment', [App\Http\Controllers\BkashTokenizePaymentController::class, 'createPayment'])->name('bkash-create-payment');
    Route::get('/bkash/callback', [App\Http\Controllers\BkashTokenizePaymentController::class, 'callBack'])->name('bkash-callBack');

    Route::get('/', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/find-job', [UserDashboardController::class, 'index'])->name('find-job');
    Route::get('/policy/{slug}', [UserDashboardController::class, 'policy_details'])->name('policy-details');
    Route::get('/job-details/{code}', [UserDashboardController::class, 'job_details'])->name('my-job-details');

    Route::get('/top-deposit-user', [UserDashboardController::class, 'top_deposit_user'])->name('top-deposit-user');
    Route::get('/top-earning-user', [UserDashboardController::class, 'top_earning_user'])->name('top-earning-user');
    Route::get('/top-referral-user', [UserDashboardController::class, 'top_referral_user'])->name('top-referral-user');

    Route::post('/instant-verify-my-account', [UserDashboardController::class, 'instant_verify_my_account'])->name('instant-verify-my-account');
    Route::get('/account-not-verified', [UserDashboardController::class, 'account_not_verified'])->name('account-not-verified');
    Route::get('/account-instant-verify', [UserDashboardController::class, 'account_instant_verify'])->name('account-instant-verify');
    Route::get('/account-instant-verify-by-nid', [UserDashboardController::class, 'account_instant_verify_by_nid'])->name('account-instant-verify-by-nid');
    Route::post('/account-instant-verify-by-nid-store', [UserDashboardController::class, 'account_instant_verify_by_nid_store'])->name('account-instant-verify-by-nid-store');

    Route::get('/message-list', [UserDashboardController::class, 'message_list'])->name('message-list');
    Route::post('/get-country', [UserDashboardController::class, 'get_country'])->name('get-country');
    Route::post('/get-continent-country', [UserDashboardController::class, 'get_continent_country'])->name('get-continent-country');
    Route::post('/get-sub-category', [UserDashboardController::class, 'get_sub_category'])->name('get-sub-category');
    Route::post('/get-sub-categorys', [UserDashboardController::class, 'get_sub_categorys'])->name('get-sub-categorys');
    Route::post('/get-sub-category-price', [UserDashboardController::class, 'get_sub_category_price'])->name('get-sub-category-price');
    Route::post('/get-new-task-complete-area', [UserDashboardController::class, 'get_new_task_complete_area'])->name('get-new-task-complete-area');
    Route::post('/get-boost-sub-category', [UserDashboardController::class, 'get_boost_sub_category'])->name('get-boost-sub-category');
    Route::post('/get-boost-sub-category-price', [UserDashboardController::class, 'get_boost_sub_category_price'])->name('get-boost-sub-category-price');

    Route::get('/deposit', [UserDepositCOntroller::class, 'index'])->name('deposit');
    Route::get('/deposit-list', [UserDepositCOntroller::class, 'deposit_list'])->name('deposit-list');
    Route::post('/deposit-account-info', [UserDepositCOntroller::class, 'deposit_account_info'])->name('deposit-account-info');
    Route::post('/deposit-store', [UserDepositCOntroller::class, 'store'])->name('deposit-store');
    Route::get('/earning-to-deposit', [UserDepositController::class, 'showEarningToDepositPage'])->name('earning-to-deposit');
    Route::post('/earning-to-deposit', [UserDepositController::class, 'earningToDeposit'])->name('user.earning-to-deposit');

    Route::get('/advertisement', [UserAdvertisementController::class, 'index'])->name('advertisement');
    Route::get('/advertisement-list', [UserAdvertisementController::class, 'advertisement_list'])->name('advertisement-list');
    Route::post('/advertisement-store', [UserAdvertisementController::class, 'store'])->name('advertisement-store');

    Route::get('/boost', [BoostPackageController::class, 'index'])->name('boost');
    Route::get('/boost-create', [BoostPackageController::class, 'create'])->name('boost-create');
    Route::post('/boost-post', [BoostPackageController::class, 'store'])->name('boost-post');
    Route::get('/boost-edit/{id}', [BoostPackageController::class, 'edit'])->name('boost-edit');
    Route::post('/boost-update/{id}', [BoostPackageController::class, 'update'])->name('boost-update');
    Route::post('/boost-delete/{id}', [BoostPackageController::class, 'destroy'])->name('boost-delete');

    Route::get('/withdraw', [UserWithdrawController::class, 'index'])->name('withdraw');
    Route::get('/new-withdraw-request', [UserWithdrawController::class, 'create'])->name('new-withdraw-request');
    Route::post('/withdraw-account-info', [UserWithdrawController::class, 'withdraw_account_info'])->name('withdraw-account-info');
    Route::post('/withdraw-store', [UserWithdrawController::class, 'store'])->name('withdraw-store');

    Route::get('/job', [UserJobController::class, 'index'])->name('job');
    Route::get('/job-create', [UserJobController::class, 'create'])->name('job-create');
    Route::post('/job-post', [UserJobController::class, 'store'])->name('job-post');
    Route::get('/job-edit/{id}', [UserJobController::class, 'edit'])->name('job-edit');
    Route::post('/job-update/{id}', [UserJobController::class, 'update'])->name('job-update');
    Route::get('/job-delete/{id}', [UserJobController::class, 'destroy'])->name('job-delete');
    Route::post('/get-job-country-wise', [UserJobController::class, 'get_job_country_wise'])->name('get-job-country-wise');
    Route::post('/get-job-category-wise', [UserJobController::class, 'get_job_category_wise'])->name('get-job-category-wise');
    Route::post('/get-regular-job', [UserJobController::class, 'get_regular_job'])->name('get-regular-job');
    Route::post('/get-recent-job', [UserJobController::class, 'get_recent_job'])->name('get-recent-job');
    Route::post('/get-heigh-cost-job', [UserJobController::class, 'get_heigh_cost_job'])->name('get-heigh-cost-job');
    Route::post('/get-load-more-job', [UserJobController::class, 'get_load_more_job'])->name('get-load-more-job');
    Route::post('/job-work-need-update/{id}', [UserJobController::class, 'job_work_need_update'])->name('job-work-need-update');
    Route::post('/job-boosting-update/{id}', [UserJobController::class, 'job_boosting_update'])->name('job-boosting-update');
    Route::get('/pause-job/{id}', [UserJobController::class, 'pause_job'])->name('pause-job');
    Route::get('/start-job/{id}', [UserJobController::class, 'start_job'])->name('start-job');

    Route::get('boost-package-delete/{id}', [UserBoostPackageController::class, 'destroy'])->name('boost-package-delete');
    Route::post('boost-package-reject/{id}', [UserBoostPackageController::class, 'reject'])->name('boost-package-reject');

    Route::get('ptc-job', [UserJobController::class, 'ptcAdd'])->name('ptcAdd');
    Route::get('ptc-job-edit/{id}', [UserJobController::class, 'ptcEdit'])->name('ptcEdit');
    Route::post('ptc-job-edit', [UserJobController::class, 'ptcEditStore'])->name('ptcEditStore');
    Route::get('ptc-job-list', [UserJobController::class, 'ptcList'])->name('ptcList');
    Route::get('ptc-earn-history', [UserJobController::class, 'ptcEarned'])->name('ptcEarned');
    Route::post('ptc-job-seeker', [UserJobController::class, 'jobSeeker'])->name('jobSeeker');
    Route::get('ptc-my-running-job', [UserJobController::class, 'myRunning'])->name('myRunning');
    Route::get('ptc-my-posted-history', [UserJobController::class, 'myPostedJobHistory'])->name('myPostedJobHistory');
    Route::post('ptc-job-store', [UserJobController::class, 'ptcAddStore'])->name('ptcAddStore');

    Route::get('/community-earn', [socialEarnController::class, 'communityEarn'])->name('communityEarn');
    Route::post('/add-post-store', [socialEarnController::class, 'communityPostStore'])->name('communityPostStore');
    Route::get('/community-earn-rate', [socialEarnController::class, 'communityEarnRate'])->name('communityEarnRate');
    Route::post('/fetch-url-preview', [socialEarnController::class, 'commynityEarnFatch'])->name('commynityEarnFatch');
    Route::get('/view-community-post/{id?}', [socialEarnController::class, 'viewCommunityPP'])->name('viewCommunityPP');
    Route::post('/new-comment/{id}', [socialEarnController::class, 'newComment'])->name('newComment');
    Route::post('/new-like', [socialEarnController::class, 'newLike'])->name('newLike');
    Route::post('/new-share', [socialEarnController::class, 'newShare'])->name('newShare');
    Route::get('/feed-post-dashboard', [socialEarnController::class, 'postFeedDashboard'])->name('postFeedDashboard');
    Route::get('/feed-post-list', [socialEarnController::class, 'myPostFeedList'])->name('myPostFeedList');

    Route::get('/spin', [UserSpinController::class, 'index'])->name('spin');
    Route::post('claim-share-bonus', [UserProfileController::class, 'claim_share_bonus'])->name('claim-share-bonus');

    Route::get('/worked-job', [UserJobWorkController::class, 'index'])->name('worked-job');
    Route::get('/complete-worked-job', [UserJobWorkController::class, 'complete'])->name('complete-worked-job');
    Route::get('/job-post-done', [UserJobController::class, 'job_post_done'])->name('job-post-done');
    Route::get('/job-pending-working-proves/{code}', [UserJobWorkController::class, 'job_pending_working_proves'])->name('job-pending-working-proves');
    Route::get('/job-working-proves/{code}', [UserJobWorkController::class, 'job_working_proves'])->name('job-working-proves');
    Route::get('/all-satisfied-job-woked/{job_id}', [UserJobWorkController::class, 'all_satisfied_of_job'])->name('all-satisfied-job-woked');
    Route::get('/all-satisfied-woked', [UserJobWorkController::class, 'all_satisfied'])->name('all-satisfied-woked');
    Route::post('/report-this-job', [UserJobWorkController::class, 'report_this_job'])->name('report-this-job');
    Route::post('/job-work-post', [UserJobWorkController::class, 'store'])->name('job-work-post');
    Route::post('/resubmit-job-work', [UserJobWorkController::class, 'resubmit_job_work'])->name('resubmit-job-work');
    Route::get('/job-work-confirm', [UserJobWorkController::class, 'job_work_confirm'])->name('job-work-confirm');
    Route::get('job-hide/{id}', [UserJobWorkController::class, 'job_hide'])->name('job-hide');
    Route::get('job-work-approve/{id}', [UserJobWorkController::class, 'job_work_approve'])->name('job-work-approve');
    Route::post('job-work-reject/{id}', [UserJobWorkController::class, 'job_work_reject'])->name('job-work-reject');
    Route::post('job-work-report/{id}', [UserJobWorkController::class, 'job_work_report'])->name('job-work-report');
    Route::post('job-work-resume/{id}', [UserJobWorkController::class, 'job_work_resume'])->name('job-work-resume');
    Route::post('job-work-rate/{id}', [UserJobWorkController::class, 'job_work_rate'])->name('job-work-rate');
    Route::post('job-work-report-to-job-woner/{id}', [UserJobWorkController::class, 'job_work_report_to_job_woner'])->name('job-work-report-to-job-woner');

    Route::get('/support-ticket', [UserSupportTicketController::class, 'index'])->name('support-ticket');
    Route::get('/support-ticket-create', [UserSupportTicketController::class, 'create'])->name('support-ticket-create');
    Route::post('/support-ticket-store', [UserSupportTicketController::class, 'store'])->name('support-ticket-store');
    Route::get('/support-ticket-show/{id}', [UserSupportTicketController::class, 'show'])->name('support-ticket-show');
    Route::get('/support-ticket-edit/{id}', [UserSupportTicketController::class, 'edit'])->name('support-ticket-edit');
    Route::post('/support-ticket-update/{id}', [UserSupportTicketController::class, 'update'])->name('support-ticket-update');
    Route::post('/support-ticket-delete/{id}', [UserSupportTicketController::class, 'destroy'])->name('support-ticket-delete');
    Route::get('/support-ticket-close/{id}', [UserSupportTicketController::class, 'close'])->name('support-ticket-close');
    Route::post('support-ticket-replay-store', [UserSupportTicketController::class, 'replay_store'])->name('support-ticket-replay.store');
    Route::get('support-ticket-data-delete-{id}', [UserSupportTicketController::class, 'destroy_data'])->name('support-ticket-data.delete');

    Route::get('lottery', [UserLotteryController::class, 'index'])->name('lottery');
    Route::get('lottery-list', [UserLotteryController::class, 'lottery_list'])->name('lottery-list');
    Route::post('/lottery-buy-confirm', [UserLotteryController::class, 'lottery_buy_confirm'])->name('lottery-buy-confirm');

    Route::get('investment-package', [UserInvestmentController::class, 'index'])->name('investment-package');
    Route::get('investment-list', [UserInvestmentController::class, 'investment_list'])->name('investment-list');
    Route::get('investment-create/{id}', [UserInvestmentController::class, 'create'])->name('investment-create');
    Route::post('investment-store', [UserInvestmentController::class, 'store'])->name('investment-store');
    Route::get('/investment-create-from-earning/{id}', [UserInvestmentController::class, 'invest_from_earning'])->name('investment-create-from-earning');
    Route::get('/investment-create-from-deposit/{id}', [UserInvestmentController::class, 'invest_from_deposit'])->name('investment-create-from-deposit');
    Route::post('/investment-buy-confirm', [UserInvestmentController::class, 'investment_buy_confirm'])->name('investment-buy-confirm');

    Route::get('service-item', [UserServiceItemController::class, 'index'])->name('service-item');
    Route::get('service-item-create/{id}', [UserServiceItemController::class, 'create'])->name('service-item-create');
    Route::post('service-item-store', [UserServiceItemController::class, 'store'])->name('service-item-store');
    Route::get('service-item-details/{id}', [UserServiceItemController::class, 'service_item_details'])->name('service-item-details');
    Route::get('service-item-list', [UserServiceItemController::class, 'service_item_booking_list'])->name('service-item-list');
    Route::get('/service-item-create-from-earning/{id}', [UserServiceItemController::class, 'service_item_from_earning'])->name('service-item-create-from-earning');
    Route::get('/service-item-create-from-deposit/{id}', [UserServiceItemController::class, 'service_item_from_deposit'])->name('service-item-create-from-deposit');
    Route::post('/service-item-buy-confirm', [UserServiceItemController::class, 'service_item_buy_confirm'])->name('service-item-buy-confirm');

    Route::get('web-script', [UserWebScriptController::class, 'index'])->name('web-script');
    Route::get('web-script-create/{id}', [UserWebScriptController::class, 'create'])->name('web-script-create');
    Route::post('web-script-store', [UserWebScriptController::class, 'store'])->name('web-script-store');
    Route::get('web-script-details/{slug}', [UserWebScriptController::class, 'web_script_details'])->name('web-script-details');
    Route::get('web-script-list', [UserWebScriptController::class, 'web_script_booking_list'])->name('web-script-list');
    Route::get('/web-script-create-from-earning/{id}', [UserWebScriptController::class, 'web_script_from_earning'])->name('web-script-create-from-earning');
    Route::get('/web-script-create-from-deposit/{id}', [UserWebScriptController::class, 'web_script_from_deposit'])->name('web-script-create-from-deposit');
    Route::post('/web-script-buy-confirm', [UserWebScriptController::class, 'web_script_buy_confirm'])->name('web-script-buy-confirm');

    Route::get('profile', [UserProfileController::class, 'index'])->name('profile');
    Route::get('user-profile/{id}', [UserProfileController::class, 'user_profile'])->name('user-profile');
    Route::get('manage-profile', [UserProfileController::class, 'edit'])->name('manage-profile');
    Route::post('profile-update-{id}', [UserProfileController::class, 'update'])->name('profile.update');
    Route::post('add-spin-mark-to-earning', [UserProfileController::class, 'add_spin_mark_to_earning'])->name('add-spin-mark-to-earning');
    Route::get('profile-verify-data', [UserProfileController::class, 'profile_verify_data'])->name('profile-verify-data');
    Route::post('profile-verify-data-update', [UserProfileController::class, 'profile_verify_data_update'])->name('profile-verify-data.update');

    Route::get('referral', [UserReferralController::class, 'index'])->name('referral');
    Route::get('referral-user', [UserReferralController::class, 'view_list'])->name('referral-user');

    /*
    |--------------------------------------------------------------------------
    | User Marketplace Panel
    |--------------------------------------------------------------------------
    */
    Route::get('/marketplace', [WuServiceController::class, 'dashboard'])->name('marketplace');

    Route::get('/marketplace/create', [WuServiceController::class, 'create'])->name('marketplace.create');
    Route::post('/marketplace/store', [WuServiceController::class, 'store'])->name('marketplace.store');
    Route::get('/marketplace/my-services', [WuServiceController::class, 'myServices'])->name('marketplace.my_services');
    Route::get('/marketplace/edit/{id}', [WuServiceController::class, 'edit'])->name('marketplace.edit');
    Route::post('/marketplace/update/{id}', [WuServiceController::class, 'update'])->name('marketplace.update');
    Route::get('/marketplace/delete/{id}', [WuServiceController::class, 'delete'])->name('marketplace.delete');

    Route::get('/marketplace/services', [WuServiceController::class, 'browseServices'])->name('marketplace.services');
    Route::get('/marketplace/services/category/{slug}', [WuServiceController::class, 'browseServicesByCategory'])->name('marketplace.services.category');
    Route::get('/marketplace/service/{slug}', [WuServiceController::class, 'serviceShow'])->name('marketplace.service.show');

    Route::get('/marketplace/inquiries', [WuServiceController::class, 'inquiries'])->name('marketplace.inquiries');
    Route::get('/marketplace/inquiry/{serviceId}/{userId}', [WuServiceController::class, 'inquiryThread'])->name('marketplace.inquiry.thread');
    Route::post('/marketplace/inquiry/{serviceId}/{userId}/send', [WuServiceController::class, 'sendInquiry'])->name('marketplace.inquiry.send');

    Route::post('/marketplace/order/{id}', [WuServiceController::class, 'order'])->name('marketplace.order');
    Route::get('/marketplace/orders', [WuServiceController::class, 'orders'])->name('marketplace.orders');
    Route::get('/marketplace/sales', [WuServiceController::class, 'sales'])->name('marketplace.sales');

    Route::get('/marketplace/order-chat/{id}', [WuServiceController::class, 'orderChat'])->name('marketplace.order_chat');
    Route::post('/marketplace/send-message/{id}', [WuServiceController::class, 'sendMessage'])->name('marketplace.send_message');

    Route::post('/marketplace/deliver/{id}', [WuServiceController::class, 'deliver'])->name('marketplace.deliver');
    Route::post('/marketplace/complete/{id}', [WuServiceController::class, 'complete'])->name('marketplace.complete');
    Route::post('/marketplace/cancel/{id}', [WuServiceController::class, 'cancelOrder'])->name('marketplace.cancel');
    Route::post('/marketplace/order-review/{id}', [WuServiceController::class, 'submitReview'])->name('marketplace.review');

    Route::get('/marketplace/message-file/{id}', [WuServiceController::class, 'messageFile'])->name('marketplace.message_file');
    Route::get('/marketplace/delivery-file/{id}', [WuServiceController::class, 'deliveryFile'])->name('marketplace.delivery_file');
    Route::get('/marketplace/download-product/{orderId}', [WuServiceController::class, 'downloadProduct'])->name('marketplace.download_product');
});

/*
|--------------------------------------------------------------------------
| Admin Account Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::get('/accounts/create', [AdminController::class, 'create'])->name('admin.account.create');
    Route::post('/accounts', [AdminController::class, 'store'])->name('admin.account.store');
    Route::get('/accounts/{id}/edit', [AdminController::class, 'edit'])->name('admin.account.edit');
    Route::put('/accounts/{id}', [AdminController::class, 'update'])->name('admin.account.update');
    Route::delete('/accounts/{id}', [AdminController::class, 'destroy'])->name('admin.account.delete');
    Route::delete('/accounts/{id}/delete', [AdminController::class, 'destroy'])->name('admin.account.delete');
});

/*
|--------------------------------------------------------------------------
| Cache Clear
|--------------------------------------------------------------------------
*/
Route::get('/clear-cache', function () {
    \Artisan::call('cache:clear');
    \Artisan::call('config:cache');
    \Artisan::call('view:clear');
    \Artisan::call('route:clear');
    \Artisan::call('optimize:clear');
    return 'All cache has been cleared';
});

/*
|--------------------------------------------------------------------------
| Verify Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/verify', [VerifyController::class, 'show'])->name('verify.show');
    Route::post('/verify', [VerifyController::class, 'verify'])->name('verify.verify');

    Route::get('/surveys', [\App\Http\Controllers\SurveyController::class, 'index'])->name('surveys.index');
    Route::get('/surveys/{survey}', [\App\Http\Controllers\SurveyController::class, 'show'])->name('surveys.show');
    Route::post('/surveys/{survey}/save', [\App\Http\Controllers\SurveyController::class, 'saveAnswer'])->name('surveys.saveAnswer');
    Route::post('/surveys/{survey}/submit', [\App\Http\Controllers\SurveyController::class, 'submit'])->name('surveys.submit');
});

/*
|--------------------------------------------------------------------------
| Public Marketplace
|--------------------------------------------------------------------------
*/
Route::get('/marketplace', [WuServiceController::class, 'publicIndex'])->name('marketplace');
Route::get('/marketplace/category/{slug}', [WuServiceController::class, 'publicCategory'])->name('marketplace.category');
Route::get('/marketplace/service/{slug}', [WuServiceController::class, 'publicShow'])->name('marketplace.service.show');

//servay page route -- points to the real surveys list (auth-gated: guests land on login first)
Route::get('/surveys-home', function () {
    return redirect()->route('surveys.index');
})->name('surveys.home');

/*
|--------------------------------------------------------------------------
| Safe cache clear (no shell/SSH access on this server) — visit the URL
| below whenever deployed code doesn't seem to take effect. Protected by
| a secret token so it can't be triggered by a random visitor.
|--------------------------------------------------------------------------
*/
Route::get('/system-cache-clear/{token}', function ($token) {
    if (!hash_equals('sRGOELHdF3jvfuekDV5sezqOGNNHhsnz', (string) $token)) {
        abort(403);
    }

    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');

    $opcacheCleared = false;
    if (function_exists('opcache_reset')) {
        $opcacheCleared = opcache_reset();
    }

    return 'Cache cleared successfully at ' . now() . '. OPcache reset: ' . ($opcacheCleared ? 'yes' : 'no/unavailable');
});

/*
|--------------------------------------------------------------------------
| Run pending database migrations (no SSH/artisan access on this server).
| Same secret token as the cache-clear route above.
|--------------------------------------------------------------------------
*/
Route::get('/system-migrate/{token}', function ($token) {
    if (!hash_equals('sRGOELHdF3jvfuekDV5sezqOGNNHhsnz', (string) $token)) {
        abort(403);
    }

    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    $output = \Illuminate\Support\Facades\Artisan::output();

    return response('<pre>' . e($output) . '</pre>');
});

/*
|--------------------------------------------------------------------------
| Diagnostic: shows exactly why a marketplace listing's image isn't
| rendering (missing on disk vs. wrong stored path vs. permissions).
| Same secret token as the cache-clear route above.
|--------------------------------------------------------------------------
*/
Route::get('/system-debug-marketplace-images/{token}', function ($token) {
    if (!hash_equals('sRGOELHdF3jvfuekDV5sezqOGNNHhsnz', (string) $token)) {
        abort(403);
    }

    $dir = base_path('uploads/wu-services');

    $filesOnDisk = file_exists($dir)
        ? collect(scandir($dir))->reject(fn($f) => in_array($f, ['.', '..']))->values()
        : 'DIRECTORY DOES NOT EXIST: ' . $dir;

    $services = \Illuminate\Support\Facades\DB::table('wu_services')
        ->orderByDesc('id')
        ->limit(15)
        ->get(['id', 'title', 'type', 'image', 'created_at'])
        ->map(function ($s) {
            $s->resolved_full_path = $s->image ? base_path(ltrim($s->image, '/')) : null;
            $s->file_exists_check = $s->image ? file_exists(base_path(ltrim($s->image, '/'))) : null;
            $s->asset_url = $s->image ? asset(ltrim($s->image, '/')) : null;
            return $s;
        });

    return response()->json([
        'base_path'           => base_path(),
        'public_path_base'   => public_path(),
        'upload_dir'         => $dir,
        'upload_dir_writable'=> is_writable(dirname($dir)),
        'files_in_upload_dir'=> $filesOnDisk,
        'recent_services'    => $services,
    ], 200, [], JSON_PRETTY_PRINT);
});

/*
|--------------------------------------------------------------------------
| Diagnostic: reproduces the exact og:image/og:title/og:description
| extraction used by the community link-preview feature (commynityEarnFatch)
| against a given ?url=, once with the same UA that feature uses and once
| with Facebook's crawler UA -- so we can see if a site is cloaking
| (showing real meta tags only to known crawler UAs). Same secret token.
|--------------------------------------------------------------------------
*/
Route::get('/system-debug-link-preview/{token}', function ($token) {
    if (!hash_equals('sRGOELHdF3jvfuekDV5sezqOGNNHhsnz', (string) $token)) {
        abort(403);
    }

    $url = request('url');
    if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
        return response()->json(['status' => false, 'error' => 'Pass a valid ?url= query parameter.'], 400);
    }

    $extract = function ($url, $userAgent) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);

        $html = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL) ?: $url;
        curl_close($ch);

        if ($error) {
            return ['status' => false, 'error' => 'Curl Error: ' . $error, 'http_code' => $httpCode];
        }
        if (!$html) {
            return ['status' => false, 'error' => 'Empty HTML returned', 'http_code' => $httpCode];
        }

        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        @$doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new \DOMXPath($doc);

        $queryTag = function ($xpath, $query) {
            $nodes = $xpath->query($query);
            return ($nodes && $nodes->length > 0) ? $nodes->item(0)->nodeValue : null;
        };

        $rawTitle = $queryTag($xpath, '//meta[@property="og:title"]/@content') ?? $queryTag($xpath, '//title');
        $rawImage = $queryTag($xpath, '//meta[@property="og:image"]/@content');
        $rawDesc = $queryTag($xpath, '//meta[@property="og:description"]/@content') ?? $queryTag($xpath, '//meta[@name="description"]/@content');

        return [
            'status' => true,
            'http_code' => $httpCode,
            'effective_url' => $effectiveUrl,
            'html_length' => strlen($html),
            'html_snippet' => substr($html, 0, 500),
            'raw_og_title' => $rawTitle,
            'raw_og_image' => $rawImage,
            'raw_og_description' => $rawDesc,
            'all_meta_tags' => (function () use ($xpath) {
                $out = [];
                foreach ($xpath->query('//meta') as $meta) {
                    $prop = $meta->getAttribute('property') ?: $meta->getAttribute('name');
                    if ($prop) {
                        $out[$prop] = $meta->getAttribute('content');
                    }
                }
                return $out;
            })(),
        ];
    };

    $siteUserAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/110.0.0.0 Safari/537.36';
    $facebookUserAgent = 'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)';

    return response()->json([
        'url' => $url,
        'as_our_scraper' => $extract($url, $siteUserAgent),
        'as_facebook_crawler' => $extract($url, $facebookUserAgent),
    ], 200, [], JSON_PRETTY_PRINT);
});