@extends('MRP&Production.components.layout', ['title' => 'Bill of Materials'])
@vite([
    'resources/css/MRP&Production/BOM/BOM.css',
    'resources/js/MRP&Production/BOM/BOM.js'
])
@section('content')
<div class="content-header">
    <div>
        <h1>Bill of Materials</h1>
        <div class="sub">Definisi material yang diperlukan untuk menghasilkan produk</div>
    </div>
    <div class="header-actions">
        <button class="btn-primary" onclick="window.location.href='{{ route('mrp.bill-of-materials.create') }}'">+ Buat BOM</button>
    </div>
</div>

{{-- STATISTICS --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="top">
            <span class="icon-badge orange">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <path d="M14 2v6h6M12 18v-6M9 15h6"/>
                </svg>
            </span>
        </div>
        <div class="value" id="statTotal">{{ number_format($totalBoms) }}</div>
        <div class="label">Total BOM</div>
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
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <path d="M14 2v6h6"/>
                </svg>
            </span>
        </div>
        <div class="value" id="statDraft">{{ number_format($draftCount) }}</div>
        <div class="label">Draft</div>
    </div>
    <div class="stat-card">
        <div class="top">
            <span class="icon-badge blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 12l2 2 4-4"/>
                    <circle cx="12" cy="12" r="10"/>
                </svg>
            </span>
        </div>
        <div class="value" id="statApproved">{{ number_format($approvedCount) }}</div>
        <div class="label">Approved</div>
    </div>
</div>

{{-- BOM TABLE --}}
<div class="panel">
    <div class="panel-heading">
        <div>
            <h3>BOM List</h3>
            <div class="sub">Daftar seluruh Bill of Materials beserta status dan komponen</div>
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
                placeholder="Cari BOM code, name, atau product..."
                style="border:none;background:transparent;outline:none;width:100%;font-size:12px;color:var(--text-1);">
        </div>
        <select id="filterStatus">
            <option value="">Semua Status</option>
            <option value="draft">Draft</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
        </select>
        <select id="filterActive">
            <option value="">Semua</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>BOM Code</th>
                    <th>Product</th>
                    <th>Plant</th>
                    <th>Version</th>
                    <th>Revision</th>
                    <th>Status</th>
                    <th>Effective Date</th>
                    <th>Components</th>
                    <th>Updated</th>
                </tr>
            </thead>
            <tbody id="bomTableBody">
                <tr>
                    <td colspan="9">
                        <div class="loading-state">Memuat data BOM...</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="paginationContainer" style="display:flex;justify-content:center;margin-top:14px;"></div>
</div>
@endsection
