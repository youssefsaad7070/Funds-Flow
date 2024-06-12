<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ManageUsersController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Opportunities\CategoryController;
use App\Http\Controllers\Opportunities\OpportunityApproveController;
use App\Http\Controllers\Opportunities\InvestmentOpportunityController;

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);

Route::get('unauthorized', [LoginController::class, 'unauthorized'])->name('unauthorized');

Route::post('password/forgot-password', [ForgetPasswordController::class, 'forgotPassword']);
Route::post('password/otpvalidation', [ResetPasswordController::class, 'otpValidation']);
Route::post('password/reset-password', [ResetPasswordController::class, 'passwordReset']);

Route::post('email-verification', [EmailVerificationController::class, 'email_verification']);
Route::post('send-email-verification', [EmailVerificationController::class, 'sendEmailVerification']);
// Route::stripeWebhooks('stripe-webhook');
Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/user', [UserController::class, 'getUser']);

    Route::get('profile', [ProfileController::class, 'getProfile']);
    Route::post('update-profile', [ProfileController::class, 'updateProfile']);

    Route::group(['middleware' => ['role:admin']], function () {
        Route::post('update-admin-profile', [ProfileController::class, 'updateAdminProfile']);
        
        Route::get('/users/investors', [ManageUsersController::class, 'indexInvestors']);
        Route::get('/users/businesses', [ManageUsersController::class, 'indexBusinesses']);
        Route::post('/user-details/{id}', [ManageUsersController::class, 'show']);
        Route::post('/user-approve-investing/{id}', [ManageUsersController::class, 'approve']);
        Route::post('/user-disApprove-investing/{id}', [ManageUsersController::class, 'disApprove']);
    });

    Route::post('email-verification', [EmailVerificationController::class, 'email_verification']);
    Route::get('email-verification', [EmailVerificationController::class, 'sendEmailVerification']);

    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('categories', [CategoryController::class, 'search']);


    Route::post('investment-opportunity/{uuid}', [InvestmentOpportunityController::class, 'index']);
    Route::post('investment-opportunity/details/{uuid}', [InvestmentOpportunityController::class, 'show']);

    Route::group(['middleware' => ['role:business']], function () {
        Route::post('opportunity/create', [InvestmentOpportunityController::class, 'store']);
        Route::post('opportunity/update/{uuid}', [InvestmentOpportunityController::class, 'update']);
        Route::delete('opportunity/delete/{uuid}', [InvestmentOpportunityController::class, 'delete']);
    });

    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('unApproved-opportunities', [OpportunityApproveController::class, 'unApprovedOpportunities']);
        Route::put('approve-opportunity/{uuid}', [OpportunityApproveController::class, 'approve']);
        Route::delete('disApprove-opportunity/{uuid}', [OpportunityApproveController::class, 'reject']);
    });

    Route::post('payment', [StripeController::class, 'session']);
    Route::post('/stripe/session-details/{sessionId}', [StripeController::class, 'getSessionDetails']);

    Route::post('logout', [LoginController::class, 'logout']);
});











