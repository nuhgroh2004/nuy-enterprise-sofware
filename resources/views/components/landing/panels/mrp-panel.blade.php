<div class="panel-dashboard">
    <aside class="panel-sidebar">
        <div class="item is-current">Perintah Produksi</div>
        <div class="item">Bill of Material</div>
        <div class="item">Kapasitas Mesin</div>
        <div class="item">Kontrol Kualitas</div>
    </aside>
    <div class="panel-main">
        <h4>Perencanaan Produksi</h4>
        <p class="sub">Lini produksi A &middot; 6 perintah kerja berjalan</p>

        <div class="panel-kpis">
            <div class="kpi-tile">
                <div class="label">Utilisasi Mesin</div>
                <div class="value">87%</div>
                <div class="delta" style="color: var(--fico);">Optimal</div>
            </div>
            <div class="kpi-tile">
                <div class="label">Unit Selesai</div>
                <div class="value">3.240</div>
                <div class="delta" style="color: var(--fico);">Sesuai target</div>
            </div>
            <div class="kpi-tile">
                <div class="label">Produk Cacat</div>
                <div class="value">0,8%</div>
                <div class="delta" style="color: var(--mrp);">Dalam batas</div>
            </div>
        </div>

        <div class="panel-bars" aria-hidden="true">
            <div class="bar" style="height: 55%; background: var(--mrp-soft);"></div>
            <div class="bar" style="height: 78%; background: var(--mrp);"></div>
            <div class="bar" style="height: 62%; background: var(--mrp-soft);"></div>
            <div class="bar" style="height: 90%; background: var(--mrp);"></div>
            <div class="bar" style="height: 70%; background: var(--mrp-soft);"></div>
            <div class="bar" style="height: 84%; background: var(--mrp);"></div>
            <div class="bar" style="height: 66%; background: var(--mrp-soft);"></div>
        </div>
    </div>
</div>
