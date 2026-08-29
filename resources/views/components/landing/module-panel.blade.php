{{--
    Wrapper untuk satu panel modul di dalam mac-window hero.
    Hanya panel dengan atribut `active` yang tampil saat load pertama,
    sisanya disembunyikan lalu ditukar oleh landing.js saat dock diklik.

    Props:
    - module (string) : kunci modul, harus sama dengan data-module pada dock
    - active (bool)   : apakah panel ini tampil saat pertama kali load
--}}
@props(['module', 'active' => false])

<div
    class="module-panel {{ $active ? 'is-visible' : '' }}"
    data-module-panel="{{ $module }}"
    style="display: {{ $active ? 'block' : 'none' }};"
>
    {{ $slot }}
</div>
