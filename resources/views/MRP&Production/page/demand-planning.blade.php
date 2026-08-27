@extends('MRP&Production.components.layout', ['title' => 'Demand Planning'])

@section('content')
    <div class="content-header">
        <div>
            <h1>Demand Planning</h1>
            <div class="sub">Perencanaan kebutuhan bahan dan produksi</div>
        </div>
        <button class="btn-primary">+ Tambah Baru</button>
    </div>
    <div class="panel">
        <h3>Fitur Demand Planning</h3>
        <div class="sub">Kelola perencanaan permintaan pasar</div>
        <p>Halaman ini berisi fitur untuk mengelola perencanaan permintaan produk, termasuk analisis tren pasar, forecast permintaan, dan perencanaan kapasitas produksi.</p>
    </div>
@endsection
