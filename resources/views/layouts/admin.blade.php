<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>{{ $title ?? 'لوحة التحكم' }}</title>
  @vite('resources/css/app.css')
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="bg-gray-100 font-sans antialiased" dir="rtl">
  <div class="min-h-screen flex flex-row-reverse">

    <div class="flex-1 flex flex-col">
      {{-- الشريط العلوي --}}
      <header class="bg-white shadow px-6 py-4 flex flex-row-reverse justify-between items-center">
        <div class="flex flex-row-reverse items-center gap-4">
          <span class="text-gray-600 text-sm">{{ Auth::user()->full_name ?? Auth::user()->email }}</span>
          <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->full_name ?? 'مستخدم') }}&background=4F46E5&color=fff"
               class="h-9 w-9 rounded-full border-2 border-indigo-500">
        </div>
        <h1 class="text-xl font-semibold text-gray-800">{{ $pageHeading ?? 'لوحة التحكم' }}</h1>
      </header>

      {{-- محتوى الصفحة --}}
      <main class="flex-1 p-6">
        @if (session('success'))
          <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2 text-emerald-800 text-sm">
            {{ session('success') }}
          </div>
        @endif

        @if (session('error'))
          <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-red-800 text-sm">
            {{ session('error') }}
          </div>
        @endif

        @yield('content')
      </main>

      {{-- الفوتر --}}
      <footer class="bg-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-6 py-3 flex flex-col md:flex-row flex-row-reverse items-center justify-between text-sm text-gray-500">
          <p>© {{ date('Y') }} منصة DT — جميع الحقوق محفوظة.</p>
          <div class="flex flex-row-reverse items-center gap-4 mt-2 md:mt-0">
            <a href="#" class="hover:text-gray-700">الخصوصية</a>
            <a href="#" class="hover:text-gray-700">الشروط</a>
            <a href="#" class="hover:text-gray-700">الدعم</a>
          </div>
        </div>
      </footer>
    </div>

    {{-- الشريط الجانبي --}}
    <aside class="w-64 bg-indigo-800 text-white flex flex-col">
      <div class="p-5 text-2xl font-bold border-b border-indigo-700 text-center">
        لوحة التحكم
      </div>
      <nav class="flex-1 p-4 space-y-2 text-sm">
        <a href="{{ route('dashboard') }}"
           class="flex flex-row-reverse items-center gap-2 justify-end px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('dashboard') ? 'bg-indigo-700' : '' }}">
          <i data-feather="home" class="w-4 h-4"></i> الرئيسية
        </a>


        @can('read roles')
        <a href="{{ route('roles.index') }}"
           class="flex flex-row-reverse items-center gap-2 justify-end px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('roles.index') ? 'bg-indigo-700' : '' }}">
          <i data-feather="shield" class="w-4 h-4"></i> الأدوار والصلاحيات
        </a>
        @endcan

        @can('read categories')
        <a href="{{ route('categories.index') }}"
           class="flex flex-row-reverse items-center gap-2 justify-end px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('categories.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="tag" class="w-4 h-4"></i> التصنيفات
        </a>
        @endcan

        @can('read users')
        <a href="{{ route('users.index') }}"
           class="flex flex-row-reverse items-center gap-2 justify-end px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('users.index') ? 'bg-indigo-700' : '' }}">
          <i data-feather="users" class="w-4 h-4"></i> المستخدمون
        </a>
        @endcan

        <a href="{{ route('profile.edit') }}"
           class="flex flex-row-reverse items-center gap-2 justify-end px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('profile.edit') ? 'bg-indigo-700' : '' }}">
          <i data-feather="user" class="w-4 h-4"></i> الملف الشخصي
        </a>

        @can('read government_entities')
        <a href="{{ route('government_entities.index') }}"
           class="flex flex-row-reverse items-center gap-2 justify-end px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('government_entities.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="layers" class="w-4 h-4"></i> الجهات الحكومية
        </a>
        @endcan

        @can('read perspectives')
        <a href="{{ route('perspectives.index') }}"
           class="flex flex-row-reverse items-center gap-2 justify-end px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('perspectives.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="layers" class="w-4 h-4"></i> المناظير
        </a>
        @endcan

        @can('read pillars')
        <a href="{{ route('pillars.index') }}"
           class="flex flex-row-reverse items-center gap-2 justify-end px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('pillars.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="columns" class="w-4 h-4"></i> المحاور
        </a>
        @endcan

        @can('read standards')
        <a href="{{ route('standards.index') }}"
           class="flex flex-row-reverse items-center gap-2 justify-end px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('standards.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="check-square" class="w-4 h-4"></i> المعايير
        </a>
        @endcan

        @can('read tools')
        <a href="{{ route('tools.index') }}"
           class="flex flex-row-reverse items-center gap-2 justify-end px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('tools.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="tool" class="w-4 h-4"></i> الأدوات
        </a>
        @endcan

        @can('read assignments')
        <a href="{{ route('assignments.index') }}"
           class="flex flex-row-reverse items-center gap-2 justify-end px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('assignments.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="shuffle" class="w-4 h-4"></i> اسناد الادوات
        </a>
        @endcan

        @can('read projects')
        <a href="{{ route('projects.index') }}"
           class="flex flex-row-reverse items-center gap-2 justify-end px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('projects.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="folder" class="w-4 h-4"></i> المشاريع
        </a>
        @endcan

      </nav>

      <div class="p-4 border-t border-indigo-700">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="flex flex-row-reverse items-center gap-2 justify-end w-full text-right px-3 py-2 rounded hover:bg-indigo-700">
            <i data-feather="log-out" class="w-4 h-4"></i> تسجيل الخروج
          </button>
        </form>
      </div>
    </aside>

  </div>
  <script>feather.replace()</script>
</body>
</html>