@extends('MRP&Production.components.layout', ['title' => 'Material Requirements'])

@vite([
    'resources/css/MRP&Production/Planning/MaterialRequirements.css',
    'resources/js/MRP&Production/Planning/MaterialRequirements.js'
])

@section('content')

<div class="content-header">
    <div>
        <h1>Material Requirements</h1>
        <div class="sub">
            Perencanaan kebutuhan material berdasarkan rencana produksi — {{ now()->format('F Y') }}
        </div>
    </div>

    <div class="header-actions">
        <button class="btn-ghost">Ekspor</button>
        <button class="btn-primary" id="btnCalculateMRP">+ Hitung Kebutuhan</button>
    </div>
</div>

{{-- STATISTICS --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="top">
            <span class="icon-badge orange">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 16V8l-9-5-9 5v8l9 5 9-5z"/>
                    <path d="M3 8l9 5 9-5"/>
                    <path d="M12 13v8"/>
                </svg>
            </span>
            <span class="delta up" id="statMaterialDelta">—</span>
        </div>
        <div class="value" id="statTotalRequired">{{ number_format($totalRequired) }}</div>
        <div class="label">Total Material Required</div>
    </div>

    <div class="stat-card">
        <div class="top">
            <span class="icon-badge green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12l4 4L19 6"/>
                    <rect x="3" y="3" width="18" height="18" rx="3"/>
                </svg>
            </span>
            <span class="delta up" id="statAvailableDelta">
                {{ $totalRequired > 0 ? number_format(($totalAvailable / $totalRequired) * 100, 1) : '0.0' }}%
            </span>
        </div>
        <div class="value" id="statTotalAvailable">{{ number_format($totalAvailable) }}</div>
        <div class="label">Material Available</div>
    </div>

    <div class="stat-card">
        <div class="top">
            <span class="icon-badge blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19h16"/>
                    <path d="M7 16V8"/>
                    <path d="M12 16V5"/>
                    <path d="M17 16v-4"/>
                </svg>
            </span>
            <span class="delta flat" id="statReservedDelta">—</span>
        </div>
        <div class="value" id="statReserved">—</div>
        <div class="label">Reserved Stock</div>
    </div>

    <div class="stat-card">
        <div class="top">
            <span class="icon-badge red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 9v4"/>
                    <path d="M12 17h.01"/>
                    <path d="M10.3 3.86L1.8 18a2 2 0 001.7 3h17a2 2 0 001.7-3L13.7 3.86a2 2 0 00-3.4 0z"/>
                </svg>
            </span>
            <span class="delta down" id="statShortageDelta">{{ $shortageCount }} material</span>
        </div>
        <div class="value" id="statTotalShortage">{{ number_format($totalShortage) }}</div>
        <div class="label">Total Shortage</div>
    </div>
</div>

{{-- MATERIAL REQUIREMENT TABLE --}}
<div class="panel">
    <div class="panel-heading">
        <div>
            <h3>Material Requirements</h3>
            <div class="sub">
                Kebutuhan material berdasarkan production plan
            </div>
        </div>
        <div class="table-actions">
            <button class="btn-ghost">Riwayat</button>
            <button class="btn-primary" id="btnProcurement">+ Procurement</button>
        </div>
    </div>

    <div class="filter-row">
        <div class="search-input">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="7"/>
                <path d="M21 21l-4.3-4.3"/>
            </svg>
            <input type="text" id="mrpSearchInput" placeholder="Cari material..."
                style="border:none;background:transparent;outline:none;width:100%;font-size:12px;color:var(--text-1);">
        </div>

        <select id="mrpFilterStatus">
            <option value="">Semua Status</option>
            <option value="draft">Draft</option>
            <option value="planned">Planned</option>
            <option value="ordered">Ordered</option>
            <option value="received">Received</option>
        </select>

        <select id="mrpFilterShortage">
            <option value="">Semua Ketersediaan</option>
            <option value="1">Hanya Shortage</option>
            <option value="0">Tidak Ada Shortage</option>
        </select>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Material</th>
                    <th>Required Qty</th>
                    <th>Available Stock</th>
                    <th>Safety Stock</th>
                    <th>Shortage Qty</th>
                    <th>Required Date</th>
                    <th>Lead Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="mrpTableBody">
                @forelse($requirements as $req)
                <tr data-mrp-id="{{ $req->id }}">
                    <td>
                        <strong>{{ $req->product->name ?? '—' }}</strong>
                        <small>{{ $req->product->code ?? '' }}</small>
                    </td>
                    <td class="num">{{ number_format($req->required_quantity, 0, ',', '.') }} {{ $req->uom->symbol ?? '' }}</td>
                    <td class="num">{{ number_format($req->available_quantity, 0, ',', '.') }}</td>
                    <td class="num">{{ number_format($req->safety_stock, 0, ',', '.') }}</td>
                    <td class="num {{ $req->shortage_quantity > 0 ? 'shortage' : '' }}">
                        {{ number_format($req->shortage_quantity, 0, ',', '.') }}
                    </td>
                    <td>{{ $req->required_date->format('d M Y') }}</td>
                    <td>{{ $req->lead_time_days }} hari</td>
                    <td>
                        @php
                            $statusClass = match($req->status) {
                                'draft' => 'safe',
                                'planned' => 'warning',
                                'ordered' => 'ongoing',
                                'received' => 'safe',
                                'cancelled' => 'shortage',
                                default => 'safe',
                            };
                        @endphp
                        <span class="status {{ $statusClass }}">{{ ucfirst($req->status) }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;color:var(--text-2);padding:40px 0;">
                        Belum ada data material requirement. Klik "+ Hitung Kebutuhan" untuk menghitung.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($requirements->hasPages())
    <div style="display:flex;justify-content:center;margin-top:14px;">
        {{ $requirements->links() }}
    </div>
    @endif
</div>

{{-- FEATURES --}}
<div class="panel">
    <h3>Fitur Material Requirements</h3>
    <div class="sub">
        Akses cepat ke seluruh fitur perencanaan kebutuhan material
    </div>

    <div class="feature-grid">
        <div class="feature-tile" id="featureCalcMRP">
            <span class="sq orange-gradient">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19h16"/>
                    <path d="M7 16V9"/>
                    <path d="M12 16V5"/>
                    <path d="M17 16v-4"/>
                </svg>
            </span>
            <div class="t">Material Requirement Calculation</div>
            <div class="d">Hitung kebutuhan material berdasarkan production plan dan quantity yang direncanakan.</div>
        </div>

        <div class="feature-tile" id="featureAvailability">
            <span class="sq green-gradient">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12l4 4L19 6"/>
                    <rect x="3" y="3" width="18" height="18" rx="3"/>
                </svg>
            </span>
            <div class="t">Material Availability</div>
            <div class="d">Periksa ketersediaan material dengan membandingkan kebutuhan dan stok.</div>
        </div>

        <div class="feature-tile" id="featureShortage">
            <span class="sq red-gradient">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 9v4"/>
                    <path d="M12 17h.01"/>
                    <path d="M10.3 3.86L1.8 18a2 2 0 001.7 3h17a2 2 0 001.7-3L13.7 3.86a2 2 0 00-3.4 0z"/>
                </svg>
            </span>
            <div class="t">Shortage Detection</div>
            <div class="d">Identifikasi material yang tidak mencukupi kebutuhan produksi.</div>
        </div>

        <div class="feature-tile" id="featureProcurement">
            <span class="sq blue-gradient">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 6h18"/>
                    <path d="M8 6V4h8v2"/>
                    <path d="M19 6l-1 15H6L5 6"/>
                </svg>
            </span>
            <div class="t">Suggested Procurement</div>
            <div class="d">Berikan rekomendasi material yang perlu dilakukan pengadaan.</div>
        </div>

        <div class="feature-tile" id="featureHistory">
            <span class="sq purple-gradient">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 12a9 9 0 1 0 3-6.7"/>
                    <path d="M3 4v6h6"/>
                </svg>
            </span>
            <div class="t">Requirement History</div>
            <div class="d">Lihat riwayat perhitungan kebutuhan material dan perubahan requirement.</div>
        </div>
    </div>
</div>

@endsection
