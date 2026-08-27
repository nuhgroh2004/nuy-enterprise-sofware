@extends('MRP&Production.components.layout', ['title' => 'Labor Cost'])

@section('content')
    <div class="content-header">
        <div>
            <h1>Labor Cost</h1>
            <div class="sub">Biaya tenaga kerja</div>
        </div>
        <button class="btn-primary">+ Tambah Baru</button>
    </div>
    <div class="panel">
        <h3>Fitur Labor Cost</h3>
        <div class="sub">Kelola biaya tenaga kerja</div>
        <p>Halaman ini berisi fitur untuk mengelola biaya tenaga kerja, termasuk hourly rate, overtime calculation, dan efficiency analysis.</p>
    </div>
@endsection
