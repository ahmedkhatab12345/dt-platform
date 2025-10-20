@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-xl shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">الجهات الحكومية</h2>
      
        <div class="flex items-center gap-3">
          {{-- فورم البحث --}}
          <form method="GET" action="{{ route('government_entities.index') }}" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="ابحث بالـ UUID أو الاسم"
                   class="border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            <button type="submit"
                    class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
              بحث
            </button>
          </form>
      
          {{-- زر الإضافة --}}
          @can('create government_entities')
          <a href="{{ route('government_entities.create') }}"
             class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
             + إضافة جهة
          </a>
          @endcan
        </div>
      </div>

    <div class="overflow-x-auto">
      <table class="w-full table-fixed border-collapse bg-white rounded-lg shadow">
          <thead>
              <tr class="bg-gray-100 text-right text-sm text-gray-700 uppercase">
                <th class="px-4 py-3 w-1/6">المعرف الفريد (UUID)</th>
                <th class="px-4 py-3 w-1/3">الاسم</th>
                  <th class="px-4 py-3 w-1/3">التصنيف</th>
                  <th class="px-4 py-3 w-1/6 text-center">الإجراءات</th>
              </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 text-sm">
              @forelse($entities as $entity)
                  <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs text-gray-600 truncate">{{ $entity->uuid }}</td>
                    <td class="px-4 py-3 truncate">{{ $entity->name }}</td>
                      <td class="px-4 py-3 truncate">
                          <span class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-50 text-indigo-700">
                              {{ $entity->classification->name ?? $entity->classification->value }}
                          </span>
                      </td>
                      <td class="px-4 py-3 text-center whitespace-nowrap flex justify-center gap-2">

                          {{-- تعديل --}}
                          @can('update government_entities')
                          <a href="{{ route('government_entities.edit',$entity) }}"
                             class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-xs">
                             تعديل
                          </a>
                          @endcan

                          {{-- حذف --}}
                          @can('delete government_entities')
                          <form method="POST" action="{{ route('government_entities.destroy',$entity) }}"
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
                          لا توجد جهات حكومية.
                      </td>
                  </tr>
              @endforelse
          </tbody>
      </table>
    </div>

    <div class="mt-4">
        {{ $entities->links() }}
    </div>
</div>
@endsection
