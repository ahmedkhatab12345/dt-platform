@extends('layouts.admin')

@php($title = 'المحاور')
@php($pageHeading = 'المحاور')

@section('content')
<div class="bg-white shadow rounded-xl p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
          <i data-feather="columns" class="w-6 h-6 text-indigo-600"></i>
          إدارة المحاور
        </h2>

        <div class="flex items-center gap-3">
            {{-- فورم البحث --}}
            <form method="GET" action="{{ route('pillars.index') }}" class="flex items-center gap-2">
                {{-- لو جاية من منظور --}}
                @if(request('perspective_id'))
                    <input type="hidden" name="perspective_id" value="{{ request('perspective_id') }}">
                @endif

                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="ابحث بالـ UUID أو الاسم"
                       class="border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <button type="submit"
                        class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
                    بحث
                </button>
            </form>

            {{-- زر الإضافة --}}
            @can('create pillars')
            <a href="{{ route('pillars.create') }}"
               class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
               + إضافة محور
            </a>
            @endcan
        </div>
    </div>


  {{-- رسالة نجاح --}}
  @if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded text-sm">{{ session('success') }}</div>
  @endif

  <div class="overflow-x-auto">
    <table class="w-full table-fixed border-collapse bg-white rounded-lg shadow">
      <thead>
        <tr class="bg-gray-100 text-right text-gray-700 text-sm uppercase">
          <th class="px-4 py-3 w-1/6">المعرف الفريد (UUID)</th>
          <th class="px-4 py-3 w-1/5">الاسم</th>
          <th class="px-4 py-3 w-1/5">المنظور</th>
          <th class="px-4 py-3 w-1/3">الوصف</th>
          <th class="px-4 py-3 w-1/6 text-center">الإجراءات</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 text-sm">
        @forelse($pillars as $pl)
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-3 font-mono text-xs text-gray-600 truncate">{{ $pl->uuid }}</td>
            <td class="px-4 py-3 font-semibold text-gray-800 truncate">{{ $pl->name }}</td>
            <td class="px-4 py-3 truncate">{{ $pl->perspective?->name }}</td>
            <td class="px-4 py-3 truncate">{{ $pl->description ?: '-' }}</td>
            <td class="px-4 py-3 text-center whitespace-nowrap flex justify-center gap-2">

                {{-- عرض المعايير --}}
                @can('read standards')
                <a href="{{ route('standards.index', ['pillar_id' => $pl->id]) }}"
                   class="flex items-center gap-1 px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-xs">
                   عرض المعايير
                   <span class="ml-1 px-2 py-0.5 bg-white text-indigo-700 rounded-full text-[10px] font-bold">
                     {{ $pl->standards()->count() }}
                   </span>
                </a>
                @endcan

                {{-- تعديل --}}
                @can('update pillars')
                <a href="{{ route('pillars.edit',$pl) }}"
                   class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-xs">
                   تعديل
                </a>
                @endcan

                {{-- حذف --}}
                @can('delete pillars')
                <form method="POST" action="{{ route('pillars.destroy',$pl) }}"
                      onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                  @csrf @method('DELETE')
                  <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs">
                    حذف
                  </button>
                </form>
                @endcan

            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-4 py-6 text-center text-gray-500 italic">لا توجد محاور.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $pillars->links() }}</div>
</div>
@endsection
