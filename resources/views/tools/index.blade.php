@extends('layouts.admin')

@php($title = 'الأدوات')
@php($pageHeading = 'الأدوات')

@section('content')
<div class="bg-white shadow rounded-xl p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i data-feather="tool" class="w-6 h-6 text-indigo-600"></i>
            إدارة الأدوات
        </h2>

        <div class="flex items-center gap-3">
            {{-- فورم البحث --}}
            <form method="GET" action="{{ route('tools.index') }}" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="ابحث بالاسم"
                       class="border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <button type="submit"
                        class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
                    بحث
                </button>
            </form>

            {{-- زر الإضافة --}}
            @can('create tools')
            <a href="{{ route('tools.create') }}"
               class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
               + إضافة أداة
            </a>
            @endcan
        </div>
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
              <th class="px-4 py-3 w-3/12">الوصف</th>
              <th class="px-4 py-3 w-4/12">الرابط</th>
              <th class="px-4 py-3 w-2/12 text-center">الإجراءات</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 text-sm">
            @forelse($tools as $tool)
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">{{ $tool->id }}</td>
                <td class="px-4 py-3 font-semibold truncate">{{ $tool->name }}</td>
                <td class="px-4 py-3 truncate">{{ $tool->description ?? '-' }}</td>
                <td class="px-4 py-3 truncate">
                  @if($tool->link)
                    <a href="{{ $tool->link }}" target="_blank" class="text-indigo-600 underline">{{ $tool->link }}</a>
                  @else
                    -
                  @endif
                </td>
                <td class="px-4 py-3 text-center flex justify-center gap-2">
                  {{-- تعديل --}}
                  @can('update tools')
                  <a href="{{ route('tools.edit',$tool) }}"
                     class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-xs">تعديل</a>
                  @endcan
                  {{-- حذف --}}
                  @can('delete tools')
                  <form method="POST" action="{{ route('tools.destroy',$tool) }}" onsubmit="return confirm('هل أنت متأكد؟')">
                    @csrf @method('DELETE')
                    <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs">حذف</button>
                  </form>
                  @endcan
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-4 py-6 text-center text-gray-500 italic">لا توجد أدوات.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-4">
        {{ $tools->appends(request()->all())->links() }}
    </div>


    <div class="mt-4">
        {{ $tools->links() }}
    </div>
</div>
@endsection
