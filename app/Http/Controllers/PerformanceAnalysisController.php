<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PerformanceAnalysisController extends Controller
{
    public function index(Request $request)
    {
        $selectedUser = $request->user_id ?? 'all';

        $usersQuery = User::orderBy('name')->where('category_id', '!=', 6);
        if ($selectedUser !== 'all') {
            $usersQuery->where('id', $selectedUser);
        }
        $users = $usersQuery->get();

        $minDate = Project::min('created_at');
        $maxDate = Project::max('created_at');

        $from = $request->from_date
            ? Carbon::parse($request->from_date)->startOfDay()
            : ($minDate ? Carbon::parse($minDate)->startOfDay() : now()->subDays(7)->startOfDay());

        $to = $request->to_date
            ? Carbon::parse($request->to_date)->endOfDay()
            : ($maxDate ? Carbon::parse($maxDate)->endOfDay() : now()->endOfDay());

        if (!$from || !$to) {
            return view('performance_analysis.index', [
                'users' => $users,
                'dates' => collect(),
                'matrix' => [],
                'from' => now()->subDays(7),
                'to' => now(),
                'selectedUser' => $selectedUser,
            ]);
        }

        $allDates = collect();
        for ($date = $from->copy(); $date->lte($to); $date->addDay()) {
            $allDates->push($date->toDateString());
        }

        $query = Project::whereBetween('created_at', [$from, $to]);
        if ($selectedUser !== 'all') {
            $query->where('created_by', $selectedUser);
        }

        $projects = $query
            ->selectRaw('DATE(created_at) as date, created_by as user_id, COUNT(*) as total')
            ->groupBy('date', 'created_by')
            ->orderBy('date')
            ->get();

        $matrix = [];
        foreach ($projects as $p) {
            $matrix[$p->date][$p->user_id] = $p->total;
        }

        return view('performance_analysis.index', compact('users', 'allDates', 'matrix', 'from', 'to', 'selectedUser'));
    }
}
