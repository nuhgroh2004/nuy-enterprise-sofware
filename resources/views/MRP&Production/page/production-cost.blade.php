@extends('MRP&Production.components.layout', ['title' => 'Production Cost'])

@section('content')
    <div class="content-header">
        <div>
            <h1>Production Cost</h1>
            <div class="sub">Biaya produksi</div>
        </div>
        <button class="btn-primary">+ Tambah Baru</button>
    </div>
    <div class="panel">
        <h3>Fitur Production Cost</h3>
        <div class="sub">Kelola biaya produksi</div>
        <p>Halaman ini berisi fitur untuk mengelola biaya produksi, termasuk total cost calculation, cost breakdown, dan profit analysis.</p>
    </div>
@endsection
