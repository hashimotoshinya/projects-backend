<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use Illuminate\Http\Request;

class DailyReportController extends Controller
{
    public function show($date)
    {
        $report = DailyReport::where('user_id', auth()->id())
            ->where('report_date', $date)
            ->first();

        return response()->json($report);
    }

    // 保存（作成 or 更新）
    public function store(Request $request)
    {
        $request->validate([
            'report_date' => 'required|date',
            'content' => 'nullable|string',
        ]);

        $report = DailyReport::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'report_date' => $request->report_date,
            ],
            [
                'content' => $request->content,
            ]
        );

        return response()->json($report);
    }
}
