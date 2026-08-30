{{--
    Kartu satu modul ERP.

    Props:
    - code     : kode singkat ditampilkan di ikon, mis. "MRP"
    - color    : nama token warna modul: mrp | crm | scm | fico | hris
    - title    : judul modul
    - tags     : array string kapabilitas singkat
    - href     : tautan "Pelajari lebih lanjut"

    Slot:
    - default  : deskripsi modul (paragraf)
--}}
@props(['code', 'color', 'title', 'tags' => [], 'href' => '#'])

<article class="module-card" data-reveal>
    <div class="module-card__icon" style="background: linear-gradient(150deg, var(--{{ $color }}), var(--{{ $color }}));">
        {{ $code }}
    </div>
    <h3>{{ $title }}</h3>
    <p>{{ $slot }}</p>

    @if (count($tags))
        <div class="module-card__tags">
            @foreach ($tags as $tag)
                <span>{{ $tag }}</span>
            @endforeach
        </div>
    @endif

    <a href="{{ $href }}" class="module-card__link" style="color: var(--{{ $color }});">
        Pelajari lebih lanjut &rarr;
    </a>
</article>
