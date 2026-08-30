{{--
    Kartu testimoni satu pelanggan.

    Props:
    - name    : nama pemberi testimoni
    - role    : jabatan & perusahaan
    - color   : token warna aksen avatar, mis. "crm"
    - initial : inisial ditampilkan di avatar

    Slot:
    - default : isi kutipan
--}}
@props(['name', 'role', 'color' => 'crm', 'initial' => '?'])

<article class="testimonial-card" data-reveal>
    <p class="testimonial-card__quote">&ldquo;{{ $slot }}&rdquo;</p>
    <div class="testimonial-card__author">
        <div class="testimonial-card__avatar" style="background: var(--{{ $color }});">
            {{ $initial }}
        </div>
        <div>
            <div class="testimonial-card__name">{{ $name }}</div>
            <div class="testimonial-card__role">{{ $role }}</div>
        </div>
    </div>
</article>
