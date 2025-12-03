@extends('layouts.admin')

@php($title = 'تقييم الابتكار')
@php($pageHeading = 'إدارة تقييم الابتكار')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

  {{-- زر إضافة تقييم جديد --}}
  <div class="flex items-center gap-4 mb-6">
    @can('create assessments')
      <a href="{{ route('innovation.assessment.create') }}"
         class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
        + إضافة تقييم جديد
      </a>
    @endcan
  </div>

  {{-- نموذج البحث --}}
  <form method="GET" action="{{ route('innovation.assessment.index') }}" class="mb-4">
    <div class="flex flex-col md:flex-row items-start md:items-end gap-3">

      {{-- بحث --}}
      <div>
        <label for="q" class="block text-sm text-gray-700 mb-1">بحث</label>
        <input id="q" type="text" name="q" value="{{ request('q') }}"
              placeholder="الملخص / القوة / الضعف / التوصية..."
              class="w-64 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
      </div>

      {{-- الجهة --}}
      <div>
        <label for="government_entity_id" class="block text-sm text-gray-700 mb-1">الجهة الحكومية</label>
        <select id="government_entity_id" name="government_entity_id"
                class="w-64 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
          <option value="">-- الكل --</option>
          @foreach($entities as $entity)
            <option value="{{ $entity->id }}" @selected(request('government_entity_id') == $entity->id)>
              {{ $entity->name }} - {{ $entity->uuid }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- أزرار --}}
      <div class="flex items-center gap-2 flex-shrink-0">
        <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
          بحث / تصفية
        </button>

        @if(request()->filled('q') || request()->filled('government_entity_id'))
          <a href="{{ route('innovation.assessment.index') }}"
             class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            إعادة تعيين
          </a>
        @endif
      </div>

    </div>
  </form>

  {{-- رسالة نجاح --}}
  @if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded text-sm">
      {{ session('success') }}
    </div>
  @endif

  {{-- جدول التقييمات --}}
  <div class="overflow-x-auto">
    <table class="w-full table-fixed border-collapse bg-white rounded-lg shadow text-sm">
      <thead>
        <tr class="bg-gray-100 text-gray-700 text-right uppercase text-xs">
          <th class="px-4 py-3">كود الجهة</th>
          <th class="px-4 py-3">اسم الجهة</th>
          <th class="px-4 py-3">المستوى</th>
          <th class="px-4 py-3">الملخص</th>
          <th class="px-4 py-3">تاريخ الإنشاء</th>
          <th class="px-4 py-3">المنشئ</th>
          <th class="px-4 py-3 text-center">الإجراءات</th>
        </tr>
      </thead>

      <tbody class="divide-y divide-gray-200">

        @forelse($assessments as $row)
        <tr class="hover:bg-gray-50 align-top">

            {{-- كود الجهة --}}
            <td class="px-4 py-3 whitespace-nowrap">
                {{ $row->governmentEntity?->uuid ?? '—' }}
            </td>

            {{-- اسم الجهة --}}
            <td class="px-4 py-3">
                {{ $row->governmentEntity?->name ?? '—' }}
            </td>

            {{-- المستوى --}}
            <td class="px-4 py-3">
              <span class="px-2 py-1 text-white text-xs rounded 
                  @if($row->understanding_level === 'high') bg-green-600
                  @elseif($row->understanding_level === 'medium') bg-yellow-500
                  @else bg-red-600
                  @endif">
                  
                  @if($row->understanding_level === 'high')
                      محترف
                  @elseif($row->understanding_level === 'medium')
                      متوسط
                  @else
                      منخفض
                  @endif
              </span>
            </td>
          
            {{-- الملخص --}}
            <td class="px-4 py-3 break-words max-w-[320px]">
                {{ \Illuminate\Support\Str::limit($row->summary ?? '', 120, '...') ?: '—' }}
            </td>

            {{-- التاريخ --}}
            <td class="px-4 py-3 whitespace-nowrap">
                {{ $row->created_at?->format('Y-m-d') }}
            </td>

            {{-- المنشئ --}}
            <td class="px-4 py-3 whitespace-nowrap">
                {{ $row->user?->name }}
            </td>

            {{-- الإجراءات --}}
            <td class="px-4 py-3 text-center">
                <div class="flex justify-center gap-2">

                    @can('update assessments')
                      <a href="{{ route('innovation.assessment.edit', $row) }}"
                         class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-xs">
                         تعديل
                      </a>
                    @endcan

                    @can('read assessments')
                        <a href="{{ route('innovation.assessment.show', $row) }}"
                          class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs">
                            عرض
                        </a>
                    @endcan
                    
                    @can('delete assessments')
                      <form method="POST"
                            action="{{ route('innovation.assessment.destroy', $row) }}"
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
            <td colspan="7" class="px-4 py-6 text-center text-gray-500 italic">
                لا توجد تقييمات ابتكار حتى الآن.
            </td>
        </tr>
        @endforelse

      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  <div class="mt-4">
    {{ $assessments->appends(request()->query())->links() }}
  </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  new TomSelect("#government_entity_id", {
    create: false,
    sortField: { field: "text", direction: "asc" },
    placeholder: "ابحث بالاسم أو الكود...",
    maxOptions: 2000,
  });

  if (window.feather) {
    window.feather.replace();
  }
});
</script>

@endsection
