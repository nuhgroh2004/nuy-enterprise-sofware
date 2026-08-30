{{--
    Komponen window chrome ala macOS, bisa dipakai ulang di mana saja.

    Props:
    - title (string)  : teks di title bar, default "ERP Suite"
    - attributes      : diteruskan ke elemen pembungkus (class tambahan, data-*, dsb)

    Slot:
    - default : konten yang dirender di dalam badan window
--}}
@props(['title' => 'ERP Suite'])

<div {{ $attributes->merge(['class' => 'mac-window']) }}>
    <div class="mac-window__titlebar">
        <div class="mac-window__dots">
            <span></span><span></span><span></span>
        </div>
        <div class="mac-window__title" data-window-title>{{ $title }}</div>
    </div>
    <div class="mac-window__body">
        {{ $slot }}
    </div>
</div>
