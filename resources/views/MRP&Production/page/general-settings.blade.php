@extends('MRP&Production.components.layout', ['title' => 'General Settings'])

@section('content')
@vite(['resources/css/general-settings.css', 'resources/js/general-settings.js'])

<div class="gs-wrap">

    {{-- Sidebar Kategori --}}
    <nav class="gs-sidebar">
        <div class="gs-sidebar-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <input type="text" placeholder="Cari pengaturan..." id="gsSearch">
        </div>

        <div class="gs-sidebar-group">
            <div class="gs-sidebar-label">Umum</div>
            <a class="gs-sidebar-item active" data-tab="general">
                <span class="gs-sidebar-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 00.3 1.9l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.7 1.7 0 00-1.9-.3 1.7 1.7 0 00-1 1.5V21a2 2 0 11-4 0v-.1a1.7 1.7 0 00-1-1.6 1.7 1.7 0 00-1.9.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.7 1.7 0 00.3-1.9 1.7 1.7 0 00-1.5-1H3a2 2 0 110-4h.1a1.7 1.7 0 001.5-1 1.7 1.7 0 00-.3-1.9l-.1-.1a2 2 0 112.8-2.8l.1.1a1.7 1.7 0 001.9.3H9a1.7 1.7 0 001-1.5V3a2 2 0 114 0v.1a1.7 1.7 0 001 1.5 1.7 1.7 0 001.9-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.7 1.7 0 00-.3 1.9V9a1.7 1.7 0 001.5 1H21a2 2 0 110 4h-.1a1.7 1.7 0 00-1.5 1z"/></svg>
                </span>
                Umum
            </a>
            <a class="gs-sidebar-item" data-tab="company">
                <span class="gs-sidebar-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 21h18M3 7v14M9 7v14M15 7v14M21 7v14M6 11h.01M6 15h.01M6 19h.01M12 11h.01M12 15h.01M12 19h.01M18 11h.01M18 15h.01M18 19h.01M3 7h18"/></svg>
                </span>
                Profil Perusahaan
            </a>
        </div>

        <div class="gs-sidebar-group">
            <div class="gs-sidebar-label">Sistem</div>
            <a class="gs-sidebar-item" data-tab="production">
                <span class="gs-sidebar-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 20h9M16.5 3.5a2.12 2.12 0 013 3L7 19l-4 1 1-4z"/></svg>
                </span>
                Produksi
            </a>
            <a class="gs-sidebar-item" data-tab="units">
                <span class="gs-sidebar-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M12 8v8M8 12h8"/></svg>
                </span>
                Satuan & Nomor
            </a>
            <a class="gs-sidebar-item" data-tab="inventory">
                <span class="gs-sidebar-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 8l-9-5-9 5 9 5 9-5z"/><path d="M3 8v8l9 5 9-5V8"/><path d="M12 13v8"/></svg>
                </span>
                Inventori
            </a>
        </div>

        <div class="gs-sidebar-group">
            <div class="gs-sidebar-label">Notifikasi</div>
            <a class="gs-sidebar-item" data-tab="notifications">
                <span class="gs-sidebar-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
                </span>
                Notifikasi
            </a>
        </div>

        <div class="gs-sidebar-group">
            <div class="gs-sidebar-label">Keamanan</div>
            <a class="gs-sidebar-item" data-tab="security">
                <span class="gs-sidebar-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </span>
                Keamanan
            </a>
            <a class="gs-sidebar-item" data-tab="users">
                <span class="gs-sidebar-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                </span>
                Pengguna & Akses
            </a>
        </div>

        <div class="gs-sidebar-group">
            <div class="gs-sidebar-label">Tampilan</div>
            <a class="gs-sidebar-item" data-tab="appearance">
                <span class="gs-sidebar-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                </span>
                Tampilan
            </a>
        </div>
    </nav>

    {{-- Konten --}}
    <div class="gs-content">

        {{-- Tab: Umum --}}
        <div class="gs-tab active" id="tab-general">
            <div class="gs-tab-header">
                <h2>Umum</h2>
                <p class="gs-tab-desc">Pengaturan dasar aplikasi dan perilaku sistem</p>
            </div>

            <div class="gs-card">
                <div class="gs-card-title">Bahasa & Lokal</div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Bahasa Aplikasi</div>
                        <div class="gs-row-desc">Pilih bahasa yang digunakan di seluruh aplikasi</div>
                    </div>
                    <select class="gs-select">
                        <option selected>Bahasa Indonesia</option>
                        <option>English</option>
                        <option>Bahasa Melayu</option>
                    </select>
                </div>
                <div class="gs-divider"></div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Format Tanggal</div>
                        <div class="gs-row-desc">Format penulisan tanggal di seluruh sistem</div>
                    </div>
                    <select class="gs-select">
                        <option selected>DD/MM/YYYY</option>
                        <option>MM/DD/YYYY</option>
                        <option>YYYY-MM-DD</option>
                    </select>
                </div>
                <div class="gs-divider"></div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Zona Waktu</div>
                        <div class="gs-row-desc">Zona waktu server untuk pencatatan data</div>
                    </div>
                    <select class="gs-select">
                        <option selected>WIB (UTC+7)</option>
                        <option>WITA (UTC+8)</option>
                        <option>WIT (UTC+9)</option>
                    </select>
                </div>
            </div>

            <div class="gs-card">
                <div class="gs-card-title">Mata Uang</div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Mata Uang Default</div>
                        <div class="gs-row-desc">Mata uang utama untuk pencatatan keuangan</div>
                    </div>
                    <select class="gs-select">
                        <option selected>IDR - Rupiah Indonesia</option>
                        <option>USD - US Dollar</option>
                        <option>SGD - Singapore Dollar</option>
                        <option>MYR - Malaysian Ringgit</option>
                    </select>
                </div>
                <div class="gs-divider"></div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Format Nominal</div>
                        <div class="gs-row-desc">Cara menampilkan angka dan desimal</div>
                    </div>
                    <select class="gs-select">
                        <option selected>1.234.567,89</option>
                        <option>1,234,567.89</option>
                        <option>1 234 567,89</option>
                    </select>
                </div>
            </div>

            <div class="gs-card">
                <div class="gs-card-title">Perilaku Sistem</div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Mode Gelap</div>
                        <div class="gs-row-desc">Aktifkan tampilan gelap untuk mata yang lebih nyaman</div>
                    </div>
                    <label class="gs-toggle">
                        <input type="checkbox" id="darkMode">
                        <span class="gs-toggle-slider"></span>
                    </label>
                </div>
                <div class="gs-divider"></div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Simpan Otomatis</div>
                        <div class="gs-row-desc">Otomatis menyimpan perubahan setiap 30 detik</div>
                    </div>
                    <label class="gs-toggle">
                        <input type="checkbox" checked>
                        <span class="gs-toggle-slider"></span>
                    </label>
                </div>
                <div class="gs-divider"></div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Suara Notifikasi</div>
                        <div class="gs-row-desc">Putar suara saat ada notifikasi baru</div>
                    </div>
                    <label class="gs-toggle">
                        <input type="checkbox" checked>
                        <span class="gs-toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Tab: Profil Perusahaan --}}
        <div class="gs-tab" id="tab-company">
            <div class="gs-tab-header">
                <h2>Profil Perusahaan</h2>
                <p class="gs-tab-desc">Informasi dasar perusahaan Anda</p>
            </div>

            <div class="gs-card">
                <div class="gs-card-title">Informasi Perusahaan</div>
                <div class="gs-field">
                    <label class="gs-field-label">Nama Perusahaan</label>
                    <input type="text" class="gs-input" value="PT Nusantara Jaya">
                </div>
                <div class="gs-field">
                    <label class="gs-field-label">Singkatan</label>
                    <input type="text" class="gs-input" value="PTNJ" style="max-width:120px;">
                </div>
                <div class="gs-field">
                    <label class="gs-field-label">Alamat</label>
                    <textarea class="gs-textarea" rows="2">Jl. Sudirman No. 123, Jakarta Selatan, DKI Jakarta</textarea>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div class="gs-field">
                        <label class="gs-field-label">Telepon</label>
                        <input type="text" class="gs-input" value="+62 21 1234 5678">
                    </div>
                    <div class="gs-field">
                        <label class="gs-field-label">Email</label>
                        <input type="email" class="gs-input" value="info@nusantarajaya.co.id">
                    </div>
                </div>
                <div class="gs-field">
                    <label class="gs-field-label">Website</label>
                    <input type="url" class="gs-input" value="https://nusantarajaya.co.id">
                </div>
            </div>

            <div class="gs-card">
                <div class="gs-card-title">Logo Perusahaan</div>
                <div class="gs-logo-upload">
                    <div class="gs-logo-preview">
                        <span>PT</span>
                    </div>
                    <div>
                        <button class="gs-btn gs-btn-primary">Upload Logo</button>
                        <div class="gs-row-desc" style="margin-top:6px;">SVG, PNG, atau JPG. Maks 2MB.</div>
                    </div>
                </div>
            </div>

            <div class="gs-card">
                <div class="gs-card-title">Pajak</div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">PPN Default</div>
                        <div class="gs-row-desc">Persentase Pajak Pertambahan Nilai</div>
                    </div>
                    <div class="gs-input-group">
                        <input type="number" class="gs-input gs-input-sm" value="11">
                        <span class="gs-input-suffix">%</span>
                    </div>
                </div>
                <div class="gs-divider"></div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Pajak Otomatis</div>
                        <div class="gs-row-desc">Otomatis tambahkan pajak pada invoice</div>
                    </div>
                    <label class="gs-toggle">
                        <input type="checkbox" checked>
                        <span class="gs-toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Tab: Produksi --}}
        <div class="gs-tab" id="tab-production">
            <div class="gs-tab-header">
                <h2>Produksi</h2>
                <p class="gs-tab-desc">Konfigurasi alur kerja produksi</p>
            </div>

            <div class="gs-card">
                <div class="gs-card-title">Alur Produksi</div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Auto-allocate Material</div>
                        <div class="gs-row-desc">Otomatis alokasikan material saat production order dibuat</div>
                    </div>
                    <label class="gs-toggle">
                        <input type="checkbox" checked>
                        <span class="gs-toggle-slider"></span>
                    </label>
                </div>
                <div class="gs-divider"></div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Konfirmasi Selesai Produksi</div>
                        <div class="gs-row-desc">Minta konfirmasi sebelum menandai produksi selesai</div>
                    </div>
                    <label class="gs-toggle">
                        <input type="checkbox" checked>
                        <span class="gs-toggle-slider"></span>
                    </label>
                </div>
                <div class="gs-divider"></div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Toleransi Waste</div>
                        <div class="gs-row-desc">Persentase toleransi material waste yang diperbolehkan</div>
                    </div>
                    <div class="gs-input-group">
                        <input type="number" class="gs-input gs-input-sm" value="5">
                        <span class="gs-input-suffix">%</span>
                    </div>
                </div>
            </div>

            <div class="gs-card">
                <div class="gs-card-title">Jadwal Produksi</div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Jam Operasional</div>
                        <div class="gs-row-desc">Jam kerja default untuk penjadwalan</div>
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;">
                        <input type="time" class="gs-input gs-input-sm" value="08:00">
                        <span class="gs-row-desc">-</span>
                        <input type="time" class="gs-input gs-input-sm" value="17:00">
                    </div>
                </div>
                <div class="gs-divider"></div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Hari Kerja</div>
                        <div class="gs-row-desc">Hari aktif operasional pabrik</div>
                    </div>
                    <div class="gs-segmented">
                        <button class="gs-seg active">Sen</button>
                        <button class="gs-seg active">Sel</button>
                        <button class="gs-seg active">Rab</button>
                        <button class="gs-seg active">Kam</button>
                        <button class="gs-seg active">Jum</button>
                        <button class="gs-seg">Sab</button>
                        <button class="gs-seg">Min</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Satuan & Nomor --}}
        <div class="gs-tab" id="tab-units">
            <div class="gs-tab-header">
                <h2>Satuan & Nomor</h2>
                <p class="gs-tab-desc">Konfigurasi satuan ukur dan penomoran dokumen</p>
            </div>

            <div class="gs-card">
                <div class="gs-card-title">Satuan Ukur</div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Satuan Panjang</div>
                        <div class="gs-row-desc">Satuan default untuk pengukuran panjang</div>
                    </div>
                    <select class="gs-select">
                        <option selected>Meter (m)</option>
                        <option>Centimeter (cm)</option>
                        <option>Millimeter (mm)</option>
                        <option>Inch (in)</option>
                    </select>
                </div>
                <div class="gs-divider"></div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Satuan Berat</div>
                        <div class="gs-row-desc">Satuan default untuk pengukuran berat</div>
                    </div>
                    <select class="gs-select">
                        <option selected>Kilogram (kg)</option>
                        <option>Gram (g)</option>
                        <option>Pound (lb)</option>
                    </select>
                </div>
            </div>

            <div class="gs-card">
                <div class="gs-card-title">Penomoran Dokumen</div>
                <div class="gs-field">
                    <label class="gs-field-label">Prefix Production Order</label>
                    <input type="text" class="gs-input" value="PO" style="max-width:120px;">
                </div>
                <div class="gs-field">
                    <label class="gs-field-label">Prefix Material Issue</label>
                    <input type="text" class="gs-input" value="MI" style="max-width:120px;">
                </div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Nomor Otomatis</div>
                        <div class="gs-row-desc">Generate nomor dokumen secara otomatis</div>
                    </div>
                    <label class="gs-toggle">
                        <input type="checkbox" checked>
                        <span class="gs-toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Tab: Inventori --}}
        <div class="gs-tab" id="tab-inventory">
            <div class="gs-tab-header">
                <h2>Inventori</h2>
                <p class="gs-tab-desc">Pengaturan manajemen stok dan gudang</p>
            </div>

            <div class="gs-card">
                <div class="gs-card-title">Stok</div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Stok Negatif</div>
                        <div class="gs-row-desc">Izinkan stok di bawah nol (backorder)</div>
                    </div>
                    <label class="gs-toggle">
                        <input type="checkbox">
                        <span class="gs-toggle-slider"></span>
                    </label>
                </div>
                <div class="gs-divider"></div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Minimum Stok Alert</div>
                        <div class="gs-row-desc">Persentase minimum sebelum alert stok rendah</div>
                    </div>
                    <div class="gs-input-group">
                        <input type="number" class="gs-input gs-input-sm" value="20">
                        <span class="gs-input-suffix">%</span>
                    </div>
                </div>
                <div class="gs-divider"></div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Metode Valuasi</div>
                        <div class="gs-row-desc">Metode perhitungan nilai inventori</div>
                    </div>
                    <select class="gs-select">
                        <option selected>FIFO</option>
                        <option>LIFO</option>
                        <option>Weighted Average</option>
                    </select>
                </div>
            </div>

            <div class="gs-card">
                <div class="gs-card-title">Gudang</div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Multi Gudang</div>
                        <div class="gs-row-desc">Aktifkan dukungan multiple gudang</div>
                    </div>
                    <label class="gs-toggle">
                        <input type="checkbox" checked>
                        <span class="gs-toggle-slider"></span>
                    </label>
                </div>
                <div class="gs-divider"></div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Transfer Otomatis</div>
                        <div class="gs-row-desc">Otomatis transfer stok antar gudang jika stok habis</div>
                    </div>
                    <label class="gs-toggle">
                        <input type="checkbox">
                        <span class="gs-toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Tab: Notifikasi --}}
        <div class="gs-tab" id="tab-notifications">
            <div class="gs-tab-header">
                <h2>Notifikasi</h2>
                <p class="gs-tab-desc">Atur cara Anda menerima notifikasi</p>
            </div>

            <div class="gs-card">
                <div class="gs-card-title">Notifikasi Email</div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Stok Rendah</div>
                        <div class="gs-row-desc">Kirim email saat stok di bawah minimum</div>
                    </div>
                    <label class="gs-toggle">
                        <input type="checkbox" checked>
                        <span class="gs-toggle-slider"></span>
                    </label>
                </div>
                <div class="gs-divider"></div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Production Selesai</div>
                        <div class="gs-row-desc">Notifikasi saat produksi selesai</div>
                    </div>
                    <label class="gs-toggle">
                        <input type="checkbox" checked>
                        <span class="gs-toggle-slider"></span>
                    </label>
                </div>
                <div class="gs-divider"></div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Quality Issue</div>
                        <div class="gs-row-desc">Alert saat ada masalah kualitas</div>
                    </div>
                    <label class="gs-toggle">
                        <input type="checkbox" checked>
                        <span class="gs-toggle-slider"></span>
                    </label>
                </div>
            </div>

            <div class="gs-card">
                <div class="gs-card-title">Notifikasi In-App</div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Push Notification</div>
                        <div class="gs-row-desc">Tampilkan notifikasi push di browser</div>
                    </div>
                    <label class="gs-toggle">
                        <input type="checkbox" checked>
                        <span class="gs-toggle-slider"></span>
                    </label>
                </div>
                <div class="gs-divider"></div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Badge Counter</div>
                        <div class="gs-row-desc">Tampilkan angka notifikasi di ikon sidebar</div>
                    </div>
                    <label class="gs-toggle">
                        <input type="checkbox" checked>
                        <span class="gs-toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Tab: Keamanan --}}
        <div class="gs-tab" id="tab-security">
            <div class="gs-tab-header">
                <h2>Keamanan</h2>
                <p class="gs-tab-desc">Pengaturan keamanan dan autentikasi</p>
            </div>

            <div class="gs-card">
                <div class="gs-card-title">Autentikasi</div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Dua Faktor Auth (2FA)</div>
                        <div class="gs-row-desc">Aktifkan verifikasi dua langkah untuk login</div>
                    </div>
                    <label class="gs-toggle">
                        <input type="checkbox">
                        <span class="gs-toggle-slider"></span>
                    </label>
                </div>
                <div class="gs-divider"></div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Sesi Otomatis Logout</div>
                        <div class="gs-row-desc">Logout otomatis setelah tidak aktif</div>
                    </div>
                    <select class="gs-select" style="max-width:160px;">
                        <option>15 menit</option>
                        <option selected>30 menit</option>
                        <option>1 jam</option>
                        <option>4 jam</option>
                    </select>
                </div>
            </div>

            <div class="gs-card">
                <div class="gs-card-title">Password</div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Minimum Panjang</div>
                        <div class="gs-row-desc">Jumlah karakter minimum untuk password</div>
                    </div>
                    <div class="gs-input-group">
                        <input type="number" class="gs-input gs-input-sm" value="8">
                        <span class="gs-input-suffix">karakter</span>
                    </div>
                </div>
                <div class="gs-divider"></div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Wajib Uppercase</div>
                        <div class="gs-row-desc">Harus ada huruf besar</div>
                    </div>
                    <label class="gs-toggle">
                        <input type="checkbox" checked>
                        <span class="gs-toggle-slider"></span>
                    </label>
                </div>
                <div class="gs-divider"></div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Wajib Angka</div>
                        <div class="gs-row-desc">Harus ada minimal satu angka</div>
                    </div>
                    <label class="gs-toggle">
                        <input type="checkbox" checked>
                        <span class="gs-toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Tab: Pengguna & Akses --}}
        <div class="gs-tab" id="tab-users">
            <div class="gs-tab-header">
                <h2>Pengguna & Akses</h2>
                <p class="gs-tab-desc">Kelola pengguna dan hak akses sistem</p>
            </div>

            <div class="gs-card">
                <div class="gs-card-title">Role & Permission</div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Self-Registration</div>
                        <div class="gs-row-desc">Izinkan pengguna baru mendaftar sendiri</div>
                    </div>
                    <label class="gs-toggle">
                        <input type="checkbox">
                        <span class="gs-toggle-slider"></span>
                    </label>
                </div>
                <div class="gs-divider"></div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Default Role</div>
                        <div class="gs-row-desc">Role yang diberikan saat pengguna baru mendaftar</div>
                    </div>
                    <select class="gs-select">
                        <option selected>Viewer</option>
                        <option>Operator</option>
                        <option>Admin</option>
                    </select>
                </div>
            </div>

            <div class="gs-card">
                <div class="gs-card-title">Approval</div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Approval Produksi</div>
                        <div class="gs-row-desc">Membutuhkan persetujuan manager untuk production order</div>
                    </div>
                    <label class="gs-toggle">
                        <input type="checkbox" checked>
                        <span class="gs-toggle-slider"></span>
                    </label>
                </div>
                <div class="gs-divider"></div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Approval Pembelian</div>
                        <div class="gs-row-desc">Membutuhkan persetujuan untuk pembelian material</div>
                    </div>
                    <label class="gs-toggle">
                        <input type="checkbox">
                        <span class="gs-toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Tab: Tampilan --}}
        <div class="gs-tab" id="tab-appearance">
            <div class="gs-tab-header">
                <h2>Tampilan</h2>
                <p class="gs-tab-desc">Sesuaikan tampilan dan tema aplikasi</p>
            </div>

            <div class="gs-card">
                <div class="gs-card-title">Tema</div>
                <div class="gs-theme-grid">
                    <div class="gs-theme-option active" data-theme="light">
                        <div class="gs-theme-preview gs-theme-light">
                            <div class="gs-theme-bar"></div>
                            <div class="gs-theme-sidebar"></div>
                        </div>
                        <span>Cahaya</span>
                    </div>
                    <div class="gs-theme-option" data-theme="dark">
                        <div class="gs-theme-preview gs-theme-dark">
                            <div class="gs-theme-bar"></div>
                            <div class="gs-theme-sidebar"></div>
                        </div>
                        <span>Gelap</span>
                    </div>
                    <div class="gs-theme-option" data-theme="auto">
                        <div class="gs-theme-preview gs-theme-auto">
                            <div class="gs-theme-bar"></div>
                            <div class="gs-theme-sidebar"></div>
                        </div>
                        <span>Otomatis</span>
                    </div>
                </div>
            </div>

            <div class="gs-card">
                <div class="gs-card-title">Aksen Warna</div>
                <div class="gs-color-grid">
                    <div class="gs-color-swatch active" style="background:#0A84FF;" data-color="#0A84FF"></div>
                    <div class="gs-color-swatch" style="background:#5E5CE6;" data-color="#5E5CE6"></div>
                    <div class="gs-color-swatch" style="background:#30D158;" data-color="#30D158"></div>
                    <div class="gs-color-swatch" style="background:#FF9F0A;" data-color="#FF9F0A"></div>
                    <div class="gs-color-swatch" style="background:#FF453A;" data-color="#FF453A"></div>
                    <div class="gs-color-swatch" style="background:#FF375F;" data-color="#FF375F"></div>
                    <div class="gs-color-swatch" style="background:#64D2FF;" data-color="#64D2FF"></div>
                    <div class="gs-color-swatch" style="background:#BF5AF2;" data-color="#BF5AF2"></div>
                </div>
            </div>

            <div class="gs-card">
                <div class="gs-card-title">Sidebar</div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Sidebar Transparan</div>
                        <div class="gs-row-desc">Efek blur transparan pada sidebar</div>
                    </div>
                    <label class="gs-toggle">
                        <input type="checkbox" checked>
                        <span class="gs-toggle-slider"></span>
                    </label>
                </div>
                <div class="gs-divider"></div>
                <div class="gs-row">
                    <div class="gs-row-info">
                        <div class="gs-row-label">Icon Size</div>
                        <div class="gs-row-desc">Ukuran ikon di sidebar navigasi</div>
                    </div>
                    <div class="gs-segmented">
                        <button class="gs-seg">Kecil</button>
                        <button class="gs-seg active">Sedang</button>
                        <button class="gs-seg">Besar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
