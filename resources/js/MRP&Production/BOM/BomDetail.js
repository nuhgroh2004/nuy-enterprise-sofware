document.addEventListener('DOMContentLoaded', () => {
    const API_BASE = '/api';

    if (typeof BOM_ID === 'undefined') return;

    /* ============================================================
     | UTILS
     ============================================================ */

    function escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    async function apiFetch(url, options = {}) {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const headers = { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json', 'Accept': 'application/json', ...options.headers };
        const res = await fetch(url, { ...options, headers });
        return res.json();
    }

    /* ============================================================
     | TABS
     ============================================================ */

    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            tab.classList.add('active');
            const target = document.getElementById(`tab-${tab.dataset.tab}`);
            if (target) target.classList.add('active');

            if (tab.dataset.tab === 'history') loadHistory();
            if (tab.dataset.tab === 'where-used') loadWhereUsed();
        });
    });

    /* ============================================================
     | LOAD UOMS
     ============================================================ */

    async function loadUoms(selectId) {
        try {
            const res = await fetch(`${API_BASE}/uom-list`);
            const json = await res.json();
            if (json.success) {
                json.data.forEach(u => {
                    const sel = document.getElementById(selectId);
                    if (!sel) return;
                    const opt = document.createElement('option');
                    opt.value = u.id;
                    opt.textContent = `${u.name} (${u.symbol})`;
                    sel.appendChild(opt);
                });
            }
        } catch (e) { console.error(e); }
    }

    loadUoms('compUom');
    loadUoms('newVersionOutputUom');

    /* ============================================================
     | LOAD ROUTING VERSIONS
     ============================================================ */

    async function loadRoutingVersions() {
        try {
            const res = await fetch(`${API_BASE}/routing-versions`);
            const json = await res.json();
            if (json.success) {
                const sel = document.getElementById('newVersionRouting');
                if (!sel) return;
                json.data.forEach(rv => {
                    const opt = document.createElement('option');
                    opt.value = rv.id;
                    const header = rv.routing_header;
                    opt.textContent = `${header?.code || ''} v${rv.version}`;
                    sel.appendChild(opt);
                });
            }
        } catch (e) { console.error(e); }
    }

    loadRoutingVersions();

    /* ============================================================
     | LOAD HISTORY
     ============================================================ */

    async function loadHistory() {
        const container = document.getElementById('historyList');
        try {
            const res = await apiFetch(`${API_BASE}/boms/${BOM_ID}/history`);
            if (!res.success) return;

            const versions = res.data;
            if (!versions.length) {
                container.innerHTML = '<div class="empty-state"><div class="title">Tidak ada history</div></div>';
                return;
            }

            container.innerHTML = versions.map(v => `
                <div class="detail-field" style="align-items:flex-start;">
                    <span class="label" style="min-width:120px;">
                        <strong>Version ${escapeHtml(v.version)}</strong>
                        ${v.revision ? ` (${escapeHtml(v.revision)})` : ''}
                        <br><span style="font-size:11px;color:var(--text-2);">${v.approval_state}</span>
                    </span>
                    <span class="value" style="text-align:left;">
                        Effective: ${v.effective_date || '—'}
                        ${v.expiry_date ? ` / Expiry: ${v.expiry_date}` : ''}
                        ${v.submitted_by ? `<br>Submitted: ${escapeHtml(v.submitted_by?.name || '')}` : ''}
                        ${v.approved_by ? `<br>Approved: ${escapeHtml(v.approved_by?.name || '')}` : ''}
                        <br>Components: ${v.components?.length ?? 0}
                    </span>
                </div>
            `).join('');
        } catch (err) {
            console.error(err);
        }
    }

    /* ============================================================
     | LOAD WHERE USED
     ============================================================ */

    async function loadWhereUsed() {
        const container = document.getElementById('whereUsedContent');
        try {
            const res = await apiFetch(`${API_BASE}/boms/${BOM_ID}/where-used`);
            if (!res.success) return;

            const usedIn = res.data;
            if (!usedIn.length) {
                container.innerHTML = '<div class="empty-state"><div class="title">Tidak digunakan di BOM lain</div><div class="desc">Produk ini belum digunakan sebagai komponen di BOM lain.</div></div>';
                return;
            }

            container.innerHTML = `
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>BOM Code</th>
                                <th>BOM Name</th>
                                <th>Parent Product</th>
                                <th>Version</th>
                                <th>Qty</th>
                                <th>UOM</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${usedIn.map(u => `
                                <tr onclick="window.location.href='/MRP/bill-of-materials/${u.bom_id}'">
                                    <td><span class="bom-code">${escapeHtml(u.bom_code)}</span></td>
                                    <td>${escapeHtml(u.bom_name)}</td>
                                    <td>${escapeHtml(u.parent_product_code)} — ${escapeHtml(u.parent_product_name)}</td>
                                    <td>${escapeHtml(u.version)}${u.revision ? ` (${escapeHtml(u.revision)})` : ''}</td>
                                    <td>${u.quantity}</td>
                                    <td>${escapeHtml(u.uom)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        } catch (err) {
            console.error(err);
        }
    }

    /* ============================================================
     | DUPLICATE BOM
     ============================================================ */

    document.getElementById('btnDuplicate')?.addEventListener('click', async () => {
        if (!confirm('Duplikat BOM ini?')) return;
        try {
            const res = await apiFetch(`${API_BASE}/boms/${BOM_ID}/duplicate`, { method: 'POST' });
            if (res.success) {
                window.location.href = `/MRP/bill-of-materials/${res.data.id}`;
            } else {
                alert(res.message || 'Gagal menduplikat BOM.');
            }
        } catch (err) {
            alert('Terjadi kesalahan.');
        }
    });

    /* ============================================================
     | ARCHIVE / RESTORE BOM
     ============================================================ */

    document.getElementById('btnArchive')?.addEventListener('click', async () => {
        if (!confirm('Arsipkan BOM ini?')) return;
        try {
            const res = await apiFetch(`${API_BASE}/boms/${BOM_ID}/archive`, { method: 'POST' });
            if (res.success) {
                window.location.reload();
            } else {
                alert(res.message || 'Gagal mengarsipkan BOM.');
            }
        } catch (err) {
            alert('Terjadi kesalahan.');
        }
    });

    document.getElementById('btnRestore')?.addEventListener('click', async () => {
        if (!confirm('Restore BOM ini?')) return;
        try {
            const res = await apiFetch(`${API_BASE}/boms/${BOM_ID}/restore`, { method: 'POST' });
            if (res.success) {
                window.location.reload();
            } else {
                alert(res.message || 'Gagal restore BOM.');
            }
        } catch (err) {
            alert('Terjadi kesalahan.');
        }
    });

    /* ============================================================
     | VERSION ACTIONS
     ============================================================ */

    window.submitVersion = async function (versionId) {
        if (!confirm('Submit version ini untuk approval?')) return;
        try {
            const res = await apiFetch(`${API_BASE}/bom-versions/${versionId}/submit`, { method: 'POST' });
            if (res.success) window.location.reload();
            else alert(res.message);
        } catch (err) { alert('Terjadi kesalahan.'); }
    };

    window.approveVersion = async function (versionId) {
        if (!confirm('Approve version ini?')) return;
        try {
            const res = await apiFetch(`${API_BASE}/bom-versions/${versionId}/approve`, { method: 'POST' });
            if (res.success) window.location.reload();
            else alert(res.message);
        } catch (err) { alert('Terjadi kesalahan.'); }
    };

    window.expireVersion = async function (versionId) {
        if (!confirm('Expire version ini?')) return;
        try {
            const res = await apiFetch(`${API_BASE}/bom-versions/${versionId}/expire`, { method: 'POST' });
            if (res.success) window.location.reload();
            else alert(res.message);
        } catch (err) { alert('Terjadi kesalahan.'); }
    };

    window.setPrimaryVersion = async function (versionId) {
        try {
            const res = await apiFetch(`${API_BASE}/bom-versions/${versionId}/primary`, { method: 'POST' });
            if (res.success) window.location.reload();
            else alert(res.message);
        } catch (err) { alert('Terjadi kesalahan.'); }
    };

    /* ============================================================
     | COMPONENT MODAL
     ============================================================ */

    const compModal = document.getElementById('componentModal');
    const compForm = document.getElementById('componentForm');

    function openCompModal(title, data = null) {
        document.getElementById('componentModalTitle').textContent = title;
        if (data) {
            document.getElementById('componentId').value = data.id;
            document.getElementById('compProductId').value = data.product_id;
            document.getElementById('compProductSearch').value = data.product ? `${data.product.code} — ${data.product.name}` : '';
            document.getElementById('compQuantity').value = data.quantity;
            document.getElementById('compUom').value = data.uom_id;
            document.getElementById('compScrap').value = data.scrap_percentage || 0;
            document.getElementById('compYield').value = data.yield_percentage || 100;
            document.getElementById('compOpSeq').value = data.operation_sequence || '';
            document.getElementById('compSubPolicy').value = data.substitute_policy || 'manual';
            document.getElementById('compAltGroup').value = data.alternative_group || '';
            document.getElementById('compFixed').checked = data.is_fixed_quantity;
            document.getElementById('compCritical').checked = data.is_critical;
            document.getElementById('compBackflush').checked = data.backflush;
            document.getElementById('compOptional').checked = data.is_optional;
            document.getElementById('compNotes').value = data.notes || '';
        } else {
            compForm.reset();
            document.getElementById('componentId').value = '';
            document.getElementById('compProductId').value = '';
            document.getElementById('compProductSearch').value = '';
            document.getElementById('compYield').value = '100';
            document.getElementById('compScrap').value = '0';
            document.getElementById('compSubPolicy').value = 'manual';
        }
        compModal.classList.add('open');
    }

    document.getElementById('btnAddComponent')?.addEventListener('click', () => openCompModal('Tambah Component'));
    document.getElementById('closeComponentModal')?.addEventListener('click', () => compModal.classList.remove('open'));
    document.getElementById('cancelComponentModal')?.addEventListener('click', () => compModal.classList.remove('open'));

    window.editComponent = async function (compId) {
        try {
            const res = await apiFetch(`${API_BASE}/boms/${BOM_ID}`);
            if (!res.success) return;
            const version = res.data.active_version || res.data.versions?.[0];
            const comp = version?.components?.find(c => c.id === compId);
            if (comp) openCompModal('Edit Component', comp);
        } catch (err) { console.error(err); }
    };

    window.removeComponent = async function (compId) {
        if (!confirm('Hapus komponen ini?')) return;
        try {
            const res = await apiFetch(`${API_BASE}/bom-components/${compId}/destroy`, { method: 'POST' });
            if (res.success) window.location.reload();
            else alert(res.message);
        } catch (err) { alert('Terjadi kesalahan.'); }
    };

    compForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const fd = new FormData(compForm);
        const data = Object.fromEntries(fd.entries());
        data.is_fixed_quantity = fd.has('is_fixed_quantity') ? '1' : '0';
        data.is_critical = fd.has('is_critical') ? '1' : '0';
        data.backflush = fd.has('backflush') ? '1' : '0';
        data.is_optional = fd.has('is_optional') ? '1' : '0';

        if (!data.product_id) { alert('Product harus dipilih.'); return; }
        if (!data.quantity || parseFloat(data.quantity) <= 0) { alert('Quantity harus lebih besar dari 0.'); return; }
        if (!data.uom_id) { alert('UOM harus dipilih.'); return; }

        const compId = data.component_id;
        delete data.component_id;

        try {
            let res;
            if (compId) {
                res = await apiFetch(`${API_BASE}/bom-components/${compId}`, { method: 'PUT', body: JSON.stringify(data) });
            } else {
                const versionId = await getActiveVersionId();
                if (!versionId) { alert('Tidak ada version aktif. Buat version terlebih dahulu.'); return; }
                res = await apiFetch(`${API_BASE}/bom-versions/${versionId}/components`, { method: 'POST', body: JSON.stringify(data) });
            }
            if (res.success) {
                compModal.classList.remove('open');
                window.location.reload();
            } else {
                alert(res.message);
            }
        } catch (err) {
            alert('Terjadi kesalahan.');
            console.error(err);
        }
    });

    async function getActiveVersionId() {
        try {
            const res = await apiFetch(`${API_BASE}/boms/${BOM_ID}`);
            if (!res.success) return null;
            const active = res.data.active_version || res.data.versions?.[0];
            return active?.id || null;
        } catch (e) { return null; }
    }

    /* ============================================================
     | VERSION MODAL
     ============================================================ */

    const verModal = document.getElementById('versionModal');
    const verForm = document.getElementById('versionForm');

    document.getElementById('btnAddVersion')?.addEventListener('click', () => {
        verForm.reset();
        verModal.classList.add('open');
    });
    document.getElementById('closeVersionModal')?.addEventListener('click', () => verModal.classList.remove('open'));
    document.getElementById('cancelVersionModal')?.addEventListener('click', () => verModal.classList.remove('open'));

    verForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const fd = new FormData(verForm);
        const data = Object.fromEntries(fd.entries());

        if (!data.version || !data.effective_date) {
            alert('Version dan Effective Date wajib diisi.');
            return;
        }

        try {
            const res = await apiFetch(`${API_BASE}/boms/${BOM_ID}/versions`, { method: 'POST', body: JSON.stringify(data) });
            if (res.success) {
                verModal.classList.remove('open');
                window.location.reload();
            } else {
                alert(res.message);
            }
        } catch (err) {
            alert('Terjadi kesalahan.');
        }
    });

    /* ============================================================
     | COMPONENT PRODUCT SEARCH (in modal)
     ============================================================ */

    const compSearch = document.getElementById('compProductSearch');
    const compDropdown = document.getElementById('compProductDropdown');
    const compHidden = document.getElementById('compProductId');

    if (compSearch && compDropdown && compHidden) {
        let timeout;
        compSearch.addEventListener('input', () => {
            clearTimeout(timeout);
            const q = compSearch.value.trim();
            if (q.length < 1) { compDropdown.classList.remove('open'); return; }
            timeout = setTimeout(async () => {
                try {
                    const res = await fetch(`${API_BASE}/products/search?search=${encodeURIComponent(q)}`);
                    const json = await res.json();
                    if (json.success && json.data.length) {
                        compDropdown.innerHTML = json.data.map(p => `
                            <div class="dropdown-item" data-id="${p.id}" data-code="${escapeHtml(p.code)}" data-name="${escapeHtml(p.name)}">
                                <span class="code">${escapeHtml(p.code)}</span>
                                <span class="name">${escapeHtml(p.name)}</span>
                            </div>
                        `).join('');
                        compDropdown.classList.add('open');
                    } else {
                        compDropdown.innerHTML = '<div class="dropdown-item" style="color:var(--text-2);cursor:default;">Tidak ditemukan</div>';
                        compDropdown.classList.add('open');
                    }
                } catch (e) { console.error(e); }
            }, 200);
        });

        compDropdown.addEventListener('click', (e) => {
            const item = e.target.closest('.dropdown-item');
            if (!item || !item.dataset.id) return;
            compHidden.value = item.dataset.id;
            compSearch.value = `${item.dataset.code} — ${item.dataset.name}`;
            compDropdown.classList.remove('open');
        });

        compSearch.addEventListener('blur', () => {
            setTimeout(() => compDropdown.classList.remove('open'), 200);
        });
    }
});
