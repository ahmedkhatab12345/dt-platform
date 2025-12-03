@extends('layouts.admin')

@php($title = 'إنشاء فعالية/مبادرة')
@php($pageHeading = 'إنشاء فعالية/مبادرة')

@section('content')
<div class="bg-white rounded-xl shadow p-6 max-w-4xl mx-auto">
  <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
    <i data-feather="plus-circle" class="w-6 h-6 text-indigo-600"></i>
    إضافة فعالية/مبادرة جديدة
  </h2>
  @if (session('success'))
  <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
      {{ session('success') }}
  </div>
  @endif

  <form method="POST" action="{{ route('innovation.events.store') }}" class="space-y-6">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      {{-- الجهة الحكومية --}}
      <div>
        <label class="block font-medium text-sm text-gray-700 mb-1">
          الجهة الحكومية <span class="text-red-600">*</span>
        </label>
        <select id="government_entity_id" name="government_entity_id"
                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2">
          <option value="">اختر الجهة</option>
          @foreach($entities as $entity)
            <option value="{{ $entity->id }}" {{ old('government_entity_id') == $entity->id ? 'selected' : '' }}>
              {{ $entity->name .' - '. $entity->uuid }}
            </option>
          @endforeach
        </select>
        @error('government_entity_id')
          <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- عنوان الفعالية --}}
      <div>
        <label class="block font-medium text-sm text-gray-700 mb-1">
          عنوان الفعالية/المبادرة <span class="text-red-600">*</span>
        </label>
        <input type="text" name="event_title" value="{{ old('event_title') }}"
               class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2"
               placeholder="أدخل عنوان الفعالية أو المبادرة">
        @error('event_title')
          <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>
    </div>

    {{-- التفاصيل --}}
    <div>
      <label class="block font-medium text-sm text-gray-700 mb-1">التفاصيل</label>
      <textarea name="details"
                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2"
                rows="6"
                placeholder="أدخل تفاصيل الفعالية أو المبادرة...">{{ old('details') }}</textarea>
      @error('details')
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- أزرار التحكم --}}
    <div class="flex justify-end gap-3">
      <a href="{{ route('innovation.events.index') }}"
         class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
         إلغاء
      </a>
      <button type="submit"
              class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        حفظ الفعالية/المبادرة
      </button>
    </div>
  </form>
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
        return `<div>${escape(data.text)}</div>`;
      }
    }
  });
});
</script>
@endsection