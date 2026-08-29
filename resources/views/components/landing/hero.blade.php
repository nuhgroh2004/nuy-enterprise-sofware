{{-- Hero section: judul utama + simulasi desktop macOS dengan window & dock --}}
<section class="hero" data-reveal>
    <div class="container">
        <div class="hero__eyebrow-row">
            <span class="eyebrow">MRP &middot; CRM &middot; SCM &middot; FICO &middot; HRIS</span>
        </div>

        <h1>
            Satu sistem, <span class="accent-gradient">seluruh bisnis Anda</span> berjalan rapi.
        </h1>
        <p class="hero__subtitle">
            ERP Suite menyatukan produksi, penjualan, rantai pasok, keuangan, dan SDM
            dalam satu tampilan yang secantik dan sesederhana desktop yang Anda kenal.
        </p>

        <div class="hero__cta">
            <a href="#cta" class="btn btn-primary">Mulai Uji Coba</a>
            <a href="#fitur" class="btn btn-secondary">Lihat Demo Produk</a>
        </div>

        <div class="hero__stage">
            <div class="hero__glow" aria-hidden="true"></div>

            <div class="hero__window">
                <x-landing.macos-window title="CRM — Sales Pipeline">
                    <x-landing.module-panel module="crm" active>
                        @include('components.landing.panels.crm-panel')
                    </x-landing.module-panel>
                    <x-landing.module-panel module="mrp">
                        @include('components.landing.panels.mrp-panel')
                    </x-landing.module-panel>
                    <x-landing.module-panel module="scm">
                        @include('components.landing.panels.scm-panel')
                    </x-landing.module-panel>
                    <x-landing.module-panel module="fico">
                        @include('components.landing.panels.fico-panel')
                    </x-landing.module-panel>
                    <x-landing.module-panel module="hris">
                        @include('components.landing.panels.hris-panel')
                    </x-landing.module-panel>
                </x-landing.macos-window>
            </div>

            <x-landing.dock />
        </div>
    </div>
</section>
