{{--
    Dock ala macOS berisi 5 modul ERP.
    Klik salah satu ikon akan menukar konten window pada hero (lihat landing.js).
--}}
@php
    $modules = [
        ['key' => 'crm', 'label' => 'CRM', 'window' => 'CRM — Sales Pipeline'],
        ['key' => 'mrp', 'label' => 'MRP', 'window' => 'MRP — Perencanaan Produksi'],
        ['key' => 'scm', 'label' => 'SCM', 'window' => 'SCM — Rantai Pasok'],
        ['key' => 'fico', 'label' => 'FICO', 'window' => 'FICO — Finance & Controlling'],
        ['key' => 'hris', 'label' => 'HRIS', 'window' => 'HRIS — Manajemen SDM'],
    ];
@endphp

<div class="dock" data-dock role="tablist" aria-label="Pilih modul untuk pratinjau">
    @foreach ($modules as $index => $module)
        <button
            type="button"
            class="dock__item @if ($index === 0) is-active @endif"
            data-module="{{ $module['key'] }}"
            data-window-label="{{ $module['window'] }}"
            role="tab"
            aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
        >
            {{ $module['label'] }}
            <span class="dock__label">{{ $module['label'] }}</span>
        </button>
    @endforeach
</div>
