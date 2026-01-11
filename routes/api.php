<?php

use App\Http\Controllers\Api\AcceptInvitationController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\InviteMembersController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ShowMyTeamController;
use App\Http\Controllers\Api\ShowTeamInvitationsController;
use App\Http\Controllers\Api\TeamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix('auth')->group(function(){
    Route::post('/',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);
    Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink']);
    Route::post('/reset-password', [PasswordResetController::class, 'ResetPssword']);
});

Route::apiResource('team',TeamController::class)->middleware(['auth:sanctum','role:student']);
Route::get('/my-team', ShowMyTeamController::class)->middleware(['auth:sanctum','role:student']);
Route::post('{team}/invite',InviteMembersController::class)->middleware(['auth:sanctum','role:student']);
Route::post('/accept', AcceptInvitationController::class)->middleware(['auth:sanctum','role:student']);
Route::get('{team}/invitations',ShowTeamInvitationsController::class)->middleware(['auth:sanctum','role:student']);

Route::middleware(['auth:sanctum'])->prefix('notifications')->group(function(){
    Route::get('/',[NotificationController::class,'index']);
    Route::get('/{id}/read',[NotificationController::class,'markAsRead']);
    Route::get('/read',[NotificationController::class,'markAllAsRead']);
});
