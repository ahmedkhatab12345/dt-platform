@extends('layouts.admin')

@php($title = 'تعديل تقييم ابتكار')
@php($pageHeading = 'تعديل تقييم ابتكار')

@section('content')
<div class="bg-white rounded-xl shadow p-6 max-w-4xl mx-auto">

  <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
    <i data-feather="edit" class="w-6 h-6 text-indigo-600"></i>
    تعديل تقييم الجهة: {{ $assessment->governmentEntity?->name }}
  </h2>

  <form method="POST" action="{{ route('innovation.assessment.update', $assessment) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

      {{-- الجهة --}}
      <div>
        <label class="block font-medium text-sm text-gray-700 mb-1">
          الجهة الحكومية <span class="text-red-600">*</span>
        </label>

        <select id="government_entity_id" name="government_entity_id"
                class="w-full border-gray-300 rounded-lg shadow-sm px-3 py-2">
          <option value="">اختر الجهة</option>
          @foreach($entities as $entity)
            <option value="{{ $entity->id }}"
                    @selected($assessment->government_entity_id == $entity->id)>
              {{ $entity->name }} - {{ $entity->uuid }}
            </option>
          @endforeach
        </select>

        @error('government_entity_id')
          <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- مستوى الفهم --}}
      <div>
        <label class="block font-medium text-sm text-gray-700 mb-1">
          مستوى فهم الجهة <span class="text-red-600">*</span>
        </label>
        <select name="understanding_level"
                class="w-full border-gray-300 rounded-lg shadow-sm px-3 py-2">
          <option value="high" @selected($assessment->understanding_level==='high')>محترف</option>
          <option value="medium" @selected($assessment->understanding_level==='medium')>متوسط</option>
          <option value="low" @selected($assessment->understanding_level==='low')>منخفض</option>
        </select>

        @error('understanding_level')
          <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

    </div>

    {{-- الملخص --}}
    <div>
      <label class="block font-medium text-sm text-gray-700 mb-1">الملخص</label>
      <textarea name="summary"
                class="rich-editor">{{ old('summary', $assessment->summary) }}</textarea>
      @error('summary') 
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p> 
      @enderror
    </div>

    {{-- نقاط القوة --}}
    <div>
      <label class="block font-medium text-sm text-gray-700 mb-1">نقاط القوة</label>
      <textarea name="strengths"
                class="rich-editor">{{ old('strengths', $assessment->strengths) }}</textarea>
      @error('strengths') 
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p> 
      @enderror
    </div>

    {{-- نقاط الضعف --}}
    <div>
      <label class="block font-medium text-sm text-gray-700 mb-1">نقاط الضعف</label>
      <textarea name="weaknesses"
                class="rich-editor">{{ old('weaknesses', $assessment->weaknesses) }}</textarea>
      @error('weaknesses') 
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p> 
      @enderror
    </div>

    {{-- التوصية --}}
    <div>
      <label class="block font-medium text-sm text-gray-700 mb-1">التوصية</label>
      <textarea name="recommendation"
                class="rich-editor">{{ old('recommendation', $assessment->recommendation) }}</textarea>
      @error('recommendation') 
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p> 
      @enderror
    </div>

    {{-- أزرار --}}
    <div class="flex justify-end gap-3">
      <a href="{{ route('innovation.assessment.index') }}"
         class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
        إلغاء
      </a>

      <button type="submit"
              class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
        تحديث التقييم
      </button>
    </div>

  </form>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  new TomSelect("#government_entity_id", {
    create: false,
    sortField: { field: "text", direction: "asc" },
    maxOptions: 2000,
  });
});
</script>

@endsection
