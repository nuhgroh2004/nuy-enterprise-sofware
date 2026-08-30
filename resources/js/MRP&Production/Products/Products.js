document.addEventListener('DOMContentLoaded', () => {
    const API_BASE = '/api';

    /* ============================================================
     | STATE
     ============================================================ */

    let currentPage = 1;
    let currentEditId = null;

    /* ============================================================
     | PRODUCTS TABLE — Load Data
     ============================================================ */

    async function loadProducts(page = 1) {
        currentPage = page;
        const search = document.getElementById('searchInput')?.value || '';
        const type = document.getElementById('filterType')?.value || '';
        const category = document.getElementById('filterCategory')?.value || '';
        const status = document.getElementById('filterStatus')?.value || '';
        const manufacturable = document.getElementById('filterManufacturable')?.value || '';

        const query = new URLSearchParams({ page, per_page: 20 });
        if (search) query.set('search', search);
        if (type) query.set('product_type_id', type);
        if (category) query.set('product_category_id', category);
        if (status) query.set('status', status);
        if (manufacturable) query.set('is_manufacturable', manufacturable);

        try {
            const res = await fetch(`${API_BASE}/products?${query}`);
            const json = await res.json();
            if (json.success) {
                renderProductTable(json.data.data);
                renderPagination(json.data);
                updateStats(json.stats);
            }
        } catch (err) {
            console.error('Failed to load products:', err);
            renderEmptyState('Gagal memuat data produk. Silakan coba lagi.');
        }
    }

    function renderProductTable(products) {
        const tbody = document.getElementById('productTableBody');
        if (!tbody) return;

        if (!products.length) {
            renderEmptyState('Belum ada data produk. Klik "+ Buat Product" untuk memulai.');
            return;
        }

        tbody.innerHTML = products.map(p => {
            const statusClass = p.status || 'active';
            const typeName = p.product_type?.name || '—';
            const catName = p.product_category?.name || '—';
            const uomName = p.uom?.symbol || p.uom?.name || '—';

            const flags = [];
            if (p.is_purchasable) flags.push('<span class="flag purchasable">PUR</span>');
            if (p.is_sellable) flags.push('<span class="flag sellable">SAL</span>');
            if (p.is_manufacturable) flags.push('<span class="flag manufacturable">MFG</span>');
            if (p.is_stockable) flags.push('<span class="flag stockable">STK</span>');

            const updatedDate = p.updated_at
                ? new Date(p.updated_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })
                : '—';

            return `
                <tr data-product-id="${p.id}" onclick="viewProduct(${p.id})">
                    <td>
                        <span class="product-code">${escapeHtml(p.code)}</span>
                    </td>
                    <td>
                        <span class="product-sku">${escapeHtml(p.sku || '—')}</span>
                    </td>
                    <td>
                        <span class="product-name">${escapeHtml(p.name)}</span>
                    </td>
                    <td>${escapeHtml(typeName)}</td>
                    <td>${escapeHtml(catName)}</td>
                    <td>${escapeHtml(uomName)}</td>
                    <td>
                        <div class="flags">${flags.join('')}</div>
                    </td>
                    <td><span class="status ${statusClass}">${capitalize(statusClass)}</span></td>
                    <td>${updatedDate}</td>
                    <td>
                        <div class="row-actions" onclick="event.stopPropagation()">
                            <button class="btn-ghost btn-view" style="padding:4px 8px;font-size:11px;"
                                onclick="viewProduct(${p.id})" title="Lihat Detail">Lihat</button>
                            <button class="btn-ghost btn-edit" style="padding:4px 8px;font-size:11px;"
                                onclick="editProduct(${p.id})" title="Edit">Edit</button>
                            <button class="btn-ghost btn-archive" style="padding:4px 8px;font-size:11px;"
                                onclick="archiveProduct(${p.id}, '${escapeHtml(p.name)}')" title="Archive">
                                ${p.status === 'inactive' ? 'Restore' : 'Archive'}
                            </button>
                            <button class="btn-ghost btn-duplicate" style="padding:4px 8px;font-size:11px;"
                                onclick="duplicateProduct(${p.id})" title="Duplicate">Dup</button>
                        </div>
                    </td>
                </tr>`;
        }).join('');
    }

    function renderEmptyState(message) {
        const tbody = document.getElementById('productTableBody');
        if (!tbody) return;
        tbody.innerHTML = `
            <tr>
                <td colspan="10">
                    <div class="empty-state">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M21 8l-9-5-9 5 9 5 9-5z"/>
                            <path d="M3 8v8l9 5 9-5V8"/>
                        </svg>
                        <div class="title">Tidak ada data</div>
                        <div class="desc">${message}</div>
                        <button class="btn-primary" onclick="openCreateModal()">+ Buat Product</button>
                    </div>
                </td>
            </tr>`;
    }

    function renderPagination(paginateData) {
        const container = document.getElementById('paginationContainer');
        if (!container) return;

        if (paginateData.last_page <= 1) {
            container.innerHTML = '';
            return;
        }

        let html = '<div style="display:flex;gap:4px;align-items:center;">';

        // Previous
        if (paginateData.current_page > 1) {
            html += `<button class="btn-ghost" style="padding:4px 10px;font-size:12px;" onclick="loadProducts(${paginateData.current_page - 1})">Prev</button>`;
        }

        // Page numbers
        const start = Math.max(1, paginateData.current_page - 2);
        const end = Math.min(paginateData.last_page, paginateData.current_page + 2);

        for (let i = start; i <= end; i++) {
            const active = i === paginateData.current_page;
            html += `<button class="btn-ghost" style="padding:4px 10px;font-size:12px;${active ? 'background:var(--accent);color:#fff;' : ''}" onclick="loadProducts(${i})">${i}</button>`;
        }

        // Next
        if (paginateData.current_page < paginateData.last_page) {
            html += `<button class="btn-ghost" style="padding:4px 10px;font-size:12px;" onclick="loadProducts(${paginateData.current_page + 1})">Next</button>`;
        }

        html += `<span style="font-size:11px;color:var(--text-2);margin-left:8px;">${paginateData.total} produk</span>`;
        html += '</div>';
        container.innerHTML = html;
    }

    function updateStats(stats) {
        if (!stats) return;
        setText('statTotal', formatNum(stats.total));
        setText('statActive', formatNum(stats.active));
        setText('statInactive', formatNum(stats.inactive));
        setText('statManufacturable', formatNum(stats.manufacturable));
    }

    /* ============================================================
     | SEARCH & FILTERS
     ============================================================ */

    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => loadProducts(1), 300);
        });
    }

    ['filterType', 'filterCategory', 'filterStatus', 'filterManufacturable'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('change', () => loadProducts(1));
    });

    /* ============================================================
     | REFERENCE DATA — Load Types, Categories, UOMs
     ============================================================ */

    async function loadReferenceData() {
        try {
            const [typesRes, catsRes, uomsRes] = await Promise.all([
                fetch(`${API_BASE}/product-types`),
                fetch(`${API_BASE}/product-categories`),
                fetch(`${API_BASE}/uoms`),
            ]);

            const typesJson = await typesRes.json();
            const catsJson = await catsRes.json();
            const uomsJson = await uomsRes.json();

            if (typesJson.success) {
                const typeSelect = document.getElementById('filterType');
                const formTypeSelect = document.getElementById('formProductType');
                typesJson.data.forEach(t => {
                    if (typeSelect) typeSelect.add(new Option(t.name, t.id));
                    if (formTypeSelect) formTypeSelect.add(new Option(`${t.code} — ${t.name}`, t.id));
                });
            }

            if (catsJson.success) {
                const catSelect = document.getElementById('filterCategory');
                const formCatSelect = document.getElementById('formProductCategory');
                flattenCategories(catsJson.data).forEach(c => {
                    const prefix = c.depth > 0 ? '  '.repeat(c.depth) + '└ ' : '';
                    if (catSelect) catSelect.add(new Option(prefix + c.name, c.id));
                    if (formCatSelect) formCatSelect.add(new Option(prefix + c.name, c.id));
                });
            }

            if (uomsJson.success) {
                const uomSelect = document.getElementById('formUom');
                uomsJson.data.forEach(u => {
                    if (uomSelect) uomSelect.add(new Option(`${u.code} (${u.symbol})`, u.id));
                });
            }
        } catch (err) {
            console.error('Failed to load reference data:', err);
        }
    }

    function flattenCategories(categories, depth = 0) {
        let result = [];
        categories.forEach(c => {
            result.push({ id: c.id, name: c.name, depth });
            if (c.children && c.children.length) {
                result = result.concat(flattenCategories(c.children, depth + 1));
            }
        });
        return result;
    }

    /* ============================================================
     | CREATE / EDIT MODAL
     ============================================================ */

    const modal = document.getElementById('productModal');
    const btnCreate = document.getElementById('btnCreateProduct');
    const btnClose = document.getElementById('closeModal');
    const btnCancel = document.getElementById('cancelModal');
    const form = document.getElementById('productForm');

    if (btnCreate) btnCreate.addEventListener('click', openCreateModal);
    if (btnClose) btnClose.addEventListener('click', closeModal);
    if (btnCancel) btnCancel.addEventListener('click', closeModal);

    // Close on overlay click
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
    }

    function openCreateModal() {
        currentEditId = null;
        document.getElementById('modalTitle').textContent = 'Buat Product Baru';
        document.getElementById('btnSubmitForm').textContent = 'Simpan Product';
        form.reset();
        document.getElementById('formStockable').checked = true;
        document.getElementById('formProductId').value = '';
        clearFormErrors();
        modal.classList.add('open');
    }

    function closeModal() {
        modal.classList.remove('open');
        form.reset();
        currentEditId = null;
        clearFormErrors();
    }

    async function editProduct(id) {
        try {
            const res = await fetch(`${API_BASE}/products/${id}`);
            const json = await res.json();
            if (!json.success) {
                alert(json.message || 'Gagal memuat data produk.');
                return;
            }

            const p = json.data;
            currentEditId = id;
            document.getElementById('modalTitle').textContent = 'Edit Product';
            document.getElementById('btnSubmitForm').textContent = 'Update Product';

            document.getElementById('formProductId').value = p.id;
            document.getElementById('formCompanyId').value = p.company_id;
            document.getElementById('formCode').value = p.code;
            document.getElementById('formName').value = p.name;
            document.getElementById('formSku').value = p.sku || '';
            document.getElementById('formBarcode').value = p.barcode || '';
            document.getElementById('formStatus').value = p.status;
            document.getElementById('formDescription').value = p.description || '';
            document.getElementById('formProductType').value = p.product_type_id || '';
            document.getElementById('formProductCategory').value = p.product_category_id || '';
            document.getElementById('formUom').value = p.uom_id || '';
            document.getElementById('formLeadTime').value = p.lead_time_days || '';

            document.getElementById('formPurchasable').checked = !!p.is_purchasable;
            document.getElementById('formSellable').checked = !!p.is_sellable;
            document.getElementById('formManufacturable').checked = !!p.is_manufacturable;
            document.getElementById('formStockable').checked = !!p.is_stockable;
            document.getElementById('formBatchTracked').checked = !!p.is_batch_tracked;
            document.getElementById('formSerialTracked').checked = !!p.is_serial_tracked;
            document.getElementById('formExpiryTracked').checked = !!p.is_expiry_tracked;

            document.getElementById('formStandardCost').value = p.standard_cost || '';
            document.getElementById('formAverageCost').value = p.average_cost || '';
            document.getElementById('formLastPurchaseCost').value = p.last_purchase_cost || '';
            document.getElementById('formMinStock').value = p.min_stock || '';
            document.getElementById('formMaxStock').value = p.max_stock || '';
            document.getElementById('formSafetyStock').value = p.safety_stock || '';
            document.getElementById('formReorderPoint').value = p.reorder_point || '';

            clearFormErrors();
            modal.classList.add('open');
        } catch (err) {
            console.error('Failed to load product:', err);
            alert('Gagal memuat data produk.');
        }
    }

    /* ============================================================
     | FORM SUBMIT
     ============================================================ */

    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            clearFormErrors();

            const formData = new FormData(form);
            const data = {};
            formData.forEach((value, key) => {
                if (key === 'id') return;
                if (key === 'company_id' || key === 'product_type_id' || key === 'product_category_id' || key === 'uom_id' || key === 'lead_time_days') {
                    data[key] = value ? parseInt(value) : null;
                } else if (['is_purchasable', 'is_sellable', 'is_manufacturable', 'is_stockable',
                    'is_batch_tracked', 'is_serial_tracked', 'is_expiry_tracked'].includes(key)) {
                    data[key] = formData.has(key) ? 1 : 0;
                } else if (['standard_cost', 'average_cost', 'last_purchase_cost',
                    'min_stock', 'max_stock', 'safety_stock', 'reorder_point'].includes(key)) {
                    data[key] = value ? parseFloat(value) : null;
                } else {
                    data[key] = value || null;
                }
            });

            const isEdit = !!currentEditId;
            const url = isEdit ? `${API_BASE}/products/${currentEditId}` : `${API_BASE}/products`;
            const method = isEdit ? 'PUT' : 'POST';

            try {
                const res = await fetch(url, {
                    method,
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(data),
                });
                const json = await res.json();
                if (json.success) {
                    closeModal();
                    loadProducts(currentPage);
                } else {
                    if (json.errors) {
                        displayFormErrors(json.errors);
                    } else {
                        alert(json.message || 'Terjadi kesalahan.');
                    }
                }
            } catch (err) {
                console.error('Save product failed:', err);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        });
    }

    function displayFormErrors(errors) {
        Object.keys(errors).forEach(key => {
            const fieldMap = {
                'company_id': 'formCompanyId',
                'code': 'formCode',
                'name': 'formName',
                'sku': 'formSku',
                'barcode': 'formBarcode',
                'status': 'formStatus',
                'description': 'formDescription',
                'product_type_id': 'formProductType',
                'product_category_id': 'formProductCategory',
                'uom_id': 'formUom',
                'lead_time_days': 'formLeadTime',
            };
            const fieldId = fieldMap[key];
            if (fieldId) {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.classList.add('input-error');
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'form-error';
                    errorDiv.textContent = Array.isArray(errors[key]) ? errors[key][0] : errors[key];
                    field.parentElement.appendChild(errorDiv);
                }
            }
        });
    }

    function clearFormErrors() {
        document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
        document.querySelectorAll('.form-error').forEach(el => el.remove());
    }

    /* ============================================================
     | ARCHIVE / RESTORE / DUPLICATE
     ============================================================ */

    window.archiveProduct = async function (id, name) {
        if (!confirm(`Archive produk "${name}"? Produk yang sudah diarsipkan tidak dapat digunakan untuk transaksi baru.`)) return;
        try {
            const res = await fetch(`${API_BASE}/products/${id}/archive`, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
            });
            const json = await res.json();
            if (json.success) {
                loadProducts(currentPage);
            } else {
                alert(json.message || 'Gagal mengarsipkan produk.');
            }
        } catch (err) {
            console.error('Archive failed:', err);
            alert('Gagal mengarsipkan produk.');
        }
    };

    window.restoreProduct = async function (id) {
        if (!confirm('Restore produk ini?')) return;
        try {
            const res = await fetch(`${API_BASE}/products/${id}/restore`, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
            });
            const json = await res.json();
            if (json.success) {
                loadProducts(currentPage);
            } else {
                alert(json.message || 'Gagal merestore produk.');
            }
        } catch (err) {
            console.error('Restore failed:', err);
            alert('Gagal merestore produk.');
        }
    };

    window.duplicateProduct = async function (id) {
        if (!confirm('Duplikat produk ini?')) return;
        try {
            const res = await fetch(`${API_BASE}/products/${id}/duplicate`, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
            });
            const json = await res.json();
            if (json.success) {
                loadProducts(currentPage);
            } else {
                alert(json.message || 'Gagal menduplikat produk.');
            }
        } catch (err) {
            console.error('Duplicate failed:', err);
            alert('Gagal menduplikat produk.');
        }
    };

    /* ============================================================
     | VIEW PRODUCT (Navigate to Detail)
     ============================================================ */

    window.viewProduct = function (id) {
        window.location.href = `/MRP/products/${id}`;
    };

    window.editProduct = editProduct;

    /* ============================================================
     | HELPER FUNCTIONS
     ============================================================ */

    function formatNum(n) {
        return Number(n || 0).toLocaleString('id-ID');
    }

    function capitalize(str) {
        return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
    }

    function escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function setText(id, text) {
        const el = document.getElementById(id);
        if (el) el.textContent = text;
    }

    /* ============================================================
     | INITIALIZE
     ============================================================ */

    loadProducts(1);
    loadReferenceData();
});
