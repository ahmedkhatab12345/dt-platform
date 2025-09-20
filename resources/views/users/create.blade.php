@extends('layouts.admin')

@php($title = 'إضافة مستخدم')
@php($pageHeading = 'إضافة مستخدم')

@section('content')
<div class="bg-white shadow rounded-xl p-6 max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i data-feather="user-plus" class="w-6 h-6 text-indigo-600"></i>
        إضافة مستخدم جديد
    </h2>

    {{-- رسائل الأخطاء --}}
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('users.store') }}" class="space-y-5">
        @csrf

        {{-- الاسم --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2"
                   required>
        </div>

        {{-- البريد الإلكتروني --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2"
                   required>
        </div>

        {{-- الهاتف --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">الهاتف</label>
            <input type="text" name="phone" value="{{ old('phone') }}"
                   class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2">
        </div>

        {{-- الفئة --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">الفئة</label>
            <select name="category_id"
                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2">
                <option value="">-- اختر الفئة --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- الحالة --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
            <select name="status"
                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2">
                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>نشط</option>
                <option value="inactive" {{ old('status', 'active') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
            </select>
        </div>

        {{-- كلمة المرور --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور</label>
            <input type="password" name="password"
                   class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2"
                   required>
        </div>

        {{-- الأدوار --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">إسناد الأدوار</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($roles as $role)
                    <label class="flex items-center space-x-2 bg-gray-50 p-2 rounded-lg hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-gray-700">{{ ucfirst($role->name) }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- الأزرار --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('users.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">إلغاء</a>
            <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
                حفظ المستخدم
            </button>
        </div>
    </form>
</div>
@endsection
