@extends('MRP&Production.components.layout', ['title' => 'Production Orders'])

@section('content')
    <div class="content-header">
        <div>
            <h1>Production Orders</h1>
            <div class="sub">Pesanan produksi</div>
        </div>
        <button class="btn-primary">+ Tambah Baru</button>
    </div>
    <div class="panel">
        <h3>Fitur Production Orders</h3>
        <div class="sub">Kelola pesanan produksi</div>
        <p>Halaman ini berisi fitur untuk mengelola pesanan produksi, termasuk pembuatan order, tracking progress, dan monitoring hasil produksi.</p>
    </div>
@endsection
