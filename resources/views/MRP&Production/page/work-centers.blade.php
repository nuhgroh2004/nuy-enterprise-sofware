@extends('MRP&Production.components.layout', ['title' => 'Work Centers'])

@section('content')
    <div class="content-header">
        <div>
            <h1>Work Centers</h1>
            <div class="sub">Pusat kerja produksi</div>
        </div>
        <button class="btn-primary">+ Tambah Baru</button>
    </div>
    <div class="panel">
        <h3>Fitur Work Centers</h3>
        <div class="sub">Kelola pusat kerja</div>
        <p>Halaman ini berisi fitur untuk mengelola pusat kerja produksi, termasuk daftar mesin, kapasitas, dan jadwal pemeliharaan.</p>
    </div>
@endsection
