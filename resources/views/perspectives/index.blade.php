@extends('layouts.admin')

@php($title = 'المناظير')
@php($pageHeading = 'المناظير')

@section('content')
<div class="bg-white shadow rounded-xl p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
          <i data-feather="layers" class="w-6 h-6 text-indigo-600"></i>
          إدارة المناظير
        </h2>

        <div class="flex items-center gap-3">
            {{-- فورم البحث --}}
            <form method="GET" action="{{ route('perspectives.index') }}" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="ابحث بالـ UUID أو الاسم"
                       class="border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <button type="submit"
                        class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
                    بحث
                </button>
            </form>

            {{-- زر الإضافة --}}
            @can('create perspectives')
            <a href="{{ route('perspectives.create') }}"
               class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
               + إضافة منظور
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
          <th class="px-4 py-3 w-1/6">الاسم</th>
          <th class="px-4 py-3 w-1/2">الوصف</th>
          <th class="px-4 py-3 w-1/6 text-right">الإجراءات</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 text-sm">
        @forelse($perspectives as $p)
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-3 font-mono text-xs text-gray-600 truncate">{{ $p->uuid }}</td>
            <td class="px-4 py-3 font-semibold text-gray-800">{{ $p->name }}</td>
            <td class="px-4 py-3 break-words">{{ $p->description ?: '-' }}</td>
            <td class="px-4 py-3 text-right flex justify-end gap-2 whitespace-nowrap">

                {{-- عرض المحاور --}}
                @can('read pillars')
                <a href="{{ route('pillars.index', ['perspective_id' => $p->id]) }}"
                   class="flex items-center gap-1 px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-xs">
                   عرض المحاور
                   <span class="ml-1 px-2 py-0.5 bg-white text-indigo-700 rounded-full text-[10px] font-bold">
                     {{ $p->pillars()->count() }}
                   </span>
                </a>
                @endcan

                {{-- تعديل --}}
                @can('update perspectives')
                <a href="{{ route('perspectives.edit',$p) }}"
                   class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-xs">
                   تعديل
                </a>
                @endcan

                {{-- حذف --}}
                @can('delete perspectives')
                <form method="POST" action="{{ route('perspectives.destroy',$p) }}"
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
            <td colspan="4" class="px-4 py-6 text-center text-gray-500 italic">لا توجد منظورات.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>


  <div class="mt-4">
    {{ $perspectives->links() }}
  </div>
</div>
@endsection
