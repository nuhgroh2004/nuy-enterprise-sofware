@extends('MRP&Production.components.layout', ['title' => 'BOM Detail'])
@vite([
    'resources/css/MRP&Production/BOM/BOM.css',
    'resources/js/MRP&Production/BOM/BomDetail.js'
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
            <h1 id="bomName">{{ $bom->name ?? $bom->code }}</h1>
            <div class="sub">
                <span id="bomCode">{{ $bom->code }}</span>
                &middot; <span id="bomProduct">{{ $bom->product->code ?? '—' }} — {{ $bom->product->name ?? '' }}</span>
                &middot; <span class="status {{ $bom->is_active ? 'active' : 'inactive' }}">{{ $bom->is_active ? 'Active' : 'Inactive' }}</span>
            </div>
        </div>
    </div>
    <div class="detail-actions">
        <button class="btn-ghost" id="btnDuplicate">Duplikat</button>
        @if($bom->is_active)
            <button class="btn-ghost" id="btnEdit" onclick="window.location.href='{{ route('mrp.bill-of-materials.edit', $bom) }}'">Edit</button>
            <button class="btn-ghost" id="btnArchive" style="color:var(--red);">Archive</button>
        @else
            <button class="btn-ghost" id="btnRestore" style="color:var(--green);">Restore</button>
        @endif
    </div>
</div>

{{-- TABS --}}
<div class="tabs">
    <button class="tab active" data-tab="overview">Overview</button>
    <button class="tab" data-tab="components">Components <span id="componentCount" style="font-size:10px;opacity:0.6;"></span></button>
    <button class="tab" data-tab="versions">Versions <span id="versionCount" style="font-size:10px;opacity:0.6;"></span></button>
    <button class="tab" data-tab="where-used">Where Used</button>
    <button class="tab" data-tab="history">History</button>
</div>

{{-- TAB CONTENT: OVERVIEW --}}
<div class="tab-content active" id="tab-overview">
    <div class="detail-grid">
        <div>
            <div class="detail-section">
                <h4>Informasi BOM</h4>
                <div class="detail-field">
                    <span class="label">BOM Code</span>
                    <span class="value">{{ $bom->code }}</span>
                </div>
                <div class="detail-field">
                    <span class="label">BOM Name</span>
                    <span class="value">{{ $bom->name ?? '—' }}</span>
                </div>
                <div class="detail-field">
                    <span class="label">Product</span>
                    <span class="value">{{ $bom->product->code ?? '—' }} — {{ $bom->product->name ?? '' }}</span>
                </div>
                <div class="detail-field">
                    <span class="label">Plant</span>
                    <span class="value">{{ $bom->plant->name ?? '—' }}</span>
                </div>
                <div class="detail-field">
                    <span class="label">Description</span>
                    <span class="value">{{ $bom->description ?? '—' }}</span>
                </div>
            </div>

            <div class="detail-section">
                <h4>Company</h4>
                <div class="detail-field">
                    <span class="label">Company</span>
                    <span class="value">{{ $bom->company->name ?? '—' }}</span>
                </div>
                <div class="detail-field">
                    <span class="label">Production Process</span>
                    <span class="value">{{ $bom->productionProcess->name ?? '—' }}</span>
                </div>
            </div>
        </div>

        <div>
            <div class="detail-section">
                <h4>Active Version</h4>
                @if($bom->activeVersion)
                    <div class="detail-field">
                        <span class="label">Version</span>
                        <span class="value">{{ $bom->activeVersion->version }}</span>
                    </div>
                    <div class="detail-field">
                        <span class="label">Revision</span>
                        <span class="value">{{ $bom->activeVersion->revision ?? '—' }}</span>
                    </div>
                    <div class="detail-field">
                        <span class="label">Status</span>
                        <span class="value"><span class="status {{ $bom->activeVersion->approval_state }}">{{ ucfirst($bom->activeVersion->approval_state) }}</span></span>
                    </div>
                    <div class="detail-field">
                        <span class="label">Effective Date</span>
                        <span class="value">{{ $bom->activeVersion->effective_date?->format('d M Y') ?? '—' }}</span>
                    </div>
                    <div class="detail-field">
                        <span class="label">Expiry Date</span>
                        <span class="value">{{ $bom->activeVersion->expiry_date?->format('d M Y') ?? '—' }}</span>
                    </div>
                    <div class="detail-field">
                        <span class="label">Yield %</span>
                        <span class="value">{{ $bom->activeVersion->yield_percent ?? '100' }}%</span>
                    </div>
                    <div class="detail-field">
                        <span class="label">Output Qty</span>
                        <span class="value">{{ $bom->activeVersion->output_qty ?? '—' }} {{ $bom->activeVersion->outputUom?->symbol ?? '' }}</span>
                    </div>
                    <div class="detail-field">
                        <span class="label">Routing</span>
                        <span class="value">{{ $bom->activeVersion->routingVersion?->routingHeader?->code ?? '—' }}</span>
                    </div>
                @else
                    <div style="font-size:12.5px;color:var(--text-2);padding:12px 0;">Belum ada version yang aktif.</div>
                @endif
            </div>

            <div class="detail-section">
                <h4>Meta</h4>
                <div class="detail-field">
                    <span class="label">Created</span>
                    <span class="value">{{ $bom->created_at?->format('d M Y H:i') ?? '—' }}</span>
                </div>
                <div class="detail-field">
                    <span class="label">Updated</span>
                    <span class="value">{{ $bom->updated_at?->format('d M Y H:i') ?? '—' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TAB CONTENT: COMPONENTS --}}
<div class="tab-content" id="tab-components">
    <div class="panel-heading" style="margin-bottom:0;border-bottom:1px solid var(--divider);">
        <div>
            <h3>Components</h3>
            <div class="sub">Daftar material komponen dalam BOM ini</div>
        </div>
        <button class="btn-primary" id="btnAddComponent">+ Tambah Component</button>
    </div>

    @if($bom->activeVersion && $bom->activeVersion->components->count() > 0)
        <div class="table-wrapper">
            <table class="component-table">
                <thead>
                    <tr>
                        <th>Seq</th>
                        <th>Component</th>
                        <th>Qty</th>
                        <th>UOM</th>
                        <th>Scrap %</th>
                        <th>Yield %</th>
                        <th>Fixed</th>
                        <th>Critical</th>
                        <th>Optional</th>
                        <th>Substitutes</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="componentTableBody">
                    @foreach($bom->activeVersion->components->sortBy('sort_order') as $comp)
                        <tr data-component-id="{{ $comp->id }}">
                            <td>{{ $comp->operation_sequence ?? $comp->sort_order }}</td>
                            <td>
                                <span class="bom-code">{{ $comp->product?->code ?? '—' }}</span>
                                <div class="bom-product">{{ $comp->product?->name ?? '' }}</div>
                            </td>
                            <td>{{ number_format($comp->quantity, 6) }}</td>
                            <td>{{ $comp->uom?->symbol ?? '—' }}</td>
                            <td>{{ $comp->scrap_percentage }}%</td>
                            <td>{{ $comp->yield_percentage }}%</td>
                            <td>{{ $comp->is_fixed_quantity ? 'Yes' : 'No' }}</td>
                            <td>{{ $comp->is_critical ? 'Yes' : 'No' }}</td>
                            <td>{{ $comp->is_optional ? 'Yes' : 'No' }}</td>
                            <td>{{ $comp->substitutes->count() }}</td>
                            <td>
                                <div class="component-actions">
                                    <button onclick="editComponent({{ $comp->id }})" title="Edit">Edit</button>
                                    <button class="danger" onclick="removeComponent({{ $comp->id }})" title="Hapus">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <div class="title">Belum ada komponen</div>
            <div class="desc">Klik "+ Tambah Component" untuk menambahkan material ke BOM ini.</div>
        </div>
    @endif
</div>

{{-- TAB CONTENT: VERSIONS --}}
<div class="tab-content" id="tab-versions">
    <div class="panel-heading" style="margin-bottom:14px;">
        <div>
            <h3>Versions</h3>
            <div class="sub">Riwayat version dan revision BOM ini</div>
        </div>
        <button class="btn-primary" id="btnAddVersion">+ Buat Version Baru</button>
    </div>

    <div class="version-timeline" id="versionTimeline">
        @forelse($bom->versions->sortByDesc('id') as $version)
            <div class="version-item {{ $version->approval_state }} {{ $version->is_default ? 'active' : '' }}">
                <div class="version-header">
                    <span class="version-number">Version {{ $version->version }}</span>
                    @if($version->revision)
                        <span style="font-size:12px;color:var(--text-2);">— {{ $version->revision }}</span>
                    @endif
                    <span class="status {{ $version->approval_state }}">{{ ucfirst($version->approval_state) }}</span>
                    @if($version->is_default)
                        <span class="flag manufacturable">Primary</span>
                    @endif
                </div>
                <div class="version-meta">
                    Effective: {{ $version->effective_date?->format('d M Y') ?? '—' }}
                    @if($version->expiry_date)
                        &middot; Expiry: {{ $version->expiry_date->format('d M Y') }}
                    @endif
                    &middot; Components: {{ $version->components->count() }}
                    @if($version->submittedBy)
                        &middot; Submitted by: {{ $version->submittedBy->name }}
                    @endif
                    @if($version->approvedByUser)
                        &middot; Approved by: {{ $version->approvedByUser->name }}
                    @endif
                </div>
                <div style="margin-top:8px;display:flex;gap:6px;">
                    @if($version->isDraft())
                        <button class="btn-ghost" style="padding:4px 10px;font-size:11px;" onclick="submitVersion({{ $version->id }})">Submit</button>
                    @endif
                    @if($version->isPending())
                        <button class="btn-ghost" style="padding:4px 10px;font-size:11px;color:var(--green);" onclick="approveVersion({{ $version->id }})">Approve</button>
                    @endif
                    @if($version->isApproved())
                        <button class="btn-ghost" style="padding:4px 10px;font-size:11px;color:var(--red);" onclick="expireVersion({{ $version->id }})">Expire</button>
                    @endif
                    @if(!$version->is_default)
                        <button class="btn-ghost" style="padding:4px 10px;font-size:11px;" onclick="setPrimaryVersion({{ $version->id }})">Set Primary</button>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="title">Belum ada version</div>
                <div class="desc">Buat version pertama untuk BOM ini.</div>
            </div>
        @endforelse
    </div>
</div>

{{-- TAB CONTENT: WHERE USED --}}
<div class="tab-content" id="tab-where-used">
    <div class="panel-heading" style="margin-bottom:14px;">
        <div>
            <h3>Where Used</h3>
            <div class="sub">Produk lain yang menggunakan BOM ini sebagai komponen</div>
        </div>
    </div>
    <div id="whereUsedContent">
        <div class="empty-state">
            <div class="title">Where Used</div>
            <div class="desc">Data where-used akan ditampilkan di sini.</div>
        </div>
    </div>
</div>

{{-- TAB CONTENT: HISTORY --}}
<div class="tab-content" id="tab-history">
    <div class="panel-heading" style="margin-bottom:14px;">
        <div>
            <h3>History</h3>
            <div class="sub">Audit trail perubahan data BOM</div>
        </div>
    </div>
    <div id="historyList">
        <div class="empty-state">
            <div class="title">Loading history...</div>
            <div class="desc">Memuat data audit trail...</div>
        </div>
    </div>
</div>

{{-- ADD COMPONENT MODAL --}}
<div id="componentModal" class="modal-overlay">
    <div class="modal-box" style="width:680px;">
        <div class="modal-header">
            <h3 id="componentModalTitle">Tambah Component</h3>
            <button class="modal-close" id="closeComponentModal">&times;</button>
        </div>
        <div class="modal-body">
        <form id="componentForm">
            @csrf
            <input type="hidden" name="component_id" id="componentId">

            <div class="form-section-title">Material</div>
            <div class="form-group">
                <label>Component Product <span style="color:var(--red);">*</span></label>
                <div class="command-selector" id="compProductSelector">
                    <input type="text" id="compProductSearch" placeholder="Cari product berdasarkan code, name, atau SKU..." autocomplete="off">
                    <input type="hidden" name="product_id" id="compProductId">
                    <div class="dropdown" id="compProductDropdown"></div>
                </div>
            </div>

            <div class="form-section-title">Quantity & UOM</div>
            <div class="form-grid cols-3">
                <div class="form-group">
                    <label>Quantity <span style="color:var(--red);">*</span></label>
                    <input type="number" name="quantity" id="compQuantity" step="0.000001" min="0.000001" required placeholder="0">
                </div>
                <div class="form-group">
                    <label>UOM <span style="color:var(--red);">*</span></label>
                    <select name="uom_id" id="compUom" required>
                        <option value="">Pilih UOM...</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Yield %</label>
                    <input type="number" name="yield_percentage" id="compYield" step="0.01" min="0" max="100" value="100" placeholder="100">
                </div>
            </div>

            <div class="form-section-title">Options</div>
            <div class="form-grid cols-3">
                <div class="form-group">
                    <label>Scrap %</label>
                    <input type="number" name="scrap_percentage" id="compScrap" step="0.01" min="0" max="100" value="0" placeholder="0">
                </div>
                <div class="form-group">
                    <label>Operation Sequence</label>
                    <input type="number" name="operation_sequence" id="compOpSeq" min="1" placeholder="Opsional">
                </div>
                <div class="form-group">
                    <label>Substitute Policy</label>
                    <select name="substitute_policy" id="compSubPolicy">
                        <option value="manual">Manual</option>
                        <option value="automatic">Automatic</option>
                        <option value="recommendation">Recommendation</option>
                    </select>
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Alternative Group</label>
                    <input type="text" name="alternative_group" id="compAltGroup" maxlength="50" placeholder="Opsional — group name untuk alternative components">
                </div>
            </div>

            <div class="form-section-title">Flags</div>
            <div class="form-checkbox-grid">
                <div class="form-checkbox">
                    <input type="checkbox" name="is_fixed_quantity" id="compFixed" value="1">
                    <label for="compFixed">Fixed Quantity</label>
                </div>
                <div class="form-checkbox">
                    <input type="checkbox" name="is_critical" id="compCritical" value="1">
                    <label for="compCritical">Critical Component</label>
                </div>
                <div class="form-checkbox">
                    <input type="checkbox" name="backflush" id="compBackflush" value="1">
                    <label for="compBackflush">Backflush</label>
                </div>
                <div class="form-checkbox">
                    <input type="checkbox" name="is_optional" id="compOptional" value="1">
                    <label for="compOptional">Optional Component</label>
                </div>
            </div>

            <div class="form-section-title">Notes</div>
            <div class="form-group">
                <textarea name="notes" id="compNotes" rows="2" maxlength="2000" placeholder="Catatan tambahan untuk komponen ini..."></textarea>
            </div>

            <div class="form-actions">
                <button type="button" id="cancelComponentModal" class="btn-ghost">Batal</button>
                <button type="submit" class="btn-primary" id="btnSubmitComponent">Simpan Component</button>
            </div>
        </form>
        </div>
    </div>
</div>

{{-- ADD VERSION MODAL --}}
<div id="versionModal" class="modal-overlay">
    <div class="modal-box" style="width:580px;">
        <div class="modal-header">
            <h3>Buat Version Baru</h3>
            <button class="modal-close" id="closeVersionModal">&times;</button>
        </div>
        <div class="modal-body">
        <form id="versionForm">
            @csrf

            <div class="form-section-title">Version Info</div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Version <span style="color:var(--red);">*</span></label>
                    <input type="text" name="version" id="newVersionNumber" required maxlength="50" placeholder="2.0">
                </div>
                <div class="form-group">
                    <label>Revision</label>
                    <input type="text" name="revision" id="newVersionRevision" maxlength="20" placeholder="Rev B">
                </div>
            </div>

            <div class="form-section-title">Effective Period</div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Effective Date <span style="color:var(--red);">*</span></label>
                    <input type="date" name="effective_date" id="newVersionEffective" required>
                </div>
                <div class="form-group">
                    <label>Expiry Date</label>
                    <input type="date" name="expiry_date" id="newVersionExpiry">
                </div>
            </div>

            <div class="form-section-title">Output & Routing</div>
            <div class="form-grid cols-3">
                <div class="form-group">
                    <label>Output Qty</label>
                    <input type="number" name="output_qty" step="0.000001" min="0" placeholder="1">
                </div>
                <div class="form-group">
                    <label>Output UOM</label>
                    <select name="output_uom_id" id="newVersionOutputUom">
                        <option value="">Pilih UOM...</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Yield %</label>
                    <input type="number" name="yield_percent" step="0.01" min="0" max="100" value="100" placeholder="100">
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Routing</label>
                    <select name="routing_version_id" id="newVersionRouting">
                        <option value="">Pilih Routing...</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <input type="text" name="notes" maxlength="2000" placeholder="Catatan untuk version ini...">
                </div>
            </div>

            <div class="form-actions">
                <button type="button" id="cancelVersionModal" class="btn-ghost">Batal</button>
                <button type="submit" class="btn-primary">Buat Version</button>
            </div>
        </form>
        </div>
    </div>
</div>

<script>
    const BOM_ID = {{ $bom->id }};
</script>
@endsection
