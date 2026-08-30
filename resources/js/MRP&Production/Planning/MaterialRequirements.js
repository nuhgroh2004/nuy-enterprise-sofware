document.addEventListener('DOMContentLoaded', () => {
    const API_BASE = '/api/planning';

    /*
    |--------------------------------------------------------------------------
    | MRP Table — Load Data
    |--------------------------------------------------------------------------
    */
    async function loadMrp(page = 1, params = {}) {
        const search = document.getElementById('mrpSearchInput')?.value || '';
        const status = document.getElementById('mrpFilterStatus')?.value || '';
        const hasShortage = document.getElementById('mrpFilterShortage')?.value || '';

        const query = new URLSearchParams({ page, per_page: 15 });
        if (search) query.set('search', search);
        if (status) query.set('status', status);
        if (hasShortage !== '') query.set('has_shortage', hasShortage);

        try {
            const res = await fetch(`${API_BASE}/mrp?${query}`);
            const json = await res.json();
            if (json.success) {
                renderMrpTable(json.data.data);
                updateStats(json.stats);
            }
        } catch (err) {
            console.error('Failed to load MRP:', err);
        }
    }

    function renderMrpTable(requirements) {
        const tbody = document.getElementById('mrpTableBody');
        if (!tbody) return;

        if (!requirements.length) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" style="text-align:center;color:var(--text-2);padding:40px 0;">
                        Belum ada data material requirement.
                    </td>
                </tr>`;
            return;
        }

        // Status classes matching DB enum: draft, planned, ordered, received, cancelled
        const statusMap = {
            draft: 'safe', planned: 'warning', ordered: 'ongoing', received: 'safe', cancelled: 'shortage'
        };
        const statusLabel = {
            draft: 'Draft', planned: 'Planned', ordered: 'Ordered', received: 'Received', cancelled: 'Cancelled'
        };

        tbody.innerHTML = requirements.map(r => {
            const sc = statusMap[r.status] || 'safe';
            const shortageClass = parseFloat(r.shortage_quantity) > 0 ? 'shortage' : '';
            return `
                <tr data-mrp-id="${r.id}">
                    <td>
                        <strong>${r.product?.name || '—'}</strong>
                        <small>${r.product?.code || ''}</small>
                    </td>
                    <td class="num">${formatNum(r.required_quantity)} ${r.uom?.symbol || ''}</td>
                    <td class="num">${formatNum(r.available_quantity)}</td>
                    <td class="num">${formatNum(r.safety_stock)}</td>
                    <td class="num ${shortageClass}">${formatNum(r.shortage_quantity)}</td>
                    <td>${formatDate(r.required_date)}</td>
                    <td>${r.lead_time_days} hari</td>
                    <td><span class="status ${sc}">${statusLabel[r.status] || capitalize(r.status)}</span></td>
                </tr>`;
        }).join('');
    }

    function updateStats(stats) {
        if (!stats) return;
        setText('statTotalRequired', formatNum(stats.total_required));
        setText('statTotalAvailable', formatNum(stats.total_available));
        setText('statTotalShortage', formatNum(stats.total_shortage));
        setText('statShortageDelta', stats.shortage_count + ' material');
        const rate = stats.total_required > 0
            ? ((stats.total_available / stats.total_required) * 100).toFixed(1)
            : '0.0';
        setText('statAvailableDelta', rate + '%');
    }

    /*
    |--------------------------------------------------------------------------
    | Search & Filters
    |--------------------------------------------------------------------------
    */
    let searchTimeout;
    const searchInput = document.getElementById('mrpSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => loadMrp(), 300);
        });
    }

    ['mrpFilterStatus', 'mrpFilterShortage'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('change', () => loadMrp());
    });

    /*
    |--------------------------------------------------------------------------
    | Calculate MRP
    |--------------------------------------------------------------------------
    */
    const calcBtn = document.getElementById('btnCalculateMRP');
    if (calcBtn) {
        calcBtn.addEventListener('click', async () => {
            if (!confirm('Hitung ulang material requirements?')) return;

            calcBtn.disabled = true;
            calcBtn.textContent = 'Menghitung...';

            try {
                const res = await fetch(`${API_BASE}/mrp/calculate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        company_id: 1,
                        plant_id: 1,
                    }),
                });
                const json = await res.json();
                if (json.success) {
                    alert(json.message);
                    loadMrp();
                } else {
                    alert(json.message || 'Gagal menghitung MRP.');
                }
            } catch (err) {
                console.error('MRP calculate failed:', err);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            } finally {
                calcBtn.disabled = false;
                calcBtn.textContent = '+ Hitung Kebutuhan';
            }
        });
    }

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
    loadMrp();
});
