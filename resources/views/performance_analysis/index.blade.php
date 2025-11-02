@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto bg-gradient-to-b from-indigo-50 to-white rounded-2xl shadow-xl p-8 space-y-10">

    {{-- 🔹 العنوان --}}
    <div class="flex justify-between items-center flex-wrap gap-3 border-b pb-4">
        <h2 class="text-3xl font-extrabold text-gray-800 flex items-center gap-2">
            <i data-feather="bar-chart-2" class="w-7 h-7 text-indigo-600"></i>
            لوحة تحليل الأداء العام
        </h2>

        <a href="{{ route('performance_analysis.export', request()->query()) }}"
           class="flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-2.5 rounded-xl shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-300 text-sm font-semibold">
            <i data-feather="download" class="w-4 h-4"></i>
            تنزيل تقرير PDF
        </a>
    </div>

    {{-- 🔍 الفلاتر --}}
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm text-gray-700 mb-1 font-medium">المستخدم</label>
            <select name="user_id" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">الكل</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>
                        {{ $user->name ?? $user->email }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm text-gray-700 mb-1 font-medium">من تاريخ</label>
            <input 
                type="date" 
                name="from_date" 
                value="{{ old('from_date', now()->toDateString()) }}" 
                class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
        </div>
        
        <div>
            <label class="block text-sm text-gray-700 mb-1 font-medium">إلى تاريخ</label>
            <input 
                type="date" 
                name="to_date" 
                value="{{ old('to_date', now()->toDateString()) }}" 
                class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
        </div>        
        <div class="flex items-end">
            <button type="submit" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 w-full flex items-center justify-center gap-2 font-semibold shadow-md transition-all duration-200">
                <i data-feather="search" class="w-4 h-4"></i>
                بحث
            </button>
        </div>
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 mt-8">

        <div class="rounded-2xl p-6 shadow-xl bg-gradient-to-r from-green-400 to-green-600 hover:from-green-500 hover:to-green-700 transition transform hover:-translate-y-1 hover:scale-[1.02] text-white">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-lg font-medium text-white/90">عدد الإضافات</p>
                    <h3 class="text-6xl font-extrabold mt-2">{{ $summary['created'] ?? 0 }}</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-full">
                    <i data-feather="plus" class="w-10 h-10"></i>
                </div>
            </div>
        </div>

        <div class="rounded-2xl p-6 shadow-xl bg-gradient-to-r from-amber-400 to-amber-600 hover:from-amber-500 hover:to-amber-700 transition transform hover:-translate-y-1 hover:scale-[1.02] text-white">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-lg font-medium text-white/90">عدد التعديلات</p>
                    <h3 class="text-6xl font-extrabold mt-2">{{ $summary['updated'] ?? 0 }}</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-full">
                    <i data-feather="edit-3" class="w-10 h-10"></i>
                </div>
            </div>
        </div>

        <div class="rounded-2xl p-6 shadow-xl bg-gradient-to-r from-rose-400 to-rose-600 hover:from-rose-500 hover:to-rose-700 transition transform hover:-translate-y-1 hover:scale-[1.02] text-white">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-lg font-medium text-white/90">عدد المحذوفات</p>
                    <h3 class="text-6xl font-extrabold mt-2">{{ $summary['deleted'] ?? 0 }}</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-full">
                    <i data-feather="trash-2" class="w-10 h-10"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mt-10">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-5 text-lg font-semibold">
            سجل النشاطات التفصيلي
        </div>

        <table class="w-full text-sm text-gray-700">
            <thead class="bg-gray-100 text-gray-800 border-b font-semibold">
                <tr class="text-center">
                    <th class="py-3 px-4">#</th>
                    <th class="py-3 px-4">المستخدم</th>
                    <th class="py-3 px-4">العملية</th>
                    <th class="py-3 px-4">الكيان</th>
                    <th class="py-3 px-4">الوصف</th>
                    <th class="py-3 px-4">التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    @php
                        $modelNames = [
                            'government_entities' => 'الجهات الحكومية',
                            'perspectives'        => 'المناظير',
                            'pillars'             => 'المحاور',
                            'projects'            => 'المشاريع',
                            'standards'           => 'المعايير',
                        ];
                        $modelArabic = $modelNames[$log->model] ?? $log->model;

                        $actions = [
                            'created' => ['إضافة', 'green', 'plus-circle'],
                            'updated' => ['تعديل', 'amber', 'edit-3'],
                            'deleted' => ['حذف', 'rose', 'trash-2'],
                        ];
                        [$text, $color, $icon] = $actions[$log->action] ?? ['غير معروف', 'gray', 'circle'];
                    @endphp

                    <tr class="hover:bg-indigo-50 transition">
                        <td class="py-3 px-4 text-center text-gray-500">{{ $loop->iteration }}</td>
                        <td class="py-3 px-4 text-right font-semibold">{{ $log->user?->name ?? 'غير معروف' }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-{{ $color }}-100 text-{{ $color }}-700">
                                <i data-feather="{{ $icon }}" class="w-3.5 h-3.5"></i>
                                {{ $text }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">{{ $modelArabic }}</td>
                        <td class="py-3 px-4 text-right">{{ $log->description }}</td>
                        <td class="py-3 px-4 text-center text-gray-500">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-5 text-center text-gray-400">لا توجد نشاطات خلال هذه الفترة.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>feather.replace();</script>
@endsection
