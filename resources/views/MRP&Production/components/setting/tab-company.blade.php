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
