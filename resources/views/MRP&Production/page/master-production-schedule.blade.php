@extends('MRP&Production.components.layout', ['title' => 'Master Production Schedule'])

@vite([
    'resources/css/MRP&Production/Planning/MasterProductionSchedule.css',
    'resources/js/MRP&Production/Planning/MasterProductionSchedule.js'
])

@section('content')

<div class="content-header">
    <div>
        <h1 id="pageTitle">Production Planning</h1>
        <div class="sub" id="pageSub">
            Rencana produksi aktif — Agustus 2026
        </div>
    </div>

    <div class="header-actions">
        <button class="btn-ghost">Ekspor</button>
        <button class="btn-primary">+ Buat Jadwal</button>
    </div>
</div>

<div class="stats-grid">

    <div class="stat-card">
        <div class="top">
            <span class="icon-badge orange">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="16" rx="2"/>
                    <path d="M3 9h18"/>
                </svg>
            </span>

            <span class="delta up">▲ 5.2%</span>
        </div>

        <div class="value">36 Jadwal</div>
        <div class="label">Jadwal Produksi Aktif</div>
    </div>

    <div class="stat-card">
        <div class="top">
            <span class="icon-badge blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 8l-9-5-9 5 9 5 9-5z"/>
                    <path d="M3 8v8l9 5 9-5V8"/>
                </svg>
            </span>

            <span class="delta flat">142.000 unit</span>
        </div>

        <div class="value">142.000 unit</div>
        <div class="label">Total Planned Quantity</div>
    </div>

    <div class="stat-card">
        <div class="top">
            <span class="icon-badge green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19h16M8 19V9m4 10V5m4 14v-7"/>
                </svg>
            </span>

            <span class="delta up">▲ 4.0%</span>
        </div>

        <div class="value">86%</div>
        <div class="label">Rata-rata Utilisasi Kapasitas</div>
    </div>

    <div class="stat-card">
        <div class="top">
            <span class="icon-badge red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 9v4m0 4h.01M10.3 3.86L1.8 18a2 2 0 001.7 3h17a2 2 0 001.7-3L13.7 3.86a2 2 0 00-3.4 0z"/>
                </svg>
            </span>

            <span class="delta down">▼ 2 jadwal</span>
        </div>

        <div class="value">4 Jadwal</div>
        <div class="label">Prioritas Tinggi / Tertunda</div>
    </div>

</div>

<div class="row2">

    <div class="panel">
        <h3>Kalender Produksi</h3>

        <div class="sub">
            Beban produksi harian — minggu ke-4 Agustus 2026
        </div>

        <div class="cal-grid">
            <div class="cal-cell load-mid"><span class="d">18</span>Sen</div>
            <div class="cal-cell load-high"><span class="d">19</span>Sel</div>
            <div class="cal-cell load-high"><span class="d">20</span>Rab</div>
            <div class="cal-cell load-mid"><span class="d">21</span>Kam</div>
            <div class="cal-cell load-low"><span class="d">22</span>Jum</div>
            <div class="cal-cell"><span class="d">23</span>Sab</div>
            <div class="cal-cell"><span class="d">24</span>Min</div>

            <div class="cal-cell load-high"><span class="d">25</span>Sen</div>
            <div class="cal-cell load-mid"><span class="d">26</span>Sel</div>
            <div class="cal-cell load-mid"><span class="d">27</span>Rab</div>
            <div class="cal-cell load-low"><span class="d">28</span>Kam</div>
            <div class="cal-cell load-high"><span class="d">29</span>Jum</div>
            <div class="cal-cell"><span class="d">30</span>Sab</div>
            <div class="cal-cell"><span class="d">31</span>Min</div>
        </div>

        <div class="cal-legend">
            <span>
                <span class="dot red"></span>
                Beban Tinggi
            </span>

            <span>
                <span class="dot orange"></span>
                Beban Sedang
            </span>

            <span>
                <span class="dot green"></span>
                Beban Rendah
            </span>
        </div>
    </div>

    <div class="panel">
        <h3>Utilisasi Work Center</h3>

        <div class="sub">
            Kapasitas terpakai per lini produksi
        </div>

        <div class="capacity-list">

            <div class="capacity-row">
                <div class="top-row">
                    <span class="name">Lini Perakitan 1</span>
                    <span class="pct">104%</span>
                </div>

                <div class="bar-track">
                    <div class="bar-fill over" style="width:100%"></div>
                </div>
            </div>

            <div class="capacity-row">
                <div class="top-row">
                    <span class="name">Lini Perakitan 2</span>
                    <span class="pct">88%</span>
                </div>

                <div class="bar-track">
                    <div class="bar-fill" style="width:88%"></div>
                </div>
            </div>

            <div class="capacity-row">
                <div class="top-row">
                    <span class="name">Lini Jahit</span>
                    <span class="pct">76%</span>
                </div>

                <div class="bar-track">
                    <div class="bar-fill" style="width:76%"></div>
                </div>
            </div>

            <div class="capacity-row">
                <div class="top-row">
                    <span class="name">Lini Packing</span>
                    <span class="pct">62%</span>
                </div>

                <div class="bar-track">
                    <div class="bar-fill" style="width:62%"></div>
                </div>
            </div>

        </div>
    </div>

</div>

<div class="panel">

    <h3>Jadwal Produksi</h3>

    <div class="sub">
        Product, kuantitas, tanggal, dan prioritas produksi
    </div>

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Planned Qty</th>
                <th>Mulai</th>
                <th>Selesai</th>
                <th>Prioritas</th>
                <th>Work Center</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>

            <tr>
                <td>Kulkas 2 Pintu 300L</td>
                <td>1.200</td>
                <td>25 Agu 2026</td>
                <td>29 Agu 2026</td>
                <td>
                    <span class="prio high">Tinggi</span>
                </td>
                <td>Lini Perakitan 1</td>
                <td>
                    <span class="status ongoing">Berjalan</span>
                </td>
            </tr>

            <tr>
                <td>Kemeja Katun Pria</td>
                <td>6.000</td>
                <td>26 Agu 2026</td>
                <td>30 Agu 2026</td>
                <td>
                    <span class="prio medium">Sedang</span>
                </td>
                <td>Lini Jahit</td>
                <td>
                    <span class="status scheduled">Terjadwal</span>
                </td>
            </tr>

            <tr>
                <td>Blender 500W</td>
                <td>2.400</td>
                <td>20 Agu 2026</td>
                <td>24 Agu 2026</td>
                <td>
                    <span class="prio low">Rendah</span>
                </td>
                <td>Lini Perakitan 2</td>
                <td>
                    <span class="status done">Selesai</span>
                </td>
            </tr>

            <tr>
                <td>Sepatu Sneakers Wanita</td>
                <td>3.800</td>
                <td>19 Agu 2026</td>
                <td>27 Agu 2026</td>
                <td>
                    <span class="prio high">Tinggi</span>
                </td>
                <td>Lini Perakitan 1</td>
                <td>
                    <span class="status delayed">Tertunda</span>
                </td>
            </tr>

            <tr>
                <td>Rice Cooker 1.8L</td>
                <td>1.900</td>
                <td>29 Agu 2026</td>
                <td>2 Sep 2026</td>
                <td>
                    <span class="prio medium">Sedang</span>
                </td>
                <td>Lini Packing</td>
                <td>
                    <span class="status scheduled">Terjadwal</span>
                </td>
            </tr>

        </tbody>
    </table>

</div>

<div class="panel">

    <h3>Fitur Master Production Schedule</h3>

    <div class="sub">
        Akses cepat ke seluruh fitur penjadwalan produksi
    </div>

    <div class="feature-grid">

        <div class="feature-tile">
            <span class="sq orange-gradient">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="16" rx="2"/>
                    <path d="M3 9h18"/>
                </svg>
            </span>

            <div class="t">Production Planning</div>
            <div class="d">
                Susun rencana produksi berdasarkan demand dan kapasitas.
            </div>
        </div>

        <div class="feature-tile">
            <span class="sq yellow-gradient">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="5" width="18" height="16" rx="2"/>
                    <path d="M8 3v4M16 3v4M3 10h18"/>
                </svg>
            </span>

            <div class="t">Production Calendar</div>
            <div class="d">
                Lihat sebaran jadwal produksi dalam tampilan kalender.
            </div>
        </div>

        <div class="feature-tile">
            <span class="sq blue-gradient">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19h16M8 19V9m4 10V5m4 14v-7"/>
                </svg>
            </span>

            <div class="t">Capacity Planning</div>
            <div class="d">
                Pantau dan atur kapasitas work center agar tidak overload.
            </div>
        </div>

        <div class="feature-tile">
            <span class="sq purple-gradient">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 21v-7m8 4V9m8 5V3"/>
                    <circle cx="4" cy="12" r="2"/>
                    <circle cx="12" cy="9" r="2"/>
                    <circle cx="20" cy="14" r="2"/>
                </svg>
            </span>

            <div class="t">Schedule Adjustment</div>
            <div class="d">
                Geser atau ubah jadwal produksi saat ada perubahan mendadak.
            </div>
        </div>

        <div class="feature-tile">
            <span class="sq red-gradient">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51z"/>
                </svg>
            </span>

            <div class="t">Priority</div>
            <div class="d">
                Tentukan urutan prioritas pengerjaan antar jadwal produksi.
            </div>
        </div>

    </div>

</div>

@endsection