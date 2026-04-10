<?php

// web.php はモーダル認証（Session Cookie）用
// Sanctum との CSRF Cookie 取得端点
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// sanctum/csrf-cookie エンドポイント
// GET で呼ばれる（axios が GET で呼んでいるため）
Route::get('/sanctum/csrf-cookie', function () {
    return response()->noContent();
})->middleware('web');