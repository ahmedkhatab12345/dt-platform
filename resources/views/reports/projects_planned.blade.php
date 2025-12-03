@extends('layouts.admin')

@php($title = 'تقرير المشاريع المخطط لها')
@php($pageHeading = 'تقرير المشاريع المخطط لها ذات الميزانية')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

  {{-- العنوان + زر الرجوع --}}
  <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold flex items-center gap-2 text-gray-800">
          <i data-feather="bar-chart-2" class="w-6 h-6 text-indigo-600"></i>
          تقرير المشاريع المخطط لها
      </h2>

      <a href="{{ route('projects.index') }}"
         class="px-4 py-2 bg-gray-600 text-white rounded-lg shadow hover:bg-gray-700 flex items-center gap-2">
         <i data-feather="arrow-right" class="w-4 h-4"></i>
         رجوع
      </a>
  </div>

  {{-- البحث --}}
  <form method="GET" action="{{ route('reports.projects_planned') }}" class="mb-6">
    <div class="flex flex-col md:flex-row items-start md:items-end gap-4">

      {{-- البحث بالاسم --}}
      <div>
        <label for="q" class="block text-sm text-gray-700 mb-1">بحث بالاسم</label>
        <input type="text" id="q" name="q" value="{{ request('q') }}"
               class="w-64 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
               placeholder="ابحث باسم المشروع...">
      </div>

      {{-- الجهة الحكومية + TomSelect --}}
      <div>
        <label for="government_entity_id" class="block text-sm text-gray-700 mb-1">الجهة الحكومية</label>
        <select id="government_entity_id" name="government_entity_id"
                class="w-64 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
          <option value="">-- الكل --</option>
          @foreach($entities as $entity)
            <option value="{{ $entity->id }}"
                @selected(request('government_entity_id') == $entity->id)>
              {{ $entity->name }} - {{ $entity->uuid }}
            </option>
          @endforeach
        </select>
      </div>

      <a href="{{ route('reports.projects_planned.export') }}"
        class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 flex items-center gap-2">
        <i data-feather="download" class="w-4 h-4"></i>
        تصدير Excel
      </a>
      {{-- زر البحث --}}
      <div>
        <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
          بحث / تصفية
        </button>

        @if(request()->filled('q') || request()->filled('government_entity_id'))
          <a href="{{ route('reports.projects_planned') }}"
             class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 ml-2">
            إعادة تعيين
          </a>
        @endif
      </div>

    </div>
  </form>

  {{-- الجدول --}}
  <div class="overflow-x-auto">
    <table class="w-full table-fixed border-collapse bg-white rounded-lg shadow text-sm">
      <thead>
        <tr class="bg-gray-100 text-gray-700 uppercase text-xs">
          <th class="px-4 py-3 w-1/4">اسم المشروع</th>
          <th class="px-4 py-3 w-1/4">الجهة الحكومية</th>
          <th class="px-4 py-3 w-1/4">تاريخ البدء</th>
          <th class="px-4 py-3 w-1/4">الميزانية</th>
        </tr>
      </thead>

      <tbody class="divide-y divide-gray-200">
        @forelse($projects as $p)
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-3">{{ $p->name }}</td>
            <td class="px-4 py-3">{{ $p->governmentEntity->name ?? '—' }}</td>
            <td class="px-4 py-3">
              {{ $p->start_date ? $p->start_date->format('Y-m-d') : '—' }}
            </td>
            <td class="px-4 py-3">
              {{ number_format($p->budget, 2) }} ريال
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="px-4 py-6 text-center text-gray-500 italic">
              لا توجد بيانات مطابقة.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- pagination --}}
  <div class="mt-6">
    {{ $projects->links() }}
  </div>

</div>

{{-- TomSelect للجهة --}}
<script>
  document.addEventListener("DOMContentLoaded", function () {
    if (window.feather) { window.feather.replace(); }

    new TomSelect("#government_entity_id", {
      create: false,
      placeholder: "ابحث بالاسم أو الكود...",
      sortField: { field: "text", direction: "asc" },
      maxOptions: 2000
    });
  });
</script>

@endsection
