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
