<?php

use App\Http\Controllers\Api\Admin\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Api\Admin\ConfigurationController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\Admin\TimeSlotController;

use App\Http\Controllers\Api\FrontEnd\SessionController;
use App\Http\Controllers\Api\Admin\SessionController as AdminSessionController;
use App\Http\Controllers\Api\Admin\TransactionController;
use App\Http\Controllers\Api\Admin\UserController;

use App\Http\Controllers\Api\Customer\PostController;
use App\Http\Controllers\Api\Customer\CommentController;

use App\Http\Controllers\Api\NotificationsController;
use App\Http\Controllers\Api\ZoomController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/time-slots', [SessionController::class, 'fetchSlots']);
Route::get('story-cost', [SessionController::class, 'storyCost']);

Route::post('login', [LoginController::class,'login']);
Route::post('reset/password', [LoginController::class,'resetPassword']);
Route::post('create-new-password', [LoginController::class,'createNewPassword']);
Route::get('user/check-token/{token}', [UserController::class,'checkToken']);
Route::post('check-remember-token-reset-password', [UserController::class,'checkRememberTokenResetPassword']);


Route::middleware('auth:sanctum')->group(function ()
{
    Route::post('upload', [DashboardController::class,'store']);

    Route::prefix('dashboard')->group(function () {
        Route::post('data', [DashboardController::class,'getData']);
        Route::get('alerts', [DashboardController::class,'alerts']);
    });

    Route::prefix('zoom')->group(function () {
        Route::get('callback/{cod}', [ZoomController::class,'callback']);

    });
    Route::prefix('notifications')->group(function () {
        Route::post('fetch', [NotificationsController::class,'fetch']);
        Route::post('old', [NotificationsController::class,'old']);
        Route::post('fetch-unread', [NotificationsController::class,'fetchUnread']);
        Route::post('read', [NotificationsController::class,'readOne']);
        Route::post('read-all', [NotificationsController::class,'readAll']);
        Route::post('read-counter', [NotificationsController::class,'readCounter']);
        Route::post('delete', [NotificationsController::class,'delete']);

    });
    Route::prefix('user')->group(function () {
        Route::post('logout', [LoginController::class,'logout']);
        Route::post('profile/update', [AdminController::class,'updateProfile']);
        Route::post('update-customer', [UserController::class,'updateCustomer']);
        Route::post('add-relatives/{id}', [UserController::class,'addRelatives']);
        Route::post('update-bio', [UserController::class,'updateBio']);
        Route::post('update-profile-image', [UserController::class,'updateProfileImage']);
        Route::get('info', [UserController::class,'userInfo']);
        Route::get('transactions', [UserController::class,'transactions']);
        Route::get('fetch-all', [UserController::class,'fetchAll']);
        Route::post('fetch', [UserController::class,'fetch']);
        Route::post('update-details', [UserController::class,'updateDetails']);
        Route::post('fetch-sub-users', [UserController::class,'fetchSubUsers']);
        Route::post('get-customer-of-sub-user', [UserController::class,'getCustomerOfSubUser']);
        Route::post('payment-methods', [UserController::class,'paymentMethods']);
        Route::post('add-payment-method', [UserController::class,'paymentMethodStore']);
        Route::post('delete-payment-method', [UserController::class,'paymentMethodDelete']);
        Route::post('cancel-subscription', [UserController::class,'cancelSubscription']);
        Route::get('subscription-amount', [UserController::class,'subscriptionAmount']);
        Route::post('subscribe', [UserController::class,'subscribe']);
        Route::post('subscription-status', [UserController::class,'subscriptionStatus']);
        Route::post('payment-method/make-preferred', [UserController::class,'cardMakePreferred']);
        Route::get('get-roles', [UserController::class,'getRoles']);
        Route::post('update-switch', [UserController::class,'updateSwitch']);
        Route::post('fetch-customer-switch', [UserController::class,'fetchCustomerForSwitch']);
        Route::get('get-switches', [UserController::class,'getSwitches']);
        Route::post('switch-account', [UserController::class,'switchAccount']);
        Route::post('video', [UserController::class,'getVideo']);
        Route::post('is-switch', [UserController::class,'isSwitch']);
        Route::post('update-user', [UserController::class,'updateUser']);
        Route::post('delete', [UserController::class,'delete']);
        Route::post('delete-fnf-member', [UserController::class,'deleteFNFMember']);
        Route::post('create-customer-account', [UserController::class,'createCustomerAccount']);
        Route::post('fnf-update', [UserController::class,'FNFUpdate']);
    });

    Route::prefix('time-slots')->group(function () {
        Route::post('fetch', [TimeSlotController::class,'fetch']);
        Route::get('fetch-all', [TimeSlotController::class,'fetchAll']);
        Route::post('store', [TimeSlotController::class,'store']);
        Route::post('edit', [TimeSlotController::class,'edit']);
        Route::post('delete', [TimeSlotController::class, 'delete']);
    });
    Route::prefix('post')->group(function () {
        Route::post('fetch', [PostController::class,'fetch']);
        Route::post('timeline', [PostController::class,'timeline']);
        Route::post('fetchSingle', [PostController::class,'fetchSingle']);
        Route::post('store', [PostController::class,'store']);
    });
    Route::prefix('comment')->group(function () {
        Route::post('fetch', [CommentController::class,'fetch']);
        Route::post('store', [CommentController::class,'store']);
        Route::post('update', [CommentController::class,'update']);
        Route::post('delete', [CommentController::class,'delete']);
    });

    Route::prefix('sessions')->group(function () {
        Route::get('fetch-all', [AdminSessionController::class,'fetchAll']);
        Route::post('fetch', [AdminSessionController::class,'fetch']);
        Route::get('as-events', [AdminSessionController::class,'sessionsAsEvents']);
        Route::post('delete', [AdminSessionController::class,'delete']);
        Route::post('upload-customer-video', [AdminSessionController::class, 'uploadCustomerVideo']);
    });

    Route::prefix('transactions')->group(function () {
        Route::get('user/{user_id}', [TransactionController::class,'userTransactions']);
        Route::get('fetch-all', [TransactionController::class,'fetchAll']);
        Route::get('fetch-subscriptions', [TransactionController::class,'fetchSubscriptions']);
    });

    Route::prefix('configuration')->group(function () {
        Route::get('fetch-all', [ConfigurationController::class,'fetchAll']);
        Route::post('update', [ConfigurationController::class,'update']);
    });

    Route::get('payment/stripe-redirect-urls', [PaymentController::class, 'redirectUrls']);

});

Route::prefix('session')->group(function () {
    Route::post('validation', [SessionController::class, 'validation']);
    Route::post('store', [SessionController::class, 'store']);
});

Route::post('payment/initiate', [PaymentController::class, 'initiatePayment']);
Route::post('payment/check-customer', [PaymentController::class, 'checkCustomer']);
Route::post('payment/token', [PaymentController::class, 'storeToken']);
Route::post('payment/check-payment', [PaymentController::class, 'checkPayment']);
Route::get('fetch-dates', [SessionController::class, 'fetchDates']);
