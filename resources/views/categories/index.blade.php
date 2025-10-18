@extends('layouts.admin')

@section('content')
<div class="bg-white shadow rounded-xl p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">إدارة التصنيفات</h2>

        {{-- زر إضافة تصنيف --}}
       @can('create categories')
        <a href="{{ route('categories.create') }}"
           class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
           + إضافة تصنيف
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
              <tr class="bg-gray-100 text-right text-sm text-gray-700 uppercase">
                  <th class="px-4 py-3 w-12">#</th>
                  <th class="px-4 py-3 w-1/4">الاسم</th>
                  <th class="px-4 py-3 w-1/2">الوصف</th>
                  <th class="px-4 py-3 w-1/6 text-center">الإجراءات</th>
              </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 text-sm">
              @forelse($categories as $category)
                  <tr class="hover:bg-gray-50">
                      <td class="px-4 py-3 text-center">{{ $category->id }}</td>
                      <td class="px-4 py-3 font-semibold text-gray-800 truncate">{{ $category->name }}</td>
                      <td class="px-4 py-3 truncate">{{ $category->description ?? '-' }}</td>
                      <td class="px-4 py-3 text-center whitespace-nowrap flex justify-center gap-2">

                          {{-- تعديل --}}
                          @can('update categories')
                          <a href="{{ route('categories.edit',$category) }}"
                             class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-xs">
                             تعديل
                          </a>
                          @endcan

                          {{-- حذف --}}
                          @can('delete categories')
                          <form method="POST" action="{{ route('categories.destroy',$category) }}"
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
                      <td colspan="4" class="px-4 py-6 text-center text-gray-500 italic">
                          لا توجد تصنيفات مسجلة.
                      </td>
                  </tr>
              @endforelse
          </tbody>
      </table>
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>
@endsection
