@extends('MRP&Production.components.layout', ['title' => 'Stock Movement'])

@section('content')
    <div class="content-header">
        <div>
            <h1>Stock Movement</h1>
            <div class="sub">Pergerakan stok</div>
        </div>
        <button class="btn-primary">+ Tambah Baru</button>
    </div>
    <div class="panel">
        <h3>Fitur Stock Movement</h3>
        <div class="sub">Kelola pergerakan stok</div>
        <p>Halaman ini berisi fitur untuk mengelola pergerakan stok, termasuk transfer antar gudang, stock opname, dan history pergerakan.</p>
    </div>
@endsection
