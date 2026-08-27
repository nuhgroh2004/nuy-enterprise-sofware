<div class="titlebar">

    <div class="traffic">
        <span class="c"></span>
        <span class="m"></span>
        <span class="z"></span>
    </div>

    <div class="menu-toggle" id="menuToggle">
        <svg
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
        >
            <path
                d="M4 6h16M4 12h16M4 18h16"
                stroke-linecap="round"
            />
        </svg>
    </div>

    <div class="title">
        {{ $title ?? 'MRP Dashboard' }}
    </div>

    <div class="searchbox">
        <svg
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
        >
            <circle cx="11" cy="11" r="7"/>
            <path
                d="M21 21l-4.3-4.3"
                stroke-linecap="round"
            />
        </svg>

        Cari modul, invoice, produk...
    </div>

</div>
