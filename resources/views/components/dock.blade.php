
 @vite([
        'resources/css/dashboard.css',
        'resources/js/dashboard.js',
        'resources/css/dock.css',
        'resources/js/dock.js'

    ])
<div class="dock-wrap">
    <div class="dock">

        {{-- MRP & Manufaktur --}}
        <a
            href="{{ url('/MRP') }}"
            class="dock-item"
            data-title="MRP & Manufaktur"
            style="background: linear-gradient(135deg, #0A84FF, #5E5CE6);"
        >
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                <rect x="3" y="3" width="7" height="9" rx="1.5" />
                <rect x="14" y="3" width="7" height="5" rx="1.5" />
                <rect x="14" y="12" width="7" height="9" rx="1.5" />
                <rect x="3" y="16" width="7" height="5" rx="1.5" />
            </svg>
            <span class="dock-dot"></span>
        </a>

        {{-- CRM / Penjualan --}}
        <a
            href="{{ url('/CRM') }}"
            class="dock-item"
            data-title="Penjualan & CRM"
            style="background: linear-gradient(135deg, #34C759, #28A745);"
        >
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" />
            </svg>
        </a>

        {{-- SCM / Rantai Pasok & Inventori --}}
        <a
            href="{{ url('/SCM') }}"
            class="dock-item"
            data-title="Rantai Pasok (SCM)"
            style="background: linear-gradient(135deg, #FF9F0A, #FF7A00);"
        >
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                <path d="M21 8l-9-5-9 5 9 5 9-5z" />
                <path d="M3 8v8l9 5 9-5V8" />
            </svg>
        </a>

        {{-- FICO / Keuangan --}}
        <a
            href="{{ url('/FICO') }}"
            class="dock-item"
            data-title="Keuangan (FICO)"
            style="background: linear-gradient(135deg, #64D2FF, #0A84FF);"
        >
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                <path d="M4 10h16M4 14h16M7 6h10a2 2 0 012 2v8a2 2 0 01-2 2H7a2 2 0 01-2-2V8a2 2 0 012-2z" />
            </svg>
        </a>

        {{-- HRIS / SDM --}}
        <a
            href="{{ url('/HRIS') }}"
            class="dock-item"
            data-title="SDM & HRIS"
            style="background: linear-gradient(135deg, #5E5CE6, #8E5CE6);"
        >
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                <circle cx="9" cy="8" r="3.2" />
                <path d="M2.5 20c0-3.6 2.9-6.3 6.5-6.3S15.5 16.4 15.5 20" />
                <circle cx="18" cy="9" r="2.4" />
            </svg>
        </a>

        {{-- Separator --}}
        <div class="dock-item sep"></div>

        {{-- Analitik --}}
        <a
            href="#"
            class="dock-item"
            data-title="Analitik & BI"
            style="background: linear-gradient(135deg, #8E8E93, #636366);"
        >
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                <path d="M4 19h16M8 19V9m4 10V5m4 14v-7" />
            </svg>
        </a>

        {{-- Pengaturan --}}
        <a
            href="#"
            class="dock-item"
            data-title="Pengaturan Sistem"
            style="background: linear-gradient(135deg, #636366, #3A3A3C);"
        >
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                <circle cx="12" cy="12" r="3" />
                <path d="M19.4 15a1.7 1.7 0 00.3 1.9l.1.1a2 2 0 11-2.8 2.8" />
            </svg>
        </a>

        {{-- Delete / Trash --}}
        <a
            href="#"
            class="dock-item"
            data-title="Hapus / Recycle Bin"
            style="background: linear-gradient(135deg, #FF3B30, #C81E1E);"
        >
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                <path d="M3 6h18" />
                <path d="M8 6V4h8v2" />
                <path d="M6 6l1 14h10l1-14" />
            </svg>
        </a>

    </div>
</div>