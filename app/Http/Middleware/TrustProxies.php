<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrustProxies
{
    /**
     * The trusted proxies for this application.
     *
     * @var array<int, string>|string|null
     */
    // --- កែប្រែត្រង់ចំណុចនេះ ទៅជាសញ្ញាផ្កាយ ដើម្បីទុកចិត្តគ្រប់ Proxy របស់ Vercel ---
    protected $proxies = '*';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // កំណត់ឱ្យ Laravel ទុកចិត្ត Proxies ទាំងអស់នៅពេលមាន Request ចូលមក
        Request::setTrustedProxies((array) $this->proxies, Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO);

        return $next($request);
    }
}
