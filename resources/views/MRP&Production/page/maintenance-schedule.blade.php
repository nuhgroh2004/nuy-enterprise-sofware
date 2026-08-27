@extends('MRP&Production.components.layout', ['title' => 'Maintenance Schedule'])

@section('content')
    <div class="content-header">
        <div>
            <h1>Maintenance Schedule</h1>
            <div class="sub">Jadwal pemeliharaan</div>
        </div>
        <button class="btn-primary">+ Tambah Baru</button>
    </div>
    <div class="panel">
        <h3>Fitur Maintenance Schedule</h3>
        <div class="sub">Kelola jadwal pemeliharaan</div>
        <p>Halaman ini berisi fitur untuk mengelola jadwal pemeliharaan, termasuk preventive maintenance, scheduling, dan notification.</p>
    </div>
@endsection
