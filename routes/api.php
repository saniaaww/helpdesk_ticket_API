<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;

Route::get('/test', function () {
    return "API OK";
});

Route::post('/login', [AuthController::class, 'login']);
Route::get('/dashboard',
    [DashboardController::class,'index']);

Route::get('/tickets',
    [TicketController::class,'index']);

Route::post('/tickets',
    [TicketController::class,'store']);

Route::get('/tickets/{id}',
    [TicketController::class,'show']);

Route::post('/tickets/{id}/assign',
    [TicketController::class,'assign']);

Route::post('/tickets/{id}/status',
    [TicketController::class,'updateStatus']);

Route::get(
    '/helpdesk/{name}/tickets',
    [TicketController::class,'byHelpdesk']);

Route::get(
    '/tickets/{id}/comments',
    [CommentController::class,'index']
);

Route::post(
    '/tickets/{id}/comments',
    [CommentController::class,'store']
);

Route::get(
    '/tickets/{id}/history',
    [HistoryController::class,'index']
);

Route::get('/users/{id}', [UserController::class, 'show']);

Route::put('/users/{id}', [UserController::class, 'update']);
Route::get('/helpdesk', [UserController::class, 'getHelpdesk']);
Route::get(
    '/helpdesk/tickets/{id}',
    [TicketController::class, 'byHelpdesk']
);
Route::get('/helpdesk-users', [UserController::class, 'helpdeskUsers']);
Route::get(
    '/user/tickets/{id}',
    [TicketController::class,'byUser']
);

Route::get(
    '/notifications/{user}',
    [NotificationController::class,'index']
);

Route::post(
    '/notifications/read/{id}',
    [NotificationController::class,'read']
);


// =======================
// CRUD USER
// =======================

Route::get('/users', [UserController::class,'index']);

Route::post('/users', [UserController::class,'store']);

Route::put('/users/{id}', [UserController::class,'update']);

Route::delete('/users/{id}', [UserController::class,'destroy']);