<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CaptureMarketingAttribution
{
    public function handle(Request $request, Closure $next): Response
    {
        $query = $request->query();
        $hasCampaign = collect([
            'utm_source',
            'utm_medium',
            'utm_campaign',
            'utm_content',
            'utm_term',
            'gclid',
            'fbclid',
            'ttclid',
        ])->contains(fn(string $key) => filled($query[$key] ?? null));

        if ($hasCampaign || $request->headers->has('referer')) {
            $attribution = session('marketing_attribution', []);
            $attribution['source'] = $query['utm_source'] ?? ($attribution['source'] ?? null);
            $attribution['medium'] = $query['utm_medium'] ?? ($attribution['medium'] ?? null);
            $attribution['campaign'] = $query['utm_campaign'] ?? ($attribution['campaign'] ?? null);
            $attribution['content'] = $query['utm_content'] ?? ($attribution['content'] ?? null);
            $attribution['term'] = $query['utm_term'] ?? ($attribution['term'] ?? null);
            $attribution['click_id'] = $query['gclid'] ?? $query['fbclid'] ?? $query['ttclid'] ?? ($attribution['click_id'] ?? null);
            $attribution['landing_url'] ??= $request->fullUrl();
            $attribution['referrer'] ??= $request->headers->get('referer');

            session(['marketing_attribution' => array_filter($attribution)]);
        }

        return $next($request);
    }
}
