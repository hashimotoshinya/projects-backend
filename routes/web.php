<?php

// web.php はモーダル認証（Session Cookie）用
// Sanctum との CSRF Cookie 取得端点
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

// sanctum/csrf-cookie エンドポイント
// GET で呼ばれる（axios が GET で呼んでいるため）
Route::get('/sanctum/csrf-cookie', function (Request $request) {
    $token = csrf_token();

    return response()
        ->json(['csrf_token' => $token])
        ->withCookie(cookie(
            'XSRF-TOKEN',
            $token,
            120,
            '/',
            env('SESSION_DOMAIN'),
            env('SESSION_SECURE_COOKIE', true),
            false,
            false,
            'none',
        ));
})->middleware('web');