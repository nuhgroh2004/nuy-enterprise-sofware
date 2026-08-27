@extends('MRP&Production.components.layout', ['title' => 'Material Requirements'])

@vite([
    'resources/css/MRP&Production/Planning/MaterialRequirements.css',
    'resources/js/MRP&Production/Planning/MaterialRequirements.js'
])

@section('content')

<div class="content-header">
    <div>
        <h1>Material Requirements</h1>
        <div class="sub">
            Perencanaan kebutuhan material berdasarkan rencana produksi — Agustus 2026
        </div>
    </div>

    <div class="header-actions">
        <button class="btn-ghost">Ekspor</button>
        <button class="btn-primary">+ Hitung Kebutuhan</button>
    </div>
</div>


{{-- STATISTICS --}}
<div class="stats-grid">

    <div class="stat-card">
        <div class="top">
            <span class="icon-badge orange">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M21 16V8l-9-5-9 5v8l9 5 9-5z"/>
                    <path d="M3 8l9 5 9-5"/>
                    <path d="M12 13v8"/>
                </svg>
            </span>

            <span class="delta up">
                48 Material
            </span>
        </div>

        <div class="value">284.600</div>
        <div class="label">Total Material Required</div>
    </div>


    <div class="stat-card">
        <div class="top">
            <span class="icon-badge green">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M5 12l4 4L19 6"/>
                    <rect x="3" y="3" width="18" height="18" rx="3"/>
                </svg>
            </span>

            <span class="delta up">
                91.4%
            </span>
        </div>

        <div class="value">260.100</div>
        <div class="label">Material Available</div>
    </div>


    <div class="stat-card">
        <div class="top">
            <span class="icon-badge blue">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M4 19h16"/>
                    <path d="M7 16V8"/>
                    <path d="M12 16V5"/>
                    <path d="M17 16v-4"/>
                </svg>
            </span>

            <span class="delta flat">
                12 Material
            </span>
        </div>

        <div class="value">24.500</div>
        <div class="label">Reserved Stock</div>
    </div>


    <div class="stat-card">
        <div class="top">
            <span class="icon-badge red">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M12 9v4"/>
                    <path d="M12 17h.01"/>
                    <path d="M10.3 3.86L1.8 18a2 2 0 001.7 3h17a2 2 0 001.7-3L13.7 3.86a2 2 0 00-3.4 0z"/>
                </svg>
            </span>

            <span class="delta down">
                9 Material
            </span>
        </div>

        <div class="value">18.700</div>
        <div class="label">Total Shortage</div>
    </div>

</div>


{{-- MATERIAL AVAILABILITY + PROCUREMENT --}}
<div class="row2">

    <div class="panel">

        <div class="panel-heading">

            <div>
                <h3>Material Availability</h3>

                <div class="sub">
                    Perbandingan kebutuhan dengan stok material tersedia
                </div>
            </div>

            <span class="availability-badge">
                91.4% Available
            </span>

        </div>


        <div class="material-list">

            <div class="material-row">

                <div class="material-info">
                    <div>
                        <span class="material-name">
                            Plat Baja SPCC
                        </span>

                        <span class="material-code">
                            MAT-001
                        </span>
                    </div>

                    <span class="material-percent">
                        98%
                    </span>
                </div>

                <div class="material-bar">
                    <div style="width:98%"></div>
                </div>

                <div class="material-meta">
                    <span>
                        Required: <strong>48.000 kg</strong>
                    </span>

                    <span>
                        Available: <strong>47.040 kg</strong>
                    </span>
                </div>

            </div>


            <div class="material-row">

                <div class="material-info">
                    <div>
                        <span class="material-name">
                            Plastik ABS
                        </span>

                        <span class="material-code">
                            MAT-002
                        </span>
                    </div>

                    <span class="material-percent">
                        94%
                    </span>
                </div>

                <div class="material-bar">
                    <div style="width:94%"></div>
                </div>

                <div class="material-meta">
                    <span>
                        Required: <strong>36.500 kg</strong>
                    </span>

                    <span>
                        Available: <strong>34.310 kg</strong>
                    </span>
                </div>

            </div>


            <div class="material-row">

                <div class="material-info">
                    <div>
                        <span class="material-name">
                            Tembaga Coil
                        </span>

                        <span class="material-code">
                            MAT-003
                        </span>
                    </div>

                    <span class="material-percent warning">
                        81%
                    </span>
                </div>

                <div class="material-bar warning">
                    <div style="width:81%"></div>
                </div>

                <div class="material-meta">
                    <span>
                        Required: <strong>24.800 kg</strong>
                    </span>

                    <span>
                        Available: <strong>20.088 kg</strong>
                    </span>
                </div>

            </div>


            <div class="material-row">

                <div class="material-info">
                    <div>
                        <span class="material-name">
                            Kain Katun
                        </span>

                        <span class="material-code">
                            MAT-004
                        </span>
                    </div>

                    <span class="material-percent">
                        92%
                    </span>
                </div>

                <div class="material-bar">
                    <div style="width:92%"></div>
                </div>

                <div class="material-meta">
                    <span>
                        Required: <strong>31.200 meter</strong>
                    </span>

                    <span>
                        Available: <strong>28.704 meter</strong>
                    </span>
                </div>

            </div>

        </div>

    </div>


    <div class="panel">

        <h3>Suggested Procurement</h3>

        <div class="sub">
            Material yang perlu segera dipenuhi
        </div>


        <div class="procurement-list">

            <div class="procurement-item">

                <div class="procurement-icon red">
                    <svg viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M3 6h18"/>
                        <path d="M8 6V4h8v2"/>
                        <path d="M19 6l-1 15H6L5 6"/>
                    </svg>
                </div>

                <div class="procurement-info">

                    <div class="procurement-name">
                        Tembaga Coil
                    </div>

                    <div class="procurement-sub">
                        Shortage 4.712 kg
                    </div>

                </div>

                <span class="procurement-qty">
                    +4.712 kg
                </span>

            </div>


            <div class="procurement-item">

                <div class="procurement-icon orange">
                    <svg viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M3 6h18"/>
                        <path d="M8 6V4h8v2"/>
                        <path d="M19 6l-1 15H6L5 6"/>
                    </svg>
                </div>

                <div class="procurement-info">

                    <div class="procurement-name">
                        Resin Plastik
                    </div>

                    <div class="procurement-sub">
                        Shortage 3.850 kg
                    </div>

                </div>

                <span class="procurement-qty">
                    +3.850 kg
                </span>

            </div>


            <div class="procurement-item">

                <div class="procurement-icon orange">
                    <svg viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M3 6h18"/>
                        <path d="M8 6V4h8v2"/>
                        <path d="M19 6l-1 15H6L5 6"/>
                    </svg>
                </div>

                <div class="procurement-info">

                    <div class="procurement-name">
                        Kain Polyester
                    </div>

                    <div class="procurement-sub">
                        Shortage 2.400 meter
                    </div>

                </div>

                <span class="procurement-qty">
                    +2.400 m
                </span>

            </div>


            <div class="procurement-item">

                <div class="procurement-icon green">
                    <svg viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M5 12l4 4L19 6"/>
                    </svg>
                </div>

                <div class="procurement-info">

                    <div class="procurement-name">
                        Plat Aluminium
                    </div>

                    <div class="procurement-sub">
                        Stock mencukupi
                    </div>

                </div>

                <span class="procurement-ok">
                    Aman
                </span>

            </div>

        </div>

    </div>

</div>


{{-- MATERIAL REQUIREMENT TABLE --}}
<div class="panel">

    <div class="panel-heading">

        <div>
            <h3>Material Requirements</h3>

            <div class="sub">
                Kebutuhan material berdasarkan production plan
            </div>
        </div>

        <div class="table-actions">

            <button class="btn-ghost">
                Riwayat
            </button>

            <button class="btn-primary">
                + Procurement
            </button>

        </div>

    </div>


    <div class="filter-row">

        <div class="search-input">

            <svg viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2">

                <circle cx="11" cy="11" r="7"/>
                <path d="M21 21l-4.3-4.3"/>

            </svg>

            Cari material...

        </div>


        <select>
            <option>Semua Status</option>
            <option>Aman</option>
            <option>Warning</option>
            <option>Shortage</option>
        </select>


        <select>
            <option>Semua Tanggal</option>
            <option>Agustus 2026</option>
            <option>September 2026</option>
        </select>

    </div>


    <div class="table-wrapper">

        <table>

            <thead>

                <tr>
                    <th>Material</th>
                    <th>Required Qty</th>
                    <th>Available Stock</th>
                    <th>Reserved Stock</th>
                    <th>Shortage Qty</th>
                    <th>Required Date</th>
                    <th>Source / Reference</th>
                    <th>Status</th>
                </tr>

            </thead>


            <tbody>

                <tr>

                    <td>
                        <strong>Plat Baja SPCC</strong>
                        <small>MAT-001</small>
                    </td>

                    <td class="num">
                        48.000 kg
                    </td>

                    <td class="num">
                        47.040 kg
                    </td>

                    <td class="num">
                        2.500 kg
                    </td>

                    <td class="num">
                        3.460 kg
                    </td>

                    <td>
                        25 Agu 2026
                    </td>

                    <td>
                        MPS-0826-001
                    </td>

                    <td>
                        <span class="status warning">
                            Warning
                        </span>
                    </td>

                </tr>


                <tr>

                    <td>
                        <strong>Plastik ABS</strong>
                        <small>MAT-002</small>
                    </td>

                    <td class="num">
                        36.500 kg
                    </td>

                    <td class="num">
                        34.310 kg
                    </td>

                    <td class="num">
                        1.200 kg
                    </td>

                    <td class="num">
                        3.390 kg
                    </td>

                    <td>
                        26 Agu 2026
                    </td>

                    <td>
                        MPS-0826-002
                    </td>

                    <td>
                        <span class="status warning">
                            Warning
                        </span>
                    </td>

                </tr>


                <tr>

                    <td>
                        <strong>Tembaga Coil</strong>
                        <small>MAT-003</small>
                    </td>

                    <td class="num">
                        24.800 kg
                    </td>

                    <td class="num">
                        20.088 kg
                    </td>

                    <td class="num">
                        2.100 kg
                    </td>

                    <td class="num shortage">
                        6.812 kg
                    </td>

                    <td>
                        27 Agu 2026
                    </td>

                    <td>
                        MPS-0826-004
                    </td>

                    <td>
                        <span class="status shortage">
                            Shortage
                        </span>
                    </td>

                </tr>


                <tr>

                    <td>
                        <strong>Kain Katun</strong>
                        <small>MAT-004</small>
                    </td>

                    <td class="num">
                        31.200 m
                    </td>

                    <td class="num">
                        28.704 m
                    </td>

                    <td class="num">
                        1.800 m
                    </td>

                    <td class="num">
                        4.296 m
                    </td>

                    <td>
                        28 Agu 2026
                    </td>

                    <td>
                        MPS-0826-005
                    </td>

                    <td>
                        <span class="status warning">
                            Warning
                        </span>
                    </td>

                </tr>


                <tr>

                    <td>
                        <strong>Plat Aluminium</strong>
                        <small>MAT-005</small>
                    </td>

                    <td class="num">
                        18.600 kg
                    </td>

                    <td class="num">
                        22.400 kg
                    </td>

                    <td class="num">
                        1.500 kg
                    </td>

                    <td class="num">
                        0 kg
                    </td>

                    <td>
                        29 Agu 2026
                    </td>

                    <td>
                        MPS-0826-006
                    </td>

                    <td>
                        <span class="status safe">
                            Aman
                        </span>
                    </td>

                </tr>


                <tr>

                    <td>
                        <strong>Resin Plastik</strong>
                        <small>MAT-006</small>
                    </td>

                    <td class="num">
                        27.500 kg
                    </td>

                    <td class="num">
                        21.450 kg
                    </td>

                    <td class="num">
                        900 kg
                    </td>

                    <td class="num shortage">
                        6.950 kg
                    </td>

                    <td>
                        30 Agu 2026
                    </td>

                    <td>
                        MPS-0826-007
                    </td>

                    <td>
                        <span class="status shortage">
                            Shortage
                        </span>
                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>


{{-- FEATURES --}}
<div class="panel">

    <h3>Fitur Material Requirements</h3>

    <div class="sub">
        Akses cepat ke seluruh fitur perencanaan kebutuhan material
    </div>


    <div class="feature-grid">

        <div class="feature-tile">

            <span class="sq orange-gradient">

                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">

                    <path d="M4 19h16"/>
                    <path d="M7 16V9"/>
                    <path d="M12 16V5"/>
                    <path d="M17 16v-4"/>

                </svg>

            </span>

            <div class="t">
                Material Requirement Calculation
            </div>

            <div class="d">
                Hitung kebutuhan material berdasarkan production plan dan quantity yang direncanakan.
            </div>

        </div>


        <div class="feature-tile">

            <span class="sq green-gradient">

                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">

                    <path d="M5 12l4 4L19 6"/>

                    <rect x="3" y="3"
                        width="18"
                        height="18"
                        rx="3"/>

                </svg>

            </span>

            <div class="t">
                Material Availability
            </div>

            <div class="d">
                Periksa ketersediaan material dengan membandingkan kebutuhan dan stok.
            </div>

        </div>


        <div class="feature-tile">

            <span class="sq red-gradient">

                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">

                    <path d="M12 9v4"/>
                    <path d="M12 17h.01"/>

                    <path d="M10.3 3.86L1.8 18a2 2 0 001.7 3h17a2 2 0 001.7-3L13.7 3.86a2 2 0 00-3.4 0z"/>

                </svg>

            </span>

            <div class="t">
                Shortage Detection
            </div>

            <div class="d">
                Identifikasi material yang tidak mencukupi kebutuhan produksi.
            </div>

        </div>


        <div class="feature-tile">

            <span class="sq blue-gradient">

                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">

                    <path d="M3 6h18"/>
                    <path d="M8 6V4h8v2"/>
                    <path d="M19 6l-1 15H6L5 6"/>

                </svg>

            </span>

            <div class="t">
                Suggested Procurement
            </div>

            <div class="d">
                Berikan rekomendasi material yang perlu dilakukan pengadaan.
            </div>

        </div>


        <div class="feature-tile">

            <span class="sq purple-gradient">

                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">

                    <path d="M3 12a9 9 0 1 0 3-6.7"/>
                    <path d="M3 4v6h6"/>

                </svg>

            </span>

            <div class="t">
                Requirement History
            </div>

            <div class="d">
                Lihat riwayat perhitungan kebutuhan material dan perubahan requirement.
            </div>

        </div>

    </div>

</div>

@endsection