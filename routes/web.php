<?php

// web.php はモーダル認証（Session Cookie）用
// Sanctum との CSRF Cookie 取得端点
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

// sanctum/csrf-cookie エンドポイント
// GET で呼ばれる（axios が GET で呼んでいるため）
Route::get('/sanctum/csrf-cookie', function (Request $request) {
    $response = app(CsrfCookieController::class)->show($request);

    return $response->setContent(json_encode([
        'csrf_token' => csrf_token(),
    ]))->header('Content-Type', 'application/json');
})->middleware('web');