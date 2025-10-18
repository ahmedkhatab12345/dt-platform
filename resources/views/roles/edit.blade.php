@extends('layouts.admin')

@php($title = 'تعديل الدور')
@php($pageHeading = 'تعديل الدور')

@section('content')
<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i data-feather="edit" class="w-6 h-6 text-indigo-600"></i>
        تعديل الدور: {{ $role->name }}
    </h2>

    {{-- عرض الأخطاء --}}
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('roles.update', $role->id) }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- تعديل اسم الدور --}}
        <div>
            <label class="block font-medium text-sm text-gray-700 mb-1">اسم الدور</label>
            <input type="text"
                   name="name"
                   value="{{ old('name', $role->name) }}"
                   class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2"
                   required>
        </div>

        {{-- قائمة الصلاحيات --}}
        <div>
            <label class="block font-medium text-sm text-gray-700 mb-2">تعيين الصلاحيات</label>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($permissions as $permission)
                    <label class="flex items-center space-x-2 space-x-reverse bg-gray-50 p-2 rounded-lg hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox"
                               name="permissions[]"
                               value="{{ $permission->name }}"
                               {{ $role->permissions->contains('id', $permission->id) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-gray-700">{{ ucwords($permission->name) }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- الأزرار --}}
        <div class="flex justify-end">
            <a href="{{ route('roles.index') }}"
               class="px-4 py-2 ml-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
               إلغاء
            </a>
            <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                تحديث الدور
            </button>
        </div>
    </form>
</div>
@endsection
