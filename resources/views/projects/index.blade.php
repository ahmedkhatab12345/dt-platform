@extends('layouts.admin')

@php($title = 'المشاريع')
@php($pageHeading = 'إدارة المشاريع')

@section('content')
<div class="bg-white rounded-xl shadow p-6">

  {{-- العنوان + زر الإضافة --}}
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
      <i data-feather="folder" class="w-6 h-6 text-indigo-600"></i>
      المشاريع
    </h2>

    @can('create projects')
      <a href="{{ route('projects.create') }}"
         class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
        + إضافة مشروع
      </a>
    @endcan
  </div>

  <form method="GET" action="{{ route('projects.index') }}" class="mb-4">
    <div class="flex flex-col md:flex-row items-start md:items-end gap-3">

      <div>
        <label for="q" class="block text-sm text-gray-700 mb-1">بحث بالاسم</label>
        <input id="q" type="text" name="q" value="{{ request('q') }}"
               placeholder="اكتب اسم المشروع..."
               class="w-64 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
      </div>

      <div>
        <label for="government_entity_id" class="block text-sm text-gray-700 mb-1">الجهة الحكومية</label>
        <select id="government_entity_id" name="government_entity_id"
                class="w-64 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
          <option value="">-- الكل --</option>
          @foreach($entities as $entity)
            <option value="{{ $entity->id }}"
                    @selected((int)request('government_entity_id') === $entity->id)>
              {{ $entity->name }} - {{ $entity->uuid }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="flex items-center gap-2">
        <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
          بحث / تصفية
        </button>

        @if(request()->filled('q') || request()->filled('government_entity_id'))
          <a href="{{ route('projects.index') }}"
             class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            إعادة تعيين
          </a>
        @endif
      </div>

    </div>
  </form>

  {{-- رسائل النجاح --}}
  @if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded text-sm">
      {{ session('success') }}
    </div>
  @endif

  {{-- جدول المشاريع --}}
<div class="overflow-x-auto">
        <table class="w-full table-fixed border-collapse bg-white rounded-lg shadow text-sm">
      <thead>
        <tr class="bg-gray-100 text-gray-700 text-right uppercase text-xs">
          <th class="px-4 py-3">الاسم</th>
          <th class="px-4 py-3">الإدارة</th>
          <th class="px-4 py-3">الجهة الحكومية</th>
          <th class="px-4 py-3">المعيار</th>
          <th class="px-4 py-3">الفترة</th>
          <th class="px-4 py-3">الميزانية</th>
          <th class="px-4 py-3">الحالة</th>
          <th class="px-4 py-3 text-center">الإجراءات</th>
        </tr>
      </thead>
  
      <tbody class="divide-y divide-gray-200">
        @forelse($projects as $project)
          <tr class="hover:bg-gray-50 align-top">
            <td class="px-4 py-3 font-semibold text-gray-800 truncate max-w-[250px]" title="{{ $project->name }}">
                {{ Str::limit($project->name, 70, '...') }}
              </td>              
            <td class="px-4 py-3 break-words max-w-[200px]">{{ $project->department ?: '—' }}</td>
            <td class="px-4 py-3 break-words max-w-[200px]">{{ $project->governmentEntity?->name ?: '—' }}</td>
            <td class="px-4 py-3 break-words max-w-[180px]">{{ $project->standard?->name ?: '—' }}</td>
            <td class="px-4 py-3 whitespace-nowrap">
              {{ optional($project->start_date)->format('Y-m-d') ?? '—' }} -
              {{ optional($project->end_date)->format('Y-m-d') ?? '—' }}
            </td>
            <td class="px-4 py-3 whitespace-nowrap">
              {{ $project->budget !== null ? number_format($project->budget, 2) . ' ريال' : '—' }}
            </td>
            <td class="px-4 py-3 whitespace-nowrap">
              <span class="px-2 py-1 text-xs rounded
                @if($project->status === 'pending' || $project->status === 'planned') bg-yellow-100 text-yellow-700
                @elseif($project->status === 'active' || $project->status === 'in_progress') bg-green-100 text-green-700
                @elseif($project->status === 'completed') bg-blue-100 text-blue-700
                @else bg-gray-100 text-gray-600 @endif">
                {{ \App\Models\Project::getStatuses()[$project->status] ?? $project->status }}
              </span>
            </td>
            <td class="px-4 py-3 text-center">
              <div class="flex justify-center gap-2">
                @can('read projects')
                  <a href="{{ route('projects.show', $project) }}"
                     class="px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-xs">
                    تفاصيل
                  </a>
                @endcan
  
                @can('update projects')
                  <a href="{{ route('projects.edit', $project) }}"
                     class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-xs">
                    تعديل
                  </a>
                @endcan
  
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
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="px-4 py-6 text-center text-gray-500 italic">
              لا توجد مشاريع حتى الآن.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  
  {{-- Pagination --}}
  <div class="mt-4">
    {{ $projects->appends(request()->query())->links() }}
  </div>

</div>

{{-- Tom Select تهيئة --}}
<script>
  document.addEventListener("DOMContentLoaded", function () {
    new TomSelect("#government_entity_id", {
      create: false,
      sortField: { field: "text", direction: "asc" },
      placeholder: "ابحث بالاسم أو الكود...",
      maxOptions: 2000,
      render: {
        option: function(data, escape) {
          return "<div>" + escape(data.text) + "</div>";
        }
      }
    });
    if (window.feather) { window.feather.replace(); }
  });
</script>
@endsection