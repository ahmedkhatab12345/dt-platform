@extends('layouts.admin')

@php($title = 'الأدوار')
@php($pageHeading = 'إدارة الأدوار')

@section('content')
<div class="bg-white rounded-xl shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i data-feather="shield" class="w-6 h-6 text-indigo-600"></i>
            إدارة الأدوار
        </h2>

        {{-- إضافة دور --}}
        @can('create roles')
        <a href="{{ route('roles.create') }}"
           class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
           + إضافة دور
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
        <table class="w-full border-collapse bg-white rounded-lg shadow">
            <thead>
                <tr class="bg-gray-100 text-right text-gray-700 text-sm uppercase">
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">الدور</th>
                    <th class="px-4 py-3">الصلاحيات</th>
                    <th class="px-4 py-3 text-left">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm">
                @forelse($roles as $role)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $role->id }}</td>
                        <td class="px-4 py-3 font-semibold text-gray-800">{{ ucfirst($role->name) }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                              @forelse($role->permissions as $perm)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                             bg-indigo-100 text-indigo-700 border border-indigo-300">
                                  <i data-feather="check" class="w-3 h-3 ml-1"></i>
                                  {{ $perm->name }}
                                </span>
                              @empty
                                <span class="text-gray-400 italic">لا توجد صلاحيات</span>
                              @endforelse
                            </div>
                        </td>
                        <td class="px-4 py-3 text-left flex justify-start gap-2">

                            {{-- تعديل --}}
                            @can('update roles')
                            <a href="{{ route('roles.edit', $role->id) }}"
                               class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-xs">
                                تعديل
                            </a>
                            @endcan

                            {{-- حذف --}}
                            @can('delete roles')
                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                  onsubmit="return confirm('هل أنت متأكد من الحذف؟')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs">
                                    حذف
                                </button>
                            </form>
                            @endcan

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500 italic">
                            لا توجد أدوار مسجلة.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
