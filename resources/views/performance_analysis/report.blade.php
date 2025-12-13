@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto bg-white rounded-2xl shadow-xl p-8 space-y-8">

  {{-- Header --}}
  <div class="flex items-center justify-between border-b pb-4">
    <div>
      <h2 class="text-2xl font-extrabold text-gray-800 flex items-center gap-2">
        <i class="fa fa-chart-line text-indigo-600"></i>
        {{ $reportLabel }} <span class="text-gray-500 font-semibold">({{ $reportModelName }})</span>
      </h2>
      <p class="text-sm text-gray-500 mt-1">
        افتراضيًا: عرض آخر 5 أيام — ويمكنك تغيير المدة من الفلتر.
      </p>
    </div>

    <a href="{{ route('performance_analysis.index') }}"
       class="text-sm text-indigo-700 hover:text-indigo-900 font-semibold hover:underline">
      رجوع للاختيارات
    </a>
  </div>

  {{-- Filter Card --}}
  <form method="GET" action="{{ route('performance_analysis.report', $reportKey) }}"
        class="bg-gray-50 border border-gray-200 rounded-2xl p-5">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">من تاريخ</label>
        <input type="date" name="from_date"
               value="{{ request('from_date', optional($from)->toDateString()) }}"
               class="w-full border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">إلى تاريخ</label>
        <input type="date" name="to_date"
               value="{{ request('to_date', optional($to)->toDateString()) }}"
               class="w-full border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">المستخدم</label>
        <select name="user_id"
                class="w-full border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
          <option value="all">جميع الأعضاء (اللي اشتغلوا)</option>
          @foreach($usersForFilter as $user)
            <option value="{{ $user->id }}" @selected($selectedUser == $user->id)>{{ $user->name }}</option>
          @endforeach
        </select>
      </div>

      <button type="submit"
              class="h-11 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl w-full flex items-center justify-center gap-2 transition">
        <i class="fa fa-search"></i>
        تطبيق
      </button>
    </div>

    <div class="mt-3 text-xs text-gray-500">
      لو الفترة مفيهاش نشاط، هتشوف أصفار عادي.
    </div>
  </form>

  {{-- Table (Full width + responsive) --}}
  <div class="border border-gray-200 rounded-2xl overflow-hidden bg-white shadow-sm">
    <div class="w-full overflow-x-auto">
      <table class="w-full min-w-max text-center text-sm table-auto">
        <thead class="bg-indigo-600 text-white">
          <tr>
            {{-- التاريخ Sticky يمين --}}
            <th class="sticky right-0 z-20 bg-indigo-600 px-4 py-3 border-l border-indigo-500/40 whitespace-nowrap">
              اليوم / التاريخ
            </th>

            @foreach($usersForTable as $user)
              <th class="px-4 py-3 border-l border-indigo-500/40 whitespace-nowrap min-w-[160px]">
                {{ $user->name }}
              </th>
            @endforeach
          </tr>
        </thead>

        <tbody class="divide-y divide-gray-100">
            @forelse($allDates as $date)
            @php
              $c = \Carbon\Carbon::parse($date)->locale('ar');
              $dayName = $c->translatedFormat('l');       // السبت، الأحد...
              $iso = $c->dayOfWeekIso;                    // Mon=1 ... Sun=7
              $isWeekend = in_array($iso, [5, 6]);        // ✅ الجمعة(5) والسبت(6)
            @endphp
          
            <tr class="{{ $isWeekend ? 'bg-amber-50' : '' }} hover:bg-gray-50 transition">
              {{-- عمود اليوم/التاريخ (Sticky يمين) --}}
              <td class="sticky right-0 z-10 px-4 py-3 font-semibold whitespace-nowrap border-l border-gray-200
                         {{ $isWeekend ? 'bg-amber-100 text-amber-900' : 'bg-gray-50 text-gray-800' }}">
                <div class="flex items-center justify-between gap-2">
                  <div>
                    <div class="font-extrabold">{{ $dayName }}</div>
                    <div class="text-xs opacity-80 font-normal">{{ $date }}</div>
                  </div>
          
                  @if($isWeekend)
                    <span class="text-[11px] font-bold px-2 py-1 rounded-full bg-amber-200 text-amber-900">
                      إجازة
                    </span>
                  @endif
                </div>
              </td>
          
              {{-- أعمدة المستخدمين --}}
              @foreach($usersForTable as $user)
              @php
              $count = $matrix[$date][$user->id] ?? 0;

              $badge = $count == 0
                  ? 'bg-gray-100 text-gray-500 border-gray-200'
                  : 'bg-white text-gray-900 border-gray-300';
              @endphp

              <td class="px-4 py-3 {{ $isWeekend ? 'bg-amber-50/40' : '' }}">
              <div class="mx-auto inline-flex items-center justify-center w-16 h-10 rounded-xl border font-extrabold text-base shadow-sm {{ $badge }}">
                  {{ $count }}
              </div>
              </td>
              @endforeach
            </tr>
          @empty
            <tr>
              <td colspan="{{ $usersForTable->count() + 1 }}" class="py-10 text-gray-400">
                لا توجد بيانات.
              </td>
            </tr>
          @endforelse          
        </tbody>

      </table>
    </div>
  </div>

</div>
@endsection
