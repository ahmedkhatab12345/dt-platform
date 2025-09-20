@extends('layouts.admin')

@php($title = 'المعايير')
@php($pageHeading = 'المعايير')

@section('content')
<div class="bg-white shadow rounded-xl p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
          <i data-feather="check-square" class="w-6 h-6 text-indigo-600"></i>
          إدارة المعايير
        </h2>

        <div class="flex items-center gap-3">
            {{-- فورم البحث --}}
            <form method="GET" action="{{ route('standards.index') }}" class="flex items-center gap-2">
                {{-- لو جاية من محور --}}
                @if(request('pillar_id'))
                    <input type="hidden" name="pillar_id" value="{{ request('pillar_id') }}">
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
            @can('create standards')
            <a href="{{ route('standards.create') }}"
               class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
               + إضافة معيار
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
          <th class="px-4 py-3 w-2/12">المعرف (UUID)</th>
          <th class="px-4 py-3 w-3/12">الاسم</th>
          <th class="px-4 py-3 w-4/12">المحور/ المنظور</th>
          <th class="px-4 py-3 w-1/12">الوزن</th>
          <th class="px-4 py-3 w-2/12 text-center">الإجراءات</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 text-sm">
        @forelse($standards as $std)
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-3 font-mono text-xs truncate">{{ $std->uuid }}</td>
            <td class="px-4 py-3 font-semibold text-gray-800 truncate">{{ $std->name }}</td>
            <td class="px-4 py-3 truncate">
              {{ $std->pillar?->name }} / {{ $std->pillar?->perspective?->name }}
            </td>
            <td class="px-4 py-3">{{ number_format($std->weight,2) }}</td>
            <td class="px-4 py-3 text-center flex justify-center gap-2">
              {{-- تعديل --}}
              @can('update standards')
              <a href="{{ route('standards.edit',$std) }}"
                 class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-xs">
                 تعديل
              </a>
              @endcan
              {{-- حذف --}}
              @can('delete standards')
              <form method="POST" action="{{ route('standards.destroy',$std) }}" onsubmit="return confirm('هل أنت متأكد؟')">
                @csrf @method('DELETE')
                <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs">حذف</button>
              </form>
              @endcan
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-4 py-6 text-center text-gray-500 italic">لا توجد معايير.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $standards->links() }}</div>
</div>
@endsection
