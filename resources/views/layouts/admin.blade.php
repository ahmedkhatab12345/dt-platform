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
    .mobile-menu-button { display: none; }

    /* موبايل */
    @media (max-width: 768px) {
      .mobile-menu-button { display: flex; right: 1rem; left: auto; }

      .sidebar {
        position: fixed;
        top: 0;
        right: -100%;
        height: 100vh;
        width: 16rem;
        background-color: #312e81;
        transition: right 0.3s ease-in-out;
        z-index: 50;
      }

      .sidebar.active { right: 0; }

      .overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 40;
      }

      .overlay.active { display: block; }

      .content-area { margin-right: 0 !important; }

      .header-content {
        flex-direction: column-reverse;
        gap: 1rem;
        text-align: center;
      }

      .user-info { justify-content: center; }
    }

    /* ديسكتوب */
    @media (min-width: 768px) {
      .sidebar {
        position: fixed;
        right: 0;
        height: 100vh;
        width: 16rem;
        transition: width 0.3s ease-in-out;
        overflow: hidden;
      }

      .content-area {
        margin-right: 16rem;
        transition: margin-right 0.3s ease-in-out;
      }

      .sidebar.collapsed { width: 4.5rem; }
      .content-area.expanded { margin-right: 4.5rem !important; }

      .sidebar.collapsed a span,
      .sidebar.collapsed button span {
        display: none;
      }

      .sidebar.collapsed a,
      .sidebar.collapsed button {
        justify-content: center;
      }
    }

    /* فوتر موبايل */
    @media (max-width: 640px) {
      .footer-content {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
      }
    }

    /* زر تصغير السايدبار */
    #toggleSidebar {
      position: fixed;
      top: 1rem;
      right: 1rem;
      z-index: 60;
      background-color: #4F46E5;
      color: white;
      padding: 0.5rem;
      border-radius: 50%;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      transition: transform 0.3s;
    }
    #toggleSidebar:hover { transform: scale(1.1); }

    /* tooltip + relative */
    .sidebar a,
    .sidebar button { position: relative; }

    .sidebar.collapsed a::after {
      content: attr(data-title);
      position: absolute;
      right: 4.8rem;
      background: #312e81;
      color: #fff;
      padding: 4px 8px;
      border-radius: 6px;
      font-size: 0.8rem;
      white-space: nowrap;
      opacity: 0;
      transform: translateY(-50%);
      top: 50%;
      pointer-events: none;
      transition: opacity 0.2s ease, right 0.2s ease;
      z-index: 9999;
    }
    .sidebar.collapsed a:hover::after { opacity: 1; right: 5.2rem; }

    .sidebar.collapsed button::after {
      content: attr(data-title);
      position: absolute;
      right: 4.8rem;
      background: #312e81;
      color: #fff;
      padding: 4px 8px;
      border-radius: 6px;
      font-size: 0.8rem;
      white-space: nowrap;
      opacity: 0;
      transform: translateY(-50%);
      top: 50%;
      pointer-events: none;
      transition: opacity 0.2s ease, right 0.2s ease;
      z-index: 9999;
    }
    .sidebar.collapsed button:hover::after { opacity: 1; right: 5.2rem; }

    /* Submenu animation (ابتكار) */
    .submenu {
      overflow: hidden;
      max-height: 0;
      opacity: 0;
      transition: max-height 0.25s ease, opacity 0.2s ease;
    }
    .submenu.open {
      max-height: 800px;
      opacity: 1;
    }

    /* لما السايدبار تتصغر: اخفي الـ submenu كمان عشان ما تزحمش */
    .sidebar.collapsed .submenu { display: none; }
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

      @php
          $platformsActive = request()->routeIs('innovation.platforms.*');
          $partnershipsActive = request()->routeIs('innovation.partnerships.*');
          $eventsActive = request()->routeIs('innovation.events.*');
          $frameworksActive = request()->routeIs('innovation.frameworks.*');
          $assessmentActive = request()->routeIs('innovation.assessment.*');
          
          $innovationActive = $platformsActive || $partnershipsActive || $eventsActive || $frameworksActive || $assessmentActive;
      @endphp

      <nav class="flex-1 p-4 space-y-2 text-sm overflow-y-auto">
        <a href="{{ route('dashboard') }}" data-title="الرئيسية"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded-lg hover:bg-indigo-700/70 transition {{ request()->routeIs('dashboard') ? 'bg-indigo-700' : '' }}">
          <i data-feather="home" class="w-4 h-4"></i> <span>الرئيسية</span>
        </a>

        @can('read projects')
        <a href="{{ route('projects.index') }}" data-title="المشاريع"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded-lg hover:bg-indigo-700/70 transition {{ request()->routeIs('projects.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="folder" class="w-4 h-4"></i> <span>المشاريع</span>
        </a>
        @endcan

        @can('read roles')
        <a href="{{ route('roles.index') }}" data-title="الأدوار والصلاحيات"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded-lg hover:bg-indigo-700/70 transition {{ request()->routeIs('roles.index') ? 'bg-indigo-700' : '' }}">
          <i data-feather="shield" class="w-4 h-4"></i> <span>الأدوار والصلاحيات</span>
        </a>
        @endcan

        @can('read categories')
        <a href="{{ route('categories.index') }}" data-title="التصنيفات"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded-lg hover:bg-indigo-700/70 transition {{ request()->routeIs('categories.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="tag" class="w-4 h-4"></i> <span>التصنيفات</span>
        </a>
        @endcan

        @can('read users')
        <a href="{{ route('users.index') }}" data-title="المستخدمون"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded-lg hover:bg-indigo-700/70 transition {{ request()->routeIs('users.index') ? 'bg-indigo-700' : '' }}">
          <i data-feather="users" class="w-4 h-4"></i> <span>المستخدمون</span>
        </a>
        @endcan

        <a href="{{ route('profile.edit') }}" data-title="الملف الشخصي"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded-lg hover:bg-indigo-700/70 transition {{ request()->routeIs('profile.edit') ? 'bg-indigo-700' : '' }}">
          <i data-feather="user" class="w-4 h-4"></i> <span>الملف الشخصي</span>
        </a>

        @can('read government_entities')
        <a href="{{ route('government_entities.index') }}" data-title="الجهات الحكومية"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded-lg hover:bg-indigo-700/70 transition {{ request()->routeIs('government_entities.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="layers" class="w-4 h-4"></i> <span>الجهات الحكومية</span>
        </a>
        @endcan

        @can('read perspectives')
        <a href="{{ route('perspectives.index') }}" data-title="المناظير"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded-lg hover:bg-indigo-700/70 transition {{ request()->routeIs('perspectives.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="grid" class="w-4 h-4"></i> <span>المناظير</span>
        </a>
        @endcan

        @can('read pillars')
        <a href="{{ route('pillars.index') }}" data-title="المحاور"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded-lg hover:bg-indigo-700/70 transition {{ request()->routeIs('pillars.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="columns" class="w-4 h-4"></i> <span>المحاور</span>
        </a>
        @endcan

        @can('read standards')
        <a href="{{ route('standards.index') }}" data-title="المعايير"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded-lg hover:bg-indigo-700/70 transition {{ request()->routeIs('standards.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="check-square" class="و-4 h-4"></i> <span>المعايير</span>
        </a>
        @endcan

        @can('read tools')
        <a href="{{ route('tools.index') }}" data-title="الأدوات"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded-lg hover:bg-indigo-700/70 transition {{ request()->routeIs('tools.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="tool" class="w-4 h-4"></i> <span>الأدوات</span>
        </a>
        @endcan

        @can('read assignments')
        <a href="{{ route('assignments.index') }}" data-title="إسناد الأدوات"
           class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded-lg hover:bg-indigo-700/70 transition {{ request()->routeIs('assignments.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="shuffle" class="w-4 h-4"></i> <span>إسناد الأدوات</span>
        </a>
        @endcan

        @can('read innovations')
        {{-- الابتكار --}}
        <div class="space-y-1">
          <button type="button"
                id="innovationToggle"
                data-title="الابتكار"
                class="w-full flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-700/70 transition {{ $innovationActive ? 'bg-indigo-700' : '' }}">

          <!-- السهم يمين -->
          <i data-feather="chevron-down"
            id="innovationChevron"
            class="w-4 h-4 transition-transform {{ $innovationActive ? 'rotate-180' : '' }} mr-auto"></i>

          <!-- الأيقونة + النص (يمين) -->
          <div class="flex flex-row-reverse items-center gap-2">
            <i data-feather="zap" class="w-4 h-4"></i>
            <span>الابتكار</span>
          </div>
        </button>
        <div id="innovationMenu"
            class="submenu {{ $innovationActive ? 'open' : '' }} mr-6 pr-3 border-r border-indigo-300/40 bg-indigo-900/10 rounded-lg py-1 space-y-1">
      
          <a href="{{ route('innovation.platforms.index') }}" data-title="المنصات"
              class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded-lg hover:bg-indigo-700/40 transition text-sm text-indigo-50/90 {{ $platformsActive ? 'bg-indigo-700/60 font-semibold' : '' }}">
              <span class="w-1.5 h-1.5 rounded-full {{ $platformsActive ? 'bg-indigo-300' : 'bg-indigo-200' }}"></span>
              <span>المنصات</span>
          </a>
      
          <a href="{{ route('innovation.partnerships.index') }}" data-title="الشراكات والاتفاقيات"
              class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded-lg hover:bg-indigo-700/40 transition text-sm text-indigo-50/90 {{ $partnershipsActive ? 'bg-indigo-700/60 font-semibold' : '' }}">
              <span class="w-1.5 h-1.5 rounded-full {{ $partnershipsActive ? 'bg-indigo-300' : 'bg-indigo-200' }}"></span>
              <span>الشراكات والاتفاقيات</span>
          </a>
      
          <a href="{{ route('innovation.events.index') }}" data-title="الفاعليات"
              class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded-lg hover:bg-indigo-700/40 transition text-sm text-indigo-50/90 {{ $eventsActive ? 'bg-indigo-700/60 font-semibold' : '' }}">
              <span class="w-1.5 h-1.5 rounded-full {{ $eventsActive ? 'bg-indigo-300' : 'bg-indigo-200' }}"></span>
              <span>الفاعليات</span>
          </a>
      
          <a href="{{ route('innovation.frameworks.index') }}" data-title="الاطر والمنهجيات"
              class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded-lg hover:bg-indigo-700/40 transition text-sm text-indigo-50/90 {{ $frameworksActive ? 'bg-indigo-700/60 font-semibold' : '' }}">
              <span class="w-1.5 h-1.5 rounded-full {{ $frameworksActive ? 'bg-indigo-300' : 'bg-indigo-200' }}"></span>
              <span>الاطر والمنهجيات</span>
          </a>

          <a href="{{ route('innovation.assessment.index') }}" data-title="تقييم الابتكار"
              class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded-lg hover:bg-indigo-700/40 transition text-sm text-indigo-50/90 {{ $assessmentActive ? 'bg-indigo-700/60 font-semibold' : '' }}">
              <span class="w-1.5 h-1.5 rounded-full {{ $assessmentActive ? 'bg-indigo-300' : 'bg-indigo-200' }}"></span>
              <span>تقييم الابتكار</span>
          </a>  
      </div>
        </div>
        @endcan
        
        @can('read performance_analysis')
        <a href="{{ route('performance_analysis.index') }}" data-title="تحليل الأداء"
          class="flex flex-row-reverse items-center justify-end gap-2 px-3 py-2 rounded-lg hover:bg-indigo-700/70 transition {{ request()->routeIs('performance_analysis.*') ? 'bg-indigo-700' : '' }}">
          <i data-feather="bar-chart-2" class="w-4 h-4"></i>
          <span>تحليل الأداء</span>
        </a>
        @endcan
      </nav>

      {{-- خروج --}}
      <div class="p-4 border-t border-indigo-700">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="flex flex-row-reverse items-center justify-end gap-2 w-full text-right px-3 py-2 rounded hover:bg-indigo-700">
            <i data-feather="log-out" class="w-4 h-4"></i> <span>تسجيل الخروج</span>
          </button>
        </form>
      </div>
    </aside>

    <div class="content-area flex-1 flex flex-col">
      <button class="mobile-menu-button fixed bottom-4 right-4 z-50 bg-indigo-600 text-white p-3 rounded-full shadow-lg md:hidden">
        <i data-feather="menu" class="w-5 h-5"></i>
      </button>

      <div class="overlay"></div>

      <header class="bg-white shadow px-8 py-6 md:px-8 md:py-6">
        <div class="header-content flex flex-row-reverse justify-between items-center">
          <div class="user-info flex flex-row-reverse items-center gap-3">
            <span class="text-gray-600 text-sm hidden sm:block">{{ Auth::user()->name ?? Auth::user()->email }}</span>
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->email ?? 'مستخدم') }}&background=4F46E5&color=fff"
                 class="h-7 w-7 md:h-8 md:w-8 rounded-full border-2 border-indigo-500">
          </div>
          <h1 class="text-base md:text-lg font-semibold text-gray-800">{{ $pageHeading ?? 'لوحة التحكم' }}</h1>
        </div>

        <button id="toggleSidebar" class="fixed top-5 right-5 z-50 bg-indigo-600 text-white p-2 rounded-full shadow-lg hover:bg-indigo-700 focus:outline-none transition">
          <i data-feather="menu"></i>
        </button>
      </header>

      <main class="flex-1 p-4 md:p-6">
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
    document.addEventListener('DOMContentLoaded', function () {
      feather.replace();

      const menuButton = document.querySelector('.mobile-menu-button');
      const closeButton = document.querySelector('.close-menu');
      const sidebar = document.querySelector('.sidebar');
      const overlay = document.querySelector('.overlay');
      const toggleSidebarBtn = document.getElementById('toggleSidebar');
      const contentArea = document.querySelector('.content-area');

      const innovationToggle = document.getElementById('innovationToggle');
      const innovationMenu = document.getElementById('innovationMenu');
      const innovationChevron = document.getElementById('innovationChevron');

      const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
      if (isCollapsed) {
        sidebar.classList.add('collapsed');
        contentArea.classList.add('expanded');
      }

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

      if (menuButton) menuButton.addEventListener('click', openSidebar);
      if (closeButton) closeButton.addEventListener('click', closeSidebar);
      if (overlay) overlay.addEventListener('click', closeSidebar);

      const sidebarLinks = document.querySelectorAll('.sidebar a');
      sidebarLinks.forEach((link) => {
        link.addEventListener('click', function () {
          if (window.innerWidth < 768) closeSidebar();
        });
      });

      window.addEventListener('resize', function () {
        if (window.innerWidth >= 768) closeSidebar();
      });

      if (toggleSidebarBtn) {
        toggleSidebarBtn.addEventListener('click', () => {
          sidebar.classList.toggle('collapsed');
          contentArea.classList.toggle('expanded');
          localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        });
      }

      if (innovationToggle && innovationMenu && innovationChevron) {
        const innovationOpen = localStorage.getItem('innovationOpen') === 'true';
        if (innovationOpen) {
          innovationMenu.classList.add('open');
          innovationChevron.style.transform = 'rotate(180deg)';
        }

        innovationToggle.addEventListener('click', () => {
          const isOpen = innovationMenu.classList.toggle('open');
          innovationChevron.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';
          localStorage.setItem('innovationOpen', isOpen);
          feather.replace();
        });
      }
    });
  </script>

  {{-- TinyMCE Editor --}}
<script src="https://cdn.jsdelivr.net/npm/tinymce@5.10.7/tinymce.min.js"></script>

<script>
  tinymce.init({
    selector: 'textarea.rich-editor',
    height: 300,
    directionality: "rtl",
    language: "ar",

    plugins: 'lists link table directionality textcolor colorpicker',

    menubar: false,

    toolbar: `
      undo redo |
      fontselect fontsizeselect |
      forecolor backcolor |
      bold italic underline |
      alignleft aligncenter alignright alignjustify |
      bullist numlist |
      link table |
      ltr rtl
    `,

    font_size_formats: "12px 14px 16px 18px 20px 24px 28px 32px 36px 48px",

    font_family_formats: `
      Tajawal=tajawal,sans-serif;
      Cairo=cairo,sans-serif;
      Arial=arial,helvetica,sans-serif;
      Times New Roman=times new roman,times;
      Traditional Arabic='Traditional Arabic';
    `,

    content_style: `
      body {
        font-family: 'Tajawal', sans-serif;
        font-size: 14px;
      }
    `
  });
</script>
</body>
</html>
