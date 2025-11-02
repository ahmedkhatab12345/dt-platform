<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>{{ $title ?? 'لوحة التحكم' }}</title>
  @vite('resources/css/app.css')
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <script src="https://unpkg.com/feather-icons"></script>
  <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

  <style>
    /* ========== أساسيات الاستجابة ========== */
    .mobile-menu-button {
      display: none;
    }

    /* الموبايل */
    @media (max-width: 768px) {
      .mobile-menu-button {
        display: flex;
        right: 1rem;
        left: auto;
      }

      .sidebar {
        position: fixed;
        top: 0;
        right: -100%; /* ← السايدبار مخفي من اليمين */
        left: auto;
        height: 100vh;
        width: 16rem;
        background-color: #312e81; /* bg-indigo-800 */
        transition: right 0.3s ease-in-out;
        z-index: 50;
      }

      .sidebar.active {
        right: 0; /* ← يظهر من اليمين */
      }

      .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 40;
      }

      .overlay.active {
        display: block;
      }

      .content-area {
        margin-right: 0 !important;
      }

      .header-content {
        flex-direction: column-reverse;
        gap: 1rem;
        text-align: center;
      }

      .user-info {
        justify-content: center;
      }
    }

    /* الديسكتوب */
    @media (min-width: 768px) {
      .sidebar {
        position: fixed;
        right: 0;
        left: auto;
        height: 100vh;
        width: 16rem;
      }

      .content-area {
        margin-right: 16rem; /* نفس عرض السايدبار */
      }
    }

    /* الفوتر على الموبايل */
    @media (max-width: 640px) {
      .footer-content {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
      }
    }
  </style>
</head>

<body class="bg-gray-100 font-sans antialiased" dir="rtl">

  <div class="min-h-screen flex flex-row-reverse">

    <aside class="sidebar text-white flex flex-col bg-indigo-800 shadow-lg">
      <button class="close-menu absolute top-4 left-4 md:hidden">
        <i data-feather="x" class="w-6 h-6"></i>
      </button>

      <div class="p-4 md:p-5 text-xl md:text-2xl font-bold border-b border-indigo-700 text-center">
        لوحة التحكم
      </div>

      <nav class="flex-1 p-4 space-y-2 text-sm overflow-y-auto">

        {{-- روابط لوحة التحكم --}}
        <a href="{{ route('dashboard') }}"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('dashboard') ? 'bg-indigo-700' : '' }}">
          <i data-feather="home" class="w-4 h-4"></i> الرئيسية
        </a>

        @can('read projects')
        <a href="{{ route('projects.index') }}"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('projects.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="folder" class="w-4 h-4"></i> المشاريع
        </a>
        @endcan

        @can('read roles')
        <a href="{{ route('roles.index') }}"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('roles.index') ? 'bg-indigo-700' : '' }}">
          <i data-feather="shield" class="w-4 h-4"></i> الأدوار والصلاحيات
        </a>
        @endcan

        @can('read categories')
        <a href="{{ route('categories.index') }}"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('categories.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="tag" class="w-4 h-4"></i> التصنيفات
        </a>
        @endcan

        @can('read users')
        <a href="{{ route('users.index') }}"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('users.index') ? 'bg-indigo-700' : '' }}">
          <i data-feather="users" class="w-4 h-4"></i> المستخدمون
        </a>
        @endcan

        <a href="{{ route('profile.edit') }}"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('profile.edit') ? 'bg-indigo-700' : '' }}">
          <i data-feather="user" class="w-4 h-4"></i> الملف الشخصي
        </a>

        @can('read government_entities')
        <a href="{{ route('government_entities.index') }}"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('government_entities.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="layers" class="w-4 h-4"></i> الجهات الحكومية
        </a>
        @endcan

        @can('read perspectives')
        <a href="{{ route('perspectives.index') }}"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('perspectives.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="grid" class="w-4 h-4"></i> المناظير
        </a>
        @endcan

        @can('read pillars')
        <a href="{{ route('pillars.index') }}"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('pillars.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="columns" class="w-4 h-4"></i> المحاور
        </a>
        @endcan

        @can('read standards')
        <a href="{{ route('standards.index') }}"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('standards.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="check-square" class="w-4 h-4"></i> المعايير
        </a>
        @endcan

        @can('read tools')
        <a href="{{ route('tools.index') }}"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('tools.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="tool" class="w-4 h-4"></i> الأدوات
        </a>
        @endcan

        @can('read assignments')
        <a href="{{ route('assignments.index') }}"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('assignments.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="shuffle" class="w-4 h-4"></i> إسناد الأدوات
        </a>
        @endcan

        @can('read performance_analysis')
        <a href="{{ route('performance_analysis.index') }}"
          class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded hover:bg-indigo-700 {{ request()->routeIs('performance_analysis.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="bar-chart-2" class="w-4 h-4"></i> تحليل الأداء
        </a>
        @endcan

      </nav>

      {{-- زر تسجيل الخروج --}}
      <div class="p-4 border-t border-indigo-700">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="flex flex-row-reverse items-center justify-end gap-2 w-full text-right px-3 py-2 rounded hover:bg-indigo-700">
            <i data-feather="log-out" class="w-4 h-4"></i>
            تسجيل الخروج
          </button>
        </form>
      </div>
    </aside>

    <div class="content-area flex-1 flex flex-col">

      {{-- زر الموبايل --}}
      <button class="mobile-menu-button fixed bottom-4 right-4 z-50 bg-indigo-600 text-white p-3 rounded-full shadow-lg md:hidden">
        <i data-feather="menu" class="w-5 h-5"></i>
      </button>

      <div class="overlay"></div>
{{-- الهيدر --}}
<header class="bg-white shadow px-8 py-6 md:px-8 md:py-6">
  <div class="header-content flex flex-row-reverse justify-between items-center">
      <div class="user-info flex flex-row-reverse items-center gap-3">
          <span class="text-gray-600 text-sm hidden sm:block">{{ Auth::user()->name ?? Auth::user()->email }}</span>
          <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->email ?? 'مستخدم') }}&background=4F46E5&color=fff"
              class="h-7 w-7 md:h-8 md:w-8 rounded-full border-2 border-indigo-500">
      </div>
      <h1 class="text-base md:text-lg font-semibold text-gray-800">{{ $pageHeading ?? 'لوحة التحكم' }}</h1>
  </div>
</header>
      {{-- المحتوى --}}
      <main class="flex-1 p-4 md:p-6">

        {{-- التنبيهات --}}
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
        <div class="max-w-7xl mx-auto px-4 py-3 md:px-6 footer-content flex flex-row-reverse items-center justify-between text-sm text-gray-500">
          <p>© {{ date('Y') }} منصة DT — جميع الحقوق محفوظة.</p>
          <div class="flex flex-row-reverse items-center gap-3 md:gap-4 mt-2 md:mt-0">
            <a href="#" class="hover:text-gray-700">الخصوصية</a>
            <a href="#" class="hover:text-gray-700">الشروط</a>
            <a href="#" class="hover:text-gray-700">الدعم</a>
          </div>
        </div>
      </footer>
    </div>
  </div>

  <script>
    feather.replace();

    document.addEventListener('DOMContentLoaded', function() {
      const menuButton = document.querySelector('.mobile-menu-button');
      const closeButton = document.querySelector('.close-menu');
      const sidebar = document.querySelector('.sidebar');
      const overlay = document.querySelector('.overlay');

      function openSidebar() {
        sidebar.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
      }

      function closeSidebar() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
      }

      menuButton.addEventListener('click', openSidebar);
      closeButton.addEventListener('click', closeSidebar);
      overlay.addEventListener('click', closeSidebar);

      const sidebarLinks = document.querySelectorAll('.sidebar a');
      sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
          if (window.innerWidth < 768) closeSidebar();
        });
      });

      window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) closeSidebar();
      });
    });
  </script>
</body>
</html>
