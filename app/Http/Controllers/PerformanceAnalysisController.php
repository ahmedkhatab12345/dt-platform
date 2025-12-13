<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class PerformanceAnalysisController extends Controller
{
    private function reportsMap(): array
    {
        return [
            'projects' => [
                'label'     => 'المشاريع',
                'model'     => \App\Models\Project::class,
                'modelName' => 'Project',
                'date_col'  => 'created_at',
                'user_col'  => 'created_by',
                'extra'     => fn (Builder $q) => $q,
            ],
            'government_entities' => [
                'label'     => 'الجهات الحكومية',
                'model'     => \App\Models\GovernmentEntity::class,
                'modelName' => 'GovernmentEntity',
                'date_col'  => 'created_at',
                'user_col'  => 'created_by',
                'extra'     => fn (Builder $q) => $q,
            ],
            'perspectives' => [
                'label'     => 'المناظير',
                'model'     => \App\Models\Perspective::class,
                'modelName' => 'Perspective',
                'date_col'  => 'created_at',
                'user_col'  => 'created_by',
                'extra'     => fn (Builder $q) => $q,
                'enabled' => false,
            ],
            'pillars' => [
                'label'     => 'المحاور',
                'model'     => \App\Models\Pillar::class,
                'modelName' => 'Pillar',
                'date_col'  => 'created_at',
                'user_col'  => 'created_by',
                'extra'     => fn (Builder $q) => $q,
                'enabled' => false,
            ],
            'standards' => [
                'label'     => 'المعايير',
                'model'     => \App\Models\Standard::class,
                'modelName' => 'Standard',
                'date_col'  => 'created_at',
                'user_col'  => 'created_by',
                'extra'     => fn (Builder $q) => $q,
                'enabled' => false,
            ],
            'tools' => [
                'label'     => 'الأدوات',
                'model'     => \App\Models\Tool::class,
                'modelName' => 'Tool',
                'date_col'  => 'created_at',
                'user_col'  => 'created_by',
                'extra'     => fn (Builder $q) => $q,
                'enabled' => false,
            ],
            'assignments' => [
                'label'     => 'إسناد الأدوات',
                'model'     => \App\Models\Assignment::class,
                'modelName' => 'Assignment',
                'date_col'  => 'created_at',
                'user_col'  => 'created_by',
                'extra'     => fn (Builder $q) => $q,
                'enabled' => false,
            ],

            'innovation.platforms' => [
                'label'     => 'الابتكار - المنصات',
                'model'     => \App\Models\InnovationPlatform::class,
                'modelName' => 'InnovationPlatform',
                'date_col'  => 'created_at',
                'user_col'  => 'created_by',
                'extra'     => fn (Builder $q) => $q,
            ],
            'innovation.partnerships' => [
                'label'     => 'الابتكار - الشراكات والاتفاقيات',
                'model'     => \App\Models\InnovationPartnership::class,
                'modelName' => 'InnovationPartnership',
                'date_col'  => 'created_at',
                'user_col'  => 'created_by',
                'extra'     => fn (Builder $q) => $q,
            ],
            'innovation.events' => [
                'label'     => 'الابتكار - الفاعليات',
                'model'     => \App\Models\InnovationEvent::class,
                'modelName' => 'InnovationEvent',
                'date_col'  => 'created_at',
                'user_col'  => 'created_by',
                'extra'     => fn (Builder $q) => $q,
            ],
            'innovation.frameworks' => [
                'label'     => 'الابتكار - الأطر والمنهجيات',
                'model'     => \App\Models\InnovationFramework::class,
                'modelName' => 'InnovationFramework',
                'date_col'  => 'created_at',
                'user_col'  => 'created_by',
                'extra'     => fn (Builder $q) => $q,
            ],
            'innovation.assessment' => [
                'label'     => 'الابتكار - تقييم الابتكار',
                'model'     => \App\Models\InnovationAssessment::class,
                'modelName' => 'InnovationAssessment',
                'date_col'  => 'created_at',
                'user_col'  => 'created_by',
                'extra'     => fn (Builder $q) => $q,
            ],
        ];
    }

    public function index()
    {
        $reports = $this->reportsMap();
        return view('performance_analysis.menu', compact('reports'));
    }

    public function report(Request $request, string $report)
    {
        $reports = $this->reportsMap();
    
        abort_if(!isset($reports[$report]), 404);
    
        $def = $reports[$report];
    
        abort_if(isset($def['enabled']) && $def['enabled'] === false, 403, 'هذا التقرير غير متاح حالياً');
    
        $selectedUser = $request->user_id ?? 'all';
    
        $model   = $def['model'];
        $dateCol = $def['date_col'];
        $userCol = $def['user_col'];
    
        $from = $request->from_date
            ? Carbon::parse($request->from_date)->startOfDay()
            : now()->subDays(4)->startOfDay();
    
        $to = $request->to_date
            ? Carbon::parse($request->to_date)->endOfDay()
            : now()->endOfDay();
    
        $allDates = collect();
        for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
            $allDates->push($d->toDateString());
        }
    
        $activeUserIds = $def['extra'](
            $model::query()
        )
            ->whereNotNull($userCol)
            ->select($userCol)
            ->distinct()
            ->pluck($userCol)
            ->filter()
            ->values();
    
        $usersForFilter = User::whereIn('id', $activeUserIds)
            ->where('category_id', '!=', 6)
            ->orderBy('name')
            ->get();
    
        $usersForTable = $usersForFilter;
        if ($selectedUser !== 'all') {
            $usersForTable = $usersForFilter->where('id', (int)$selectedUser)->values();
        }
    
        $q = $def['extra'](
            $model::query()->whereBetween($dateCol, [$from, $to])
        );
    
        if ($selectedUser !== 'all') {
            $q->where($userCol, $selectedUser);
        } else {
            if ($usersForFilter->isNotEmpty()) {
                $q->whereIn($userCol, $usersForFilter->pluck('id'));
            }
        }
    
        $rows = $q->selectRaw("DATE($dateCol) as date, $userCol as user_id, COUNT(*) as total")
            ->groupBy('date', 'user_id')
            ->orderBy('date')
            ->get();
    
        $matrix = [];
        foreach ($rows as $r) {
            $matrix[$r->date][$r->user_id] = (int) $r->total;
        }
    
        return view('performance_analysis.report', [
            'reportKey'       => $report,
            'reportLabel'     => $def['label'],
            'reportModelName' => $def['modelName'],
            'usersForFilter'  => $usersForFilter,
            'usersForTable'   => $usersForTable,
            'allDates'        => $allDates,
            'matrix'          => $matrix,
            'from'            => $from,
            'to'              => $to,
            'selectedUser'    => $selectedUser,
        ]);
    } 
}
