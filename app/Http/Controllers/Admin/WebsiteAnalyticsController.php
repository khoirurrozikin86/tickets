<?php

namespace App\Http\Controllers\Admin;

use App\Exports\WebsiteVisitsExport;
use App\Http\Controllers\Controller;
use App\Models\WebsiteVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class WebsiteAnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        [$dateFrom, $dateTo] = $this->validatedDates($request);
        $query = $this->filteredVisits($dateFrom, $dateTo);

        return view('super.website-analytics.index', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'pageviews' => (clone $query)->count(),
            'visitors' => (clone $query)->distinct('visitor_hash')->count('visitor_hash'),
            'topPages' => (clone $query)
                ->select('path')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('path')
                ->orderByDesc('total')
                ->limit(10)
                ->get(),
            'referrers' => (clone $query)
                ->selectRaw("COALESCE(referrer_host, 'Direct') as source")
                ->selectRaw('COUNT(*) as total')
                ->groupBy('source')
                ->orderByDesc('total')
                ->get(),
            'devices' => (clone $query)
                ->select('device')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('device')
                ->orderByDesc('total')
                ->get(),
            'browsers' => (clone $query)
                ->select('browser')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('browser')
                ->orderByDesc('total')
                ->get(),
        ]);
    }

    public function dt(Request $request)
    {
        [$dateFrom, $dateTo] = $this->validatedDates($request);

        $query = $this->filteredVisits($dateFrom, $dateTo)
            ->select('visitor_hash', 'referrer_host', 'device', 'browser')
            ->selectRaw('COUNT(*) as pageviews')
            ->selectRaw('MAX(visited_at) as last_seen')
            ->groupBy('visitor_hash', 'referrer_host', 'device', 'browser')
            ->orderByDesc('last_seen');

        return DataTables::eloquent($query)
            ->addColumn('visitor_id', fn ($visit) => substr($visit->visitor_hash, 0, 12))
            ->addColumn('source', fn ($visit) => $visit->referrer_host ?: 'Direct')
            ->addColumn('device_browser', fn ($visit) => ucfirst($visit->device) . ' / ' . $visit->browser)
            ->editColumn('last_seen', fn ($visit) => Carbon::parse($visit->last_seen)->format('d/m/Y H:i'))
            ->toJson();
    }

    public function export(Request $request): BinaryFileResponse
    {
        [$dateFrom, $dateTo] = $this->validatedDates($request);

        return Excel::download(
            new WebsiteVisitsExport($dateFrom, $dateTo),
            'website-visits-' . $dateFrom . '-to-' . $dateTo . '.xlsx'
        );
    }

    private function validatedDates(Request $request): array
    {
        $validated = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        return [
            Carbon::parse($validated['date_from'] ?? now()->toDateString())->startOfDay(),
            Carbon::parse($validated['date_to'] ?? now()->toDateString())->endOfDay(),
        ];
    }

    private function filteredVisits(Carbon $dateFrom, Carbon $dateTo)
    {
        return WebsiteVisit::query()
            ->where('device', '!=', 'bot')
            ->whereBetween('visited_at', [$dateFrom, $dateTo]);
    }
}
