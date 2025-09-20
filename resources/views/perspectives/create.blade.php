@extends('layouts.admin')

@php($title = 'إضافة منظور')
@php($pageHeading = 'إضافة منظور')

@section('content')
<div class="bg-white shadow rounded-xl p-6 max-w-xl mx-auto">
  <h2 class="text-2xl font-bold text-gray-800 mb-6">إضافة منظور</h2>

  @if ($errors->any())
    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
      <ul class="list-disc list-inside text-sm">
        @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('perspectives.store') }}" class="space-y-5">
    @csrf

    {{-- المعرف الفريد --}}
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">المعرف الفريد (UUID)</label>
      <input type="text" name="uuid" value="{{ old('uuid') }}" required
             class="w-full border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    {{-- الاسم --}}
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
      <input type="text" name="name" value="{{ old('name') }}" required
             class="w-full border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    {{-- الوصف --}}
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
      <textarea name="description" rows="3"
                class="w-full border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
    </div>

    {{-- الأزرار --}}
    <div class="flex justify-end gap-3">
      <a href="{{ route('perspectives.index') }}"
         class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">إلغاء</a>
      <button class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">حفظ</button>
    </div>
  </form>
</div>
@endsection
