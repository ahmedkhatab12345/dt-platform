@extends('layouts.admin')

@php($title = 'تعديل الإسناد')
@php($pageHeading = 'تعديل إسناد الأداة')

@section('content')
<div class="bg-white shadow rounded-xl p-6 max-w-xl mx-auto">
  <h2 class="text-2xl font-bold text-gray-800 mb-6">تعديل الإسناد</h2>

  @if ($errors->any())
    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
      <ul class="list-disc list-inside text-sm">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('assignments.update', $assignment) }}" class="space-y-5">
    @csrf
    @method('PUT')

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">المعيار</label>
      <select name="standard_id" class="w-full border-gray-300 rounded-lg px-3 py-2">
        @foreach($standards as $std)
          <option value="{{ $std->id }}" {{ old('standard_id',$assignment->standard_id) == $std->id ? 'selected' : '' }}>
            {{ $std->name }}
          </option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">الأداة</label>
      <select name="tool_id" class="w-full border-gray-300 rounded-lg px-3 py-2">
        @foreach($tools as $tool)
          <option value="{{ $tool->id }}" {{ old('tool_id',$assignment->tool_id) == $tool->id ? 'selected' : '' }}>
            {{ $tool->name }}
          </option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">الجهة الحكومية</label>
      <select name="government_entity_id" class="w-full border-gray-300 rounded-lg px-3 py-2">
        @foreach($entities as $entity)
          <option value="{{ $entity->id }}" {{ old('government_entity_id',$assignment->government_entity_id) == $entity->id ? 'selected' : '' }}>
            {{ $entity->name }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="flex justify-end gap-3">
      <a href="{{ route('assignments.index') }}"
         class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">إلغاء</a>
      <button type="submit"
              class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
        تحديث
      </button>
    </div>
  </form>
</div>
@endsection
