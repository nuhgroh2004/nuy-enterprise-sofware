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
