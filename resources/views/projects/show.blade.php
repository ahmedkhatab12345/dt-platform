@extends('layouts.admin')

@php($title = 'عرض المشروع')
@php($pageHeading = 'تفاصيل المشروع')

@section('content')
<div class="bg-white rounded-xl shadow p-6 max-w-5xl mx-auto">

    {{-- العنوان --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i data-feather="folder" class="w-6 h-6 text-indigo-600"></i>
            المشروع: {{ $project->name }}
        </h2>

        <div class="flex gap-2">
            @can('update projects')
            <a href="{{ route('projects.edit', $project) }}"
               class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 text-sm">
               تعديل
            </a>
            @endcan
            <a href="{{ route('projects.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm">
               رجوع للقائمة
            </a>
        </div>
    </div>

    {{-- بيانات عامة --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600">الإدارة</p>
            <p class="font-semibold text-gray-800">{{ $project->department }}</p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600">الجهة الحكومية</p>
            <p class="font-semibold text-gray-800">{{ $project->governmentEntity?->name }}</p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600">المعيار</p>
            <p class="font-semibold text-gray-800">{{ $project->standard?->name }}</p>
        </div>
        
        <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600">الفترة</p>
            <p class="font-semibold text-gray-800">
                {{ optional($project->start_date)->format('Y-m-d') ?? '—' }}
                -
                {{ optional($project->end_date)->format('Y-m-d') ?? '—' }}
            </p>            
        </div>
        <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600">الميزانية</p>
            <p class="font-semibold text-gray-800">{{ number_format($project->budget, 2) }} ريال</p>
        </div>
        <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600">الحالة</p>
            <span class="inline-block px-3 py-1 text-sm rounded
                @if($project->status === 'pending') bg-yellow-100 text-yellow-700
                @elseif($project->status === 'active') bg-green-100 text-green-700
                @elseif($project->status === 'completed') bg-blue-100 text-blue-700
                @else bg-gray-100 text-gray-600 @endif">
                {{ \App\Models\Project::getStatuses()[$project->status] ?? $project->status }}
            </span>
        </div>
    </div>

    {{-- نبذة --}}
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center gap-2">
            <i data-feather="info" class="w-5 h-5 text-indigo-600"></i> نبذة عن المشروع
        </h3>
        <div class="p-4 bg-gray-50 rounded-lg text-gray-700 leading-relaxed">
            {{ $project->overview ?: 'لا توجد نبذة مسجلة.' }}
        </div>
    </div>

    {{-- المؤشرات --}}
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center gap-2">
            <i data-feather="activity" class="w-5 h-5 text-indigo-600"></i> المؤشرات
        </h3>
        @if($project->indicators && count($project->indicators) > 0)
            <ul class="list-disc list-inside bg-gray-50 p-4 rounded-lg space-y-1 text-gray-700">
                @foreach($project->indicators as $ind)
                    <li>{{ $ind }}</li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500 italic">لا توجد مؤشرات.</p>
        @endif
    </div>

    {{-- المخرجات النهائية --}}
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center gap-2">
            <i data-feather="check-circle" class="w-5 h-5 text-indigo-600"></i> المخرجات النهائية
        </h3>
        @if($project->final_deliverables && count($project->final_deliverables) > 0)
            <ul class="list-disc list-inside bg-gray-50 p-4 rounded-lg space-y-1 text-gray-700">
                @foreach($project->final_deliverables as $del)
                    <li>{{ $del }}</li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500 italic">لا توجد مخرجات نهائية.</p>
        @endif
    </div>

    {{-- الأنشطة --}}
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center gap-2">
            <i data-feather="list" class="w-5 h-5 text-indigo-600"></i> الأنشطة
        </h3>
        @if($project->activities && count($project->activities) > 0)
            <ul class="list-disc list-inside bg-gray-50 p-4 rounded-lg space-y-1 text-gray-700">
                @foreach($project->activities as $act)
                    <li>{{ $act }}</li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500 italic">لا توجد أنشطة.</p>
        @endif
    </div>

</div>
@endsection
