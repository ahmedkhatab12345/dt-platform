@extends('layouts.admin')

@php($title = 'إضافة أداة')
@php($pageHeading = 'إضافة أداة')

@section('content')
<div class="bg-white shadow rounded-xl p-6 max-w-xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">إضافة أداة</h2>

    <form method="POST" action="{{ route('tools.store') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                   required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
            <textarea name="description"
                      class="w-full border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                      rows="3">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">الرابط</label>
            <input type="url" name="link" value="{{ old('link') }}"
                   class="w-full border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('tools.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                إلغاء
            </a>
            <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
                حفظ
            </button>
        </div>
    </form>
</div>
@endsection
