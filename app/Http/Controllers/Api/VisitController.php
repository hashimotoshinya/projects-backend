<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Store;
use App\Models\VisitPlan;

class VisitController extends Controller
{
    public function store(Request $request, $storeId)
    {
        $store = Store::where('user_id', auth()->id())
            ->findOrFail($storeId);

        $validated = $request->validate([
            'memo' => 'nullable|string',
            'date' => 'nullable|date',
        ]);

        $date = $validated['date'] ?? now()->toDateString();

        // ① 既存のPlanを探す or 作る
        $plan = VisitPlan::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'store_id' => $store->id,
                'visit_date' => $date,
            ],
            [
                'memo' => null, // 初回だけ入る
            ]
        );

        // ② Visit作成（planに紐付け）
        $visit = Visit::create([
            'store_id' => $store->id,
            'user_id' => auth()->id(),
            'visit_plan_id' => $plan->id,
            'memo' => $validated['memo'] ?? null,
            'visited_at' => $date,
        ]);

        return response()->json($visit);
    }

    public function index($storeId)
    {
        $store = Store::where('user_id', auth()->id())
            ->findOrFail($storeId);

        return Visit::with('visitPlan')
            ->where('store_id', $store->id)
            ->where('user_id', auth()->id())
            ->latest('visited_at')
            ->get();
    }

    public function byDate($date)
    {
        $visits = Visit::with('store')
            ->whereDate('visited_at', $date)
            ->where('user_id', auth()->id())
            ->orderBy('visited_at')
            ->get();

        return response()->json($visits);
    }

    public function calendar()
    {
        $userId = auth()->id();

        $visits = Visit::selectRaw('DATE(visited_at) as date, COUNT(*) as count')
            ->where('user_id', $userId)
            ->groupBy('date')
            ->pluck('count','date');

        $plans = VisitPlan::selectRaw('visit_date as date, COUNT(*) as count')
            ->where('user_id', $userId)
            ->groupBy('date')
            ->pluck('count','date');

        $pending = $plans->mapWithKeys(function($count, $date) use ($visits) {
            $v = $visits->get($date, 0);
            return [$date => max(0, $count - $v)];
        });

        return response()->json([
            'visits' => $visits,
            'plans' => $plans,
            'pending' => $pending,
        ]);
    }
}