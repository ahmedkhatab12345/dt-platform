@extends('layouts.admin')

@php($title = 'تعديل المشروع')
@php($pageHeading = 'تعديل المشروع')

@section('content')
<div class="bg-white rounded-xl shadow p-6 max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i data-feather="edit" class="w-6 h-6 text-indigo-600"></i>
        تعديل المشروع: {{ $project->name }}
    </h2>

    <form method="POST" action="{{ route('projects.update', $project) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- اسم المشروع --}}
            <div>
                <label class="block font-medium text-sm text-gray-700 mb-1">
                    اسم المشروع  <span class="text-red-600">*</span>
                  </label>  
                     <input type="text"
                       name="name"
                       value="{{ old('name', $project->name) }}"
                       class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2">
                @error('name')
                  <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- الإدارة --}}
            <div>
                <label class="block font-medium text-sm text-gray-700 mb-1">الإدارة</label>
                <input type="text"
                       name="department"
                       value="{{ old('department', $project->department) }}"
                       class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2">
                @error('department')
                  <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

           {{-- الجهة الحكومية --}}
            <div>
                <label class="block font-medium text-sm text-gray-700 mb-1">
                الجهة الحكومية <span class="text-red-600">*</span>
                </label>
            
                <select id="government_entity_id"
                        name="government_entity_id"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2">
                <option value="">اختر الجهة</option>
                @foreach($entities as $entity)
                    <option value="{{ $entity->id }}"
                    {{ old('government_entity_id', $project->government_entity_id ?? request('government_entity_id')) == $entity->id ? 'selected' : '' }}>
                    {{ $entity->name .' - '. $entity->uuid }}
                    </option>
                @endforeach
                </select>
            
                @error('government_entity_id')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            {{-- المعيار --}}
            <div>
                <label class="block font-medium text-sm text-gray-700 mb-1">
                    المعيار <span class="text-red-600">*</span>
                  </label>                <select name="standard_id"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2">
                    @foreach($standards as $std)
                        <option value="{{ $std->id }}" {{ old('standard_id', $project->standard_id) == $std->id ? 'selected' : '' }}>
                            {{ $std->name }}
                        </option>
                    @endforeach
                </select>
                @error('standard_id')
                  <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- تاريخ البداية --}}
            <div>
                <label class="block font-medium text-sm text-gray-700 mb-1">تاريخ البداية</label>
                <input type="date"
                    name="start_date"
                    value="{{ old('start_date', optional($project->start_date)->format('Y-m-d')) }}"
                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2">
                @error('start_date')
                  <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- تاريخ النهاية --}}
            <div>
                <label class="block font-medium text-sm text-gray-700 mb-1">تاريخ النهاية</label>
                <input type="date"
                    name="end_date"
                    value="{{ old('end_date', optional($project->end_date)->format('Y-m-d')) }}"
                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2">
                @error('end_date')
                  <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- الميزانية --}}
            <div>
                <label class="block font-medium text-sm text-gray-700 mb-1">الميزانية (ريال سعودي)</label>
                <input type="number"
                       name="budget"
                       value="{{ old('budget', $project->budget) }}"
                       step="0.01"
                       min="0"
                       class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2">
                @error('budget')
                  <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- الحالة --}}
            <div>
                <label class="block font-medium text-sm text-gray-700 mb-1">الحالة</label>
                <select name="status"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2">
                    @foreach(\App\Models\Project::getStatuses() as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $project->status) == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                  <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- نبذة عن المشروع --}}
        <div>
            <label class="block font-medium text-sm text-gray-700 mb-1">نبذة عن المشروع</label>
            <textarea name="overview"
                      class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2"
                      rows="4">{{ old('overview', $project->overview) }}</textarea>
            @error('overview')
              <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- المؤشرات --}}
        <div>
            <label class="block font-medium text-sm text-gray-700 mb-2">المؤشرات</label>
            <div id="indicators-container">
                @forelse($project->indicators ?? [] as $indicator)
                    <div class="flex gap-2 mb-2">
                        <input type="text" name="indicators[]" value="{{ $indicator }}"
                               class="flex-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-3 py-2">
                        <button type="button" onclick="removeIndicator(this)"
                                class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">حذف</button>
                    </div>
                @empty
                    <div class="flex gap-2 mb-2">
                        <input type="text" name="indicators[]" class="flex-1 border-gray-300 rounded-lg shadow-sm px-3 py-2" placeholder="أدخل مؤشر">
                        <button type="button" onclick="removeIndicator(this)" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">حذف</button>
                    </div>
                @endforelse
            </div>
            <button type="button" onclick="addIndicator()" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 text-sm">
                + إضافة مؤشر
            </button>
        </div>

        {{-- المخرجات النهائية --}}
        <div>
            <label class="block font-medium text-sm text-gray-700 mb-2">المخرجات النهائية</label>
            <div id="deliverables-container">
                @forelse($project->final_deliverables ?? [] as $deliverable)
                    <div class="flex gap-2 mb-2">
                        <input type="text" name="final_deliverables[]" value="{{ $deliverable }}"
                               class="flex-1 border-gray-300 rounded-lg shadow-sm px-3 py-2">
                        <button type="button" onclick="removeDeliverable(this)"
                                class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">حذف</button>
                    </div>
                @empty
                    <div class="flex gap-2 mb-2">
                        <input type="text" name="final_deliverables[]" class="flex-1 border-gray-300 rounded-lg shadow-sm px-3 py-2" placeholder="أدخل مخرج نهائي">
                        <button type="button" onclick="removeDeliverable(this)" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">حذف</button>
                    </div>
                @endforelse
            </div>
            <button type="button" onclick="addDeliverable()" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 text-sm">
                + إضافة مخرج نهائي
            </button>
        </div>

        {{-- الأنشطة --}}
        <div>
            <label class="block font-medium text-sm text-gray-700 mb-2">الأنشطة</label>
            <div id="activities-container">
                @forelse($project->activities ?? [] as $activity)
                    <div class="flex gap-2 mb-2">
                        <input type="text" name="activities[]" value="{{ $activity }}"
                               class="flex-1 border-gray-300 rounded-lg shadow-sm px-3 py-2">
                        <button type="button" onclick="removeActivity(this)"
                                class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">حذف</button>
                    </div>
                @empty
                    <div class="flex gap-2 mb-2">
                        <input type="text" name="activities[]" class="flex-1 border-gray-300 rounded-lg shadow-sm px-3 py-2" placeholder="أدخل نشاط">
                        <button type="button" onclick="removeActivity(this)" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">حذف</button>
                    </div>
                @endforelse
            </div>
            <button type="button" onclick="addActivity()" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 text-sm">
                + إضافة نشاط
            </button>
        </div>

        {{-- الأزرار --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('projects.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
               إلغاء
            </a>
            <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                تحديث المشروع
            </button>
        </div>
    </form>
</div>

<script>
function addIndicator() {
    const container = document.getElementById('indicators-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2 mb-2';
    div.innerHTML = `
        <input type="text" name="indicators[]" class="flex-1 border-gray-300 rounded-lg shadow-sm px-3 py-2" placeholder="أدخل مؤشر">
        <button type="button" onclick="removeIndicator(this)" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">حذف</button>
    `;
    container.appendChild(div);
}
function removeIndicator(button) { button.parentElement.remove(); }

function addDeliverable() {
    const container = document.getElementById('deliverables-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2 mb-2';
    div.innerHTML = `
        <input type="text" name="final_deliverables[]" class="flex-1 border-gray-300 rounded-lg shadow-sm px-3 py-2" placeholder="أدخل مخرج نهائي">
        <button type="button" onclick="removeDeliverable(this)" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">حذف</button>
    `;
    container.appendChild(div);
}
function removeDeliverable(button) { button.parentElement.remove(); }

function addActivity() {
    const container = document.getElementById('activities-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2 mb-2';
    div.innerHTML = `
        <input type="text" name="activities[]" class="flex-1 border-gray-300 rounded-lg shadow-sm px-3 py-2" placeholder="أدخل نشاط">
        <button type="button" onclick="removeActivity(this)" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">حذف</button>
    `;
    container.appendChild(div);
}
function removeActivity(button) { button.parentElement.remove(); }

document.addEventListener("DOMContentLoaded", function () {
      new TomSelect("#government_entity_id", {
        create: false,
        placeholder: "ابحث بالاسم أو الكود...",
        sortField: { field: "text", direction: "asc" },
        maxOptions: 1000
      });
    });
</script>
@endsection
