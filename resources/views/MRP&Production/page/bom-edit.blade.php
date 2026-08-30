@extends('MRP&Production.components.layout', ['title' => 'Edit BOM'])
@vite([
    'resources/css/MRP&Production/BOM/BOM.css',
    'resources/js/MRP&Production/BOM/BomEditor.js'
])
@section('content')
<div class="detail-header">
    <div class="detail-header-left">
        <button class="back-btn" onclick="window.location.href='/MRP/bill-of-materials/{{ $bom->id }}'" title="Kembali ke detail BOM">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M15 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <div class="detail-title">
            <h1>Edit BOM — {{ $bom->code }}</h1>
            <div class="sub">{{ $bom->product->code ?? '' }} — {{ $bom->product->name ?? '' }}</div>
        </div>
    </div>
</div>

<div class="panel" style="padding:20px;">
    <form id="bomEditForm">
        @csrf
        @method('PUT')

        <div class="form-section-title">Informasi BOM</div>
        <div class="form-grid">
            <div class="form-group">
                <label>Plant</label>
                <select name="plant_id" id="formPlantId">
                    <option value="">Pilih Plant...</option>
                </select>
            </div>
            <div class="form-group">
                <label>BOM Code <span style="color:var(--red);">*</span></label>
                <input type="text" name="code" id="formCode" required maxlength="50" value="{{ $bom->code }}">
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label>BOM Name</label>
                <input type="text" name="name" id="formName" maxlength="255" value="{{ $bom->name }}">
            </div>
            <div class="form-group">
                <label>Production Process</label>
                <select name="production_process_id" id="formProductionProcess">
                    <option value="">Pilih Process...</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" id="formDescription" rows="2" maxlength="2000">{{ $bom->description }}</textarea>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label>Active</label>
                <div class="form-checkbox" style="margin-top:6px;">
                    <input type="checkbox" name="is_active" id="formActive" value="1" {{ $bom->is_active ? 'checked' : '' }}>
                    <label for="formActive">BOM Active</label>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="button" class="btn-ghost" onclick="window.location.href='/MRP/bill-of-materials/{{ $bom->id }}'">Batal</button>
            <button type="submit" class="btn-primary" id="btnSubmit">Simpan Perubahan</button>
        </div>
    </form>
</div>

<script>
    const BOM_ID = {{ $bom->id }};
    const BOM_DATA = @json($bom);
</script>
@endsection
