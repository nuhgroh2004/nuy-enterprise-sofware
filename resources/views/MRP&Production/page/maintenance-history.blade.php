@extends('MRP&Production.components.layout', ['title' => 'Maintenance History'])

@section('content')
    <div class="content-header">
        <div>
            <h1>Maintenance History</h1>
            <div class="sub">Riwayat pemeliharaan</div>
        </div>
        <button class="btn-primary">+ Tambah Baru</button>
    </div>
    <div class="panel">
        <h3>Fitur Maintenance History</h3>
        <div class="sub">Kelola riwayat pemeliharaan</div>
        <p>Halaman ini berisi fitur untuk mengelola riwayat pemeliharaan, termasuk history perbaikan, cost analysis, dan performance report.</p>
    </div>
@endsection
