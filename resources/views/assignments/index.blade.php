@extends('layouts.admin')

@php($title = 'الإسنادات')
@php($pageHeading = 'قائمة الإسنادات')

@section('content')
<div class="bg-white shadow rounded-xl p-6">
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">الإسنادات</h2>
    @can('create assignments')
    <a href="{{ route('assignments.create') }}"
       class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
       + إضافة إسناد
    </a>
    @endcan
  </div>

  @if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded text-sm">
      {{ session('success') }}
    </div>
  @endif

  <div class="overflow-x-auto">
    <table class="w-full table-fixed border-collapse bg-white rounded-lg shadow">
      <thead>
        <tr class="bg-gray-100 text-right text-gray-700 text-sm uppercase">
          <th class="px-4 py-3 w-12">#</th>
          <th class="px-4 py-3 w-1/4">المعيار</th>
          <th class="px-4 py-3 w-1/4">الأداة</th>
          <th class="px-4 py-3 w-1/4">الجهة الحكومية</th>
          <th class="px-4 py-3 w-1/6">تاريخ الإنشاء</th>
          <th class="px-4 py-3 w-1/6 text-right">الإجراءات</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 text-sm">
        @forelse($assignments as $assign)
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-3 text-center">{{ $loop->iteration }}</td>
            <td class="px-4 py-3 truncate">{{ $assign->standard?->name }}</td>
            <td class="px-4 py-3 truncate">{{ $assign->tool?->name }}</td>
            <td class="px-4 py-3 truncate">{{ $assign->governmentEntity?->name }}</td>
            <td class="px-4 py-3">{{ $assign->created_at->format('Y-m-d') }}</td>
            <td class="px-4 py-3 text-right flex justify-end gap-2 whitespace-nowrap">

              {{-- Edit --}}
              @can('update assignments')
              <a href="{{ route('assignments.edit', $assign) }}"
                 class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-xs">
                 تعديل
              </a>
              @endcan

              {{-- Delete --}}
              @can('delete assignments')
              <form method="POST" action="{{ route('assignments.destroy',$assign) }}"
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
            <td colspan="6" class="px-4 py-6 text-center text-gray-500 italic">لا توجد إسنادات.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $assignments->links() }}</div>
</div>
@endsection
