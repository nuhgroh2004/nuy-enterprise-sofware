@extends('MRP&Production.components.layout', ['title' => 'Demand Planning'])
@vite([
    'resources/css/MRP&Production/Planning/DemandPlanning.css',
    'resources/js/MRP&Production/Planning/DemandPlanning.js'
])
@section('content')
<div class="content-header">
    <div>
        <h1>Demand Planning</h1>
        <div class="sub">
            Perencanaan dan analisis kebutuhan produk — {{ now()->format('F Y') }}
        </div>
    </div>
    <div class="header-actions">
        <button class="btn-ghost" id="btnExport">Ekspor</button>
        <button class="btn-primary" id="btnCreateDemand">+ Buat Demand Plan</button>
    </div>
</div>

{{-- STATISTICS --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="top">
            <span class="icon-badge orange">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M4 19V5"/>
                    <path d="M4 19h16"/>
                    <path d="M7 15l4-5 3 3 5-7"/>
                </svg>
            </span>
            <span class="delta up" id="statDemandDelta">—</span>
        </div>
        <div class="value" id="statTotalDemand">{{ number_format($totalDemand) }}</div>
        <div class="label">Total Forecast Demand</div>
    </div>
    <div class="stat-card">
        <div class="top">
            <span class="icon-badge blue">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M3 3v18h18"/>
                    <path d="M7 16l4-5 3 2 5-6"/>
                </svg>
            </span>
            <span class="delta up" id="statFulfilledDelta">—</span>
        </div>
        <div class="value" id="statTotalFulfilled">{{ number_format($totalFulfilled) }}</div>
        <div class="label">Fulfilled Demand</div>
    </div>
    <div class="stat-card">
        <div class="top">
            <span class="icon-badge green">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M4 19h16"/>
                    <path d="M7 16l4-6 3 3 4-8"/>
                </svg>
            </span>
            <span class="delta up" id="statAccuracyDelta">—</span>
        </div>
        <div class="value" id="statAccuracy">
            {{ $totalDemand > 0 ? number_format(($totalFulfilled / $totalDemand) * 100, 1) : '0.0' }}%
        </div>
        <div class="label">Fulfillment Rate</div>
    </div>
    <div class="stat-card">
        <div class="top">
            <span class="icon-badge red">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M12 3v18"/>
                    <path d="M17 7c0-2-2-4-5-4S7 5 7 7s2 3 5 4 5 2 5 4-2 4-5 4-5-2-5-4"/>
                </svg>
            </span>
            <span class="delta down" id="statDraftDelta">{{ $draftCount }} draft</span>
        </div>
        <div class="value" id="statDemandCount">{{ $demandCount }}</div>
        <div class="label">Total Demand Documents</div>
    </div>
</div>

{{-- DEMAND TABLE --}}
<div class="panel">
    <div class="panel-heading">
        <div>
            <h3>Demand Planning</h3>
            <div class="sub">
                Daftar demand, status, dan sumber data
            </div>
        </div>
        <div class="table-actions">
            <button class="btn-ghost" id="btnImportDemand">Import Demand</button>
            <button class="btn-primary" id="btnManualPlanning">+ Manual Planning</button>
        </div>
    </div>

    <div class="filter-row">
        <div class="search-input" id="searchContainer">
            <svg viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="7"/>
                <path d="M21 21l-4.3-4.3"/>
            </svg>
            <input type="text"
                id="searchInput"
                placeholder="Cari produk..."
                style="border:none;background:transparent;outline:none;width:100%;font-size:12px;color:var(--text-1);">
        </div>
        <select id="filterPriority">
            <option value="">Semua Prioritas</option>
            <option value="low">Rendah</option>
            <option value="normal">Normal</option>
            <option value="high">Tinggi</option>
            <option value="urgent">Mendesak</option>
        </select>
        <select id="filterStatus">
            <option value="">Semua Status</option>
            <option value="draft">Draft</option>
            <option value="confirmed">Confirmed</option>
            <option value="planned">Planned</option>
            <option value="fulfilled">Fulfilled</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Dokumen</th>
                    <th>Plant</th>
                    <th>Total Qty</th>
                    <th>Fulfilled</th>
                    <th>Tanggal Demand</th>
                    <th>Required Date</th>
                    <th>Prioritas</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="demandTableBody">
                @forelse($demands as $demand)
                <tr data-demand-id="{{ $demand->id }}">
                    <td>
                        <strong>{{ $demand->document_number }}</strong>
                        <small>{{ $demand->source_type }}</small>
                    </td>
                    <td>{{ $demand->plant->name ?? '—' }}</td>
                    <td class="num">{{ number_format($demand->lines->sum('quantity'), 0, ',', '.') }}</td>
                    <td class="num">{{ number_format($demand->lines->sum('fulfilled_quantity'), 0, ',', '.') }}</td>
                    <td>{{ $demand->demand_date->format('d M Y') }}</td>
                    <td>{{ $demand->required_date->format('d M Y') }}</td>
                    <td>
                        @php
                            $prioClass = match($demand->priority) {
                                'low' => 'low',
                                'normal' => 'medium',
                                'high' => 'high',
                                'urgent' => 'high',
                                default => 'medium',
                            };
                            $prioLabel = match($demand->priority) {
                                'low' => 'Rendah',
                                'normal' => 'Normal',
                                'high' => 'Tinggi',
                                'urgent' => 'Mendesak',
                                default => ucfirst($demand->priority),
                            };
                        @endphp
                        <span class="prio {{ $prioClass }}">{{ $prioLabel }}</span>
                    </td>
                    <td>
                        @php
                            $statusClass = match($demand->status) {
                                'draft' => 'draft',
                                'confirmed' => 'published',
                                'planned' => 'approved',
                                'fulfilled' => 'done',
                                'cancelled' => 'delayed',
                                default => 'draft',
                            };
                            $statusLabel = match($demand->status) {
                                'draft' => 'Draft',
                                'confirmed' => 'Confirmed',
                                'planned' => 'Planned',
                                'fulfilled' => 'Fulfilled',
                                'cancelled' => 'Cancelled',
                                default => ucfirst($demand->status),
                            };
                        @endphp
                        <span class="status {{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td>
                        @if($demand->status === 'draft')
                            <button class="btn-ghost" style="padding:4px 8px;font-size:11px;"
                                onclick="submitDemand({{ $demand->id }})">Submit</button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;color:var(--text-2);padding:40px 0;">
                        Belum ada data demand. Klik "+ Buat Demand Plan" untuk memulai.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($demands->hasPages())
    <div style="display:flex;justify-content:center;margin-top:14px;">
        {{ $demands->links() }}
    </div>
    @endif
</div>

{{-- FEATURES --}}
<div class="panel">
    <h3>Fitur Demand Planning</h3>
    <div class="sub">
        Akses cepat ke seluruh fitur perencanaan demand
    </div>
    <div class="feature-grid">
        <div class="feature-tile" id="featureForecast">
            <span class="sq orange-gradient">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M4 19V5"/>
                    <path d="M4 19h16"/>
                    <path d="M7 15l4-5 3 3 5-7"/>
                </svg>
            </span>
            <div class="t">Forecast Demand</div>
            <div class="d">Buat dan kelola prediksi kebutuhan produk berdasarkan data demand.</div>
        </div>
        <div class="feature-tile" id="featureHistory">
            <span class="sq blue-gradient">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M3 3v18h18"/>
                    <path d="M7 16l4-5 3 2 5-6"/>
                </svg>
            </span>
            <div class="t">Demand History</div>
            <div class="d">Lihat histori demand produk untuk membantu proses perencanaan.</div>
        </div>
        <div class="feature-tile" id="featureManual">
            <span class="sq purple-gradient">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14"/>
                    <path d="M5 12h14"/>
                </svg>
            </span>
            <div class="t">Manual Demand Planning</div>
            <div class="d">Atur kebutuhan produk secara manual berdasarkan keputusan planner.</div>
        </div>
        <div class="feature-tile" id="featureImport">
            <span class="sq green-gradient">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M12 3v12"/>
                    <path d="M7 10l5 5 5-5"/>
                    <path d="M4 21h16"/>
                </svg>
            </span>
            <div class="t">Import Demand</div>
            <div class="d">Import data demand dari file eksternal untuk mempercepat input.</div>
        </div>
        <div class="feature-tile" id="featureCompare">
            <span class="sq red-gradient">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M4 18l6-6 4 4 6-8"/>
                    <path d="M18 8h2v2"/>
                </svg>
            </span>
            <div class="t">Forecast Comparison</div>
            <div class="d">Bandingkan hasil forecast dengan actual demand dan histori.</div>
        </div>
    </div>
</div>

{{-- DEMAND MODAL --}}
<div id="demandModal" style="display:none;position:fixed;inset:0;z-index:1000;background:rgba(0,0,0,.4);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
    <div style="background:var(--card-bg);border-radius:16px;padding:24px;width:560px;max-height:80vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <h3 style="margin:0;">Buat Demand Plan</h3>
            <button id="closeModal" style="background:none;border:none;font-size:20px;cursor:pointer;color:var(--text-2);">&times;</button>
        </div>
        <form id="demandForm">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label style="font-size:11px;color:var(--text-2);display:block;margin-bottom:4px;">Company ID</label>
                    <input type="number" name="company_id" required
                        style="width:100%;padding:8px;border:1px solid var(--divider);border-radius:8px;font-size:12px;">
                </div>
                <div>
                    <label style="font-size:11px;color:var(--text-2);display:block;margin-bottom:4px;">Plant ID</label>
                    <input type="number" name="plant_id" required
                        style="width:100%;padding:8px;border:1px solid var(--divider);border-radius:8px;font-size:12px;">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label style="font-size:11px;color:var(--text-2);display:block;margin-bottom:4px;">Source Type</label>
                    <select name="source_type" required
                        style="width:100%;padding:8px;border:1px solid var(--divider);border-radius:8px;font-size:12px;">
                        <option value="manual">Manual</option>
                        <option value="sales_order">Sales Order</option>
                        <option value="forecast">Forecast</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label style="font-size:11px;color:var(--text-2);display:block;margin-bottom:4px;">Priority</label>
                    <select name="priority" required
                        style="width:100%;padding:8px;border:1px solid var(--divider);border-radius:8px;font-size:12px;">
                        <option value="low">Rendah</option>
                        <option value="normal" selected>Normal</option>
                        <option value="high">Tinggi</option>
                        <option value="urgent">Mendesak</option>
                    </select>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label style="font-size:11px;color:var(--text-2);display:block;margin-bottom:4px;">Demand Date</label>
                    <input type="date" name="demand_date" required
                        style="width:100%;padding:8px;border:1px solid var(--divider);border-radius:8px;font-size:12px;">
                </div>
                <div>
                    <label style="font-size:11px;color:var(--text-2);display:block;margin-bottom:4px;">Required Date</label>
                    <input type="date" name="required_date" required
                        style="width:100%;padding:8px;border:1px solid var(--divider);border-radius:8px;font-size:12px;">
                </div>
            </div>
            <div style="margin-bottom:12px;">
                <label style="font-size:11px;color:var(--text-2);display:block;margin-bottom:4px;">Notes</label>
                <textarea name="notes" rows="2"
                    style="width:100%;padding:8px;border:1px solid var(--divider);border-radius:8px;font-size:12px;resize:vertical;"></textarea>
            </div>

            <div style="margin-bottom:12px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <label style="font-size:12px;font-weight:600;">Demand Lines</label>
                    <button type="button" id="addLine" class="btn-ghost" style="padding:4px 10px;font-size:11px;">+ Tambah Line</button>
                </div>
                <div id="linesContainer">
                    <div class="demand-line" style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:8px;margin-bottom:8px;align-items:end;">
                        <div>
                            <label style="font-size:10px;color:var(--text-2);">Product ID</label>
                            <input type="number" name="lines[0][product_id]" required
                                style="width:100%;padding:6px;border:1px solid var(--divider);border-radius:6px;font-size:11px;">
                        </div>
                        <div>
                            <label style="font-size:10px;color:var(--text-2);">UOM ID</label>
                            <input type="number" name="lines[0][uom_id]" required
                                style="width:100%;padding:6px;border:1px solid var(--divider);border-radius:6px;font-size:11px;">
                        </div>
                        <div>
                            <label style="font-size:10px;color:var(--text-2);">Quantity</label>
                            <input type="number" name="lines[0][quantity]" step="0.0001" min="0.0001" required
                                style="width:100%;padding:6px;border:1px solid var(--divider);border-radius:6px;font-size:11px;">
                        </div>
                        <button type="button" class="removeLine btn-ghost" style="padding:4px 8px;font-size:11px;color:var(--red);">&times;</button>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:8px;justify-content:flex-end;">
                <button type="button" id="cancelModal" class="btn-ghost">Batal</button>
                <button type="submit" class="btn-primary">Simpan Demand</button>
            </div>
        </form>
    </div>
</div>

@endsection
