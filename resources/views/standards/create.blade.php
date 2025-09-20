@extends('layouts.admin')

@php($title = 'إضافة معيار')
@php($pageHeading = 'إضافة معيار')

@section('content')
<div class="bg-white shadow rounded-xl p-6 max-w-xl mx-auto">
  <h2 class="text-2xl font-bold text-gray-800 mb-6">إضافة معيار</h2>

  @if ($errors->any())
    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
      <ul class="list-disc list-inside text-sm">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <form method="POST" action="{{ route('standards.store') }}" class="space-y-5">
    @csrf

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">المعرف الفريد (UUID)</label>
      <input type="text" name="uuid" value="{{ old('uuid') }}" required
             class="w-full border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">الركيزة</label>
      <select name="pillar_id" required
              class="w-full border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
        @foreach($pillars as $pl)
          <option value="{{ $pl->id }}" {{ old('pillar_id')==$pl->id?'selected':'' }}>
            {{ $pl->name }} — ({{ $pl->perspective?->name }})
          </option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
      <input type="text" name="name" value="{{ old('name') }}" required
             class="w-full border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">المعايير</label>
      <textarea name="criteria" rows="3"
                class="w-full border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">{{ old('criteria') }}</textarea>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">الوزن (0-100)</label>
      <input type="number" step="0.01" min="0" max="100" name="weight" value="{{ old('weight',0) }}"
             class="w-full border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <div class="flex justify-end gap-3">
      <a href="{{ route('standards.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">إلغاء</a>
      <button class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">حفظ</button>
    </div>
  </form>
</div>
@endsection
