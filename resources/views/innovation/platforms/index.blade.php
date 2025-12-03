@extends('layouts.admin')

@php($title = 'المنصات')
@php($pageHeading = 'إدارة المنصات')

@section('content')
<div class="bg-white rounded-xl shadow p-6">

  <div class="flex items-center gap-4 mb-6">
    @can('create platforms')
      <a href="{{ route('innovation.platforms.create') }}"
        class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
        + إضافة منصة
      </a>
    @endcan
  </div>

  <form method="GET" action="{{ route('innovation.platforms.index') }}" class="mb-4">
    <div class="flex flex-col md:flex-row items-start md:items-end gap-3">

      <div>
        <label for="q" class="block text-sm text-gray-700 mb-1">بحث</label>
        <input id="q" type="text" name="q" value="{{ request('q') }}"
              placeholder="اسم المنصة / الوظيفة / الرابط..."
              class="w-64 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
      </div>

      <div>
        <label for="government_entity_id" class="block text-sm text-gray-700 mb-1">الجهة الحكومية</label>
        <select id="government_entity_id" name="government_entity_id"
                class="w-64 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
          <option value="">-- الكل --</option>
          @foreach($entities as $entity)
            <option value="{{ $entity->id }}" @selected((int)request('government_entity_id') === $entity->id)>
              {{ $entity->name }} - {{ $entity->uuid }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="flex items-center gap-2 flex-shrink-0">
        <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
          بحث / تصفية
        </button>

        @if(request()->filled('q') || request()->filled('government_entity_id'))
          <a href="{{ route('innovation.platforms.index') }}"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            إعادة تعيين
          </a>
        @endif
      </div>

    </div>
  </form>

  @if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded text-sm">
      {{ session('success') }}
    </div>
  @endif

  <div class="overflow-x-auto">
    <table class="w-full table-fixed border-collapse bg-white rounded-lg shadow text-sm">
      <thead>
        <tr class="bg-gray-100 text-gray-700 text-right uppercase text-xs">
          <th class="px-4 py-3">كود الجهة</th>
          <th class="px-4 py-3">اسم الجهة</th>
          <th class="px-4 py-3">اسم المنصة</th>
          <th class="px-4 py-3">الوظيفة</th>
          <th class="px-4 py-3">الرابط</th>
          <th class="px-4 py-3">تاريخ الإنشاء</th>
          <th class="px-4 py-3">المنشئ</th>
          <th class="px-4 py-3 text-center">الإجراءات</th>
        </tr>
      </thead>

      <tbody class="divide-y divide-gray-200">
        @forelse($platforms as $row)
          <tr class="hover:bg-gray-50 align-top">
            <td class="px-4 py-3 whitespace-nowrap">{{ $row->governmentEntity?->uuid ?? '—' }}</td>
            <td class="px-4 py-3 break-words max-w-[220px]">{{ $row->governmentEntity?->name ?? '—' }}</td>

            <td class="px-4 py-3 font-semibold text-gray-800 break-words max-w-[250px]">
              {{ $row->platform_name ?? '—' }}
            </td>

            <td class="px-4 py-3 break-words max-w-[240px]">{{ $row->platform_function ?: '—' }}</td>

            <td class="px-4 py-3 break-words max-w-[260px]">
              @if(!empty($row->platform_url))
                <a href="{{ $row->platform_url }}" target="_blank" class="text-indigo-600 hover:underline">
                  {{ \Illuminate\Support\Str::limit($row->platform_url, 60, '...') }}
                </a>
              @else
                —
              @endif
            </td>

            <td class="px-4 py-3 whitespace-nowrap">{{ $row->created_at?->format('Y-m-d') ?? '—' }}</td>
            <td class="px-4 py-3 whitespace-nowrap">{{ $row->user?->name ?? '—' }}</td>

            <td class="px-4 py-3 text-center">
              <div class="flex justify-center gap-2">
                @can('update platforms')
                  <a href="{{ route('innovation.platforms.edit', $row) }}"
                    class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-xs">
                    تعديل
                  </a>
                @endcan

                @can('delete platforms')
                  <form method="POST" action="{{ route('innovation.platforms.destroy', $row) }}"
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
              لا توجد منصات حتى الآن.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $platforms->appends(request()->query())->links() }}
  </div>

</div>

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
