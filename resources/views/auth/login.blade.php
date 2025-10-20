<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تسجيل الدخول</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="min-h-screen flex items-center justify-center bg-slate-50 relative">
  <!-- خلفية متدرجة ناعمة -->
  <div class="absolute inset-0 -z-10 overflow-hidden">
    <div class="absolute -top-24 -right-16 h-80 w-80 rounded-full bg-gradient-to-tr from-indigo-400 to-violet-400 blur-3xl opacity-30"></div>
    <div class="absolute -bottom-24 -left-16 h-96 w-96 rounded-full bg-gradient-to-tr from-cyan-400 to-blue-400 blur-3xl opacity-30"></div>
  </div>

  <!-- البطاقة -->
  <div class="w-full max-w-[420px] rounded-3xl bg-white/80 backdrop-blur-md border border-white/50 shadow-2xl p-8 text-right">
    <div class="text-center">
      <h1 class="text-2xl font-semibold text-slate-900">تسجيل الدخول</h1>
      <p class="mt-1 text-sm text-slate-500">أدخل بياناتك للوصول إلى حسابك</p>
    </div>

    @if (session('status'))
      <div class="mt-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-2">
        {{ session('status') }}
      </div>
    @endif

    <form id="loginForm" method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
      @csrf

      <!-- البريد الإلكتروني -->
      <div>
        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">البريد الإلكتروني</label>
        <div class="relative">
          <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
            placeholder="example@mail.com"
            class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-300 bg-white/70 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-right">
          <svg class="absolute right-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-width="2" d="M4 6h16M4 6l8 6 8-6M4 18h16V6" />
          </svg>
        </div>
        @error('email')
          <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- كلمة المرور -->
      <div>
        <div class="flex items-center justify-between mb-1">
          <label for="password" class="block text-sm font-medium text-slate-700">كلمة المرور</label>
          @if (Route::has('password.request'))
            {{-- <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-800">نسيت كلمة المرور؟</a> --}}
          @endif
        </div>
      
        <div class="relative">
          <input id="password" type="password" name="password" required autocomplete="current-password"
            placeholder="••••••••"
            class="w-full pr-4 pl-14 py-2 rounded-xl border border-slate-300 bg-white/70 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-right">
          
          <!-- الأيقونة -->
          <svg class="absolute right-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-width="2" d="M12 15c1.657 0 3-.895 3-2s-1.343-2-3-2-3 .895-3 2 1.343 2 3 2z" />
            <path stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg>
      
          <!-- الزر داخل الحقل -->
          <button type="button" id="togglePassword"
            class="absolute left-3 top-1/2 -translate-y-1/2 text-xs px-2 py-1 rounded-md text-slate-600 hover:text-slate-800 bg-white/50">
            إظهار
          </button>
        </div>
      
        @error('password')
          <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>
      

      <!-- تذكرني -->
      <div class="flex items-center justify-between">
        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
          <input id="remember_me" type="checkbox" name="remember"
            class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
          تذكرني
        </label>
      </div>

      <!-- الزر -->
      <button id="submitBtn" type="submit"
        class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 text-white font-medium py-2.5 shadow-lg hover:bg-indigo-700 hover:shadow-xl active:scale-[.99] transition">
        <svg id="spinner" class="hidden h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg>
        <span>تسجيل الدخول</span>
      </button>
    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const pwd = document.getElementById('password');
      const toggle = document.getElementById('togglePassword');
      const form = document.getElementById('loginForm');
      const submit = document.getElementById('submitBtn');
      const spinner = document.getElementById('spinner');

      if (toggle) {
        toggle.addEventListener('click', function () {
          const isHidden = pwd.type === 'password';
          pwd.type = isHidden ? 'text' : 'password';
          this.textContent = isHidden ? 'إخفاء' : 'إظهار';
          this.classList.toggle('text-indigo-600', isHidden);
        });
      }

      if (form) {
        form.addEventListener('submit', function () {
          submit.disabled = true;
          spinner.classList.remove('hidden');
        });
      }
    });
  </script>
</body>
</html>
