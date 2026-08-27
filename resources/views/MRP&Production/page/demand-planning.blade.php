@extends('MRP&Production.components.layout', ['title' => 'Demand Planning'])
@vite([
    'resources/css/MRP&Production/Planning/DemandPlanning.css',
    'resources/js/MRP&Production/Planning/DemandPlanning.js'
])
@section('content')
<div class="content-header">
    <div>
        <h1>Demand Planning</h1>
        <div class="sub">
            Perencanaan dan analisis kebutuhan produk — Agustus 2026
        </div>
    </div>
    <div class="header-actions">
        <button class="btn-ghost">Ekspor</button>
        <button class="btn-primary">+ Buat Demand Plan</button>
    </div>
</div>
{{-- STATISTICS --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="top">
            <span class="icon-badge orange">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M4 19V5"/>
                    <path d="M4 19h16"/>
                    <path d="M7 15l4-5 3 3 5-7"/>
                </svg>
            </span>
            <span class="delta up">
                ▲ 8.4%
            </span>
        </div>
        <div class="value">128.400</div>
        <div class="label">Total Forecast Demand</div>
    </div>
    <div class="stat-card">
        <div class="top">
            <span class="icon-badge blue">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M3 3v18h18"/>
                    <path d="M7 16l4-5 3 2 5-6"/>
                </svg>
            </span>
            <span class="delta up">
                ▲ 5.7%
            </span>
        </div>
        <div class="value">121.800</div>
        <div class="label">Actual Demand</div>
    </div>
    <div class="stat-card">
        <div class="top">
            <span class="icon-badge green">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M4 19h16"/>
                    <path d="M7 16l4-6 3 3 4-8"/>
                </svg>
            </span>
            <span class="delta up">
                ▲ 3.2%
            </span>
        </div>
        <div class="value">94.9%</div>
        <div class="label">Forecast Accuracy</div>
    </div>
    <div class="stat-card">
        <div class="top">
            <span class="icon-badge red">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M12 3v18"/>
                    <path d="M17 7c0-2-2-4-5-4S7 5 7 7s2 3 5 4 5 2 5 4-2 4-5 4-5-2-5-4"/>
                </svg>
            </span>
            <span class="delta down">
                7 produk
            </span>
        </div>
        <div class="value">12.600</div>
        <div class="label">Demand Gap</div>
    </div>
</div>
{{-- CHART + SUMMARY --}}
<div class="row2">
    <div class="panel">
        <div class="panel-heading">
            <div>
                <h3>Forecast vs Actual Demand</h3>
                <div class="sub">
                    Perbandingan forecast dengan demand aktual
                </div>
            </div>
            <select class="period-select">
                <option>6 Bulan</option>
                <option>3 Bulan</option>
                <option>12 Bulan</option>
            </select>
        </div>
        <div class="chart-container">
            <div class="chart-y">
                <span>30K</span>
                <span>25K</span>
                <span>20K</span>
                <span>15K</span>
                <span>10K</span>
                <span>5K</span>
                <span>0</span>
            </div>
            <div class="chart-area">
                <div class="grid-line line-1"></div>
                <div class="grid-line line-2"></div>
                <div class="grid-line line-3"></div>
                <div class="grid-line line-4"></div>
                <div class="grid-line line-5"></div>
                <div class="grid-line line-6"></div>
                <svg
                    class="demand-chart"
                    viewBox="0 0 700 260"
                    preserveAspectRatio="none"
                >
                    <defs>
                        <linearGradient
                            id="forecastFill"
                            x1="0"
                            y1="0"
                            x2="0"
                            y2="1"
                        >
                            <stop
                                offset="0%"
                                stop-color="#FF9F0A"
                                stop-opacity=".25"
                            />
                            <stop
                                offset="100%"
                                stop-color="#FF9F0A"
                                stop-opacity="0"
                            />
                        </linearGradient>
                    </defs>
                    <path
                        class="forecast-area"
                        d="M0 190
                           L115 160
                           L230 125
                           L350 145
                           L465 95
                           L580 75
                           L700 45
                           L700 260
                           L0 260 Z"
                    />
                    <polyline
                        class="forecast-line"
                        points="
                        0,190
                        115,160
                        230,125
                        350,145
                        465,95
                        580,75
                        700,45"
                    />
                    <polyline
                        class="actual-line"
                        points="
                        0,205
                        115,180
                        230,145
                        350,160
                        465,115
                        580,95
                        700,65"
                    />
                    <circle cx="0" cy="205" r="4"/>
                    <circle cx="115" cy="180" r="4"/>
                    <circle cx="230" cy="145" r="4"/>
                    <circle cx="350" cy="160" r="4"/>
                    <circle cx="465" cy="115" r="4"/>
                    <circle cx="580" cy="95" r="4"/>
                    <circle cx="700" cy="65" r="4"/>
                </svg>
                <div class="chart-x">
                    <span>Mar</span>
                    <span>Apr</span>
                    <span>Mei</span>
                    <span>Jun</span>
                    <span>Jul</span>
                    <span>Agu</span>
                    <span>Sep</span>
                </div>
            </div>
        </div>
        <div class="chart-legend">
            <span>
                <i class="legend-dot forecast"></i>
                Forecast
            </span>
            <span>
                <i class="legend-dot actual"></i>
                Actual Demand
            </span>
        </div>
    </div>
    <div class="panel">
        <h3>Forecast Summary</h3>
        <div class="sub">
            Ringkasan forecast berdasarkan produk
        </div>
        <div class="forecast-list">
            <div class="forecast-item">
                <div class="forecast-info">
                    <span class="product-name">
                        Kulkas 2 Pintu 300L
                    </span>
                    <span class="product-value">
                        28.400
                    </span>
                </div>
                <div class="mini-bar">
                    <div style="width:92%"></div>
                </div>
            </div>
            <div class="forecast-item">
                <div class="forecast-info">
                    <span class="product-name">
                        Blender 500W
                    </span>
                    <span class="product-value">
                        21.600
                    </span>
                </div>
                <div class="mini-bar">
                    <div style="width:78%"></div>
                </div>
            </div>
            <div class="forecast-item">
                <div class="forecast-info">
                    <span class="product-name">
                        Rice Cooker 1.8L
                    </span>
                    <span class="product-value">
                        18.900
                    </span>
                </div>
                <div class="mini-bar">
                    <div style="width:68%"></div>
                </div>
            </div>
            <div class="forecast-item">
                <div class="forecast-info">
                    <span class="product-name">
                        Kemeja Katun Pria
                    </span>
                    <span class="product-value">
                        15.700
                    </span>
                </div>
                <div class="mini-bar">
                    <div style="width:56%"></div>
                </div>
            </div>
            <div class="forecast-item">
                <div class="forecast-info">
                    <span class="product-name">
                        Sepatu Sneakers Wanita
                    </span>
                    <span class="product-value">
                        12.300
                    </span>
                </div>
                <div class="mini-bar">
                    <div style="width:45%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- DEMAND TABLE --}}
<div class="panel">
    <div class="panel-heading">
        <div>
            <h3>Demand Planning</h3>
            <div class="sub">
                Forecast, actual demand, histori, dan sumber data
            </div>
        </div>
        <div class="table-actions">
            <button class="btn-ghost">
                Import Demand
            </button>
            <button class="btn-primary">
                + Manual Planning
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
            Cari produk...
        </div>
        <select>
            <option>Semua Periode</option>
            <option>Agustus 2026</option>
            <option>September 2026</option>
            <option>Oktober 2026</option>
        </select>
        <select>
            <option>Semua Status</option>
            <option>Draft</option>
            <option>Approved</option>
            <option>Published</option>
        </select>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Period</th>
                    <th>Forecast Qty</th>
                    <th>Actual Demand</th>
                    <th>Historical Demand</th>
                    <th>Source</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Kulkas 2 Pintu 300L</strong>
                    </td>
                    <td>Agustus 2026</td>
                    <td class="num">
                        28.400
                    </td>
                    <td class="num">
                        26.900
                    </td>
                    <td class="num">
                        25.700
                    </td>
                    <td>
                        System Forecast
                    </td>
                    <td>
                        <span class="status approved">
                            Approved
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Blender 500W</strong>
                    </td>
                    <td>Agustus 2026</td>
                    <td class="num">
                        21.600
                    </td>
                    <td class="num">
                        20.800
                    </td>
                    <td class="num">
                        19.900
                    </td>
                    <td>
                        System Forecast
                    </td>
                    <td>
                        <span class="status approved">
                            Approved
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Rice Cooker 1.8L</strong>
                    </td>
                    <td>September 2026</td>
                    <td class="num">
                        18.900
                    </td>
                    <td class="num">
                        —
                    </td>
                    <td class="num">
                        17.400
                    </td>
                    <td>
                        Manual
                    </td>
                    <td>
                        <span class="status draft">
                            Draft
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Kemeja Katun Pria</strong>
                    </td>
                    <td>Agustus 2026</td>
                    <td class="num">
                        15.700
                    </td>
                    <td class="num">
                        15.200
                    </td>
                    <td class="num">
                        14.800
                    </td>
                    <td>
                        Imported
                    </td>
                    <td>
                        <span class="status published">
                            Published
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Sepatu Sneakers Wanita</strong>
                    </td>
                    <td>September 2026</td>
                    <td class="num">
                        12.300
                    </td>
                    <td class="num">
                        —
                    </td>
                    <td class="num">
                        11.600
                    </td>
                    <td>
                        Manual
                    </td>
                    <td>
                        <span class="status draft">
                            Draft
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
{{-- FEATURES --}}
<div class="panel">
    <h3>Fitur Demand Planning</h3>
    <div class="sub">
        Akses cepat ke seluruh fitur perencanaan demand
    </div>
    <div class="feature-grid">
        <div class="feature-tile">
            <span class="sq orange-gradient">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M4 19V5"/>
                    <path d="M4 19h16"/>
                    <path d="M7 15l4-5 3 3 5-7"/>
                </svg>
            </span>
            <div class="t">
                Forecast Demand
            </div>
            <div class="d">
                Buat dan kelola prediksi kebutuhan produk berdasarkan data demand.
            </div>
        </div>
        <div class="feature-tile">
            <span class="sq blue-gradient">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M3 3v18h18"/>
                    <path d="M7 16l4-5 3 2 5-6"/>
                </svg>
            </span>
            <div class="t">
                Demand History
            </div>
            <div class="d">
                Lihat histori demand produk untuk membantu proses perencanaan.
            </div>
        </div>
        <div class="feature-tile">
            <span class="sq purple-gradient">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14"/>
                    <path d="M5 12h14"/>
                </svg>
            </span>
            <div class="t">
                Manual Demand Planning
            </div>
            <div class="d">
                Atur kebutuhan produk secara manual berdasarkan keputusan planner.
            </div>
        </div>
        <div class="feature-tile">
            <span class="sq green-gradient">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M12 3v12"/>
                    <path d="M7 10l5 5 5-5"/>
                    <path d="M4 21h16"/>
                </svg>
            </span>
            <div class="t">
                Import Demand
            </div>
            <div class="d">
                Import data demand dari file eksternal untuk mempercepat input.
            </div>
        </div>
        <div class="feature-tile">
            <span class="sq red-gradient">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M4 18l6-6 4 4 6-8"/>
                    <path d="M18 8h2v2"/>
                </svg>
            </span>
            <div class="t">
                Forecast Comparison
            </div>
            <div class="d">
                Bandingkan hasil forecast dengan actual demand dan histori.
            </div>
        </div>
    </div>
</div>
@endsection