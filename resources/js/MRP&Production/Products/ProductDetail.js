document.addEventListener('DOMContentLoaded', () => {
    const API_BASE = '/api';

    // Extract product ID from URL
    const pathParts = window.location.pathname.split('/');
    const productId = pathParts[pathParts.length - 1];

    if (!productId) {
        console.error('Product ID not found in URL');
        return;
    }

    /* ============================================================
     | TAB SWITCHING
     ============================================================ */

    const tabs = document.querySelectorAll('.tab[data-tab]');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const target = tab.dataset.tab;

            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            tabContents.forEach(tc => tc.classList.remove('active'));
            const content = document.getElementById(`tab-${target}`);
            if (content) content.classList.add('active');

            // Load tab-specific data on demand
            if (target === 'variants') loadVariants();
            if (target === 'usage') loadUsage();
        });
    });

    /* ============================================================
     | ACTIONS — Edit, Archive, Restore, Duplicate
     ============================================================ */

    const btnEdit = document.getElementById('btnEdit');
    const btnArchive = document.getElementById('btnArchive');
    const btnRestore = document.getElementById('btnRestore');
    const btnDuplicate = document.getElementById('btnDuplicate');

    if (btnEdit) {
        btnEdit.addEventListener('click', () => {
            // Navigate to list page with edit modal open
            window.location.href = `/MRP/products?edit=${productId}`;
        });
    }

    if (btnArchive) {
        btnArchive.addEventListener('click', async () => {
            if (!confirm('Archive produk ini? Produk yang sudah diarsipkan tidak dapat digunakan untuk transaksi baru.')) return;
            try {
                const res = await fetch(`${API_BASE}/products/${productId}/archive`, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                });
                const json = await res.json();
                if (json.success) {
                    window.location.reload();
                } else {
                    alert(json.message || 'Gagal mengarsipkan produk.');
                }
            } catch (err) {
                console.error('Archive failed:', err);
                alert('Gagal mengarsipkan produk.');
            }
        });
    }

    if (btnRestore) {
        btnRestore.addEventListener('click', async () => {
            if (!confirm('Restore produk ini?')) return;
            try {
                const res = await fetch(`${API_BASE}/products/${productId}/restore`, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                });
                const json = await res.json();
                if (json.success) {
                    window.location.reload();
                } else {
                    alert(json.message || 'Gagal merestore produk.');
                }
            } catch (err) {
                console.error('Restore failed:', err);
                alert('Gagal merestore produk.');
            }
        });
    }

    if (btnDuplicate) {
        btnDuplicate.addEventListener('click', async () => {
            if (!confirm('Duplikat produk ini?')) return;
            try {
                const res = await fetch(`${API_BASE}/products/${productId}/duplicate`, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                });
                const json = await res.json();
                if (json.success && json.data) {
                    window.location.href = `/MRP/products/${json.data.id}`;
                } else {
                    alert(json.message || 'Gagal menduplikat produk.');
                }
            } catch (err) {
                console.error('Duplicate failed:', err);
                alert('Gagal menduplikat produk.');
            }
        });
    }

    /* ============================================================
     | VARIANTS
     ============================================================ */

    const variantModal = document.getElementById('variantModal');
    const btnAddVariant = document.getElementById('btnAddVariant');
    const btnCloseVariant = document.getElementById('closeVariantModal');
    const btnCancelVariant = document.getElementById('cancelVariantModal');
    const variantForm = document.getElementById('variantForm');

    let editingVariantId = null;

    if (btnAddVariant) {
        btnAddVariant.addEventListener('click', () => {
            editingVariantId = null;
            document.getElementById('variantModalTitle').textContent = 'Tambah Variant';
            document.getElementById('btnSubmitVariant').textContent = 'Simpan';
            variantForm.reset();
            document.getElementById('variantActive').checked = true;
            document.getElementById('variantId').value = '';
            variantModal.classList.add('open');
        });
    }

    if (btnCloseVariant) btnCloseVariant.addEventListener('click', closeVariantModal);
    if (btnCancelVariant) btnCancelVariant.addEventListener('click', closeVariantModal);

    if (variantModal) {
        variantModal.addEventListener('click', (e) => {
            if (e.target === variantModal) closeVariantModal();
        });
    }

    function closeVariantModal() {
        variantModal.classList.remove('open');
        variantForm.reset();
        editingVariantId = null;
    }

    window.editVariant = async function (variantId) {
        try {
            const res = await fetch(`${API_BASE}/products/${productId}`);
            const json = await res.json();
            if (!json.success) return;

            const variant = json.data.variants?.find(v => v.id === variantId);
            if (!variant) {
                alert('Variant tidak ditemukan.');
                return;
            }

            editingVariantId = variantId;
            document.getElementById('variantModalTitle').textContent = 'Edit Variant';
            document.getElementById('btnSubmitVariant').textContent = 'Update';
            document.getElementById('variantId').value = variantId;
            document.getElementById('variantCode').value = variant.code || '';
            document.getElementById('variantName').value = variant.name || '';
            document.getElementById('variantSku').value = variant.sku || '';
            document.getElementById('variantBarcode').value = variant.barcode || '';
            document.getElementById('variantCost').value = variant.additional_cost || '';
            document.getElementById('variantActive').checked = !!variant.is_active;
            variantModal.classList.add('open');
        } catch (err) {
            console.error('Failed to load variant:', err);
        }
    };

    window.deleteVariant = async function (variantId, code) {
        if (!confirm(`Hapus variant "${code}"?`)) return;
        try {
            const res = await fetch(`${API_BASE}/products/variants/${variantId}`, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json' },
            });
            const json = await res.json();
            if (json.success) {
                loadVariants();
            } else {
                alert(json.message || 'Gagal menghapus variant.');
            }
        } catch (err) {
            console.error('Delete variant failed:', err);
            alert('Gagal menghapus variant.');
        }
    };

    if (variantForm) {
        variantForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(variantForm);
            const data = {
                code: formData.get('code'),
                name: formData.get('name') || null,
                sku: formData.get('sku') || null,
                barcode: formData.get('barcode') || null,
                additional_cost: formData.get('additional_cost') ? parseFloat(formData.get('additional_cost')) : null,
                is_active: formData.has('is_active') ? 1 : 0,
            };

            const isEdit = !!editingVariantId;
            const url = isEdit
                ? `${API_BASE}/products/variants/${editingVariantId}`
                : `${API_BASE}/products/${productId}/variants`;
            const method = isEdit ? 'PUT' : 'POST';

            try {
                const res = await fetch(url, {
                    method,
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(data),
                });
                const json = await res.json();
                if (json.success) {
                    closeVariantModal();
                    loadVariants();
                } else {
                    alert(json.message || Object.values(json.errors || {}).flat().join('\n'));
                }
            } catch (err) {
                console.error('Save variant failed:', err);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        });
    }

    async function loadVariants() {
        try {
            const res = await fetch(`${API_BASE}/products/${productId}`);
            const json = await res.json();
            if (!json.success) return;

            const variants = json.data.variants || [];
            const grid = document.getElementById('variantGrid');
            const countEl = document.getElementById('variantCount');
            if (countEl) countEl.textContent = variants.length ? `(${variants.length})` : '';

            if (!variants.length) {
                grid.innerHTML = `
                    <div class="empty-state" style="grid-column:1/-1;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M21 8l-9-5-9 5 9 5 9-5z"/>
                            <path d="M3 8v8l9 5 9-5V8"/>
                        </svg>
                        <div class="title">Belum ada variant</div>
                        <div class="desc">Klik "+ Tambah Variant" untuk membuat variant produk baru.</div>
                    </div>`;
                return;
            }

            grid.innerHTML = variants.map(v => {
                const statusClass = v.is_active ? 'active' : 'inactive';
                const statusText = v.is_active ? 'Active' : 'Inactive';
                const attributes = v.attributes || {};
                const attrHtml = Object.entries(attributes)
                    .map(([k, val]) => `<span style="font-size:10px;padding:2px 6px;background:rgba(0,0,0,0.05);border-radius:4px;margin-right:4px;">${k}: ${val}</span>`)
                    .join('');

                return `
                    <div class="variant-card">
                        <div class="variant-card-header">
                            <span class="code">${escapeHtml(v.code)}</span>
                            <span class="status ${statusClass}">${statusText}</span>
                        </div>
                        <div class="name">${escapeHtml(v.name || '—')}</div>
                        <div class="meta">
                            ${v.sku ? `<span>SKU: ${escapeHtml(v.sku)}</span>` : ''}
                            ${v.barcode ? `<span>Barcode: ${escapeHtml(v.barcode)}</span>` : ''}
                        </div>
                        ${attrHtml ? `<div style="margin-top:6px;">${attrHtml}</div>` : ''}
                        <div class="variant-card-actions">
                            <button class="btn-ghost" style="padding:3px 8px;font-size:11px;" onclick="editVariant(${v.id})">Edit</button>
                            <button class="btn-ghost" style="padding:3px 8px;font-size:11px;color:var(--red);" onclick="deleteVariant(${v.id}, '${escapeHtml(v.code)}')">Hapus</button>
                        </div>
                    </div>`;
            }).join('');
        } catch (err) {
            console.error('Failed to load variants:', err);
        }
    }

    /* ============================================================
     | HELPERS
     ============================================================ */

    function escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
});
