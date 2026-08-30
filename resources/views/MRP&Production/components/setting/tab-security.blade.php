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
