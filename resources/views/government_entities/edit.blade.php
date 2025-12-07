@extends('layouts.admin')

@php($title = 'تعديل جهة حكومية')
@php($pageHeading = 'تعديل جهة حكومية')

@section('content')

<div class="bg-white shadow rounded-2xl p-8 max-w-3xl mx-auto">

    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i data-feather="edit" class="w-6 h-6 text-indigo-600"></i>
        تعديل جهة حكومية
    </h2>

    {{-- رسائل التحقق من الأخطاء --}}
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-xl border border-red-200">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('government_entities.update', $entity) }}" class="space-y-7">
        @csrf
        @method('PUT')

        {{-- UUID --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">المعرف الفريد (UUID)</label>
            <input type="text" name="uuid" 
                   value="{{ old('uuid',$entity->uuid) }}" 
                   required
                   class="w-full border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        {{-- الاسم --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
            <input type="text" name="name"
                   value="{{ old('name', $entity->name) }}"
                   class="w-full border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                   required>
        </div>

        {{-- التصنيف --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">التصنيف</label>
            <select name="classification"
                    class="w-full border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
                @foreach($classifications as $classification)
                    <option value="{{ $classification->value }}"
                        {{ $entity->classification->value === $classification->value ? 'selected' : '' }}>
                        {{ $classification->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- الرسالة --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">الرسالة (Mission)</label>
            <textarea name="mission"
                      class="w-full border-gray-300 rounded-lg px-3 py-2 h-32 focus:border-indigo-500 focus:ring-indigo-500">{{ old('mission', $entity->strategy?->mission) }}</textarea>
        </div>

        {{-- الرؤية --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">الرؤية (Vision)</label>
            <textarea name="vision"
                      class="w-full border-gray-300 rounded-lg px-3 py-2 h-32 focus:border-indigo-500 focus:ring-indigo-500">{{ old('vision', $entity->strategy?->vision) }}</textarea>
        </div>

        {{-- الأهداف --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">الأهداف</label>

            <div id="goals-wrapper" class="space-y-3">

                {{-- الأهداف القديمة --}}
                @foreach($entity->goals as $goal)
                    <div class="flex gap-2">
                        <input type="text" name="goals[]" 
                               value="{{ $goal->goal }}"
                               class="w-full border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">

                        <button type="button"
                                onclick="this.parentElement.remove()"
                                class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            -
                        </button>
                    </div>
                @endforeach

            </div>

            {{-- زر إضافة هدف جديد --}}
            <button type="button"
                    onclick="addGoal()"
                    class="mt-3 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                + إضافة هدف
            </button>
        </div>

        {{-- الأزرار --}}
        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('government_entities.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
               إلغاء
            </a>

            <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
                تحديث
            </button>
        </div>

    </form>
</div>

<script>
    function addGoal() {
        const wrapper = document.getElementById('goals-wrapper');
        const div = document.createElement('div');
        div.classList.add('flex', 'gap-2');

        div.innerHTML = `
            <input type="text" name="goals[]"
                   class="w-full border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">

            <button type="button"
                    onclick="this.parentElement.remove()"
                    class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                -
            </button>
        `;

        wrapper.appendChild(div);
    }

    if (window.feather) { window.feather.replace(); }
</script>

@endsection
