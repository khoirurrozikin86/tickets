<?php

namespace App\Http\Middleware;

use App\Models\WebsiteVisit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class RecordWebsiteVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (
            !$request->isMethod('GET') ||
            $request->is('super', 'super/*', 'login', 'register', 'up') ||
            $request->user() ||
            $request->expectsJson() ||
            $response->getStatusCode() >= 400
        ) {
            return $response;
        }

        $visitorId = session('website_visitor_id');

        if (!$visitorId) {
            $visitorId = (string) Str::uuid();
            session(['website_visitor_id' => $visitorId]);
        }

        $incomingReferrer = parse_url($request->headers->get('referer', ''), PHP_URL_HOST);
        if ($incomingReferrer === $request->getHost()) {
            $incomingReferrer = null;
        }

        if ($incomingReferrer) {
            session(['website_referrer_host' => session('website_referrer_host') ?: $incomingReferrer]);
        }

        $referrerHost = session('website_referrer_host') ?: $incomingReferrer;
        $userAgent = strtolower($request->userAgent() ?? '');
        $device = preg_match('/bot|crawler|spider|slurp/', $userAgent)
            ? 'bot'
            : (preg_match('/tablet|ipad/', $userAgent)
                ? 'tablet'
                : (preg_match('/mobile|android|iphone/', $userAgent) ? 'mobile' : 'desktop'));
        $browser = match (true) {
            str_contains($userAgent, 'edg/') => 'Edge',
            str_contains($userAgent, 'opr/') => 'Opera',
            str_contains($userAgent, 'chrome/') => 'Chrome',
            str_contains($userAgent, 'firefox/') => 'Firefox',
            str_contains($userAgent, 'safari/') => 'Safari',
            str_contains($userAgent, 'bot'),
            str_contains($userAgent, 'crawler') => 'Bot',
            default => 'Other',
        };

        WebsiteVisit::create([
            'visitor_hash' => hash_hmac('sha256', $visitorId, (string) config('app.key')),
            'path' => '/' . ltrim($request->path(), '/'),
            'referrer_host' => $referrerHost,
            'device' => $device,
            'browser' => $browser,
            'visited_at' => now(),
        ]);

        return $response;
    }
}
