<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PerformanceAnalysisController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderBy('name')->get();

        $from = $request->from_date ? Carbon::parse($request->from_date)->startOfDay() : now()->subDays(7);
        $to = $request->to_date ? Carbon::parse($request->to_date)->endOfDay() : now();

        $query = ActivityLog::query()->with('user')->whereBetween('created_at', [$from, $to]);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $summary = $logs->groupBy('action')->map->count();

        return view('performance_analysis.index', compact('users', 'logs', 'summary', 'from', 'to'));
    }

    private function getFilteredLogs(Request $request)
    {
        $query = \App\Models\ActivityLog::query()
            ->with('user')
            ->orderBy('created_at', 'desc');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        return $query->get();
    }


    private function getSummary($logs)
    {
        return [
            'created' => $logs->where('action', 'created')->count(),
            'updated' => $logs->where('action', 'updated')->count(),
            'deleted' => $logs->where('action', 'deleted')->count(),
        ];
    }

    public function export(Request $request)
    {
        $logs = $this->getFilteredLogs($request);
        $summary = $this->getSummary($logs);
        $users = User::all();

        $selectedUser = null;
        if ($request->filled('user_id')) {
            $selectedUser = User::find($request->user_id);
        }

        $from = $request->from_date ?? now()->subDays(7)->toDateString();
        $to   = $request->to_date ?? now()->toDateString();

        if ($selectedUser) {
            $fileName = "تقرير_تحليل_الأداء_لـ_" . str_replace(' ', '_', $selectedUser->name)
                    . "_من_" . $from . "_إلى_" . $to . ".pdf";
        } else {
            $fileName = "تقرير_تحليل_الأداء_العام_من_" . $from . "_إلى_" . $to . ".pdf";
        }

        $pdf = Pdf::loadView('performance_analysis.pdf', compact('logs', 'summary', 'users', 'selectedUser', 'from', 'to'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download($fileName);
    }
}
