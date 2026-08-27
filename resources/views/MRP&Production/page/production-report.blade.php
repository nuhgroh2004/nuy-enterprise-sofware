@extends('MRP&Production.components.layout', ['title' => 'Production Report'])

@section('content')
    <div class="content-header">
        <div>
            <h1>Production Report</h1>
            <div class="sub">Laporan produksi</div>
        </div>
        <button class="btn-primary">Export</button>
    </div>
    <div class="panel">
        <h3>Fitur Production Report</h3>
        <div class="sub">Kelola laporan produksi</div>
        <p>Halaman ini berisi fitur untuk mengelola laporan produksi, termasuk daily report, weekly summary, dan monthly analysis.</p>
    </div>
@endsection
