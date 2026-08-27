@extends('MRP&Production.components.layout', ['title' => 'Material Requirements'])

@section('content')
    <div class="content-header">
        <div>
            <h1>Material Requirements</h1>
            <div class="sub">Kebutuhan material produksi</div>
        </div>
        <button class="btn-primary">+ Tambah Baru</button>
    </div>
    <div class="panel">
        <h3>Fitur Material Requirements</h3>
        <div class="sub">Kelola kebutuhan material</div>
        <p>Halaman ini berisi fitur untuk mengelola kebutuhan material produksi, termasuk perhitungan kebutuhan bahan, reorder point, dan manajemen stok.</p>
    </div>
@endsection
