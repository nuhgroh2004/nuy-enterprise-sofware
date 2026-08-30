@extends('MRP&Production.components.layout', ['title' => 'Master Production Schedule'])
@vite([
    'resources/css/MRP&Production/Planning/MasterProductionSchedule.css',
    'resources/js/MRP&Production/Planning/MasterProductionSchedule.js'
])
@section('content')
<div class="content-header">
    <div>
        <h1 id="pageTitle">Production Planning</h1>
        <div class="sub" id="pageSub">
            Rencana produksi aktif — {{ now()->format('F Y') }}
        </div>
    </div>
    <div class="header-actions">
        <button class="btn-ghost">Ekspor</button>
        <button class="btn-primary" id="btnCreateSchedule">+ Buat Jadwal</button>
    </div>
</div>

{{-- STATISTICS --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="top">
            <span class="icon-badge orange">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="16" rx="2"/>
                    <path d="M3 9h18"/>
                </svg>
            </span>
            <span class="delta up" id="statActiveDelta">—</span>
        </div>
        <div class="value" id="statActiveSchedules">{{ $activeCount }} Jadwal</div>
        <div class="label">Jadwal Produksi Aktif</div>
    </div>
    <div class="stat-card">
        <div class="top">
            <span class="icon-badge blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 8l-9-5-9 5 9 5 9-5z"/>
                    <path d="M3 8v8l9 5 9-5V8"/>
                </svg>
            </span>
            <span class="delta flat" id="statPlannedDelta">{{ number_format($totalPlanned) }} unit</span>
        </div>
        <div class="value" id="statTotalPlanned">{{ number_format($totalPlanned) }} unit</div>
        <div class="label">Total Planned Quantity</div>
    </div>
    <div class="stat-card">
        <div class="top">
            <span class="icon-badge green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19h16M8 19V9m4 10V5m4 14v-7"/>
                </svg>
            </span>
            <span class="delta up" id="statUtilDelta">—</span>
        </div>
        <div class="value" id="statScheduleCount">{{ $scheduleCount }}</div>
        <div class="label">Total Schedules</div>
    </div>
    <div class="stat-card">
        <div class="top">
            <span class="icon-badge red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 9v4m0 4h.01M10.3 3.86L1.8 18a2 2 0 001.7 3h17a2 2 0 001.7-3L13.7 3.86a2 2 0 00-3.4 0z"/>
                </svg>
            </span>
            <span class="delta down" id="statDelayedDelta">—</span>
        </div>
        <div class="value" id="statDelayedCount">0</div>
        <div class="label">Delayed / Overdue</div>
    </div>
</div>

{{-- SCHEDULE TABLE --}}
<div class="panel">
    <div class="panel-heading">
        <div>
            <h3>Jadwal Produksi</h3>
            <div class="sub">
                Product, kuantitas, tanggal, dan prioritas produksi
            </div>
        </div>
        <div class="table-actions">
            <button class="btn-ghost">Riwayat</button>
            <button class="btn-primary" id="btnNewSchedule">+ Buat Jadwal</button>
        </div>
    </div>

    <div class="filter-row">
        <div class="search-input">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="7"/>
                <path d="M21 21l-4.3-4.3"/>
            </svg>
            <input type="text" id="mpsSearchInput" placeholder="Cari produk..."
                style="border:none;background:transparent;outline:none;width:100%;font-size:12px;color:var(--text-1);">
        </div>
        <select id="mpsFilterStatus">
            <option value="">Semua Status</option>
            <option value="draft">Draft</option>
            <option value="confirmed">Confirmed</option>
            <option value="frozen">Frozen</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Dokumen</th>
                    <th>Plant</th>
                    <th>Plan Date</th>
                    <th>Period</th>
                    <th>Total Lines</th>
                    <th>Total Qty</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="mpsTableBody">
                @forelse($schedules as $schedule)
                <tr data-mps-id="{{ $schedule->id }}">
                    <td>
                        <strong>{{ $schedule->document_number }}</strong>
                    </td>
                    <td>{{ $schedule->plant->name ?? '—' }}</td>
                    <td>{{ $schedule->plan_date->format('d M Y') }}</td>
                    <td>{{ $schedule->from_date->format('d M') }} — {{ $schedule->to_date->format('d M Y') }}</td>
                    <td class="num">{{ $schedule->lines->count() }}</td>
                    <td class="num">{{ number_format($schedule->lines->sum('planned_quantity'), 0, ',', '.') }}</td>
                    <td>
                        @php
                            $statusClass = match($schedule->status) {
                                'draft' => 'draft',
                                'confirmed' => 'ongoing',
                                'frozen' => 'scheduled',
                                'cancelled' => 'delayed',
                                default => 'draft',
                            };
                        @endphp
                        <span class="status {{ $statusClass }}">{{ ucfirst($schedule->status) }}</span>
                    </td>
                    <td>
                        @if($schedule->status === 'draft')
                            <button class="btn-ghost" style="padding:4px 8px;font-size:11px;"
                                onclick="submitMps({{ $schedule->id }})">Submit</button>
                            <button class="btn-ghost" style="padding:4px 8px;font-size:11px;color:var(--red);"
                                onclick="deleteMps({{ $schedule->id }})">Hapus</button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;color:var(--text-2);padding:40px 0;">
                        Belum ada jadwal produksi. Klik "+ Buat Jadwal" untuk memulai.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($schedules->hasPages())
    <div style="display:flex;justify-content:center;margin-top:14px;">
        {{ $schedules->links() }}
    </div>
    @endif
</div>

{{-- FEATURES --}}
<div class="panel">
    <h3>Fitur Master Production Schedule</h3>
    <div class="sub">
        Akses cepat ke seluruh fitur penjadwalan produksi
    </div>
    <div class="feature-grid">
        <div class="feature-tile">
            <span class="sq orange-gradient">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="16" rx="2"/>
                    <path d="M3 9h18"/>
                </svg>
            </span>
            <div class="t">Production Planning</div>
            <div class="d">Susun rencana produksi berdasarkan demand dan kapasitas.</div>
        </div>
        <div class="feature-tile">
            <span class="sq yellow-gradient">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="5" width="18" height="16" rx="2"/>
                    <path d="M8 3v4M16 3v4M3 10h18"/>
                </svg>
            </span>
            <div class="t">Production Calendar</div>
            <div class="d">Lihat sebaran jadwal produksi dalam tampilan kalender.</div>
        </div>
        <div class="feature-tile">
            <span class="sq blue-gradient">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19h16M8 19V9m4 10V5m4 14v-7"/>
                </svg>
            </span>
            <div class="t">Capacity Planning</div>
            <div class="d">Pantau dan atur kapasitas work center agar tidak overload.</div>
        </div>
        <div class="feature-tile">
            <span class="sq purple-gradient">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 21v-7m8 4V9m8 5V3"/>
                    <circle cx="4" cy="12" r="2"/>
                    <circle cx="12" cy="9" r="2"/>
                    <circle cx="20" cy="14" r="2"/>
                </svg>
            </span>
            <div class="t">Schedule Adjustment</div>
            <div class="d">Geser atau ubah jadwal produksi saat ada perubahan mendadak.</div>
        </div>
        <div class="feature-tile">
            <span class="sq red-gradient">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51z"/>
                </svg>
            </span>
            <div class="t">Priority</div>
            <div class="d">Tentukan urutan prioritas pengerjaan antar jadwal produksi.</div>
        </div>
    </div>
</div>

{{-- MPS CREATE MODAL --}}
<div id="mpsModal" style="display:none;position:fixed;inset:0;z-index:1000;background:rgba(0,0,0,.4);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
    <div style="background:var(--card-bg);border-radius:16px;padding:24px;width:600px;max-height:80vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <h3 style="margin:0;">Buat Jadwal Produksi</h3>
            <button id="closeMpsModal" style="background:none;border:none;font-size:20px;cursor:pointer;color:var(--text-2);">&times;</button>
        </div>
        <form id="mpsForm">
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
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label style="font-size:11px;color:var(--text-2);display:block;margin-bottom:4px;">Plan Date</label>
                    <input type="date" name="plan_date" required
                        style="width:100%;padding:8px;border:1px solid var(--divider);border-radius:8px;font-size:12px;">
                </div>
                <div>
                    <label style="font-size:11px;color:var(--text-2);display:block;margin-bottom:4px;">From Date</label>
                    <input type="date" name="from_date" required
                        style="width:100%;padding:8px;border:1px solid var(--divider);border-radius:8px;font-size:12px;">
                </div>
                <div>
                    <label style="font-size:11px;color:var(--text-2);display:block;margin-bottom:4px;">To Date</label>
                    <input type="date" name="to_date" required
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
                    <label style="font-size:12px;font-weight:600;">MPS Lines</label>
                    <button type="button" id="addMpsLine" class="btn-ghost" style="padding:4px 10px;font-size:11px;">+ Tambah Line</button>
                </div>
                <div id="mpsLinesContainer">
                    <div class="mps-line" style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr auto;gap:8px;margin-bottom:8px;align-items:end;">
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
                            <label style="font-size:10px;color:var(--text-2);">Planned Qty</label>
                            <input type="number" name="lines[0][planned_quantity]" step="0.0001" min="0.0001" required
                                style="width:100%;padding:6px;border:1px solid var(--divider);border-radius:6px;font-size:11px;">
                        </div>
                        <div>
                            <label style="font-size:10px;color:var(--text-2);">Planned Date</label>
                            <input type="date" name="lines[0][planned_date]" required
                                style="width:100%;padding:6px;border:1px solid var(--divider);border-radius:6px;font-size:11px;">
                        </div>
                        <button type="button" class="removeMpsLine btn-ghost" style="padding:4px 8px;font-size:11px;color:var(--red);">&times;</button>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:8px;justify-content:flex-end;">
                <button type="button" id="cancelMpsModal" class="btn-ghost">Batal</button>
                <button type="submit" class="btn-primary">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>
@endsection
