@extends('layouts.admin')

@php($title = 'المستخدمون')
@php($pageHeading = 'إدارة المستخدمين')

@section('content')
<div class="bg-white shadow rounded-xl p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i data-feather="users" class="w-6 h-6 text-indigo-600"></i>
            إدارة المستخدمين
        </h2>

        {{-- زر إضافة مستخدم --}}
        @can('create users')
        <a href="{{ route('users.create') }}"
           class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
           + إضافة مستخدم
        </a>
        @endcan
    </div>

    {{-- رسالة نجاح --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full table-fixed border-collapse bg-white rounded-lg shadow">
          <thead>
            <tr class="bg-gray-100 text-right text-gray-700 text-sm uppercase">
              <th class="px-4 py-3 w-1/12">#</th>
              <th class="px-4 py-3 w-2/12">الاسم</th>
              <th class="px-4 py-3 w-2/12">البريد الإلكتروني</th>
              <th class="px-4 py-3 w-2/12">الهاتف</th>
              <th class="px-4 py-3 w-2/12">الفئة</th>
              <th class="px-4 py-3 w-1/12">الحالة</th>
              <th class="px-4 py-3 w-2/12">الأدوار</th>
              <th class="px-4 py-3 w-2/12 text-center">الإجراءات</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 text-sm">
            @forelse($users as $user)
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-center">{{ $loop->iteration }}</td>
                <td class="px-4 py-3 font-semibold truncate">{{ $user->name }}</td>
                <td class="px-4 py-3 truncate">{{ $user->email }}</td>
                <td class="px-4 py-3 truncate">{{ $user->phone ?? '-' }}</td>
                <td class="px-4 py-3">{{ $user->category?->name ?? '-' }}</td>
                <td class="px-4 py-3">
                  <span class="px-2 py-1 text-xs font-semibold rounded-full
                    {{ $user->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $user->status == 'active' ? 'نشط' : 'غير نشط' }}
                  </span>
                </td>
                <td class="px-4 py-3">
                  <div class="flex flex-wrap gap-1 max-h-20 overflow-y-auto">
                    @forelse($user->roles as $role)
                      <span class="inline-block bg-indigo-50 text-indigo-700 text-xs font-medium px-2 py-1 rounded truncate">
                        {{ $role->name }}
                      </span>
                    @empty
                      <span class="text-gray-400 italic">لا توجد أدوار</span>
                    @endforelse
                  </div>
                </td>
                <td class="px-4 py-3 text-center flex justify-center gap-2">
                  {{-- تعديل --}}
                  @can('update users')
                  <a href="{{ route('users.edit',$user) }}"
                     class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-xs">تعديل</a>
                  @endcan
                  {{-- حذف --}}
                  @can('delete users')
                  <form method="POST" action="{{ route('users.destroy',$user) }}" onsubmit="return confirm('هل أنت متأكد؟')">
                    @csrf @method('DELETE')
                    <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs">حذف</button>
                  </form>
                  @endcan
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="px-4 py-6 text-center text-gray-500 italic">لا يوجد مستخدمون.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>


    {{-- ترقيم الصفحات --}}
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
