<?php

use App\Http\Controllers\Api\AcceptInvitationController;
use App\Http\Controllers\Api\AcceptTeamJoinRequestController;
use App\Http\Controllers\Api\Admin\AcceptProjectRequestController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\InviteSupervisorController;
use App\Http\Controllers\Api\Admin\ProjectController;
use App\Http\Controllers\Api\Admin\ProjectRequestController as AdminProjectRequestController;
use App\Http\Controllers\Api\Admin\RejectProjectRequestController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\InviteMembersController;
use App\Http\Controllers\Api\JoinRequestController;
use App\Http\Controllers\Api\MyteamController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProjectRequestController;
use App\Http\Controllers\Api\RejectTeamJoinRequestController;
use App\Http\Controllers\Api\ShowMyTeamController;
use App\Http\Controllers\Api\ShowTeamInvitationsController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\GetOpenTeamsController;
use App\Http\Controllers\RemoveTeamMemberControlle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SupervisorController;
use App\Http\Controllers\Api\Supervisor\ProjectController as SupservisorProjectController;


Route::prefix('auth')->group(function(){
    Route::post('/',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);
    Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink']);
    Route::post('/reset-password', [PasswordResetController::class, 'ResetPssword']);
});

Route::apiResource('team',TeamController::class)->middleware(['auth:sanctum','role:student']);
Route::post('{team}/invite',InviteMembersController::class)->middleware(['auth:sanctum','role:student']);
Route::post('/accept', AcceptInvitationController::class)->middleware(['auth:sanctum','role:student']);
Route::get('{team}/invitations',ShowTeamInvitationsController::class)->middleware(['auth:sanctum','role:student']);
Route::delete('{team}/remove/{student}', RemoveTeamMemberControlle::class)->middleware(['auth:sanctum','role:student']);

Route::middleware(['auth:sanctum'])->prefix('notifications')->group(function(){
    Route::get('/',[NotificationController::class,'index']);
    Route::get('/{id}/read',[NotificationController::class,'markAsRead']);
    Route::get('/read',[NotificationController::class,'markAllAsRead']);
});


Route::apiResource('myteam',MyteamController::class)->middleware(['auth:sanctum','role:student']);
Route::apiResource('project-request',ProjectRequestController::class)->middleware(['auth:sanctum']);
Route::get('/open-teams',GetOpenTeamsController::class)->middleware(['auth:sanctum','role:student']);

Route::apiResource("join-request",JoinRequestController::class)->middleware(['auth:sanctum','role:student']);
Route::patch('team-join-requests/{team_join_request}/accept', AcceptTeamJoinRequestController::class)->middleware(['auth:sanctum','role:student']);
Route::patch('team-join-requests/{team_join_request}/reject', RejectTeamJoinRequestController::class)->middleware(['auth:sanctum','role:student']);

Route::apiResource("supervisor",SupervisorController::class)->middleware(['auth:sanctum']);

Route::apiResource('project',ProjectController::class)->middleware(['auth:sanctum']);
Route::apiResource('admin-project-request',AdminProjectRequestController::class)->middleware(['auth:sanctum','role:admin']);
Route::post('project-request/{projectRequest}/accept', AcceptProjectRequestController::class)->middleware(['auth:sanctum', 'role:admin']);
Route::post('project-request/{projectRequest}/reject', RejectProjectRequestController::class)->middleware(['auth:sanctum', 'role:admin']);;


Route::post('invite-supervisor',InviteSupervisorController::class)->middleware(['auth:sanctum','role:admin']);
Route::get('/dashboard/statistics', DashboardController::class)->middleware(['auth:sanctum','role:admin']);


Route::middleware(['auth:sanctum', 'role:supervisor'])->prefix('projects')->group(function () {
    Route::get('/', [SupservisorProjectController::class, 'index']);
    Route::patch('/{projectRequest}/mark-as-reviewed', [SupservisorProjectController::class, 'markAsReviewed']);
    Route::patch('/{projectRequest}/complete', [SupservisorProjectController::class, 'complete']);
});
