@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto bg-white rounded-2xl shadow-xl p-8 space-y-6">

  <div class="flex items-center justify-between border-b pb-4">
    <h2 class="text-2xl font-extrabold text-gray-800 flex items-center gap-2">
      <i class="fa fa-bar-chart text-indigo-600"></i>
      تحليل الأداء - اختر نوع التقرير
    </h2>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    @foreach($reports as $key => $r)
      @php
        $disabled = isset($r['enabled']) && $r['enabled'] === false;
      @endphp

      @if($disabled)
        <div class="rounded-2xl border p-5 bg-gray-50 opacity-60 cursor-not-allowed select-none">
          <div class="font-bold text-gray-900 text-lg">{{ $r['label'] }}</div>
          <div class="text-sm text-gray-600 mt-1">
            Model: <span class="font-semibold text-indigo-700">{{ $r['modelName'] }}</span>
          </div>
          <div class="text-xs text-gray-400 mt-2">{{ $key }}</div>

          <div class="mt-3 inline-flex items-center gap-2 text-xs font-bold px-3 py-1 rounded-full bg-red-100 text-red-700">
            <i class="fa fa-lock"></i>
            مغلق مؤقتًا
          </div>
        </div>
      @else
        <a href="{{ route('performance_analysis.report', $key) }}"
           class="rounded-2xl border p-5 hover:shadow-lg transition bg-gray-50 hover:bg-indigo-50">
          <div class="font-bold text-gray-900 text-lg">{{ $r['label'] }}</div>
          <div class="text-sm text-gray-600 mt-1">
            Model: <span class="font-semibold text-indigo-700">{{ $r['modelName'] }}</span>
          </div>
          <div class="text-xs text-gray-400 mt-2">{{ $key }}</div>

          <div class="mt-3 inline-flex items-center gap-2 text-xs font-bold px-3 py-1 rounded-full bg-green-100 text-green-700">
            <i class="fa fa-check"></i>
            متاح
          </div>
        </a>
      @endif
    @endforeach
  </div>

</div>
@endsection
