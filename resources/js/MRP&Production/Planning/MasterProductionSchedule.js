document.addEventListener('DOMContentLoaded', () => {
    const API_BASE = '/api/planning';

    /*
    |--------------------------------------------------------------------------
    | MPS Table — Load Data
    |--------------------------------------------------------------------------
    */
    async function loadMps(page = 1, params = {}) {
        const search = document.getElementById('mpsSearchInput')?.value || '';
        const status = document.getElementById('mpsFilterStatus')?.value || '';

        const query = new URLSearchParams({ page, per_page: 15 });
        if (search) query.set('search', search);
        if (status) query.set('status', status);

        try {
            const res = await fetch(`${API_BASE}/mps?${query}`);
            const json = await res.json();
            if (json.success) {
                renderMpsTable(json.data.data);
                updateStats(json.stats);
            }
        } catch (err) {
            console.error('Failed to load MPS:', err);
        }
    }

    function renderMpsTable(schedules) {
        const tbody = document.getElementById('mpsTableBody');
        if (!tbody) return;

        if (!schedules.length) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" style="text-align:center;color:var(--text-2);padding:40px 0;">
                        Belum ada jadwal produksi.
                    </td>
                </tr>`;
            return;
        }

        const statusMap = {
            draft: 'draft', confirmed: 'ongoing', frozen: 'scheduled', cancelled: 'delayed'
        };

        tbody.innerHTML = schedules.map(s => {
            const totalQty = s.lines?.reduce((sum, l) => sum + parseFloat(l.planned_quantity), 0) || 0;
            const sc = statusMap[s.status] || 'draft';
            let actions = '';
            if (s.status === 'draft') {
                actions = `
                    <button class="btn-ghost" style="padding:4px 8px;font-size:11px;" onclick="submitMps(${s.id})">Submit</button>
                    <button class="btn-ghost" style="padding:4px 8px;font-size:11px;color:var(--red);" onclick="deleteMps(${s.id})">Hapus</button>`;
            }
            return `
                <tr data-mps-id="${s.id}">
                    <td><strong>${s.document_number}</strong></td>
                    <td>${s.plant?.name || '—'}</td>
                    <td>${formatDate(s.plan_date)}</td>
                    <td>${formatDate(s.from_date)} — ${formatDate(s.to_date)}</td>
                    <td class="num">${s.lines?.length || 0}</td>
                    <td class="num">${formatNum(totalQty)}</td>
                    <td><span class="status ${sc}">${capitalize(s.status)}</span></td>
                    <td>${actions}</td>
                </tr>`;
        }).join('');
    }

    function updateStats(stats) {
        if (!stats) return;
        setText('statActiveSchedules', stats.active_count + ' Jadwal');
        setText('statTotalPlanned', formatNum(stats.total_planned) + ' unit');
        setText('statScheduleCount', stats.schedule_count);
        setText('statDelayedCount', stats.delayed_count);
        setText('statDelayedDelta', stats.delayed_count + ' jadwal');
    }

    /*
    |--------------------------------------------------------------------------
    | Search & Filters
    |--------------------------------------------------------------------------
    */
    let searchTimeout;
    const searchInput = document.getElementById('mpsSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => loadMps(), 300);
        });
    }

    const filterStatus = document.getElementById('mpsFilterStatus');
    if (filterStatus) {
        filterStatus.addEventListener('change', () => loadMps());
    }

    /*
    |--------------------------------------------------------------------------
    | Create MPS — Modal
    |--------------------------------------------------------------------------
    */
    const modal = document.getElementById('mpsModal');
    const btnCreate = document.getElementById('btnCreateSchedule');
    const btnNew = document.getElementById('btnNewSchedule');
    const btnClose = document.getElementById('closeMpsModal');
    const btnCancel = document.getElementById('cancelMpsModal');
    const form = document.getElementById('mpsForm');
    const addLineBtn = document.getElementById('addMpsLine');
    const linesContainer = document.getElementById('mpsLinesContainer');

    let mpsLineIndex = 1;

    function openModal() { modal.style.display = 'flex'; }
    function closeModal() { modal.style.display = 'none'; }

    if (btnCreate) btnCreate.addEventListener('click', openModal);
    if (btnNew) btnNew.addEventListener('click', openModal);
    if (btnClose) btnClose.addEventListener('click', closeModal);
    if (btnCancel) btnCancel.addEventListener('click', closeModal);

    if (addLineBtn) {
        addLineBtn.addEventListener('click', () => {
            const html = `
                <div class="mps-line" style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr auto;gap:8px;margin-bottom:8px;align-items:end;">
                    <div>
                        <label style="font-size:10px;color:var(--text-2);">Product ID</label>
                        <input type="number" name="lines[${mpsLineIndex}][product_id]" required
                            style="width:100%;padding:6px;border:1px solid var(--divider);border-radius:6px;font-size:11px;">
                    </div>
                    <div>
                        <label style="font-size:10px;color:var(--text-2);">UOM ID</label>
                        <input type="number" name="lines[${mpsLineIndex}][uom_id]" required
                            style="width:100%;padding:6px;border:1px solid var(--divider);border-radius:6px;font-size:11px;">
                    </div>
                    <div>
                        <label style="font-size:10px;color:var(--text-2);">Planned Qty</label>
                        <input type="number" name="lines[${mpsLineIndex}][planned_quantity]" step="0.0001" min="0.0001" required
                            style="width:100%;padding:6px;border:1px solid var(--divider);border-radius:6px;font-size:11px;">
                    </div>
                    <div>
                        <label style="font-size:10px;color:var(--text-2);">Planned Date</label>
                        <input type="date" name="lines[${mpsLineIndex}][planned_date]" required
                            style="width:100%;padding:6px;border:1px solid var(--divider);border-radius:6px;font-size:11px;">
                    </div>
                    <button type="button" class="removeMpsLine btn-ghost" style="padding:4px 8px;font-size:11px;color:var(--red);">&times;</button>
                </div>`;
            linesContainer.insertAdjacentHTML('beforeend', html);
            mpsLineIndex++;
        });
    }

    if (linesContainer) {
        linesContainer.addEventListener('click', e => {
            if (e.target.classList.contains('removeMpsLine')) {
                e.target.closest('.mps-line').remove();
            }
        });
    }

    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);

            const lines = [];
            const lineEls = form.querySelectorAll('.mps-line');
            lineEls.forEach(lineEl => {
                const pidInput = lineEl.querySelector('input[name$="[product_id]"]');
                const uidInput = lineEl.querySelector('input[name$="[uom_id]"]');
                const qtyInput = lineEl.querySelector('input[name$="[planned_quantity]"]');
                const dateInput = lineEl.querySelector('input[name$="[planned_date]"]');
                const pid = pidInput?.value;
                const uid = uidInput?.value;
                const qty = qtyInput?.value;
                const date = dateInput?.value;
                if (pid && uid && qty && date) {
                    lines.push({
                        product_id: parseInt(pid),
                        uom_id: parseInt(uid),
                        planned_quantity: parseFloat(qty),
                        planned_date: date,
                        demand_quantity: parseFloat(qty),
                    });
                }
            });

            const data = {
                company_id: parseInt(formData.get('company_id')),
                plant_id: parseInt(formData.get('plant_id')),
                plan_date: formData.get('plan_date'),
                from_date: formData.get('from_date'),
                to_date: formData.get('to_date'),
                notes: formData.get('notes') || null,
                lines: lines,
            };

            if (!data.lines.length) {
                alert('Tambahkan minimal 1 MPS line.');
                return;
            }

            try {
                const res = await fetch(`${API_BASE}/mps`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(data),
                });
                const json = await res.json();
                if (json.success) {
                    closeModal();
                    form.reset();
                    loadMps();
                } else {
                    const msg = json.message || Object.values(json.errors || {}).flat().join('\n');
                    alert(msg);
                }
            } catch (err) {
                console.error('Create MPS failed:', err);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Submit / Delete MPS
    |--------------------------------------------------------------------------
    */
    window.submitMps = async function (id) {
        if (!confirm('Submit jadwal produksi ini?')) return;
        try {
            const res = await fetch(`${API_BASE}/mps/${id}/submit`, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
            });
            const json = await res.json();
            if (json.success) loadMps();
            else alert(json.message || 'Gagal submit jadwal.');
        } catch (err) {
            console.error('Submit failed:', err);
        }
    };

    window.deleteMps = async function (id) {
        if (!confirm('Hapus jadwal produksi ini?')) return;
        try {
            const res = await fetch(`${API_BASE}/mps/${id}`, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json' },
            });
            const json = await res.json();
            if (json.success) loadMps();
            else alert(json.message || 'Gagal hapus jadwal.');
        } catch (err) {
            console.error('Delete failed:', err);
        }
    };

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    function formatNum(n) {
        return Number(n || 0).toLocaleString('id-ID');
    }

    function formatDate(d) {
        if (!d) return '—';
        return new Date(d).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    function setText(id, text) {
        const el = document.getElementById(id);
        if (el) el.textContent = text;
    }

    function capitalize(s) {
        return s ? s.charAt(0).toUpperCase() + s.slice(1) : '';
    }

    // Initial load
    loadMps();
});
