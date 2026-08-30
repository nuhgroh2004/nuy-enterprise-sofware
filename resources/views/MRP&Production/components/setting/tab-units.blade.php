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
