document.addEventListener('DOMContentLoaded', () => {
    const API_BASE = '/api/planning';

    /*
    |--------------------------------------------------------------------------
    | Demand Table — Load Data
    |--------------------------------------------------------------------------
    */
    async function loadDemands(page = 1, params = {}) {
        const search = document.getElementById('searchInput')?.value || '';
        const status = document.getElementById('filterStatus')?.value || '';
        const priority = document.getElementById('filterPriority')?.value || '';

        const query = new URLSearchParams({ page, per_page: 15 });
        if (search) query.set('search', search);
        if (status) query.set('status', status);
        if (priority) query.set('priority', priority);

        try {
            const res = await fetch(`${API_BASE}/demands?${query}`);
            const json = await res.json();
            if (json.success) {
                renderDemandTable(json.data.data);
                updateStats(json.stats);
            }
        } catch (err) {
            console.error('Failed to load demands:', err);
        }
    }

    function renderDemandTable(demands) {
        const tbody = document.getElementById('demandTableBody');
        if (!tbody) return;

        if (!demands.length) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" style="text-align:center;color:var(--text-2);padding:40px 0;">
                        Belum ada data demand.
                    </td>
                </tr>`;
            return;
        }

        const prioMap = { low: ['Rendah', 'low'], normal: ['Normal', 'medium'], high: ['Tinggi', 'high'], urgent: ['Mendesak', 'high'] };
        const statusMap = {
            draft: ['Draft', 'draft'], confirmed: ['Confirmed', 'published'],
            planned: ['Planned', 'approved'], fulfilled: ['Fulfilled', 'done'], cancelled: ['Cancelled', 'delayed']
        };

        tbody.innerHTML = demands.map(d => {
            const [prioLabel, prioClass] = prioMap[d.priority] || [d.priority, 'medium'];
            const [statusLabel, statusClass] = statusMap[d.status] || [d.status, 'draft'];
            const totalQty = d.lines?.reduce((s, l) => s + parseFloat(l.quantity), 0) || 0;
            const fulfilled = d.lines?.reduce((s, l) => s + parseFloat(l.fulfilled_quantity), 0) || 0;

            let actions = '';
            if (d.status === 'draft') {
                actions = `
                    <button class="btn-ghost" style="padding:4px 8px;font-size:11px;" onclick="submitDemand(${d.id})">Submit</button>
                    <button class="btn-ghost" style="padding:4px 8px;font-size:11px;color:var(--red);" onclick="deleteDemand(${d.id})">Hapus</button>`;
            } else if (d.status === 'confirmed') {
                actions = `<button class="btn-ghost" style="padding:4px 8px;font-size:11px;color:var(--red);" onclick="cancelDemand(${d.id})">Cancel</button>`;
            }

            return `
                <tr data-demand-id="${d.id}">
                    <td><strong>${d.document_number}</strong><small>${d.source_type}</small></td>
                    <td>${d.plant?.name || '—'}</td>
                    <td class="num">${formatNum(totalQty)}</td>
                    <td class="num">${formatNum(fulfilled)}</td>
                    <td>${formatDate(d.demand_date)}</td>
                    <td>${formatDate(d.required_date)}</td>
                    <td><span class="prio ${prioClass}">${prioLabel}</span></td>
                    <td><span class="status ${statusClass}">${statusLabel}</span></td>
                    <td>${actions}</td>
                </tr>`;
        }).join('');
    }

    function updateStats(stats) {
        if (!stats) return;
        setText('statTotalDemand', formatNum(stats.total_demand));
        setText('statTotalFulfilled', formatNum(stats.total_fulfilled));
        setText('statDemandCount', stats.demand_count);
        setText('statDraftDelta', stats.draft_count + ' draft');
        const rate = stats.total_demand > 0
            ? ((stats.total_fulfilled / stats.total_demand) * 100).toFixed(1)
            : '0.0';
        setText('statAccuracy', rate + '%');
    }

    /*
    |--------------------------------------------------------------------------
    | Search & Filters
    |--------------------------------------------------------------------------
    */
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => loadDemands(), 300);
        });
    }

    ['filterStatus', 'filterPriority'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('change', () => loadDemands());
    });

    /*
    |--------------------------------------------------------------------------
    | Create Demand — Modal
    |--------------------------------------------------------------------------
    */
    const modal = document.getElementById('demandModal');
    const btnCreate = document.getElementById('btnCreateDemand');
    const btnClose = document.getElementById('closeModal');
    const btnCancel = document.getElementById('cancelModal');
    const form = document.getElementById('demandForm');
    const addLineBtn = document.getElementById('addLine');
    const linesContainer = document.getElementById('linesContainer');

    let lineIndex = 1;

    if (btnCreate) btnCreate.addEventListener('click', () => modal.style.display = 'flex');
    if (btnClose) btnClose.addEventListener('click', () => modal.style.display = 'none');
    if (btnCancel) btnCancel.addEventListener('click', () => modal.style.display = 'none');

    if (addLineBtn) {
        addLineBtn.addEventListener('click', () => {
            const html = `
                <div class="demand-line" style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:8px;margin-bottom:8px;align-items:end;">
                    <div>
                        <label style="font-size:10px;color:var(--text-2);">Product ID</label>
                        <input type="number" name="lines[${lineIndex}][product_id]" required
                            style="width:100%;padding:6px;border:1px solid var(--divider);border-radius:6px;font-size:11px;">
                    </div>
                    <div>
                        <label style="font-size:10px;color:var(--text-2);">UOM ID</label>
                        <input type="number" name="lines[${lineIndex}][uom_id]" required
                            style="width:100%;padding:6px;border:1px solid var(--divider);border-radius:6px;font-size:11px;">
                    </div>
                    <div>
                        <label style="font-size:10px;color:var(--text-2);">Quantity</label>
                        <input type="number" name="lines[${lineIndex}][quantity]" step="0.0001" min="0.0001" required
                            style="width:100%;padding:6px;border:1px solid var(--divider);border-radius:6px;font-size:11px;">
                    </div>
                    <button type="button" class="removeLine btn-ghost" style="padding:4px 8px;font-size:11px;color:var(--red);">&times;</button>
                </div>`;
            linesContainer.insertAdjacentHTML('beforeend', html);
            lineIndex++;
        });
    }

    if (linesContainer) {
        linesContainer.addEventListener('click', e => {
            if (e.target.classList.contains('removeLine')) {
                e.target.closest('.demand-line').remove();
            }
        });
    }

    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);

            // Collect lines by reading name attributes from actual inputs
            const lines = [];
            const lineEls = form.querySelectorAll('.demand-line');
            lineEls.forEach(lineEl => {
                const pidInput = lineEl.querySelector('input[name$="[product_id]"]');
                const uidInput = lineEl.querySelector('input[name$="[uom_id]"]');
                const qtyInput = lineEl.querySelector('input[name$="[quantity]"]');
                const pid = pidInput?.value;
                const uid = uidInput?.value;
                const qty = qtyInput?.value;
                if (pid && uid && qty) {
                    lines.push({
                        product_id: parseInt(pid),
                        uom_id: parseInt(uid),
                        quantity: parseFloat(qty),
                    });
                }
            });

            const data = {
                company_id: parseInt(formData.get('company_id')),
                plant_id: parseInt(formData.get('plant_id')),
                source_type: formData.get('source_type'),
                demand_date: formData.get('demand_date'),
                required_date: formData.get('required_date'),
                priority: formData.get('priority'),
                notes: formData.get('notes') || null,
                lines: lines,
            };

            if (!data.lines.length) {
                alert('Tambahkan minimal 1 demand line.');
                return;
            }

            try {
                const res = await fetch(`${API_BASE}/demands`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(data),
                });
                const json = await res.json();
                if (json.success) {
                    modal.style.display = 'none';
                    form.reset();
                    loadDemands();
                } else {
                    const msg = json.message || Object.values(json.errors || {}).flat().join('\n');
                    alert(msg);
                }
            } catch (err) {
                console.error('Create demand failed:', err);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Submit / Cancel / Delete Demand
    |--------------------------------------------------------------------------
    */
    window.submitDemand = async function (id) {
        if (!confirm('Submit demand ini?')) return;
        try {
            const res = await fetch(`${API_BASE}/demands/${id}/submit`, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
            });
            const json = await res.json();
            if (json.success) loadDemands();
            else alert(json.message || 'Gagal submit demand.');
        } catch (err) {
            console.error('Submit failed:', err);
        }
    };

    window.cancelDemand = async function (id) {
        if (!confirm('Batalkan demand ini?')) return;
        try {
            const res = await fetch(`${API_BASE}/demands/${id}/cancel`, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
            });
            const json = await res.json();
            if (json.success) loadDemands();
            else alert(json.message || 'Gagal cancel demand.');
        } catch (err) {
            console.error('Cancel failed:', err);
        }
    };

    window.deleteDemand = async function (id) {
        if (!confirm('Hapus demand ini?')) return;
        try {
            const res = await fetch(`${API_BASE}/demands/${id}`, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json' },
            });
            const json = await res.json();
            if (json.success) loadDemands();
            else alert(json.message || 'Gagal hapus demand.');
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

    // Initial load
    loadDemands();
});
