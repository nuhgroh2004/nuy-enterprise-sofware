@extends('MRP&Production.components.layout', ['title' => 'Material Issue'])

@section('content')
    <div class="content-header">
        <div>
            <h1>Material Issue</h1>
            <div class="sub">Pengeluaran material</div>
        </div>
        <button class="btn-primary">+ Tambah Baru</button>
    </div>
    <div class="panel">
        <h3>Fitur Material Issue</h3>
        <div class="sub">Kelola pengeluaran material</div>
        <p>Halaman ini berisi fitur untuk mengelola pengeluaran material, termasuk issue ke production, return material, dan tracking penggunaan.</p>
    </div>
@endsection
