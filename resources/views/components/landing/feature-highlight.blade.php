{{--
    Satu baris fitur dengan tata letak berselang-seling (copy di satu sisi,
    window mock di sisi lain).

    Props:
    - eyebrow  : label kecil di atas judul, mis. "MRP"
    - title    : judul fitur
    - reverse  : bool, jika true visual berada di kiri
    - color    : token warna aksen untuk ikon centang, mis. "mrp"
    - points   : array poin-poin singkat

    Slot:
    - default : paragraf deskripsi
    - visual  : konten mac-window (opsional, override default)
--}}
@props(['eyebrow' => '', 'title', 'reverse' => false, 'color' => 'crm', 'points' => []])

<section class="feature {{ $reverse ? 'is-reverse' : '' }}" data-reveal>
    <div class="container feature__inner">
        <div class="feature__copy">
            @if ($eyebrow)
                <span class="eyebrow">{{ $eyebrow }}</span>
            @endif
            <h3>{{ $title }}</h3>
            <p>{{ $slot }}</p>

            @if (count($points))
                <ul class="feature__list">
                    @foreach ($points as $point)
                        <li>
                            <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="10" cy="10" r="10" fill="var(--{{ $color }})" opacity="0.15"/>
                                <path d="M6 10.5l2.5 2.5L14 7" stroke="var(--{{ $color }})" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>{{ $point }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="feature__visual">
            {{ $visual ?? '' }}
        </div>
    </div>
</section>
