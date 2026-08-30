@extends('MRP&Production.components.layout', ['title' => 'Product Detail'])
@vite([
    'resources/css/MRP&Production/Products/Products.css',
    'resources/js/MRP&Production/Products/ProductDetail.js'
])
@section('content')
<div class="detail-header">
    <div class="detail-header-left">
        <button class="back-btn" onclick="window.location.href='/MRP/products'" title="Kembali ke daftar produk">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M15 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <div class="detail-title">
            <h1 id="detailProductName">{{ $product->name }}</h1>
            <div class="sub">
                <span id="detailProductCode">{{ $product->code }}</span>
                @if($product->sku)
                    &middot; SKU: <span id="detailProductSku">{{ $product->sku }}</span>
                @endif
                &middot; <span class="status {{ $product->status }}" id="detailProductStatus">{{ ucfirst($product->status) }}</span>
            </div>
        </div>
    </div>
    <div class="detail-actions">
        <button class="btn-ghost" id="btnDuplicate">Duplikat</button>
        <button class="btn-ghost" id="btnEdit">Edit</button>
        @if($product->status === 'active')
            <button class="btn-ghost" id="btnArchive" style="color:var(--red);">Archive</button>
        @else
            <button class="btn-ghost" id="btnRestore" style="color:var(--green);">Restore</button>
        @endif
    </div>
</div>

{{-- TABS --}}
<div class="tabs">
    <button class="tab active" data-tab="overview">Overview</button>
    <button class="tab" data-tab="variants">Variants <span id="variantCount" style="font-size:10px;opacity:0.6;"></span></button>
    <button class="tab" data-tab="boms">BOMs <span id="bomCount" style="font-size:10px;opacity:0.6;"></span></button>
    <button class="tab" data-tab="routings">Routings <span id="routingCount" style="font-size:10px;opacity:0.6;"></span></button>
    <button class="tab" data-tab="inventory">Inventory</button>
    <button class="tab" data-tab="quality">Quality</button>
    <button class="tab" data-tab="costing">Costing</button>
    <button class="tab" data-tab="history">History</button>
</div>

{{-- TAB CONTENT: OVERVIEW --}}
<div class="tab-content active" id="tab-overview">
    <div class="detail-grid">
        <div>
            <div class="detail-section">
                <h4>Informasi Dasar</h4>
                <div class="detail-field">
                    <span class="label">Code</span>
                    <span class="value" id="ovCode">{{ $product->code }}</span>
                </div>
                <div class="detail-field">
                    <span class="label">SKU</span>
                    <span class="value" id="ovSku">{{ $product->sku ?? '—' }}</span>
                </div>
                <div class="detail-field">
                    <span class="label">Barcode</span>
                    <span class="value" id="ovBarcode">{{ $product->barcode ?? '—' }}</span>
                </div>
                <div class="detail-field">
                    <span class="label">Name</span>
                    <span class="value" id="ovName">{{ $product->name }}</span>
                </div>
                <div class="detail-field">
                    <span class="label">Description</span>
                    <span class="value" id="ovDescription">{{ $product->description ?? '—' }}</span>
                </div>
            </div>

            <div class="detail-section">
                <h4>Klasifikasi</h4>
                <div class="detail-field">
                    <span class="label">Product Type</span>
                    <span class="value" id="ovType">{{ $product->productType->name ?? '—' }}</span>
                </div>
                <div class="detail-field">
                    <span class="label">Category</span>
                    <span class="value" id="ovCategory">{{ $product->productCategory->name ?? '—' }}</span>
                </div>
                <div class="detail-field">
                    <span class="label">Base UOM</span>
                    <span class="value" id="ovUom">{{ $product->uom->name ?? '—' }} ({{ $product->uom->symbol ?? '' }})</span>
                </div>
                <div class="detail-field">
                    <span class="label">Status</span>
                    <span class="value"><span class="status {{ $product->status }}">{{ ucfirst($product->status) }}</span></span>
                </div>
            </div>
        </div>

        <div>
            <div class="detail-section">
                <h4>Flags</h4>
                <div class="flags" id="ovFlags" style="gap:8px;">
                    @if($product->is_purchasable)
                        <span class="flag purchasable" style="font-size:11px;padding:4px 8px;">Purchasable</span>
                    @endif
                    @if($product->is_sellable)
                        <span class="flag sellable" style="font-size:11px;padding:4px 8px;">Sellable</span>
                    @endif
                    @if($product->is_manufacturable)
                        <span class="flag manufacturable" style="font-size:11px;padding:4px 8px;">Manufacturable</span>
                    @endif
                    @if($product->is_stockable)
                        <span class="flag stockable" style="font-size:11px;padding:4px 8px;">Stockable</span>
                    @endif
                </div>
                <div style="margin-top:10px;">
                    <div class="detail-field">
                        <span class="label">Batch Tracked</span>
                        <span class="value">{{ $product->is_batch_tracked ? 'Yes' : 'No' }}</span>
                    </div>
                    <div class="detail-field">
                        <span class="label">Serial Tracked</span>
                        <span class="value">{{ $product->is_serial_tracked ? 'Yes' : 'No' }}</span>
                    </div>
                    <div class="detail-field">
                        <span class="label">Expiry Tracked</span>
                        <span class="value">{{ $product->is_expiry_tracked ? 'Yes' : 'No' }}</span>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <h4>Cost & Stock</h4>
                <div class="detail-field">
                    <span class="label">Standard Cost</span>
                    <span class="value num">{{ $product->standard_cost ? number_format($product->standard_cost, 4, ',', '.') : '—' }}</span>
                </div>
                <div class="detail-field">
                    <span class="label">Average Cost</span>
                    <span class="value num">{{ $product->average_cost ? number_format($product->average_cost, 4, ',', '.') : '—' }}</span>
                </div>
                <div class="detail-field">
                    <span class="label">Last Purchase Cost</span>
                    <span class="value num">{{ $product->last_purchase_cost ? number_format($product->last_purchase_cost, 4, ',', '.') : '—' }}</span>
                </div>
                <div class="detail-field">
                    <span class="label">Lead Time</span>
                    <span class="value">{{ $product->lead_time_days ? $product->lead_time_days . ' hari' : '—' }}</span>
                </div>
                <div class="detail-field">
                    <span class="label">Min Stock</span>
                    <span class="value num">{{ $product->min_stock ? number_format($product->min_stock, 4, ',', '.') : '—' }}</span>
                </div>
                <div class="detail-field">
                    <span class="label">Max Stock</span>
                    <span class="value num">{{ $product->max_stock ? number_format($product->max_stock, 4, ',', '.') : '—' }}</span>
                </div>
                <div class="detail-field">
                    <span class="label">Reorder Point</span>
                    <span class="value num">{{ $product->reorder_point ? number_format($product->reorder_point, 4, ',', '.') : '—' }}</span>
                </div>
                <div class="detail-field">
                    <span class="label">Safety Stock</span>
                    <span class="value num">{{ $product->safety_stock ? number_format($product->safety_stock, 4, ',', '.') : '—' }}</span>
                </div>
            </div>

            <div class="detail-section">
                <h4>Product UOMs</h4>
                <div id="productUomsList">
                    @forelse($product->productUoms as $pu)
                        <div class="detail-field">
                            <span class="label">{{ ucfirst($pu->usage_type) }}</span>
                            <span class="value">{{ $pu->uom->name ?? '—' }} ({{ $pu->uom->symbol ?? '' }}) — factor: {{ $pu->conversion_factor }}</span>
                        </div>
                    @empty
                        <div style="font-size:12px;color:var(--text-2);padding:8px 0;">Tidak ada UOM alternatif</div>
                    @endforelse
                </div>
            </div>

            <div class="detail-section">
                <h4>Meta</h4>
                <div class="detail-field">
                    <span class="label">Created</span>
                    <span class="value">{{ $product->created_at?->format('d M Y H:i') ?? '—' }}</span>
                </div>
                <div class="detail-field">
                    <span class="label">Updated</span>
                    <span class="value">{{ $product->updated_at?->format('d M Y H:i') ?? '—' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TAB CONTENT: VARIANTS --}}
<div class="tab-content" id="tab-variants">
    <div class="panel-heading" style="margin-bottom:14px;">
        <div>
            <h3>Product Variants</h3>
            <div class="sub">Variant dari produk ini dengan SKU dan barcode terpisah</div>
        </div>
        <button class="btn-primary" id="btnAddVariant">+ Tambah Variant</button>
    </div>
    <div class="variant-grid" id="variantGrid">
        @forelse($product->variants as $variant)
            <div class="variant-card">
                <div class="variant-card-header">
                    <span class="code">{{ $variant->code }}</span>
                    <span class="status {{ $variant->is_active ? 'active' : 'inactive' }}">{{ $variant->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
                <div class="name">{{ $variant->name ?? '—' }}</div>
                <div class="meta">
                    @if($variant->sku)<span>SKU: {{ $variant->sku }}</span>@endif
                    @if($variant->barcode)<span>Barcode: {{ $variant->barcode }}</span>@endif
                </div>
                @if($variant->attributes)
                    <div style="margin-top:6px;">
                        @foreach($variant->attributes as $key => $value)
                            <span style="font-size:10px;padding:2px 6px;background:rgba(0,0,0,0.05);border-radius:4px;margin-right:4px;">{{ $key }}: {{ $value }}</span>
                        @endforeach
                    </div>
                @endif
                <div class="variant-card-actions">
                    <button class="btn-ghost" style="padding:3px 8px;font-size:11px;" onclick="editVariant({{ $variant->id }})">Edit</button>
                    <button class="btn-ghost" style="padding:3px 8px;font-size:11px;color:var(--red);" onclick="deleteVariant({{ $variant->id }}, '{{ addslashes($variant->code) }}')">Hapus</button>
                </div>
            </div>
        @empty
            <div class="empty-state" style="grid-column:1/-1;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M21 8l-9-5-9 5 9 5 9-5z"/>
                    <path d="M3 8v8l9 5 9-5V8"/>
                </svg>
                <div class="title">Belum ada variant</div>
                <div class="desc">Klik "+ Tambah Variant" untuk membuat variant produk baru.</div>
            </div>
        @endforelse
    </div>
</div>

{{-- TAB CONTENT: BOMs --}}
<div class="tab-content" id="tab-boms">
    <div class="panel-heading" style="margin-bottom:14px;">
        <div>
            <h3>Bill of Materials</h3>
            <div class="sub">BOM yang menggunakan produk ini</div>
        </div>
    </div>
    <div id="bomList">
        @forelse($product->bomHeaders as $bom)
            <div class="detail-field">
                <span class="label">
                    <a href="/MRP/bill-of-materials" style="color:var(--blue);text-decoration:none;">{{ $bom->code }}</a>
                </span>
                <span class="value">{{ $bom->name }}</span>
            </div>
        @empty
            <div class="empty-state">
                <div class="title">Tidak ada BOM</div>
                <div class="desc">Produk ini belum memiliki Bill of Materials.</div>
            </div>
        @endforelse
    </div>
</div>

{{-- TAB CONTENT: ROUTINGS --}}
<div class="tab-content" id="tab-routings">
    <div class="panel-heading" style="margin-bottom:14px;">
        <div>
            <h3>Routings</h3>
            <div class="sub">Routing yang terkait dengan produk ini</div>
        </div>
    </div>
    <div id="routingList">
        @forelse($product->routingHeaders as $routing)
            <div class="detail-field">
                <span class="label">
                    <a href="/MRP/routing" style="color:var(--blue);text-decoration:none;">{{ $routing->code }}</a>
                </span>
                <span class="value">{{ $routing->name }}</span>
            </div>
        @empty
            <div class="empty-state">
                <div class="title">Tidak ada Routing</div>
                <div class="desc">Produk ini belum memiliki routing.</div>
            </div>
        @endforelse
    </div>
</div>

{{-- TAB CONTENT: INVENTORY --}}
<div class="tab-content" id="tab-inventory">
    <div class="panel-heading" style="margin-bottom:14px;">
        <div>
            <h3>Inventory</h3>
            <div class="sub">Stock balance dan stock movement produk ini</div>
        </div>
    </div>
    <div id="inventoryContent">
        <div class="empty-state">
            <div class="title">Data inventory</div>
            <div class="desc">Informasi stock balance dan stock movement akan ditampilkan di sini.</div>
        </div>
    </div>
</div>

{{-- TAB CONTENT: QUALITY --}}
<div class="tab-content" id="tab-quality">
    <div class="panel-heading" style="margin-bottom:14px;">
        <div>
            <h3>Quality</h3>
            <div class="sub">Quality inspection terkait produk ini</div>
        </div>
    </div>
    <div id="qualityContent">
        <div class="empty-state">
            <div class="title">Data quality</div>
            <div class="desc">Informasi quality inspection akan ditampilkan di sini.</div>
        </div>
    </div>
</div>

{{-- TAB CONTENT: COSTING --}}
<div class="tab-content" id="tab-costing">
    <div class="panel-heading" style="margin-bottom:14px;">
        <div>
            <h3>Costing</h3>
            <div class="sub">Biaya produksi dan cost breakdown</div>
        </div>
    </div>
    <div id="costingContent">
        <div class="empty-state">
            <div class="title">Data costing</div>
            <div class="desc">Informasi biaya produksi dan cost breakdown akan ditampilkan di sini.</div>
        </div>
    </div>
</div>

{{-- TAB CONTENT: HISTORY --}}
<div class="tab-content" id="tab-history">
    <div class="panel-heading" style="margin-bottom:14px;">
        <div>
            <h3>History</h3>
            <div class="sub">Audit trail perubahan data produk</div>
        </div>
    </div>
    <div id="historyList">
        @forelse($product->auditLogs->take(50) as $log)
            <div class="detail-field" style="align-items:flex-start;">
                <span class="label" style="min-width:120px;">
                    <strong>{{ ucfirst($log->event) }}</strong><br>
                    <span style="font-size:11px;color:var(--text-2);">{{ $log->created_at?->format('d M Y H:i') }}</span>
                    @if($log->user)<br><span style="font-size:11px;">{{ $log->user->name }}</span>@endif
                </span>
                <span class="value" style="text-align:left;">
                    @if($log->event === 'created')
                        Product dibuat
                    @elseif($log->event === 'updated')
                        @if($log->old_values && is_array($log->old_values))
                            @foreach($log->old_values as $field => $change)
                                @if(is_array($change) && isset($change['old'], $change['new']))
                                    <div style="font-size:11px;margin-bottom:2px;">
                                        <strong>{{ $field }}</strong>: {{ $change['old'] ?? '—' }} → {{ $change['new'] ?? '—' }}
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    @elseif($log->event === 'archived')
                        Product diarsipkan
                    @elseif($log->event === 'restored')
                        Product direstore
                    @endif
                </span>
            </div>
        @empty
            <div class="empty-state">
                <div class="title">Tidak ada history</div>
                <div class="desc">Belum ada perubahan data yang tercatat.</div>
            </div>
        @endforelse
    </div>
</div>

{{-- VARIANT MODAL --}}
<div id="variantModal" class="modal-overlay">
    <div class="modal-box" style="width:480px;">
        <div class="modal-header">
            <h3 id="variantModalTitle">Tambah Variant</h3>
            <button class="modal-close" id="closeVariantModal">&times;</button>
        </div>
        <form id="variantForm">
            @csrf
            <input type="hidden" name="variant_id" id="variantId">
            <div class="form-grid">
                <div class="form-group">
                    <label>Code <span style="color:var(--red);">*</span></label>
                    <input type="text" name="code" id="variantCode" required maxlength="50">
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" id="variantName" maxlength="255">
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>SKU</label>
                    <input type="text" name="sku" id="variantSku" maxlength="100">
                </div>
                <div class="form-group">
                    <label>Barcode</label>
                    <input type="text" name="barcode" id="variantBarcode" maxlength="100">
                </div>
            </div>
            <div class="form-group">
                <label>Additional Cost</label>
                <input type="number" name="additional_cost" id="variantCost" step="0.0001" min="0">
            </div>
            <div class="form-checkbox">
                <input type="checkbox" name="is_active" id="variantActive" value="1" checked>
                <label for="variantActive">Active</label>
            </div>
            <div class="form-actions">
                <button type="button" id="cancelVariantModal" class="btn-ghost">Batal</button>
                <button type="submit" class="btn-primary" id="btnSubmitVariant">Simpan</button>
            </div>
        </form>
    </div>
</div>

@endsection
