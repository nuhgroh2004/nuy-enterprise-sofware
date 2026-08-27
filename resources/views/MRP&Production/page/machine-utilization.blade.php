@extends('MRP&Production.components.layout', ['title' => 'Machine Utilization'])

@section('content')
    <div class="content-header">
        <div>
            <h1>Machine Utilization</h1>
            <div class="sub">Utilisasi mesin</div>
        </div>
        <button class="btn-primary">Export</button>
    </div>
    <div class="panel">
        <h3>Fitur Machine Utilization</h3>
        <div class="sub">Kelola utilisasi mesin</div>
        <p>Halaman ini berisi fitur untuk mengelola utilisasi mesin, termasuk utilization rate, downtime tracking, dan capacity analysis.</p>
    </div>
@endsection
