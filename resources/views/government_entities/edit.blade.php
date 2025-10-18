@extends('layouts.admin')

@php($title = 'تعديل جهة حكومية')
@php($pageHeading = 'تعديل جهة حكومية')

@section('content')
<div class="bg-white shadow rounded-xl p-6 max-w-xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i data-feather="edit" class="w-6 h-6 text-indigo-600"></i>
        تعديل جهة حكومية
    </h2>

    {{-- رسائل التحقق من الأخطاء --}}
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('government_entities.update', $entity) }}" class="space-y-5">
        @csrf
        @method('PUT')

        {{-- الاسم --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
            <input type="text" name="name"
                   value="{{ old('name', $entity->name) }}"
                   class="w-full border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                   required>
        </div>

        {{-- التصنيف --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">التصنيف</label>
            <select name="classification"
                    class="w-full border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
                @foreach($classifications as $classification)
                    <option value="{{ $classification->value }}"
                        {{ $entity->classification->value === $classification->value ? 'selected' : '' }}>
                        {{ $classification->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- الأزرار --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('government_entities.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
               إلغاء
            </a>
            <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
                تحديث
            </button>
        </div>
    </form>
</div>
@endsection
