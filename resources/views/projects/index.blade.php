@extends('layouts.admin')

@php($title = 'المشاريع')
@php($pageHeading = 'إدارة المشاريع')

@section('content')
<div class="bg-white rounded-xl shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i data-feather="folder" class="w-6 h-6 text-indigo-600"></i>
            المشاريع
        </h2>

        {{-- زر إضافة مشروع --}}
        @can('create projects')
        <a href="{{ route('projects.create') }}"
           class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
           + إضافة مشروع
        </a>
        @endcan
    </div>

    {{-- رسائل النجاح --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- جدول المشاريع --}}
    <div class="overflow-x-auto">
        <table class="w-full border-collapse bg-white rounded-lg shadow text-sm">
            <thead>
                <tr class="bg-gray-100 text-gray-700 text-right uppercase text-xs">
                    <th class="px-4 py-3 w-1/6">الاسم</th>
                    <th class="px-4 py-3 w-1/6">الإدارة</th>
                    <th class="px-4 py-3 w-1/6">الجهة الحكومية</th>
                    <th class="px-4 py-3 w-1/6">المعيار</th>
                    <th class="px-4 py-3 w-1/6">الفترة</th>
                    <th class="px-4 py-3 w-1/6">الميزانية</th>
                    <th class="px-4 py-3 w-1/6">الحالة</th>
                    <th class="px-4 py-3 w-1/6 text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($projects as $project)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold text-gray-800 truncate">{{ $project->name }}</td>
                        <td class="px-4 py-3 truncate">{{ $project->department }}</td>
                        <td class="px-4 py-3 truncate">{{ $project->governmentEntity?->name }}</td>
                        <td class="px-4 py-3 truncate">{{ $project->standard?->name }}</td>
                        <td class="px-4 py-3 truncate">
                            {{ optional($project->start_date)->format('Y-m-d') ?? '—' }}
                            -
                            {{ optional($project->end_date)->format('Y-m-d') ?? '—' }}
                        </td>                        
                        <td class="px-4 py-3 truncate">{{ number_format($project->budget,2) }} ريال</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded
                                @if($project->status === 'pending') bg-yellow-100 text-yellow-700
                                @elseif($project->status === 'active') bg-green-100 text-green-700
                                @elseif($project->status === 'completed') bg-blue-100 text-blue-700
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ \App\Models\Project::getStatuses()[$project->status] ?? $project->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center flex justify-center gap-2">

                            {{-- عرض التفاصيل --}}
                            @can('read projects')
                            <a href="{{ route('projects.show', $project) }}"
                               class="px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-xs">
                               تفاصيل
                            </a>
                            @endcan

                            {{-- تعديل --}}
                            @can('update projects')
                            <a href="{{ route('projects.edit', $project) }}"
                               class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-xs">
                               تعديل
                            </a>
                            @endcan

                            {{-- حذف --}}
                            @can('delete projects')
                            <form method="POST" action="{{ route('projects.destroy', $project) }}"
                                  onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs">
                                    حذف
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500 italic">
                            لا توجد مشاريع حتى الآن.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $projects->links() }}
    </div>
</div>
@endsection
