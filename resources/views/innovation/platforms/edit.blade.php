@extends('layouts.admin')

@php($title = 'تعديل منصة')
@php($pageHeading = 'تعديل منصة')

@section('content')
<div class="bg-white rounded-xl shadow p-6 max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i data-feather="edit" class="w-6 h-6 text-indigo-600"></i>
        تعديل المنصة: {{ \Illuminate\Support\Str::limit($platform->platform_name, 60, '...') }}
    </h2>      

  <form method="POST" action="{{ route('innovation.platforms.update', $platform) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block font-medium text-sm text-gray-700 mb-1">
          الجهة الحكومية <span class="text-red-600">*</span>
        </label>
        <select id="government_entity_id" name="government_entity_id"
                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2">
          <option value="">اختر الجهة</option>
          @foreach($entities as $entity)
            <option value="{{ $entity->id }}" {{ old('government_entity_id', $platform->government_entity_id) == $entity->id ? 'selected' : '' }}>
              {{ $entity->name .' - '. $entity->uuid }}
            </option>
          @endforeach
        </select>
        @error('government_entity_id')
          <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block font-medium text-sm text-gray-700 mb-1">
          اسم المنصة <span class="text-red-600">*</span>
        </label>
        <textarea name="platform_name" rows="1"
                  class="autogrow w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2 resize-none overflow-hidden"
                  placeholder="أدخل اسم المنصة">{{ old('platform_name', $platform->platform_name) }}</textarea>
        @error('platform_name')
          <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block font-medium text-sm text-gray-700 mb-1">وظيفة المنصة</label>
        <textarea name="platform_function" rows="1"
                  class="autogrow w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2 resize-none overflow-hidden"
                  placeholder="أدخل وظيفة المنصة">{{ old('platform_function', $platform->platform_function) }}</textarea>
        @error('platform_function')
          <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block font-medium text-sm text-gray-700 mb-1">رابط المنصة</label>
        <input type="url"
               name="platform_url"
               value="{{ old('platform_url', $platform->platform_url) }}"
               class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2"
               placeholder="https://example.com">
        @error('platform_url')
          <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div>
      <label class="block font-medium text-sm text-gray-700 mb-1">التفاصيل</label>
      <textarea name="details"
                class="rich-editor"
                placeholder="أدخل تفاصيل عن المنصة">{{ old('details', $platform->details) }}</textarea>
      @error('details')
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div class="flex justify-end gap-3">
      <a href="{{ route('innovation.platforms.index') }}"
         class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">إلغاء</a>
      <button type="submit"
              class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">تحديث المنصة</button>
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

  const autoGrow = (el) => {
    el.style.height = 'auto';
    el.style.height = (el.scrollHeight) + 'px';
  };

  document.querySelectorAll('textarea.autogrow').forEach((el) => {
    autoGrow(el);
    el.addEventListener('input', () => autoGrow(el));
  });
});
</script>
@endsection
