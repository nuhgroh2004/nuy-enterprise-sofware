@extends('MRP&Production.components.layout', ['title' => 'Equipment'])

@section('content')
    <div class="content-header">
        <div>
            <h1>Equipment</h1>
            <div class="sub">Peralatan produksi</div>
        </div>
        <button class="btn-primary">+ Tambah Baru</button>
    </div>
    <div class="panel">
        <h3>Fitur Equipment</h3>
        <div class="sub">Kelola peralatan</div>
        <p>Halaman ini berisi fitur untuk mengelola peralatan produksi, termasuk daftar equipment, status, dan history pemeliharaan.</p>
    </div>
@endsection
