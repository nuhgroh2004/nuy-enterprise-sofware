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
