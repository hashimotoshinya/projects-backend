<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\VisitController;
use App\Http\Controllers\Api\VisitPlanController;
use App\Http\Controllers\Api\DailyReportController;

// 認証不要
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/stores', [StoreController::class,'index']);

// 認証必須（Session Cookie）
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', fn(Request $request) => $request->user());

    Route::post('/logout', [AuthController::class, 'logout']);

    // 店舗
    Route::get('/stores/{store}/visits', [VisitController::class, 'index']);
    Route::post('/stores/{store}/visits', [VisitController::class, 'store']);
    Route::get('/stores', [StoreController::class,'index']);
    Route::post('/stores', [StoreController::class,'store']);
    Route::get('/stores/{store}', [StoreController::class, 'show']);
    Route::put('/stores/{store}', [StoreController::class, 'update']);
    Route::delete('/stores/{id}', [StoreController::class, 'destroy']);

    // 訪問履歴
    Route::get('/visits/calendar', [VisitController::class, 'calendar']);
    Route::get('/visits/date/{date}', [VisitController::class, 'byDate']);

    // 予定
    Route::post('/visit-plans', [VisitPlanController::class, 'store']);
    Route::get('/visit-plans/store/{storeId}', [VisitPlanController::class, 'byStore']);
    Route::get('/visit-plans/future', [VisitPlanController::class, 'byFuture']);
    Route::get('/visit-plans/{date}', [VisitPlanController::class, 'byDate']);
    Route::put('/visit-plans/{id}', [VisitPlanController::class, 'update']);
    Route::delete('/visit-plans/{id}', [VisitPlanController::class, 'destroy']);

    // 訪問操作（重要）
    Route::put('/visit-plans/{id}/complete', [VisitPlanController::class, 'complete']);
    Route::put('/visit-plans/{id}/uncomplete', [VisitPlanController::class, 'uncomplete']);

    // 営業レポート
    Route::get('/reports/{date}', [DailyReportController::class, 'show']);
    Route::post('/reports', [DailyReportController::class, 'store']);
});