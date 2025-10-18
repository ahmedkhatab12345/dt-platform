@extends('layouts.admin')

@section('content')
    {{-- صندوق الترحيب --}}
    <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl shadow-xl p-12 text-center mb-10 text-white relative overflow-hidden">

        {{-- تأثير دوائر متحركة في الخلفية --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-white opacity-10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 right-0 w-72 h-72 bg-black opacity-10 rounded-full blur-3xl animate-pulse"></div>

        {{-- اسم المستخدم --}}
        <h2 class="text-5xl font-extrabold mb-6 tracking-wide animate-fadeIn">
            مرحبًا {{ Auth::user()->name ?? Auth::user()->email }}
        </h2>

        {{-- الفريق / التصنيف --}}
        @if(Auth::user()->category)
        <div class="mb-6 animate-bounce">
            <span class="inline-block bg-white text-indigo-700 px-8 py-3 rounded-full text-2xl font-bold shadow-lg">
                فريق {{ Auth::user()->category->name }}
            </span>
        </div>
        @endif

        {{-- الوصف --}}
        <p class="text-lg text-gray-100 leading-relaxed max-w-2xl mx-auto mb-10">
            أهلاً بك في لوحة التحكم الخاصة بك. هنا تبدأ رحلتك لإدارة المشاريع، المعايير، والجهات
            بكل سهولة وسلاسة.
        </p>

    </div>

    {{-- Tailwind Animations --}}
    <style>
    @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
    animation: fadeIn 1.5s ease-in-out;
    }
    </style>

{{-- التحليلات --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mt-10">

    @can('read users')
    {{-- إجمالي المستخدمين --}}
    <a href="{{ route('users.index') }}"
       class="relative bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-2xl shadow-xl p-8 text-white overflow-hidden transform transition duration-500 hover:scale-105 hover:shadow-2xl block">
        <div class="absolute -top-10 -left-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl animate-pulse"></div>
        <div class="flex flex-row-reverse items-center gap-6 relative z-10">
            <div class="bg-white bg-opacity-20 p-5 rounded-2xl">
                <i data-feather="users" class="w-10 h-10"></i>
            </div>
            <div>
                <p class="text-base text-indigo-100">إجمالي المستخدمين</p>
                <h3 class="text-3xl font-extrabold">{{ $totalUsers }}</h3>
            </div>
        </div>
    </a>
    @endcan

    @can('read government_entities')
    {{-- الجهات الحكومية --}}
    <a href="{{ route('government_entities.index') }}"
       class="relative bg-gradient-to-r from-green-500 to-green-600 rounded-2xl shadow-xl p-8 text-white overflow-hidden transform transition duration-500 hover:scale-105 hover:shadow-2xl block">
        <div class="absolute bottom-0 right-0 w-40 h-40 bg-black opacity-10 rounded-full blur-3xl animate-pulse"></div>
        <div class="flex flex-row-reverse items-center gap-6 relative z-10">
            <div class="bg-white bg-opacity-20 p-5 rounded-2xl">
                <i data-feather="briefcase" class="w-10 h-10"></i>
            </div>
            <div>
                <p class="text-base text-green-100">الجهات الحكومية</p>
                <h3 class="text-3xl font-extrabold">{{ $totalEntities }}</h3>
            </div>
        </div>
    </a>
    @endcan

    @can('read pillars')
    {{-- المحاور --}}
    <a href="{{ route('pillars.index') }}"
       class="relative bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-2xl shadow-xl p-8 text-white overflow-hidden transform transition duration-500 hover:scale-105 hover:shadow-2xl block">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-20 rounded-full blur-3xl animate-pulse"></div>
        <div class="flex flex-row-reverse items-center gap-6 relative z-10">
            <div class="bg-white bg-opacity-20 p-5 rounded-2xl">
                <i data-feather="layers" class="w-10 h-10"></i>
            </div>
            <div>
                <p class="text-base text-yellow-100">المحاور</p>
                <h3 class="text-3xl font-extrabold">{{ $totalPillars }}</h3>
            </div>
        </div>
    </a>
    @endcan

    @can('read standards')
    {{-- المعايير --}}
    <a href="{{ route('standards.index') }}"
       class="relative bg-gradient-to-r from-pink-500 to-red-500 rounded-2xl shadow-xl p-8 text-white overflow-hidden transform transition duration-500 hover:scale-105 hover:shadow-2xl block">
        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-black opacity-10 rounded-full blur-3xl animate-pulse"></div>
        <div class="flex flex-row-reverse items-center gap-6 relative z-10">
            <div class="bg-white bg-opacity-20 p-5 rounded-2xl">
                <i data-feather="check-square" class="w-10 h-10"></i>
            </div>
            <div>
                <p class="text-base text-pink-100">المعايير</p>
                <h3 class="text-3xl font-extrabold">{{ $totalStandards }}</h3>
            </div>
        </div>
    </a>
    @endcan

</div>


@endsection

@php($title = 'لوحة التحكم')
@php($pageHeading = 'لوحة التحكم')

@push('scripts')
<script>
  feather.replace()
</script>
@endpush
