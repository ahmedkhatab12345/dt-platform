@extends('layouts.admin')

@php($title = 'الملف الشخصي')
@php($pageHeading = 'الملف الشخصي')

@section('content')
  <div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white shadow rounded-xl p-6">
      <h2 class="text-lg font-semibold text-gray-800 mb-4">معلومات الحساب</h2>
      <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('PATCH')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">الاسم الكامل</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
          <input type="email" name="email" value="{{ old('email', $user->email) }}"
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
          @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">رقم الجوال</label>
          <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
          @error('phone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور الحالية</label>
            <input type="password" name="current_password"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            @error('current_password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور الجديدة</label>
            <input type="password" name="password"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">تأكيد كلمة المرور الجديدة</label>
            <input type="password" name="password_confirmation"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <div class="pt-2">
          <button class="inline-flex items-center rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-5 py-2.5">
            حفظ التغييرات
          </button>
        </div>
      </form>
    </div>

  </div>
@endsection
