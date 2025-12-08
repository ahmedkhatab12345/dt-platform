@extends('layouts.admin')

@php($title = 'عرض تقييم الابتكار')
@php($pageHeading = 'عرض تقييم الابتكار')

@section('content')

<div class="max-w-5xl mx-auto">

    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">

        {{-- العنوان --}}
        <div class="flex items-center gap-3 mb-8">
            <div class="p-3 bg-indigo-100 rounded-xl">
                <i data-feather="eye" class="w-6 h-6 text-indigo-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">تفاصيل تقييم الابتكار</h2>
        </div>

        {{-- معلومات الجهة --}}
        <div class="mb-10">
            <h3 class="font-semibold text-xl text-gray-700 mb-4">معلومات الجهة</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                    <p class="text-gray-500 text-sm">كود الجهة</p>
                    <p class="text-lg font-medium text-gray-800">
                        {{ $assessment->governmentEntity?->uuid ?? '—' }}
                    </p>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                    <p class="text-gray-500 text-sm">اسم الجهة</p>
                    <p class="text-lg font-medium text-gray-800">
                        {{ $assessment->governmentEntity?->name ?? '—' }}
                    </p>
                </div>

            </div>
        </div>

        {{-- مستوى الفهم --}}
        <div class="mb-10">
            <h3 class="font-semibold text-xl text-gray-700 mb-4">مستوى الفهم</h3>

            <span class="px-4 py-1.5 text-white rounded-lg text-sm
                @if($assessment->understanding_level === 'high') bg-green-600
                @elseif($assessment->understanding_level === 'medium') bg-yellow-500
                @else bg-red-600
                @endif">

                @if($assessment->understanding_level === 'high')
                    محترف
                @elseif($assessment->understanding_level === 'medium')
                    متوسط
                @else
                    منخفض
                @endif
            </span>
        </div>

        {{-- تفاصيل النصوص --}}
        <div class="space-y-8">

            {{-- الملخص --}}
            <div>
                <h3 class="font-semibold text-xl text-gray-700 mb-3">الملخص</h3>
                <div class="bg-gray-50 border border-gray-200 p-5 rounded-xl leading-relaxed text-gray-800 prose prose-indigo max-w-none">
                    {!! $assessment->summary ?: '—' !!}
                </div>
            </div>

            {{-- نقاط القوة --}}
            <div>
                <h3 class="font-semibold text-xl text-gray-700 mb-3">نقاط القوة</h3>
                <div class="bg-green-50 border border-green-200 p-5 rounded-xl leading-relaxed text-gray-800 prose max-w-none">
                    {!! $assessment->strengths ?: '—' !!}
                </div>
            </div>

            {{-- نقاط الضعف --}}
            <div>
                <h3 class="font-semibold text-xl text-gray-700 mb-3">نقاط الضعف</h3>
                <div class="bg-red-50 border border-red-200 p-5 rounded-xl leading-relaxed text-gray-800 prose max-w-none">
                    {!! $assessment->weaknesses ?: '—' !!}
                </div>
            </div>

            {{-- التوصية --}}
            <div>
                <h3 class="font-semibold text-xl text-gray-700 mb-3">التوصية</h3>
                <div class="bg-blue-50 border border-blue-200 p-5 rounded-xl leading-relaxed text-gray-800 prose max-w-none">
                    {!! $assessment->recommendation ?: '—' !!}
                </div>
            </div>

        </div>

        {{-- زر العودة --}}
        <div class="flex justify-end mt-10">
            <a href="{{ route('innovation.assessment.index') }}"
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
