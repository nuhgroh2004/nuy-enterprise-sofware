@extends('MRP&Production.components.layout', ['title' => 'Products'])
@vite([
    'resources/css/MRP&Production/Products/Products.css',
    'resources/js/MRP&Production/Products/Products.js'
])
@section('content')
<div class="content-header">
    <div>
        <h1>Products</h1>
        <div class="sub">Master data produk — source of truth untuk seluruh sistem ERP</div>
    </div>
    <div class="header-actions">
        <button class="btn-ghost" id="btnExport" disabled>Ekspor</button>
        <button class="btn-primary" id="btnCreateProduct">+ Buat Product</button>
    </div>
</div>

{{-- STATISTICS --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="top">
            <span class="icon-badge orange">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 8l-9-5-9 5 9 5 9-5z"/>
                    <path d="M3 8v8l9 5 9-5V8"/>
                </svg>
            </span>
        </div>
        <div class="value" id="statTotal">{{ number_format($totalProducts) }}</div>
        <div class="label">Total Products</div>
    </div>
    <div class="stat-card">
        <div class="top">
            <span class="icon-badge green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                    <path d="M22 4L12 14.01l-3-3"/>
                </svg>
            </span>
        </div>
        <div class="value" id="statActive">{{ number_format($activeCount) }}</div>
        <div class="label">Active</div>
    </div>
    <div class="stat-card">
        <div class="top">
            <span class="icon-badge teal">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 3v18h18"/>
                    <path d="M7 14l4-4 3 3 5-6"/>
                </svg>
            </span>
        </div>
        <div class="value" id="statManufacturable">{{ number_format($manufacturableCount) }}</div>
        <div class="label">Manufacturable</div>
    </div>
    <div class="stat-card">
        <div class="top">
            <span class="icon-badge red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M15 9l-6 6M9 9l6 6"/>
                </svg>
            </span>
        </div>
        <div class="value" id="statInactive">{{ number_format($inactiveCount) }}</div>
        <div class="label">Inactive</div>
    </div>
</div>

{{-- PRODUCT TABLE --}}
<div class="panel">
    <div class="panel-heading">
        <div>
            <h3>Product List</h3>
            <div class="sub">Daftar seluruh produk, status, dan informasi master data</div>
        </div>
        <div class="table-actions">
            <button class="btn-ghost" id="btnImport" disabled>Import</button>
        </div>
    </div>

    <div class="filter-row">
        <div class="search-input" id="searchContainer">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="7"/>
                <path d="M21 21l-4.3-4.3"/>
            </svg>
            <input type="text"
                id="searchInput"
                placeholder="Cari code, SKU, barcode, atau nama..."
                style="border:none;background:transparent;outline:none;width:100%;font-size:12px;color:var(--text-1);">
        </div>
        <select id="filterType">
            <option value="">Semua Tipe</option>
        </select>
        <select id="filterCategory">
            <option value="">Semua Kategori</option>
        </select>
        <select id="filterStatus">
            <option value="">Semua Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="discontinued">Discontinued</option>
        </select>
        <select id="filterManufacturable">
            <option value="">Manufacturable</option>
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>SKU</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Category</th>
                    <th>Base UOM</th>
                    <th>Flags</th>
                    <th>Status</th>
                    <th>Updated</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="productTableBody">
                <tr>
                    <td colspan="10">
                        <div class="loading-state">Memuat data produk...</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="paginationContainer" style="display:flex;justify-content:center;margin-top:14px;"></div>
</div>

{{-- CREATE / EDIT MODAL --}}
<div id="productModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="modalTitle">Buat Product Baru</h3>
            <button class="modal-close" id="closeModal">&times;</button>
        </div>
        <form id="productForm">
            @csrf
            <input type="hidden" name="id" id="formProductId">

            <div class="form-section-title">Informasi Dasar</div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Company ID <span style="color:var(--red);">*</span></label>
                    <input type="number" name="company_id" id="formCompanyId" required>
                </div>
                <div class="form-group">
                    <label>Code <span style="color:var(--red);">*</span></label>
                    <input type="text" name="code" id="formCode" required maxlength="50" placeholder="PRD-001">
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Name <span style="color:var(--red);">*</span></label>
                    <input type="text" name="name" id="formName" required maxlength="255" placeholder="Product name">
                </div>
                <div class="form-group">
                    <label>SKU</label>
                    <input type="text" name="sku" id="formSku" maxlength="100" placeholder="Stock Keeping Unit">
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Barcode</label>
                    <input type="text" name="barcode" id="formBarcode" maxlength="100" placeholder="Barcode">
                </div>
                <div class="form-group">
                    <label>Status <span style="color:var(--red);">*</span></label>
                    <select name="status" id="formStatus" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="discontinued">Discontinued</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" id="formDescription" rows="2" maxlength="2000" placeholder="Deskripsi produk..."></textarea>
            </div>

            <div class="form-section-title">Klasifikasi</div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Product Type <span style="color:var(--red);">*</span></label>
                    <select name="product_type_id" id="formProductType" required>
                        <option value="">Pilih tipe...</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="product_category_id" id="formProductCategory">
                        <option value="">Pilih kategori...</option>
                    </select>
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Base UOM <span style="color:var(--red);">*</span></label>
                    <select name="uom_id" id="formUom" required>
                        <option value="">Pilih UOM...</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Lead Time (days)</label>
                    <input type="number" name="lead_time_days" id="formLeadTime" min="0" placeholder="0">
                </div>
            </div>

            <div class="form-section-title">Flags</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:4px 16px;">
                <div class="form-checkbox">
                    <input type="checkbox" name="is_purchasable" id="formPurchasable" value="1">
                    <label for="formPurchasable">Purchasable</label>
                </div>
                <div class="form-checkbox">
                    <input type="checkbox" name="is_sellable" id="formSellable" value="1">
                    <label for="formSellable">Sellable</label>
                </div>
                <div class="form-checkbox">
                    <input type="checkbox" name="is_manufacturable" id="formManufacturable" value="1">
                    <label for="formManufacturable">Manufacturable</label>
                </div>
                <div class="form-checkbox">
                    <input type="checkbox" name="is_stockable" id="formStockable" value="1" checked>
                    <label for="formStockable">Stockable</label>
                </div>
                <div class="form-checkbox">
                    <input type="checkbox" name="is_batch_tracked" id="formBatchTracked" value="1">
                    <label for="formBatchTracked">Batch Tracked</label>
                </div>
                <div class="form-checkbox">
                    <input type="checkbox" name="is_serial_tracked" id="formSerialTracked" value="1">
                    <label for="formSerialTracked">Serial Tracked</label>
                </div>
                <div class="form-checkbox">
                    <input type="checkbox" name="is_expiry_tracked" id="formExpiryTracked" value="1">
                    <label for="formExpiryTracked">Expiry Tracked</label>
                </div>
            </div>

            <div class="form-section-title">Cost & Stock Thresholds</div>
            <div class="form-grid cols-3">
                <div class="form-group">
                    <label>Standard Cost</label>
                    <input type="number" name="standard_cost" id="formStandardCost" step="0.0001" min="0">
                </div>
                <div class="form-group">
                    <label>Average Cost</label>
                    <input type="number" name="average_cost" id="formAverageCost" step="0.0001" min="0">
                </div>
                <div class="form-group">
                    <label>Last Purchase Cost</label>
                    <input type="number" name="last_purchase_cost" id="formLastPurchaseCost" step="0.0001" min="0">
                </div>
            </div>
            <div class="form-grid cols-3">
                <div class="form-group">
                    <label>Min Stock</label>
                    <input type="number" name="min_stock" id="formMinStock" step="0.0001" min="0">
                </div>
                <div class="form-group">
                    <label>Max Stock</label>
                    <input type="number" name="max_stock" id="formMaxStock" step="0.0001" min="0">
                </div>
                <div class="form-group">
                    <label>Safety Stock</label>
                    <input type="number" name="safety_stock" id="formSafetyStock" step="0.0001" min="0">
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Reorder Point</label>
                    <input type="number" name="reorder_point" id="formReorderPoint" step="0.0001" min="0">
                </div>
            </div>

            <div class="form-actions">
                <button type="button" id="cancelModal" class="btn-ghost">Batal</button>
                <button type="submit" class="btn-primary" id="btnSubmitForm">Simpan Product</button>
            </div>
        </form>
    </div>
</div>

@endsection
