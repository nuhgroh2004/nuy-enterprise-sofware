@extends('MRP&Production.components.layout', ['title' => 'Bill of Materials'])

@section('content')
    <div class="content-header">
        <div>
            <h1>Bill of Materials</h1>
            <div class="sub">Daftar material produk</div>
        </div>
        <button class="btn-primary">+ Tambah Baru</button>
    </div>
    <div class="panel">
        <h3>Fitur Bill of Materials</h3>
        <div class="sub">Kelola daftar material</div>
        <p>Halaman ini berisi fitur untuk mengelola daftar material produk (BOM), termasuk struktur produk, komponen, dan hubungan antar material.</p>
    </div>
@endsection
