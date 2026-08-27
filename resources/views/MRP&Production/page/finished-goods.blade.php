@extends('MRP&Production.components.layout', ['title' => 'Finished Goods'])

@section('content')
    <div class="content-header">
        <div>
            <h1>Finished Goods</h1>
            <div class="sub">Barang jadi</div>
        </div>
        <button class="btn-primary">+ Tambah Baru</button>
    </div>
    <div class="panel">
        <h3>Fitur Finished Goods</h3>
        <div class="sub">Kelola barang jadi</div>
        <p>Halaman ini berisi fitur untuk mengelola barang jadi, termasuk daftar produk jadi, stock finished goods, dan manajemen gudang.</p>
    </div>
@endsection
