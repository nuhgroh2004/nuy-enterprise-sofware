@extends('MRP&Production.components.layout', ['title' => 'Numbering'])

@section('content')
    <div class="content-header">
        <div>
            <h1>Numbering</h1>
            <div class="sub">Penomoran dokumen</div>
        </div>
        <button class="btn-primary">+ Tambah Baru</button>
    </div>
    <div class="panel">
        <h3>Fitur Numbering</h3>
        <div class="sub">Kelola penomoran</div>
        <p>Halaman ini berisi fitur untuk mengelola penomoran dokumen, termasuk auto-numbering, prefix configuration, dan sequence management.</p>
    </div>
@endsection
