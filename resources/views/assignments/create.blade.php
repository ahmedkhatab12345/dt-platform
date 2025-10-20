@extends('layouts.admin')

@php($title = 'إسناد الأدوات')
@php($pageHeading = 'إسناد الأدوات إلى المعايير والجهات')

@section('content')
<div class="bg-white shadow rounded-xl p-6 max-w-4xl mx-auto">
  <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
    <i data-feather="link" class="w-6 h-6 text-indigo-600"></i>
    إسناد الأدوات
  </h2>

  <form method="POST" action="{{ route('assignments.store') }}" class="space-y-6">
    @csrf

    <div id="assignments-wrapper" class="space-y-5">
      <div class="assignment-row grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-lg border relative">

        {{-- أداة --}}
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">الأداة</label>
          <select name="assignments[0][tool_id]"
                  class="auto-search w-full border-gray-300 rounded-lg px-3 py-2">
            @foreach($tools as $tool)
              <option value="{{ $tool->id }}">{{ $tool->name }}</option>
            @endforeach
          </select>
        </div>

        {{-- المعيار --}}
        <div class="flex-1">
          <label class="block text-sm font-medium text-gray-700 mb-1">المعيار</label>
          <div class="flex items-center gap-2">
            <select name="assignments[0][standard_id]"
                    class="auto-search w-full border-gray-300 rounded-lg px-3 py-2">
              @foreach($standards as $std)
                <option value="{{ $std->id }}">{{ $std->name }}</option>
              @endforeach
            </select>
            <label class="flex items-center gap-1 text-xs text-gray-600">
              <input type="checkbox" id="lockStandard" class="rounded border-gray-300">
              تثبيت
            </label>
          </div>
        </div>

        {{-- الجهة الحكومية --}}
        <div class="flex-1">
          <label class="block text-sm font-medium text-gray-700 mb-1">الجهة الحكومية</label>
          <div class="flex items-center gap-2">
            <select name="assignments[0][government_entity_id]"
                    class="auto-search w-full border-gray-300 rounded-lg px-3 py-2">
              @foreach($entities as $entity)
                <option value="{{ $entity->id }}">{{ $entity->name }}</option>
              @endforeach
            </select>
            <label class="flex items-center gap-1 text-xs text-gray-600">
              <input type="checkbox" id="lockGovEntity" class="rounded border-gray-300">
              تثبيت
            </label>
          </div>
        </div>

        {{-- زر حذف --}}
        <button type="button"
                class="absolute -top-2 -left-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center shadow hover:bg-red-600 remove-row hidden">
          &times;
        </button>
      </div>
    </div>

    {{-- قالب الصف الجديد --}}
    <template id="row-tpl">
      <div class="assignment-row grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-lg border relative">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">الأداة</label>
          <select name="assignments[__INDEX__][tool_id]"
                  class="auto-search w-full border-gray-300 rounded-lg px-3 py-2">
            @foreach($tools as $tool)
              <option value="{{ $tool->id }}">{{ $tool->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="flex-1">
          <label class="block text-sm font-medium text-gray-700 mb-1">المعيار</label>
          <select name="assignments[__INDEX__][standard_id]"
                  class="auto-search w-full border-gray-300 rounded-lg px-3 py-2">
            @foreach($standards as $std)
              <option value="{{ $std->id }}">{{ $std->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="flex-1">
          <label class="block text-sm font-medium text-gray-700 mb-1">الجهة الحكومية</label>
          <select name="assignments[__INDEX__][government_entity_id]"
                  class="auto-search w-full border-gray-300 rounded-lg px-3 py-2">
            @foreach($entities as $entity)
              <option value="{{ $entity->id }}">{{ $entity->name }}</option>
            @endforeach
          </select>
        </div>

        <button type="button"
                class="absolute -top-2 -left-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center shadow hover:bg-red-600 remove-row">
          &times;
        </button>
      </div>
    </template>

    {{-- إضافة صف --}}
    <div>
      <button type="button" id="add-row"
              class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 text-sm flex items-center gap-1">
        <i data-feather="plus-circle" class="w-4 h-4"></i>
        إضافة صف آخر
      </button>
    </div>

    {{-- أزرار التحكم --}}
    <div class="flex justify-end gap-3">
      <a href="{{ route('assignments.index') }}"
         class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">إلغاء</a>
      <button type="submit"
              class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
        حفظ الإسناد
      </button>
    </div>
  </form>
</div>

{{-- Tom Select --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<script>
  let index = 1;
  const wrapper = document.getElementById('assignments-wrapper');
  const addBtn = document.getElementById('add-row');
  const tpl = document.getElementById('row-tpl');

  const lockStd = document.getElementById('lockStandard');
  const lockGov = document.getElementById('lockGovEntity');
  const firstStdSelect = wrapper.querySelector('select[name="assignments[0][standard_id]"]');
  const firstGovSelect = wrapper.querySelector('select[name="assignments[0][government_entity_id]"]');

  function initAutoSearch(select) {
    if (select.tomselect) {
      try { select.tomselect.destroy(); } catch (e) {}
    }
    new TomSelect(select, {
      create: false,
      sortField: { field: "text", direction: "asc" },
      placeholder: "ابحث...",
      dropdownParent: "body"
    });
  }

  document.querySelectorAll('.auto-search').forEach(sel => initAutoSearch(sel));

  function updateRemoveButtons() {
    const rows = wrapper.querySelectorAll('.assignment-row');
    rows.forEach((row, i) => {
      const btn = row.querySelector('.remove-row');
      btn.style.display = i === 0 ? 'none' : 'flex';
      btn.onclick = () => row.remove();
    });
  }

  function addRow() {
    const node = document.importNode(tpl.content, true);
    node.querySelectorAll('select').forEach(select => {
      select.name = select.name.replace('__INDEX__', index);
      initAutoSearch(select);
    });

    wrapper.appendChild(node);

    const newRow = wrapper.querySelectorAll('.assignment-row')[wrapper.querySelectorAll('.assignment-row').length - 1];

    if (lockStd.checked) {
      const stdSel = newRow.querySelector('select[name*="[standard_id]"]').tomselect;
      stdSel.setValue(firstStdSelect.tomselect.getValue());
    }

    if (lockGov.checked) {
      const govSel = newRow.querySelector('select[name*="[government_entity_id]"]').tomselect;
      govSel.setValue(firstGovSelect.tomselect.getValue());
    }

    index++;
    updateRemoveButtons();
  }

  addBtn.addEventListener('click', addRow);
  updateRemoveButtons();
</script>
@endsection
