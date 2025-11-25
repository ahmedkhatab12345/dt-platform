@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto bg-white rounded-2xl shadow-xl p-8 space-y-10">

    {{-- 🔹 العنوان --}}
    <div class="flex justify-between items-center border-b pb-4">
        <h2 class="text-2xl font-extrabold text-gray-800 flex items-center gap-2">
            <i class="fa fa-chart-line text-indigo-600"></i>
            تقرير إنتاجية الفريق (عدد المشاريع المضافة يوميًا)
        </h2>
    </div>

    {{-- 🔍 الفلترة --}}
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">من تاريخ</label>
            <input type="date" name="from_date" value="{{ request('from_date', optional($from)->toDateString()) }}" 
                   class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">إلى تاريخ</label>
            <input type="date" name="to_date" value="{{ request('to_date', optional($to)->toDateString()) }}" 
                   class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">المستخدم</label>
            <select name="user_id" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                <option value="all">جميع الأعضاء</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected($selectedUser == $user->id)>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" 
                class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg w-full flex items-center justify-center gap-2 transition">
                <i class="fa fa-search"></i>
                تطبيق الفلترة
            </button>
        </div>
    </form>

    {{-- 📊 جدول الإنتاجية --}}
    <div class="bg-gradient-to-b from-gray-50 to-white rounded-2xl shadow-xl p-6 mt-8 fade-in">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-center border border-gray-200 rounded-xl shadow-lg text-sm text-gray-700">
                <thead class="bg-indigo-600 text-white uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-5 py-3 border border-indigo-400">التاريخ</th>
                        @foreach($users as $user)
                            <th class="px-5 py-3 border border-indigo-400">{{ $user->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($allDates as $date)
                        <tr class="odd:bg-gray-50 even:bg-white hover:bg-indigo-50 transition">
                            <td class="px-5 py-3 border font-semibold text-gray-800">{{ $date }}</td>
                            @foreach($users as $user)
                                @php
                                    $count = $matrix[$date][$user->id] ?? 0;
                                    $color = match(true) {
                                        $count >= 5 => 'bg-green-600 text-white',
                                        $count >= 3 => 'bg-green-500 text-white',
                                        $count == 2 => 'bg-yellow-500 text-gray-900 font-bold',
                                        $count == 1 => 'bg-blue-600 text-white font-semibold',
                                        default => 'bg-gray-100 text-gray-400'
                                    };
                                @endphp
                                <td class="px-5 py-3 border font-semibold text-gray-900 text-lg">
                                    {{ $count }}
                                </td>
                                
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $users->count() + 1 }}" class="py-5 text-center text-gray-400">
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
