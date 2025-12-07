@extends('layouts.admin')

@php($title = 'عرض الجهة الحكومية')
@php($pageHeading = 'عرض الجهة الحكومية')

@section('content')

<div class="max-w-5xl mx-auto">

    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">

        {{-- العنوان --}}
        <div class="flex items-center gap-3 mb-8">
            <div class="p-3 bg-indigo-100 rounded-xl">
                <i data-feather="eye" class="w-6 h-6 text-indigo-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">تفاصيل الجهة الحكومية</h2>
        </div>

        {{-- معلومات أساسية عن الجهة --}}
        <div class="mb-10">
            <h3 class="font-semibold text-xl text-gray-700 mb-4">معلومات الجهة</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                    <p class="text-gray-500 text-sm">المعرف الفريد (UUID)</p>
                    <p class="text-lg font-medium text-gray-800">
                        {{ $government_entity->uuid }}
                    </p>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                    <p class="text-gray-500 text-sm">اسم الجهة</p>
                    <p class="text-lg font-medium text-gray-800">
                        {{ $government_entity->name }}
                    </p>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                    <p class="text-gray-500 text-sm">التصنيف</p>
                    <p class="text-lg font-medium text-gray-800">
                        {{ $government_entity->classification->name ?? $government_entity->classification->value }}
                    </p>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                    <p class="text-gray-500 text-sm">أنشئت بواسطة</p>
                    <p class="text-lg font-medium text-gray-800">
                        {{ $government_entity->user?->name ?? '—' }}
                    </p>
                </div>

            </div>
        </div>

        {{-- الاستراتيجية --}}
        <div class="mb-10">
            <h3 class="font-semibold text-xl text-gray-700 mb-4">الاستراتيجية</h3>

            <div class="space-y-6">

                {{-- الرسالة --}}
                <div class="bg-blue-50 border border-blue-200 p-5 rounded-xl">
                    <h4 class="font-semibold text-gray-700 mb-2">الرسالة (Mission)</h4>
                    <p class="text-gray-800 leading-relaxed break-words whitespace-pre-line">
                        {{ $government_entity->strategy?->mission ?: '—' }}
                    </p>
                </div>

                {{-- الرؤية --}}
                <div class="bg-indigo-50 border border-indigo-200 p-5 rounded-xl">
                    <h4 class="font-semibold text-gray-700 mb-2">الرؤية (Vision)</h4>
                    <p class="text-gray-800 leading-relaxed break-words whitespace-pre-line">
                        {{ $government_entity->strategy?->vision ?: '—' }}
                    </p>
                </div>

            </div>
        </div>

        {{-- الأهداف --}}
        <div class="mb-10">
            <h3 class="font-semibold text-xl text-gray-700 mb-4">الأهداف</h3>

            @if($government_entity->goals->count() > 0)
                <ul class="space-y-3">
                    @foreach($government_entity->goals as $goal)
                        <li class="bg-green-50 border border-green-200 p-4 rounded-xl text-gray-800 leading-relaxed">
                            • {{ $goal->goal }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">لا توجد أهداف مسجلة.</p>
            @endif
        </div>
        {{-- زر العودة --}}
        <div class="flex justify-end mt-10">
            <a href="{{ route('government_entities.index') }}"
                class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition">
                ← رجوع
            </a>
        </div>

    </div>

</div>

<script>
    if (window.feather) { window.feather.replace(); }
</script>

@endsection
