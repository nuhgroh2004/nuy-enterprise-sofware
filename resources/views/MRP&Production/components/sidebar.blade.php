<div class="sidebar" id="sidebar">

    {{-- PROFILE --}}
    <div class="profile">

        <div class="avatar">
            PT
        </div>

        <div class="who">

            <div class="name">
                PT Nusantara Jaya
            </div>

            <div class="role">
                Admin • Owner
            </div>

        </div>

    </div>


    {{-- UTAMA --}}
    <div class="section-label">
        Utama
    </div>


    {{-- Dashboard --}}
    <a href="{{ route('mrp.dashboard') }}"
       class="navitem {{ request()->route()->getName() === 'mrp.dashboard' ? 'active' : '' }}">

        <span
            class="ico"
            style="background:var(--accent);"
        >
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="#fff"
                stroke-width="2"
            >
                <rect
                    x="3"
                    y="3"
                    width="7"
                    height="9"
                    rx="1.5"
                />

                <rect
                    x="14"
                    y="3"
                    width="7"
                    height="5"
                    rx="1.5"
                />

                <rect
                    x="14"
                    y="12"
                    width="7"
                    height="9"
                    rx="1.5"
                />

                <rect
                    x="3"
                    y="16"
                    width="7"
                    height="5"
                    rx="1.5"
                />
            </svg>
        </span>

        Dashboard

    </a>


    {{-- Planning --}}
    <div class="navitem has-sub {{ request()->is('MRP/demand-planning') || request()->is('MRP/master-production-schedule') || request()->is('MRP/material-requirements') ? 'open' : '' }}"
         data-submenu="planning">

        <span
            class="ico"
            style="background:var(--accent-2);"
        >
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="#fff"
                stroke-width="2"
            >
                <path
                    d="M4 6h16M4 12h10M4 18h14"
                    stroke-linecap="round"
                />
            </svg>
        </span>

        Planning

        <span class="arrow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>

    </div>

    <div class="submenu {{ request()->is('MRP/demand-planning') || request()->is('MRP/master-production-schedule') || request()->is('MRP/material-requirements') ? 'open' : '' }}"
         id="submenu-planning">

        <a href="{{ route('mrp.demand-planning') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.demand-planning' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(94,92,230,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent-2)" stroke-width="2">
                    <path d="M3 3v18h18" stroke-linecap="round"/>
                    <path d="M7 14l4-4 3 3 5-6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            Demand Planning
        </a>

        <a href="{{ route('mrp.master-production-schedule') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.master-production-schedule' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(94,92,230,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent-2)" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <path d="M3 9h18M9 3v18" stroke-linecap="round"/>
                </svg>
            </span>
            Master Production Schedule
        </a>

        <a href="{{ route('mrp.material-requirements') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.material-requirements' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(94,92,230,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent-2)" stroke-width="2">
                    <path d="M6 2h9l3 3v17H6z"/>
                    <path d="M9 12h6M9 16h6M9 8h3"/>
                </svg>
            </span>
            Material Requirements
        </a>

    </div>


    {{-- Products --}}
    <div class="navitem has-sub {{ request()->is('MRP/products') || request()->is('MRP/bill-of-materials') || request()->is('MRP/routing') || request()->is('MRP/work-centers') ? 'open' : '' }}"
         data-submenu="products">

        <span
            class="ico"
            style="background:var(--orange);"
        >
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="#fff"
                stroke-width="2"
            >
                <path d="M21 8l-9-5-9 5 9 5 9-5z"/>
                <path d="M3 8v8l9 5 9-5V8"/>
                <path d="M12 13v8"/>
            </svg>
        </span>

        Products

        <span class="arrow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>

    </div>

    <div class="submenu {{ request()->is('MRP/products') || request()->is('MRP/bill-of-materials') || request()->is('MRP/routing') || request()->is('MRP/work-centers') ? 'open' : '' }}"
         id="submenu-products">

        <a href="{{ route('mrp.products') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.products' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(255,159,10,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--orange)" stroke-width="2">
                    <path d="M21 8l-9-5-9 5 9 5 9-5z"/>
                    <path d="M3 8v8l9 5 9-5V8"/>
                </svg>
            </span>
            Products
        </a>

        <a href="{{ route('mrp.bill-of-materials') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.bill-of-materials' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(255,159,10,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--orange)" stroke-width="2">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <path d="M14 2v6h6M12 18v-6M9 15h6"/>
                </svg>
            </span>
            Bill of Materials
        </a>

        <a href="{{ route('mrp.routing') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.routing' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(255,159,10,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--orange)" stroke-width="2">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                </svg>
            </span>
            Routing
        </a>

        <a href="{{ route('mrp.work-centers') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.work-centers' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(255,159,10,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--orange)" stroke-width="2">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 7V5a4 4 0 00-8 0v2"/>
                </svg>
            </span>
            Work Centers
        </a>

    </div>


    {{-- Production --}}
    <div class="navitem has-sub {{ request()->is('MRP/production-orders') || request()->is('MRP/production-schedule') || request()->is('MRP/material-consumption') || request()->is('MRP/work-orders') || request()->is('MRP/production-results') ? 'open' : '' }}"
         data-submenu="production">

        <span
            class="ico"
            style="background:var(--green);"
        >
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="#fff"
                stroke-width="2"
            >
                <path
                    d="M3 3v18h18"
                    stroke-linecap="round"
                />

                <path
                    d="M7 14l4-4 3 3 5-6"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
        </span>

        Production

        <span class="arrow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>

    </div>

    <div class="submenu {{ request()->is('MRP/production-orders') || request()->is('MRP/production-schedule') || request()->is('MRP/material-consumption') || request()->is('MRP/work-orders') || request()->is('MRP/production-results') ? 'open' : '' }}"
         id="submenu-production">

        <a href="{{ route('mrp.production-orders') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.production-orders' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(48,209,88,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" stroke-linecap="round"/>
                </svg>
            </span>
            Production Orders
        </a>

        <a href="{{ route('mrp.production-schedule') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.production-schedule' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(48,209,88,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
            </span>
            Production Schedule
        </a>

        <a href="{{ route('mrp.material-consumption') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.material-consumption' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(48,209,88,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2">
                    <path d="M6 2h9l3 3v17H6z"/>
                    <path d="M9 12h6M9 16h6"/>
                </svg>
            </span>
            Material Consumption
        </a>

        <a href="{{ route('mrp.work-orders') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.work-orders' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(48,209,88,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <path d="M14 2v6h6"/>
                    <path d="M9 15l2 2 4-4"/>
                </svg>
            </span>
            Work Orders
        </a>

        <a href="{{ route('mrp.production-results') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.production-results' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(48,209,88,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                    <path d="M22 4L12 14.01l-3-3"/>
                </svg>
            </span>
            Production Results
        </a>

    </div>


    {{-- Inventory --}}
    <div class="navitem has-sub {{ request()->is('MRP/material-availability') || request()->is('MRP/material-issue') || request()->is('MRP/finished-goods') || request()->is('MRP/stock-movement') ? 'open' : '' }}"
         data-submenu="inventory">

        <span
            class="ico"
            style="background:var(--teal);"
        >
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="#fff"
                stroke-width="2"
            >
                <path d="M21 8l-9-5-9 5 9 5 9-5z"/>
                <path d="M3 8v8l9 5 9-5V8"/>
                <path d="M12 13v8"/>
            </svg>
        </span>

        Inventory

        <span class="arrow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>

    </div>

    <div class="submenu {{ request()->is('MRP/material-availability') || request()->is('MRP/material-issue') || request()->is('MRP/finished-goods') || request()->is('MRP/stock-movement') ? 'open' : '' }}"
         id="submenu-inventory">

        <a href="{{ route('mrp.material-availability') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.material-availability' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(100,210,255,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2">
                    <path d="M9 12l2 2 4-4"/>
                    <circle cx="12" cy="12" r="10"/>
                </svg>
            </span>
            Material Availability
        </a>

        <a href="{{ route('mrp.material-issue') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.material-issue' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(100,210,255,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                    <circle cx="12" cy="12" r="10"/>
                </svg>
            </span>
            Material Issue
        </a>

        <a href="{{ route('mrp.finished-goods') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.finished-goods' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(100,210,255,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2">
                    <path d="M21 8l-9-5-9 5 9 5 9-5z"/>
                    <path d="M3 8v8l9 5 9-5V8"/>
                    <path d="M12 13v8"/>
                </svg>
            </span>
            Finished Goods
        </a>

        <a href="{{ route('mrp.stock-movement') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.stock-movement' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(100,210,255,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2">
                    <path d="M7 17l9.2-9.2M17 17V7H7"/>
                </svg>
            </span>
            Stock Movement
        </a>

    </div>


    {{-- LAPORAN --}}
    <div class="section-label">
        Laporan
    </div>


    {{-- Quality --}}
    <div class="navitem has-sub {{ request()->is('MRP/quality-inspection') || request()->is('MRP/inspection-results') || request()->is('MRP/non-conformance') || request()->is('MRP/rework') ? 'open' : '' }}"
         data-submenu="quality">

        <span
            class="ico"
            style="background:var(--red);"
        >
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="#fff"
                stroke-width="2"
            >
                <path
                    d="M9 12l2 2 4-4"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"
                />
            </svg>
        </span>

        Quality

        <span class="arrow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>

    </div>

    <div class="submenu {{ request()->is('MRP/quality-inspection') || request()->is('MRP/inspection-results') || request()->is('MRP/non-conformance') || request()->is('MRP/rework') ? 'open' : '' }}"
         id="submenu-quality">

        <a href="{{ route('mrp.quality-inspection') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.quality-inspection' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(255,69,58,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2">
                    <path d="M9 12l2 2 4-4"/>
                    <circle cx="12" cy="12" r="10"/>
                </svg>
            </span>
            Quality Inspection
        </a>

        <a href="{{ route('mrp.inspection-results') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.inspection-results' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(255,69,58,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                    <path d="M22 4L12 14.01l-3-3"/>
                </svg>
            </span>
            Inspection Results
        </a>

        <a href="{{ route('mrp.non-conformance') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.non-conformance' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(255,69,58,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M15 9l-6 6M9 9l6 6"/>
                </svg>
            </span>
            Non-Conformance
        </a>

        <a href="{{ route('mrp.rework') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.rework' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(255,69,58,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2">
                    <path d="M1 4v6h6"/>
                    <path d="M3.51 15a9 9 0 105.64-11.36L3 9"/>
                </svg>
            </span>
            Rework
        </a>

    </div>


    {{-- Maintenance --}}
    <div class="navitem has-sub {{ request()->is('MRP/equipment') || request()->is('MRP/maintenance-schedule') || request()->is('MRP/maintenance-history') ? 'open' : '' }}"
         data-submenu="maintenance">

        <span
            class="ico"
            style="background:#8E8E93;"
        >
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="#fff"
                stroke-width="2"
            >
                <path
                    d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
        </span>

        Maintenance

        <span class="arrow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>

    </div>

    <div class="submenu {{ request()->is('MRP/equipment') || request()->is('MRP/maintenance-schedule') || request()->is('MRP/maintenance-history') ? 'open' : '' }}"
         id="submenu-maintenance">

        <a href="{{ route('mrp.equipment') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.equipment' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(142,142,147,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="#8E8E93" stroke-width="2">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 7V5a4 4 0 00-8 0v2"/>
                </svg>
            </span>
            Equipment
        </a>

        <a href="{{ route('mrp.maintenance-schedule') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.maintenance-schedule' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(142,142,147,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="#8E8E93" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
            </span>
            Maintenance Schedule
        </a>

        <a href="{{ route('mrp.maintenance-history') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.maintenance-history' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(142,142,147,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="#8E8E93" stroke-width="2">
                    <path d="M12 20h9"/>
                    <path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/>
                </svg>
            </span>
            Maintenance History
        </a>

    </div>


    {{-- Costing --}}
    <div class="navitem has-sub {{ request()->is('MRP/product-cost') || request()->is('MRP/material-cost') || request()->is('MRP/labor-cost') || request()->is('MRP/overhead') || request()->is('MRP/production-cost') ? 'open' : '' }}"
         data-submenu="costing">

        <span
            class="ico"
            style="background:var(--yellow);"
        >
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="#fff"
                stroke-width="2"
            >
                <path
                    d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"
                    stroke-linecap="round"
                />
            </svg>
        </span>

        Costing

        <span class="arrow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>

    </div>

    <div class="submenu {{ request()->is('MRP/product-cost') || request()->is('MRP/material-cost') || request()->is('MRP/labor-cost') || request()->is('MRP/overhead') || request()->is('MRP/production-cost') ? 'open' : '' }}"
         id="submenu-costing">

        <a href="{{ route('mrp.product-cost') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.product-cost' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(255,214,10,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--yellow)" stroke-width="2">
                    <path d="M21 8l-9-5-9 5 9 5 9-5z"/>
                    <path d="M3 8v8l9 5 9-5V8"/>
                </svg>
            </span>
            Product Cost
        </a>

        <a href="{{ route('mrp.material-cost') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.material-cost' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(255,214,10,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--yellow)" stroke-width="2">
                    <path d="M6 2h9l3 3v17H6z"/>
                    <path d="M9 12h6"/>
                </svg>
            </span>
            Material Cost
        </a>

        <a href="{{ route('mrp.labor-cost') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.labor-cost' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(255,214,10,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--yellow)" stroke-width="2">
                    <circle cx="12" cy="8" r="5"/>
                    <path d="M20 21a8 8 0 10-16 0"/>
                </svg>
            </span>
            Labor Cost
        </a>

        <a href="{{ route('mrp.overhead') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.overhead' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(255,214,10,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--yellow)" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 6v6l4 2"/>
                </svg>
            </span>
            Overhead
        </a>

        <a href="{{ route('mrp.production-cost') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.production-cost' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(255,214,10,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--yellow)" stroke-width="2">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" stroke-linecap="round"/>
                </svg>
            </span>
            Production Cost
        </a>

    </div>


    {{-- Reports --}}
    <div class="navitem has-sub {{ request()->is('MRP/production-report') || request()->is('MRP/production-efficiency') || request()->is('MRP/waste-scrap') || request()->is('MRP/machine-utilization') ? 'open' : '' }}"
         data-submenu="reports">

        <span
            class="ico"
            style="background:var(--pink);"
        >
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="#fff"
                stroke-width="2"
            >
                <path
                    d="M4 19h16M8 19V9m4 10V5m4 14v-7"
                    stroke-linecap="round"
                />
            </svg>
        </span>

        Reports

        <span class="arrow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>

    </div>

    <div class="submenu {{ request()->is('MRP/production-report') || request()->is('MRP/production-efficiency') || request()->is('MRP/waste-scrap') || request()->is('MRP/machine-utilization') ? 'open' : '' }}"
         id="submenu-reports">

        <a href="{{ route('mrp.production-report') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.production-report' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(255,55,95,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--pink)" stroke-width="2">
                    <path d="M4 19h16M8 19V9m4 10V5m4 14v-7"/>
                </svg>
            </span>
            Production Report
        </a>

        <a href="{{ route('mrp.material-consumption') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.material-consumption' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(255,55,95,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--pink)" stroke-width="2">
                    <path d="M6 2h9l3 3v17H6z"/>
                    <path d="M9 12h6M9 16h6"/>
                </svg>
            </span>
            Material Consumption
        </a>

        <a href="{{ route('mrp.production-efficiency') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.production-efficiency' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(255,55,95,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--pink)" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                    <path d="M22 4L12 14.01l-3-3"/>
                </svg>
            </span>
            Production Efficiency
        </a>

        <a href="{{ route('mrp.waste-scrap') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.waste-scrap' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(255,55,95,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--pink)" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M15 9l-6 6M9 9l6 6"/>
                </svg>
            </span>
            Waste &amp; Scrap
        </a>

        <a href="{{ route('mrp.machine-utilization') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.machine-utilization' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(255,55,95,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--pink)" stroke-width="2">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                </svg>
            </span>
            Machine Utilization
        </a>

        <a href="{{ route('mrp.production-cost') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.production-cost' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(255,55,95,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--pink)" stroke-width="2">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" stroke-linecap="round"/>
                </svg>
            </span>
            Production Cost
        </a>

    </div>


    {{-- SISTEM --}}
    <div class="section-label">
        Sistem
    </div>


    {{-- Settings --}}
    <div class="navitem has-sub {{ request()->is('MRP/general-settings') || request()->is('MRP/production-settings') || request()->is('MRP/production-calendar') || request()->is('MRP/units') || request()->is('MRP/numbering') ? 'open' : '' }}"
         data-submenu="settings">

        <span
            class="ico"
            style="background:#636366;"
        >
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="#fff"
                stroke-width="2"
            >
                <circle
                    cx="12"
                    cy="12"
                    r="3"
                />

                <path d="M19.4 15a1.7 1.7 0 00.3 1.9l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.7 1.7 0 00-1.9-.3 1.7 1.7 0 00-1 1.5V21a2 2 0 11-4 0v-.1a1.7 1.7 0 00-1-1.6 1.7 1.7 0 00-1.9.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.7 1.7 0 00.3-1.9 1.7 1.7 0 00-1.5-1H3a2 2 0 110-4h.1a1.7 1.7 0 001.5-1 1.7 1.7 0 00-.3-1.9l-.1-.1a2 2 0 112.8-2.8l.1.1a1.7 1.7 0 001.9.3H9a1.7 1.7 0 001-1.5V3a2 2 0 114 0v.1a1.7 1.7 0 001 1.5 1.7 1.7 0 001.9-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.7 1.7 0 00-.3 1.9V9a1.7 1.7 0 001.5 1H21a2 2 0 110 4h-.1a1.7 1.7 0 00-1.5 1z"/>
            </svg>
        </span>

        Settings

        <span class="arrow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>

    </div>

    <div class="submenu {{ request()->is('MRP/general-settings') || request()->is('MRP/production-settings') || request()->is('MRP/production-calendar') || request()->is('MRP/units') || request()->is('MRP/numbering') ? 'open' : '' }}"
         id="submenu-settings">

        <a href="{{ route('mrp.general-settings') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.general-settings' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(99,99,102,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="#636366" stroke-width="2">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.4 15a1.7 1.7 0 00.3 1.9l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.7 1.7 0 00-1.9-.3 1.7 1.7 0 00-1 1.5V21a2 2 0 11-4 0v-.1a1.7 1.7 0 00-1-1.6 1.7 1.7 0 00-1.9.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.7 1.7 0 00.3-1.9 1.7 1.7 0 00-1.5-1H3a2 2 0 110-4h.1a1.7 1.7 0 001.5-1 1.7 1.7 0 00-.3-1.9l-.1-.1a2 2 0 112.8-2.8l.1.1a1.7 1.7 0 001.9.3H9a1.7 1.7 0 001-1.5V3a2 2 0 114 0v.1a1.7 1.7 0 001 1.5 1.7 1.7 0 001.9-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.7 1.7 0 00-.3 1.9V9a1.7 1.7 0 001.5 1H21a2 2 0 110 4h-.1a1.7 1.7 0 00-1.5 1z"/>
                </svg>
            </span>
            General Settings
        </a>

        <a href="{{ route('mrp.production-settings') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.production-settings' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(99,99,102,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="#636366" stroke-width="2">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.4 15a1.7 1.7 0 00.3 1.9l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.7 1.7 0 00-1.9-.3 1.7 1.7 0 00-1 1.5V21a2 2 0 11-4 0v-.1a1.7 1.7 0 00-1-1.6 1.7 1.7 0 00-1.9.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.7 1.7 0 00.3-1.9 1.7 1.7 0 00-1.5-1H3a2 2 0 110-4h.1a1.7 1.7 0 001.5-1 1.7 1.7 0 00-.3-1.9l-.1-.1a2 2 0 112.8-2.8l.1.1a1.7 1.7 0 001.9.3H9a1.7 1.7 0 001-1.5V3a2 2 0 114 0v.1a1.7 1.7 0 001 1.5 1.7 1.7 0 001.9-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.7 1.7 0 00-.3 1.9V9a1.7 1.7 0 001.5 1H21a2 2 0 110 4h-.1a1.7 1.7 0 00-1.5 1z"/>
                </svg>
            </span>
            Production Settings
        </a>

        <a href="{{ route('mrp.production-calendar') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.production-calendar' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(99,99,102,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="#636366" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
            </span>
            Production Calendar
        </a>

        <a href="{{ route('mrp.units') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.units' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(99,99,102,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="#636366" stroke-width="2">
                    <path d="M3 3h18v18H3z"/>
                    <path d="M12 8v8M8 12h8"/>
                </svg>
            </span>
            Units
        </a>

        <a href="{{ route('mrp.numbering') }}"
           class="subnavitem {{ request()->route()->getName() === 'mrp.numbering' ? 'active' : '' }}">
            <span class="sub-ico" style="background:rgba(99,99,102,0.15);">
                <svg viewBox="0 0 24 24" fill="none" stroke="#636366" stroke-width="2">
                    <path d="M4 7h16M4 12h16M4 17h10"/>
                </svg>
            </span>
            Numbering
        </a>
    </div>
</div>
