<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VisitPlan;
use App\Models\Visit;
use Illuminate\Http\Request;

class VisitPlanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'visit_date' => 'required|date',
            'memo' => 'nullable|string'
        ]);

        $plan = VisitPlan::create([
            'user_id' => auth()->id(),
            'store_id' => $request->store_id,
            'visit_date' => $request->visit_date,
            'memo' => $request->memo
        ]);

        return response()->json($plan);
    }

    public function byStore($storeId)
    {
        $plans = VisitPlan::with(['visit'])
            ->where('store_id', $storeId)
            ->where('user_id', auth()->id())
            ->orderBy('visit_date', 'desc')
            ->get();

        return response()->json($plans);
    }

    public function byDate($date)
    {
        $plans = VisitPlan::with(['store', 'visit'])
            ->where('visit_date', $date)
            ->where('user_id', auth()->id())
            ->get();

        return response()->json($plans);
    }

    public function byFuture()
    {
        $plans = VisitPlan::with('store')
            ->where('visit_date', '>=', now()->toDateString())
            ->where('user_id', auth()->id())
            ->orderBy('visit_date')
            ->get();

        return response()->json($plans);
    }

    public function update(Request $request, $id)
    {
        $plan = VisitPlan::where('user_id', auth()->id())
            ->findOrFail($id);

        $plan->update([
            'memo' => $request->memo,
        ]);

        return response()->json($plan);
    }

    public function destroy($id)
    {
        $plan = VisitPlan::where('user_id', auth()->id())
            ->findOrFail($id);

        $plan->visit()->delete();

        $plan->delete();

        return response()->json(['message' => '削除しました']);
    }

    public function complete(Request $request, $id)
    {
        $plan = VisitPlan::where('user_id', auth()->id())
            ->findOrFail($id);

        if ($plan->visit) {
            return response()->json($plan->load('visit'));
        }

        $visit = Visit::create([
            'store_id' => $plan->store_id,
            'user_id' => auth()->id(),
            'visit_plan_id' => $plan->id,
            'memo' => $request->memo ?? null,
            'visited_at' => now(),
        ]);

        return response()->json($plan->load('visit'));
    }

    public function uncomplete($id)
    {
        $plan = VisitPlan::where('user_id', auth()->id())
            ->findOrFail($id);

        $plan->visit()->delete();

        return response()->json($plan->load('visit'));
    }
}