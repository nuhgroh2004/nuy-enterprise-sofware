{{-- Kumpulan baris fitur mendalam untuk tiap gugus modul --}}
<section id="fitur">
    <div class="container">
        <div class="section-heading" data-reveal>
            <span class="eyebrow">Cara Kerja</span>
            <h2>Dirancang agar tim operasional, penjualan, dan keuangan tidak lagi berpindah aplikasi</h2>
        </div>
    </div>

    <x-landing.feature-highlight
        eyebrow="MRP + SCM"
        title="Dari bahan baku sampai barang jadi, terlihat dalam satu alur"
        color="mrp"
        :points="[
            'Bill of material otomatis memicu permintaan pembelian',
            'Kapasitas mesin dan jadwal kerja terlihat langsung',
            'Stok multi-gudang tersinkron tanpa entri ganda',
        ]"
    >
        Modul produksi dan rantai pasok berbagi data yang sama secara langsung,
        sehingga perencana produksi tahu persis kapan bahan baku akan tiba —
        tanpa harus menelepon gudang.
        <x-slot:visual>
            <x-landing.macos-window title="MRP — Perencanaan Produksi">
                @include('components.landing.panels.mrp-panel')
            </x-landing.macos-window>
        </x-slot:visual>
    </x-landing.feature-highlight>

    <x-landing.feature-highlight
        eyebrow="CRM"
        title="Pipeline penjualan yang jujur soal peluang mana yang nyata"
        color="crm"
        reverse
        :points="[
            'Tahapan pipeline yang bisa disesuaikan tim penjualan',
            'Pengingat follow-up otomatis, tidak ada prospek yang hilang',
            'Riwayat komunikasi tersimpan di satu kartu pelanggan',
        ]"
    >
        Setiap peluang punya nilai, tahap, dan aktivitas berikutnya yang jelas.
        Manajer penjualan melihat forecast tanpa perlu meminta laporan mingguan.
        <x-slot:visual>
            <x-landing.macos-window title="CRM — Sales Pipeline">
                @include('components.landing.panels.crm-panel')
            </x-landing.macos-window>
        </x-slot:visual>
    </x-landing.feature-highlight>

    <x-landing.feature-highlight
        eyebrow="FICO + HRIS"
        title="Keuangan dan SDM berjalan dengan angka yang sama persis"
        color="fico"
        :points="[
            'Penggajian otomatis terhubung ke buku besar',
            'Laporan konsolidasi lintas entitas dalam hitungan menit',
            'Jejak audit lengkap untuk setiap perubahan data',
        ]"
    >
        Tidak ada lagi rekonsiliasi manual antara data payroll dan pembukuan.
        Tim finance dan HR bekerja dari sumber data yang sama.
        <x-slot:visual>
            <x-landing.macos-window title="FICO — Finance &amp; Controlling">
                @include('components.landing.panels.fico-panel')
            </x-landing.macos-window>
        </x-slot:visual>
    </x-landing.feature-highlight>
</section>
