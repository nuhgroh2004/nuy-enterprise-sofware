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
