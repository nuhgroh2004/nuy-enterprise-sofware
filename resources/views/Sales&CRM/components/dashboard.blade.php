<div class="desktop">
    <div class="window">

        {{-- TITLE BAR --}}
        <div class="titlebar">

            <div class="traffic">
                <span class="c"></span>
                <span class="m"></span>
                <span class="z"></span>
            </div>

            <div class="menu-toggle" id="menuToggle">
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        d="M4 6h16M4 12h16M4 18h16"
                        stroke-linecap="round"
                    />
                </svg>
            </div>

            <div class="title">
                ERP Dashboard — Ringkasan Perusahaan
            </div>

            <div class="searchbox">
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <circle cx="11" cy="11" r="7"/>
                    <path
                        d="M21 21l-4.3-4.3"
                        stroke-linecap="round"
                    />
                </svg>

                Cari modul, invoice, produk...
            </div>

        </div>

        {{-- SIDEBAR BACKDROP --}}
        <div
            class="sidebar-backdrop"
            id="sidebarBackdrop"
        ></div>

        <div class="body-area">

            {{-- SIDEBAR --}}
            @include('Sales&CRM.components.sidebar')

            {{-- MAIN CONTENT --}}
            <div class="content">

                {{-- HEADER --}}
                <div class="content-header">
                    <div>
                        <h1>Selamat pagi, Bapak/Ibu 👋</h1>

                        <div class="sub">
                            Rabu, 26 Agustus 2026 —
                            Ringkasan operasional hari ini
                        </div>
                    </div>

                    <button class="btn-primary">
                        + Transaksi Baru
                    </button>
                </div>


                {{-- STAT CARDS --}}
                <div class="stats-grid">

                    {{-- Pendapatan --}}
                    <div class="stat-card">
                        <div class="top">

                            <span
                                class="icon-badge"
                                style="background:var(--green);"
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

                            <span class="delta up">
                                ▲ 12.4%
                            </span>

                        </div>

                        <div class="value">
                            Rp 482,3jt
                        </div>

                        <div class="label">
                            Pendapatan Bulan Ini
                        </div>
                    </div>


                    {{-- Pesanan --}}
                    <div class="stat-card">
                        <div class="top">

                            <span
                                class="icon-badge"
                                style="background:var(--accent);"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#fff"
                                    stroke-width="2"
                                >
                                    <path d="M3 3h18v14H3z"/>
                                    <path d="M8 21h8M12 17v4"/>
                                </svg>
                            </span>

                            <span class="delta up">
                                ▲ 8.1%
                            </span>

                        </div>

                        <div class="value">
                            1.284
                        </div>

                        <div class="label">
                            Pesanan Baru
                        </div>
                    </div>


                    {{-- Stok --}}
                    <div class="stat-card">
                        <div class="top">

                            <span
                                class="icon-badge"
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
                                </svg>
                            </span>

                            <span class="delta down">
                                ▼ 3.2%
                            </span>

                        </div>

                        <div class="value">
                            9.640 unit
                        </div>

                        <div class="label">
                            Stok Tersedia
                        </div>
                    </div>


                    {{-- Karyawan --}}
                    <div class="stat-card">
                        <div class="top">

                            <span
                                class="icon-badge"
                                style="background:var(--accent-2);"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#fff"
                                    stroke-width="2"
                                >
                                    <circle cx="9" cy="8" r="3.2"/>
                                    <path
                                        d="M2.5 20c0-3.6 2.9-6.3 6.5-6.3S15.5 16.4 15.5 20"
                                    />
                                    <circle cx="18" cy="9" r="2.4"/>
                                </svg>
                            </span>

                            <span class="delta up">
                                ▲ 2 baru
                            </span>

                        </div>

                        <div class="value">
                            248
                        </div>

                        <div class="label">
                            Karyawan Aktif
                        </div>
                    </div>

                </div>


                {{-- CHART + DONUT --}}
                <div class="row2">

                    {{-- Chart --}}
                    <div class="panel">

                        <h3>
                            Pendapatan vs Target
                        </h3>

                        <div class="sub">
                            6 bulan terakhir (dalam juta rupiah)
                        </div>

                        <div class="chart">

                            <div class="bar-wrap">
                                <div class="bar" style="height:55%"></div>
                                <div class="bar alt" style="height:48%"></div>
                                <div class="m">Mar</div>
                            </div>

                            <div class="bar-wrap">
                                <div class="bar" style="height:68%"></div>
                                <div class="bar alt" style="height:60%"></div>
                                <div class="m">Apr</div>
                            </div>

                            <div class="bar-wrap">
                                <div class="bar" style="height:50%"></div>
                                <div class="bar alt" style="height:62%"></div>
                                <div class="m">Mei</div>
                            </div>

                            <div class="bar-wrap">
                                <div class="bar" style="height:78%"></div>
                                <div class="bar alt" style="height:66%"></div>
                                <div class="m">Jun</div>
                            </div>

                            <div class="bar-wrap">
                                <div class="bar" style="height:64%"></div>
                                <div class="bar alt" style="height:70%"></div>
                                <div class="m">Jul</div>
                            </div>

                            <div class="bar-wrap">
                                <div class="bar" style="height:92%"></div>
                                <div class="bar alt" style="height:80%"></div>
                                <div class="m">Agu</div>
                            </div>

                        </div>
                    </div>


                    {{-- Donut --}}
                    <div class="panel">

                        <h3>
                            Distribusi Penjualan
                        </h3>

                        <div class="sub">
                            Berdasarkan kategori produk
                        </div>

                        <div class="donut-wrap">

                            <svg
                                width="110"
                                height="110"
                                viewBox="0 0 42 42"
                            >
                                <circle
                                    cx="21"
                                    cy="21"
                                    r="15.9"
                                    fill="transparent"
                                    stroke="#e9e9ec"
                                    stroke-width="5"
                                />

                                <circle
                                    cx="21"
                                    cy="21"
                                    r="15.9"
                                    fill="transparent"
                                    stroke="#0A84FF"
                                    stroke-width="5"
                                    stroke-dasharray="40 60"
                                    stroke-dashoffset="25"
                                />

                                <circle
                                    cx="21"
                                    cy="21"
                                    r="15.9"
                                    fill="transparent"
                                    stroke="#5E5CE6"
                                    stroke-width="5"
                                    stroke-dasharray="25 75"
                                    stroke-dashoffset="-15"
                                />

                                <circle
                                    cx="21"
                                    cy="21"
                                    r="15.9"
                                    fill="transparent"
                                    stroke="#FF9F0A"
                                    stroke-width="5"
                                    stroke-dasharray="20 80"
                                    stroke-dashoffset="-40"
                                />

                                <circle
                                    cx="21"
                                    cy="21"
                                    r="15.9"
                                    fill="transparent"
                                    stroke="#30D158"
                                    stroke-width="5"
                                    stroke-dasharray="15 85"
                                    stroke-dashoffset="-60"
                                />
                            </svg>


                            <div class="legend">

                                <div class="row">
                                    <span class="name">
                                        <span
                                            class="dot"
                                            style="background:var(--accent)"
                                        ></span>
                                        Elektronik
                                    </span>

                                    <span class="pct">
                                        40%
                                    </span>
                                </div>

                                <div class="row">
                                    <span class="name">
                                        <span
                                            class="dot"
                                            style="background:var(--accent-2)"
                                        ></span>
                                        Fashion
                                    </span>

                                    <span class="pct">
                                        25%
                                    </span>
                                </div>

                                <div class="row">
                                    <span class="name">
                                        <span
                                            class="dot"
                                            style="background:var(--orange)"
                                        ></span>
                                        Rumah Tangga
                                    </span>

                                    <span class="pct">
                                        20%
                                    </span>
                                </div>

                                <div class="row">
                                    <span class="name">
                                        <span
                                            class="dot"
                                            style="background:var(--green)"
                                        ></span>
                                        Lainnya
                                    </span>

                                    <span class="pct">
                                        15%
                                    </span>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- TRANSAKSI --}}
                <div
                    class="panel"
                    style="margin-bottom:14px;"
                >

                    <h3>
                        Transaksi Terbaru
                    </h3>

                    <div class="sub">
                        Faktur yang dibuat 7 hari terakhir
                    </div>

                    <table>

                        <thead>
                            <tr>
                                <th>Pelanggan</th>
                                <th>No. Faktur</th>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            <tr>
                                <td class="who-cell">
                                    <span class="mini-avatar">BS</span>
                                    Budi Santoso
                                </td>

                                <td>INV-2026-0842</td>
                                <td>24 Agu 2026</td>
                                <td>Rp 12.450.000</td>

                                <td>
                                    <span class="status paid">
                                        Lunas
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td class="who-cell">
                                    <span class="mini-avatar">RA</span>
                                    Rina Amelia
                                </td>

                                <td>INV-2026-0841</td>
                                <td>23 Agu 2026</td>
                                <td>Rp 3.200.000</td>

                                <td>
                                    <span class="status pending">
                                        Menunggu
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td class="who-cell">
                                    <span class="mini-avatar">TW</span>
                                    Toko Wijaya
                                </td>

                                <td>INV-2026-0839</td>
                                <td>21 Agu 2026</td>
                                <td>Rp 27.900.000</td>

                                <td>
                                    <span class="status paid">
                                        Lunas
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td class="who-cell">
                                    <span class="mini-avatar">CV</span>
                                    CV Makmur Sentosa
                                </td>

                                <td>INV-2026-0836</td>
                                <td>19 Agu 2026</td>
                                <td>Rp 8.750.000</td>

                                <td>
                                    <span class="status overdue">
                                        Jatuh Tempo
                                    </span>
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>


                {{-- SEMUA MODUL --}}
                <div class="panel">

                    <h3>
                        Semua Modul
                    </h3>

                    <div class="sub">
                        Akses cepat ke seluruh modul ERP
                    </div>

                    <div class="apps-grid">

                        <div class="app-tile">
                            <span
                                class="sq"
                                style="background:linear-gradient(135deg,#34C759,#28A745);"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#fff"
                                    stroke-width="2"
                                >
                                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
                                </svg>
                            </span>
                            <span class="lbl">Penjualan</span>
                        </div>

                        <div class="app-tile">
                            <span
                                class="sq"
                                style="background:linear-gradient(135deg,#FF9F0A,#FF7A00);"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#fff"
                                    stroke-width="2"
                                >
                                    <path d="M21 8l-9-5-9 5 9 5 9-5z"/>
                                    <path d="M3 8v8l9 5 9-5V8"/>
                                </svg>
                            </span>
                            <span class="lbl">Inventori</span>
                        </div>

                        <div class="app-tile">
                            <span
                                class="sq"
                                style="background:linear-gradient(135deg,#FF375F,#D6194B);"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#fff"
                                    stroke-width="2"
                                >
                                    <path d="M6 9V7a6 6 0 0112 0v2"/>
                                    <rect x="4" y="9" width="16" height="11" rx="2"/>
                                </svg>
                            </span>
                            <span class="lbl">Pembelian</span>
                        </div>

                        <div class="app-tile">
                            <span
                                class="sq"
                                style="background:linear-gradient(135deg,#64D2FF,#0A84FF);"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#fff"
                                    stroke-width="2"
                                >
                                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
                                </svg>
                            </span>
                            <span class="lbl">Keuangan</span>
                        </div>

                        <div class="app-tile">
                            <span
                                class="sq"
                                style="background:linear-gradient(135deg,#5E5CE6,#8E5CE6);"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#fff"
                                    stroke-width="2"
                                >
                                    <circle cx="9" cy="8" r="3.2"/>
                                    <path d="M2.5 20c0-3.6 2.9-6.3 6.5-6.3S15.5 16.4 15.5 20"/>
                                    <circle cx="18" cy="9" r="2.4"/>
                                </svg>
                            </span>
                            <span class="lbl">SDM / HR</span>
                        </div>

                        <div class="app-tile">
                            <span
                                class="sq"
                                style="background:linear-gradient(135deg,#FF453A,#C81E1E);"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#fff"
                                    stroke-width="2"
                                >
                                    <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51z"/>
                                </svg>
                            </span>
                            <span class="lbl">CRM</span>
                        </div>

                        <div class="app-tile">
                            <span
                                class="sq"
                                style="background:linear-gradient(135deg,#8E8E93,#636366);"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#fff"
                                    stroke-width="2"
                                >
                                    <path d="M4 19h16M8 19V9m4 10V5m4 14v-7"/>
                                </svg>
                            </span>
                            <span class="lbl">Analitik</span>
                        </div>

                        <div class="app-tile">
                            <span
                                class="sq"
                                style="background:linear-gradient(135deg,#FFD60A,#FF9F0A);"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#fff"
                                    stroke-width="2"
                                >
                                    <path d="M6 2h9l3 3v17H6z"/>
                                    <path d="M9 12h6M9 16h6M9 8h3"/>
                                </svg>
                            </span>
                            <span class="lbl">Pajak</span>
                        </div>

                        <div class="app-tile">
                            <span
                                class="sq"
                                style="background:linear-gradient(135deg,#30D158,#0A9C3F);"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#fff"
                                    stroke-width="2"
                                >
                                    <path d="M3 21l6-6M21 3l-9 9"/>
                                    <rect x="3" y="12" width="6" height="9" rx="1"/>
                                    <rect x="15" y="3" width="6" height="9" rx="1"/>
                                </svg>
                            </span>
                            <span class="lbl">Aset</span>
                        </div>

                        <div class="app-tile">
                            <span
                                class="sq"
                                style="background:linear-gradient(135deg,#0A84FF,#5E5CE6);"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#fff"
                                    stroke-width="2"
                                >
                                    <rect x="3" y="4" width="18" height="14" rx="2"/>
                                    <path d="M8 21h8M12 18v3"/>
                                </svg>
                            </span>
                            <span class="lbl">POS</span>
                        </div>

                        <div class="app-tile">
                            <span
                                class="sq"
                                style="background:linear-gradient(135deg,#AF52DE,#7A3FC0);"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#fff"
                                    stroke-width="2"
                                >
                                    <path d="M4 4h16v4H4zM4 10h10v4H4zM4 16h16v4H4z"/>
                                </svg>
                            </span>
                            <span class="lbl">Produksi</span>
                        </div>

                        <div class="app-tile">
                            <span
                                class="sq"
                                style="background:linear-gradient(135deg,#636366,#3A3A3C);"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#fff"
                                    stroke-width="2"
                                >
                                    <circle cx="12" cy="12" r="3"/>
                                    <path d="M19.4 15a1.7 1.7 0 00.3 1.9l.1.1a2 2 0 11-2.8 2.8"/>
                                </svg>
                            </span>
                            <span class="lbl">Pengaturan</span>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- DOCK --}}
@include('components.dock')