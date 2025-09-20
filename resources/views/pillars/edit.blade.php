@extends('layouts.admin')

@php($title = 'تعديل محور')
@php($pageHeading = 'تعديل محور')

@section('content')
<div class="bg-white shadow rounded-xl p-6 max-w-xl mx-auto">
  <h2 class="text-2xl font-bold text-gray-800 mb-6">تعديل محور</h2>

  @if ($errors->any())
    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
      <ul class="list-disc list-inside text-sm">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <form method="POST" action="{{ route('pillars.update',$pillar) }}" class="space-y-5">
    @csrf @method('PUT')

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">المعرف الفريد (UUID)</label>
      <input type="text" name="uuid" value="{{ old('uuid',$pillar->uuid) }}" required
             class="w-full border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">المنظور</label>
      <select name="perspective_id" required
              class="w-full border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
        @foreach($perspectives as $p)
          <option value="{{ $p->id }}" {{ old('perspective_id',$pillar->perspective_id)==$p->id?'selected':'' }}>
            {{ $p->name }}
          </option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
      <input type="text" name="name" value="{{ old('name',$pillar->name) }}" required
             class="w-full border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
      <textarea name="description" rows="3"
                class="w-full border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">{{ old('description',$pillar->description) }}</textarea>
    </div>

    <div class="flex justify-end gap-3">
      <a href="{{ route('pillars.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">إلغاء</a>
      <button class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">تحديث</button>
    </div>
  </form>
</div>
@endsection
