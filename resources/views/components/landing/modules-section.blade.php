{{-- Grid berisi 5 pilar modul ERP --}}
<section class="modules" id="modules">
    <div class="container">
        <div class="section-heading center" data-reveal>
            <span class="eyebrow">Lima Modul, Satu Sistem</span>
            <h2>Semua fungsi bisnis, dalam satu tampilan yang konsisten</h2>
            <p>Setiap modul dirancang seperti aplikasi bawaan macOS: ringan, cepat, dan saling terhubung tanpa gesekan data.</p>
        </div>

        <div class="modules__grid">
            <x-landing.module-card
                code="MRP"
                color="mrp"
                title="Manufacturing Resource Planning"
                :tags="['Bill of Material', 'Perintah Kerja', 'Kapasitas Mesin']"
            >
                Rencanakan produksi, kelola bill of material, dan pantau kapasitas
                lini produksi secara langsung tanpa spreadsheet terpisah.
            </x-landing.module-card>

            <x-landing.module-card
                code="CRM"
                color="crm"
                title="Customer Relationship Management"
                :tags="['Pipeline Penjualan', 'Follow-up Otomatis', 'Insight Pelanggan']"
            >
                Kelola prospek dari kontak pertama hingga closing, dengan pipeline
                visual dan pengingat aktivitas yang tidak pernah terlewat.
            </x-landing.module-card>

            <x-landing.module-card
                code="SCM"
                color="scm"
                title="Supply Chain Management"
                :tags="['Multi-gudang', 'Pemasok', 'Pengiriman']"
            >
                Sinkronkan stok di seluruh gudang, kelola pemasok, dan lacak
                pengiriman secara waktu nyata dari satu dasbor.
            </x-landing.module-card>

            <x-landing.module-card
                code="FICO"
                color="fico"
                title="Finance &amp; Controlling"
                :tags="['Buku Besar', 'Anggaran', 'Laporan Konsolidasi']"
            >
                Satukan pembukuan, anggaran, dan pelaporan keuangan lintas entitas
                dengan kontrol akses yang jelas untuk setiap peran.
            </x-landing.module-card>

            <x-landing.module-card
                code="HRIS"
                color="hris"
                title="Human Resource Information System"
                :tags="['Absensi', 'Penggajian', 'Rekrutmen']"
            >
                Kelola data karyawan, absensi, penggajian, dan proses rekrutmen
                dalam satu alur kerja yang rapi dan mudah diaudit.
            </x-landing.module-card>
        </div>
    </div>
</section>
