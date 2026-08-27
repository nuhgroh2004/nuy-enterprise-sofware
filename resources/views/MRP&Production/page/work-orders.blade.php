@extends('MRP&Production.components.layout', ['title' => 'Work Orders'])

@section('content')
    <div class="content-header">
        <div>
            <h1>Work Orders</h1>
            <div class="sub">Perintah kerja</div>
        </div>
        <button class="btn-primary">+ Tambah Baru</button>
    </div>
    <div class="panel">
        <h3>Fitur Work Orders</h3>
        <div class="sub">Kelola perintah kerja</div>
        <p>Halaman ini berisi fitur untuk mengelola perintah kerja, termasuk pembuatan work order, assign ke team, dan tracking progress.</p>
    </div>
@endsection
