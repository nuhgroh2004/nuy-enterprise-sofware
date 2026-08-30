document.addEventListener('DOMContentLoaded', () => {
    const API_BASE = '/api';

    let currentPage = 1;

    /* ============================================================
     | BOM TABLE — Load Data
     ============================================================ */

    async function loadBoms(page = 1) {
        currentPage = page;
        const search = document.getElementById('searchInput')?.value || '';
        const status = document.getElementById('filterStatus')?.value || '';
        const active = document.getElementById('filterActive')?.value || '';

        const query = new URLSearchParams({ page, per_page: 20 });
        if (search) query.set('search', search);
        if (status) query.set('status', status);
        if (active) query.set('is_active', active);

        try {
            const res = await fetch(`${API_BASE}/boms?${query}`);
            const json = await res.json();
            if (json.success) {
                renderBomTable(json.data.data);
                renderPagination(json.data);
                updateStats(json.stats);
            }
        } catch (err) {
            console.error('Failed to load BOMs:', err);
            renderEmptyState('Gagal memuat data BOM. Silakan coba lagi.');
        }
    }

    function renderBomTable(boms) {
        const tbody = document.getElementById('bomTableBody');
        if (!tbody) return;

        if (!boms.length) {
            tbody.innerHTML = `<tr><td colspan="9"><div class="empty-state"><div class="title">Belum ada data BOM</div><div class="desc">Klik "+ Buat BOM" untuk membuat Bill of Materials baru.</div></div></td></tr>`;
            return;
        }

        tbody.innerHTML = boms.map(b => {
            const productCode = b.product?.code || '—';
            const productName = b.product?.name || '';
            const plantName = b.plant?.name || '—';
            const activeVersion = b.active_version;
            const version = activeVersion?.version || '—';
            const revision = activeVersion?.revision || '';
            const status = activeVersion?.approval_state || 'draft';
            const effectiveDate = activeVersion?.effective_date
                ? new Date(activeVersion.effective_date).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })
                : '—';
            const componentCount = activeVersion?.components_count ?? 0;
            const updatedDate = b.updated_at
                ? new Date(b.updated_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })
                : '—';

            return `
                <tr onclick="viewBom(${b.id})">
                    <td><span class="bom-code">${escapeHtml(b.code)}</span></td>
                    <td>
                        <span class="bom-name">${escapeHtml(productCode)}</span>
                        <div class="bom-product">${escapeHtml(productName)}</div>
                    </td>
                    <td>${escapeHtml(plantName)}</td>
                    <td>${escapeHtml(version)}</td>
                    <td>${escapeHtml(revision)}</td>
                    <td><span class="status ${status}">${capitalize(status)}</span></td>
                    <td>${effectiveDate}</td>
                    <td>${componentCount}</td>
                    <td>${updatedDate}</td>
                </tr>
            `;
        }).join('');
    }

    function updateStats(stats) {
        if (!stats) return;
        const el = (id, val) => { const e = document.getElementById(id); if (e) e.textContent = Number(val).toLocaleString(); };
        el('statTotal', stats.total);
        el('statActive', stats.active);
        el('statDraft', stats.draft);
        el('statApproved', stats.approved);
    }

    function renderPagination(data) {
        const container = document.getElementById('paginationContainer');
        if (!container || !data.last_page || data.last_page <= 1) {
            if (container) container.innerHTML = '';
            return;
        }

        let html = '<div style="display:flex;gap:4px;">';
        for (let i = 1; i <= data.last_page; i++) {
            const active = i === data.current_page ? 'background:var(--accent);color:#fff;' : '';
            html += `<button class="btn-ghost" style="padding:5px 10px;font-size:12px;${active}" onclick="loadBoms(${i})">${i}</button>`;
        }
        html += '</div>';
        container.innerHTML = html;
    }

    function renderEmptyState(msg) {
        const tbody = document.getElementById('bomTableBody');
        if (tbody) {
            tbody.innerHTML = `<tr><td colspan="9"><div class="empty-state"><div class="title">${msg}</div></div></td></tr>`;
        }
    }

    /* ============================================================
     | HELPERS
     ============================================================ */

    function escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    function capitalize(str) {
        return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
    }

    /* ============================================================
     | NAVIGATION
     ============================================================ */

    window.viewBom = function (id) {
        window.location.href = `/MRP/bill-of-materials/${id}`;
    };

    window.loadBoms = loadBoms;

    /* ============================================================
     | EVENT LISTENERS
     ============================================================ */

    let searchTimeout;
    document.getElementById('searchInput')?.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadBoms(1), 300);
    });

    document.getElementById('filterStatus')?.addEventListener('change', () => loadBoms(1));
    document.getElementById('filterActive')?.addEventListener('change', () => loadBoms(1));

    /* ============================================================
     | INIT
     ============================================================ */

    loadBoms(1);
});
