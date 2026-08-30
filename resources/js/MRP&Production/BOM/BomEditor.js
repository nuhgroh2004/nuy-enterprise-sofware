document.addEventListener('DOMContentLoaded', () => {
    const API_BASE = '/api';

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

    async function loadSelectOptions(url, selectId, placeholder, mapper) {
        try {
            const res = await fetch(url);
            const json = await res.json();
            if (json.success) {
                const sel = document.getElementById(selectId);
                if (!sel) return;
                json.data.forEach(item => {
                    const opt = document.createElement('option');
                    const mapped = mapper(item);
                    opt.value = mapped.value;
                    opt.textContent = mapped.label;
                    sel.appendChild(opt);
                });
            }
        } catch (e) { console.error(`Failed to load ${selectId}:`, e); }
    }

    /* ============================================================
     | LOAD ALL REFERENCE DATA
     ============================================================ */

    async function loadAllReferenceData() {
        await Promise.all([
            loadSelectOptions(`${API_BASE}/companies`, 'formCompanyId', 'Pilih Company...', c => ({ value: c.id, label: `${c.code} — ${c.name}` })),
            loadSelectOptions(`${API_BASE}/uom-list`, 'formOutputUom', 'Pilih UOM...', u => ({ value: u.id, label: `${u.name} (${u.symbol})` })),
        ]);

        const companyId = document.getElementById('formCompanyId')?.value;
        if (companyId) {
            await loadPlantOptions(companyId);
            await loadProcessOptions(companyId);
            await loadRoutingOptions(companyId);
        }
    }

    async function loadPlantOptions(companyId) {
        const sel = document.getElementById('formPlantId');
        if (!sel) return;
        sel.innerHTML = '<option value="">Pilih Plant...</option>';
        if (!companyId) return;
        await loadSelectOptions(`${API_BASE}/plants?company_id=${companyId}`, 'formPlantId', 'Pilih Plant...', p => ({ value: p.id, label: `${p.code} — ${p.name}` }));
    }

    async function loadProcessOptions(companyId) {
        const sel = document.getElementById('formProductionProcess');
        if (!sel) return;
        sel.innerHTML = '<option value="">Pilih Process...</option>';
        if (!companyId) return;
        await loadSelectOptions(`${API_BASE}/production-processes?company_id=${companyId}`, 'formProductionProcess', 'Pilih Process...', p => ({ value: p.id, label: `${p.code} — ${p.name}` }));
    }

    async function loadRoutingOptions(companyId) {
        const sel = document.getElementById('formRoutingVersion');
        if (!sel) return;
        sel.innerHTML = '<option value="">Pilih Routing...</option>';
        if (!companyId) return;
        try {
            const res = await fetch(`${API_BASE}/routing-versions?company_id=${companyId}`);
            const json = await res.json();
            if (json.success) {
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

    /* ============================================================
     | COMPANY CHANGE → RELOAD PLANTS, PROCESSES, ROUTINGS
     ============================================================ */

    document.getElementById('formCompanyId')?.addEventListener('change', async (e) => {
        const companyId = e.target.value;
        await loadPlantOptions(companyId);
        await loadProcessOptions(companyId);
        await loadRoutingOptions(companyId);
    });

    /* ============================================================
     | PRODUCT SEARCH SELECTOR
     ============================================================ */

    function setupProductSearch(searchId, dropdownId, hiddenInputId) {
        const search = document.getElementById(searchId);
        const dropdown = document.getElementById(dropdownId);
        const hidden = document.getElementById(hiddenInputId);
        if (!search || !dropdown || !hidden) return;

        let timeout;
        search.addEventListener('input', () => {
            clearTimeout(timeout);
            const q = search.value.trim();
            if (q.length < 1) { dropdown.classList.remove('open'); return; }
            timeout = setTimeout(async () => {
                try {
                    const companyId = document.getElementById('formCompanyId')?.value || '';
                    let url = `${API_BASE}/products/search?search=${encodeURIComponent(q)}`;
                    if (companyId) url += `&company_id=${companyId}`;
                    const res = await fetch(url);
                    const json = await res.json();
                    if (json.success && json.data.length) {
                        dropdown.innerHTML = json.data.map(p => `
                            <div class="dropdown-item" data-id="${p.id}" data-code="${escapeHtml(p.code)}" data-name="${escapeHtml(p.name)}">
                                <span class="code">${escapeHtml(p.code)}</span>
                                <span class="name">${escapeHtml(p.name)}</span>
                            </div>
                        `).join('');
                        dropdown.classList.add('open');
                    } else {
                        dropdown.innerHTML = '<div class="dropdown-item" style="color:var(--text-2);cursor:default;">Tidak ditemukan</div>';
                        dropdown.classList.add('open');
                    }
                } catch (e) { console.error(e); }
            }, 200);
        });

        dropdown.addEventListener('click', (e) => {
            const item = e.target.closest('.dropdown-item');
            if (!item || !item.dataset.id) return;
            hidden.value = item.dataset.id;
            search.value = `${item.dataset.code} — ${item.dataset.name}`;
            dropdown.classList.remove('open');
        });

        search.addEventListener('blur', () => {
            setTimeout(() => dropdown.classList.remove('open'), 200);
        });
    }

    setupProductSearch('productSearch', 'productDropdown', 'formProductId');

    /* ============================================================
     | CREATE BOM FORM
     ============================================================ */

    const createForm = document.getElementById('bomCreateForm');
    if (createForm) {
        loadAllReferenceData();

        createForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const fd = new FormData(createForm);
            const data = Object.fromEntries(fd.entries());

            if (!data.product_id) {
                alert('Product harus dipilih.');
                return;
            }
            if (!data.code) {
                alert('BOM Code wajib diisi.');
                return;
            }
            if (!data.company_id) {
                alert('Company harus dipilih.');
                return;
            }

            const btn = document.getElementById('btnSubmit');
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            try {
                const result = await apiFetch(`${API_BASE}/boms`, {
                    method: 'POST',
                    body: JSON.stringify(data),
                });

                if (result.success) {
                    const bom = result.data;
                    if (data.version && data.effective_date) {
                        await apiFetch(`${API_BASE}/boms/${bom.id}/versions`, {
                            method: 'POST',
                            body: JSON.stringify({
                                version: data.version,
                                revision: data.revision || null,
                                effective_date: data.effective_date,
                                expiry_date: data.expiry_date || null,
                                output_qty: data.output_qty || null,
                                output_uom_id: data.output_uom_id || null,
                                yield_percent: data.yield_percent || 100,
                                routing_version_id: data.routing_version_id || null,
                                notes: data.notes || null,
                            }),
                        });
                    }
                    window.location.href = `/MRP/bill-of-materials/${bom.id}`;
                } else {
                    alert(result.message || 'Gagal membuat BOM.');
                }
            } catch (err) {
                alert('Terjadi kesalahan. Silakan coba lagi.');
                console.error(err);
            } finally {
                btn.disabled = false;
                btn.textContent = 'Buat BOM';
            }
        });
    }

    /* ============================================================
     | EDIT BOM FORM
     ============================================================ */

    const editForm = document.getElementById('bomEditForm');
    if (editForm && typeof BOM_DATA !== 'undefined') {
        (async () => {
            await loadAllReferenceData();

            if (BOM_DATA.company_id) {
                document.getElementById('formCompanyId').value = BOM_DATA.company_id;
                await loadPlantOptions(BOM_DATA.company_id);
                await loadProcessOptions(BOM_DATA.company_id);
            }
            if (BOM_DATA.plant_id) {
                document.getElementById('formPlantId').value = BOM_DATA.plant_id;
            }
            if (BOM_DATA.production_process_id) {
                document.getElementById('formProductionProcess').value = BOM_DATA.production_process_id;
            }
        })();

        editForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const fd = new FormData(editForm);
            const data = Object.fromEntries(fd.entries());
            data.is_active = fd.has('is_active') ? '1' : '0';

            const btn = document.getElementById('btnSubmit');
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            try {
                const result = await apiFetch(`${API_BASE}/boms/${BOM_ID}`, {
                    method: 'PUT',
                    body: JSON.stringify(data),
                });

                if (result.success) {
                    window.location.href = `/MRP/bill-of-materials/${BOM_ID}`;
                } else {
                    alert(result.message || 'Gagal mengupdate BOM.');
                }
            } catch (err) {
                alert('Terjadi kesalahan. Silakan coba lagi.');
                console.error(err);
            } finally {
                btn.disabled = false;
                btn.textContent = 'Simpan Perubahan';
            }
        });
    }
});
