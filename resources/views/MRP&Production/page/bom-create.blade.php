@extends('MRP&Production.components.layout', ['title' => 'Create BOM'])
@vite([
    'resources/css/MRP&Production/BOM/BOM.css',
    'resources/js/MRP&Production/BOM/BomEditor.js'
])
@section('content')
<div class="detail-header">
    <div class="detail-header-left">
        <button class="back-btn" onclick="window.location.href='/MRP/bill-of-materials'" title="Kembali ke daftar BOM">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M15 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <div class="detail-title">
            <h1>Buat BOM Baru</h1>
            <div class="sub">Definisikan Bill of Materials untuk produk baru</div>
        </div>
    </div>
</div>

<div class="panel" style="padding:20px;">
    <form id="bomCreateForm">
        @csrf

        <div class="form-section-title">Informasi BOM</div>
        <div class="form-grid">
            <div class="form-group">
                <label>Company <span style="color:var(--red);">*</span></label>
                <select name="company_id" id="formCompanyId" required>
                    <option value="">Pilih Company...</option>
                </select>
            </div>
            <div class="form-group">
                <label>Plant</label>
                <select name="plant_id" id="formPlantId">
                    <option value="">Pilih Plant...</option>
                </select>
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label>BOM Code <span style="color:var(--red);">*</span></label>
                <input type="text" name="code" id="formCode" required maxlength="50" placeholder="BOM-001">
            </div>
            <div class="form-group">
                <label>BOM Name</label>
                <input type="text" name="name" id="formName" maxlength="255" placeholder="Nama BOM">
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label>Product <span style="color:var(--red);">*</span></label>
                <div class="command-selector" id="productSelector">
                    <input type="text" id="productSearch" placeholder="Cari product..." autocomplete="off">
                    <input type="hidden" name="product_id" id="formProductId">
                    <div class="dropdown" id="productDropdown"></div>
                </div>
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
            <textarea name="description" id="formDescription" rows="2" maxlength="2000" placeholder="Deskripsi BOM..."></textarea>
        </div>

        <div class="form-section-title">Versi Awal</div>
        <div class="form-grid">
            <div class="form-group">
                <label>Version <span style="color:var(--red);">*</span></label>
                <input type="text" name="version" id="formVersion" required maxlength="50" placeholder="1.0">
            </div>
            <div class="form-group">
                <label>Revision</label>
                <input type="text" name="revision" id="formRevision" maxlength="20" placeholder="Rev A">
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label>Effective Date <span style="color:var(--red);">*</span></label>
                <input type="date" name="effective_date" id="formEffectiveDate" required>
            </div>
            <div class="form-group">
                <label>Expiry Date</label>
                <input type="date" name="expiry_date" id="formExpiryDate">
            </div>
        </div>
        <div class="form-grid cols-3">
            <div class="form-group">
                <label>Output Quantity</label>
                <input type="number" name="output_qty" id="formOutputQty" step="0.000001" min="0" placeholder="1">
            </div>
            <div class="form-group">
                <label>Output UOM</label>
                <select name="output_uom_id" id="formOutputUom">
                    <option value="">Pilih UOM...</option>
                </select>
            </div>
            <div class="form-group">
                <label>Yield %</label>
                <input type="number" name="yield_percent" id="formYieldPercent" step="0.01" min="0" max="100" value="100">
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label>Routing</label>
                <select name="routing_version_id" id="formRoutingVersion">
                    <option value="">Pilih Routing...</option>
                </select>
            </div>
            <div class="form-group">
                <label>Notes</label>
                <input type="text" name="notes" id="formNotes" maxlength="2000" placeholder="Catatan...">
            </div>
        </div>

        <div class="form-actions">
            <button type="button" class="btn-ghost" onclick="window.location.href='/MRP/bill-of-materials'">Batal</button>
            <button type="submit" class="btn-primary" id="btnSubmit">Buat BOM</button>
        </div>
    </form>
</div>
@endsection
