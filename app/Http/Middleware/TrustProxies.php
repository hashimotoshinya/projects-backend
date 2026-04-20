<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * 全プロキシを信頼（Render / Cloudflare対策）
     */
    protected $proxies = '*';

    /**
     * ヘッダーをすべて信頼
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}