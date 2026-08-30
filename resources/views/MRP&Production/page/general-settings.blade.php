@extends('MRP&Production.components.layout', ['title' => 'General Settings'])

@section('content')
@vite(['resources/css/general-settings.css', 'resources/js/general-settings.js'])

<div class="gs-wrap">

    {{-- Sidebar Kategori --}}
    @include('MRP&Production.components.setting.sidebar')

    {{-- Konten --}}
    <div class="gs-content">

        {{-- Tab: Umum --}}
        @include('MRP&Production.components.setting.tab-general')

        {{-- Tab: Profil Perusahaan --}}
        @include('MRP&Production.components.setting.tab-company')

        {{-- Tab: Produksi --}}
        @include('MRP&Production.components.setting.tab-production')

        {{-- Tab: Satuan & Nomor --}}
        @include('MRP&Production.components.setting.tab-units')

        {{-- Tab: Inventori --}}
        @include('MRP&Production.components.setting.tab-inventory')

        {{-- Tab: Notifikasi --}}
        @include('MRP&Production.components.setting.tab-notifications')

        {{-- Tab: Keamanan --}}
        @include('MRP&Production.components.setting.tab-security')

        {{-- Tab: Pengguna & Akses --}}
        @include('MRP&Production.components.setting.tab-users')

        {{-- Tab: Tampilan --}}
        @include('MRP&Production.components.setting.tab-appearance')

    </div>
</div>
@endsection
