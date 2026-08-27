@extends('MRP&Production.components.layout', ['title' => 'Production Schedule'])

@section('content')
    <div class="content-header">
        <div>
            <h1>Production Schedule</h1>
            <div class="sub">Jadwal produksi</div>
        </div>
        <button class="btn-primary">+ Tambah Baru</button>
    </div>
    <div class="panel">
        <h3>Fitur Production Schedule</h3>
        <div class="sub">Kelola jadwal produksi</div>
        <p>Halaman ini berisi fitur untuk mengelola jadwal produksi, termasuk penjadwalan harian, mingguan, dan monitoring timeline produksi.</p>
    </div>
@endsection
