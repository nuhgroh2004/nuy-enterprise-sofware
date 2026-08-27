@extends('MRP&Production.components.layout', ['title' => 'Material Availability'])

@section('content')
    <div class="content-header">
        <div>
            <h1>Material Availability</h1>
            <div class="sub">Ketersediaan material</div>
        </div>
        <button class="btn-primary">+ Tambah Baru</button>
    </div>
    <div class="panel">
        <h3>Fitur Material Availability</h3>
        <div class="sub">Kelola ketersediaan material</div>
        <p>Halaman ini berisi fitur untuk mengelola ketersediaan material, termasuk stock check, availability forecast, dan alert ketersediaan.</p>
    </div>
@endsection
