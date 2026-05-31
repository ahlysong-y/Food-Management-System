<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * Proxies ដែលយើងទុកចិត្ត (ប្រើប្រាស់សញ្ញាផ្កាយសម្រាប់ Vercel)
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';

    /**
     * Headers ដែលប្រើប្រាស់សម្រាប់ចាប់យកទិន្នន័យពី Proxy
     *
     * @var int
     */
    protected $headers =
    Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
