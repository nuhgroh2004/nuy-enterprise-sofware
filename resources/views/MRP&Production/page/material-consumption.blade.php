@extends('MRP&Production.components.layout', ['title' => 'Material Consumption'])

@section('content')
    <div class="content-header">
        <div>
            <h1>Material Consumption</h1>
            <div class="sub">Konsumsi material produksi</div>
        </div>
        <button class="btn-primary">+ Tambah Baru</button>
    </div>
    <div class="panel">
        <h3>Fitur Material Consumption</h3>
        <div class="sub">Kelola konsumsi material</div>
        <p>Halaman ini berisi fitur untuk mengelola konsumsi material produksi, termasuk pencatatan penggunaan bahan, waste tracking, dan analisis konsumsi.</p>
    </div>
@endsection
