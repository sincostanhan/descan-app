<script>
    document.addEventListener('DOMContentLoaded', function () {
        renderPreview(); // render awal (penting untuk halaman Edit yang sudah ada data)

        document.body.addEventListener('click', function (e) {
            const addChildBtn = e.target.closest('.btn-add-child');
            const addSiblingBtn = e.target.closest('.btn-add-sibling');
            const removeBtn = e.target.closest('.btn-remove-node');

            if (addChildBtn) {
                const node = addChildBtn.closest('.header-node');
                node.querySelector(':scope > .node-children').insertAdjacentHTML('beforeend', nodeTemplate(node.dataset.axis));
                renderPreview();
            }

            if (addSiblingBtn) {
                const node = addSiblingBtn.closest('.header-node');
                node.insertAdjacentHTML('afterend', nodeTemplate(node.dataset.axis));
                renderPreview();
            }

            if (removeBtn) {
                const node = removeBtn.closest('.header-node');
                if (confirm('Hapus baris/kolom ini beserta seluruh sub-levelnya? (Data Kelurahan yang sudah pernah mengisi sel ini tetap aman tersimpan, hanya tidak tampil lagi di form.)')) {
                    node.remove();
                    renderPreview();
                }
            }
        });

        // Preview ikut update saat label/rt_value/data_type diketik/diganti
        document.body.addEventListener('input', function (e) {
            if (e.target.matches('.node-label, .node-rt-value')) renderPreview();
        });
        document.body.addEventListener('change', function (e) {
            if (e.target.matches('.node-data-type')) renderPreview();
        });
    });

    function nodeTemplate(axis) {
        const secondField = axis === 'row'
            ? `<input type="text" class="input input-sm node-rt-value" placeholder="Nilai RT (opsional, untuk Dashboard Peta)">`
            : `<select class="select select-sm node-data-type">
                    <option value="numeric">Hanya Angka</option>
                    <option value="text">Hanya Teks</option>
                    <option value="both">Angka & Teks</option>
               </select>`;

        return `
        <div class="header-node" data-axis="${axis}">
            <div class="node-row flex gap-2 items-start bg-base-200/40 p-3 rounded-box">
                <input type="hidden" class="node-id" value="">
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-2">
                    <input type="text" class="input input-sm node-label" placeholder="Label (misal: ${axis === 'row' ? 'RT 01' : 'Laki-laki'})">
                    ${secondField}
                </div>
                <div class="flex gap-1 shrink-0">
                    <button type="button" class="btn btn-xs btn-outline btn-add-child" title="Tambah sub-level">Sub</button>
                    <button type="button" class="btn btn-xs btn-outline btn-add-sibling" title="Tambah sejajar">+</button>
                    <button type="button" class="btn btn-xs btn-soft btn-error btn-remove-node" title="Hapus">✕</button>
                </div>
            </div>
            <div class="node-children ml-6 mt-2 space-y-2 border-l-2 border-base-300 pl-4"></div>
        </div>`;
    }

    function addRootNode(containerId, axis) {
        document.getElementById(containerId).insertAdjacentHTML('beforeend', nodeTemplate(axis));
        renderPreview();
    }

    /**
     * Serialisasi rekursif DOM tree -> JSON, dikirim sebagai row_headers/column_headers
     * ke CreateStatisticTemplate & UpdateStatisticTemplate Action. Juga dipakai ulang oleh renderPreview().
     */
    function serializeNode(nodeEl, axis) {
        const row = nodeEl.querySelector(':scope > .node-row');
        const label = row.querySelector('.node-label').value.trim();
        const idInput = row.querySelector('.node-id');
        const id = idInput && idInput.value ? parseInt(idInput.value) : null;

        const childrenContainer = nodeEl.querySelector(':scope > .node-children');
        const children = Array.from(childrenContainer.children).map(child => serializeNode(child, axis));

        const obj = { label };
        if (id) obj.id = id;

        if (axis === 'row') {
            const rt = row.querySelector('.node-rt-value')?.value.trim();
            obj.rt_value = rt ? rt : null;
        }

        if (axis === 'column' && children.length === 0) {
            obj.data_type = row.querySelector('.node-data-type')?.value || 'text';
        }

        if (children.length > 0) {
            obj.children = children;
        }

        return obj;
    }

    function serializeAxis(containerId, axis) {
        return Array.from(document.getElementById(containerId).children).map(node => serializeNode(node, axis));
    }

    function prepareSubmit() {
        // const rowData = serializeAxis('row-headers-container', 'row');
        const rowSource = document.querySelector('[name=row_source]:checked').value;
        const rowData = rowSource === 'manual' ? serializeAxis('row-headers-container', 'row') : [];
        const columnData = serializeAxis('column-headers-container', 'column');

        // if (rowData.length === 0 || columnData.length === 0) {
            // alert('Minimal harus ada 1 struktur Baris dan 1 struktur Kolom.');
        if (rowSource === 'manual' && rowData.length === 0) {
            alert('Minimal harus ada 1 struktur Baris (atau pilih mode "Otomatis dari RT/RW").');
            return false;
        }
        if (columnData.length === 0) {
            alert('Minimal harus ada 1 struktur Kolom.');
            return false;
        }

        document.getElementById('row_headers_input').value = JSON.stringify(rowData);
        document.getElementById('column_headers_input').value = JSON.stringify(columnData);

        return true;
    }

    // ================= PREVIEW TABEL (rowspan/colspan) =================
    // Logika di bawah ini sengaja dibuat MIRROR dari getLeafSpanAttribute() & maxHeaderDepth()
    // pada model StatisticTemplateHeader/StatisticTemplate (backend), supaya preview di sini
    // konsisten dengan tampilan spreadsheet editor Kelurahan nanti.

    function computeSpans(nodes) {
        function walk(node, depth) {
            node._depth = depth;
            if (!node.children || node.children.length === 0) {
                node._isLeaf = true;
                node._leafSpan = 1;
                return 1;
            }
            node._isLeaf = false;
            node._leafSpan = node.children.reduce((sum, c) => sum + walk(c, depth + 1), 0);
            return node._leafSpan;
        }
        nodes.forEach(n => walk(n, 0));
    }

    function maxDepthOf(nodes) {
        let max = 0;
        function walk(node) {
            if (node._isLeaf) { max = Math.max(max, node._depth); }
            else { node.children.forEach(walk); }
        }
        nodes.forEach(walk);
        return max + 1;
    }

    function buildColumnRows(nodes, maxDepth) {
        const rows = Array.from({ length: maxDepth }, () => []);
        function walk(node) {
            rows[node._depth].push(node);
            if (!node._isLeaf) node.children.forEach(walk);
        }
        nodes.forEach(walk);
        return rows;
    }

    function buildRowHeaderMatrix(nodes, maxDepth) {
        const leaves = [];
        const matrix = [];
        function walk(node, depth) {
            if (node._isLeaf) {
                const idx = leaves.length;
                leaves.push(node);
                matrix[idx] = matrix[idx] || [];
                matrix[idx][depth] = { node, rowspan: 1, colspan: maxDepth - depth };
                return;
            }
            const startIdx = leaves.length;
            node.children.forEach(c => walk(c, depth + 1));
            matrix[startIdx] = matrix[startIdx] || [];
            matrix[startIdx][depth] = { node, rowspan: node._leafSpan, colspan: 1 };
        }
        nodes.forEach(n => walk(n, 0));
        return { leaves, matrix };
    }

    function renderPreview() {
        const wrapper = document.getElementById('template-preview-wrapper');
        if (!wrapper) return; // safety, kalau partial ini dipakai di halaman tanpa preview

        const rowSourceInput = document.querySelector('[name=row_source]:checked');
        const rowSource = rowSourceInput ? rowSourceInput.value : 'manual';

        // const rowData = serializeAxis('row-headers-container', 'row');
        const rowData = rowSource === 'rt_rw'
            ? dummyRtRowPreview() // baris CONTOH saja, tidak dikirim ke server
            : serializeAxis('row-headers-container', 'row');
        const columnData = serializeAxis('column-headers-container', 'column');

        if (rowData.length === 0 || columnData.length === 0) {
            // wrapper.innerHTML = '<p class="text-sm text-base-content/50 italic">Tambahkan minimal 1 struktur Baris dan 1 struktur Kolom untuk melihat preview.</p>';
            const rowHint = rowSource === 'rt_rw'
                ? '' // tidak mungkin kosong, dummyRtRowPreview() selalu isi 2 baris
                : 'Tambahkan minimal 1 struktur Baris dan ';
            wrapper.innerHTML = `<p class="text-sm text-base-content/50 italic">${rowHint}Tambahkan minimal 1 struktur Kolom untuk melihat preview.</p>`;
            return;
        }

        if (rowSource === 'rt_rw') {
            wrapper.insertAdjacentHTML('afterbegin', '');
        }

        computeSpans(rowData);
        computeSpans(columnData);
        const maxRowDepth = maxDepthOf(rowData);
        const maxColDepth = maxDepthOf(columnData);

        const colRows = buildColumnRows(columnData, maxColDepth);
        const { leaves: rowLeaves, matrix } = buildRowHeaderMatrix(rowData, maxRowDepth);

        const columnLeaves = [];
        (function collectLeaves(nodes) {
            nodes.forEach(n => n._isLeaf ? columnLeaves.push(n) : collectLeaves(n.children));
        })(columnData);

        let thead = '<thead>';
        for (let d = 0; d < maxColDepth; d++) {
            thead += '<tr>';
            if (d === 0) {
                thead += `<th rowspan="${maxColDepth}" colspan="${maxRowDepth}" class="bg-base-200"></th>`;
            }
            colRows[d].forEach(node => {
                const rowspan = node._isLeaf ? (maxColDepth - node._depth) : 1;
                thead += `<th colspan="${node._leafSpan}" rowspan="${rowspan}" class="bg-base-200 text-center whitespace-nowrap">${escapeHtml(node.label) || '<span class="opacity-40">(kosong)</span>'}</th>`;
            });
            thead += '</tr>';
        }
        thead += '</thead>';

        let tbody = '<tbody>';
        rowLeaves.forEach((leaf, i) => {
            tbody += '<tr>';
            for (let d = 0; d < maxRowDepth; d++) {
                const cell = matrix[i] && matrix[i][d];
                if (cell) {
                    tbody += `<th rowspan="${cell.rowspan}" colspan="${cell.colspan}" class="bg-base-100 text-left whitespace-nowrap">${escapeHtml(cell.node.label) || '<span class="opacity-40">(kosong)</span>'}</th>`;
                }
            }
            columnLeaves.forEach(() => {
                tbody += '<td class="text-center text-base-content/30 text-xs">—</td>';
            });
            tbody += '</tr>';
        });
        tbody += '</tbody>';

        wrapper.innerHTML = `<div class="overflow-x-auto"><table class="table table-xs border border-base-300">${thead}${tbody}</table></div>`;

         (rowSource === 'rt_rw') {
            wrapper.insertAdjacentHTML('beforeend', `
                <p class="text-xs text-warning mt-2">
                    ⚠️ Baris di atas hanya CONTOH ilustrasi. Jumlah & nomor RT/RW sesungguhnya akan
                    mengikuti data masing-masing Kelurahan secara otomatis, bisa berbeda-beda tiap Kelurahan.
                </p>`);
        }
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str || '';
        return div.innerHTML;
    }

    function toggleRowSourceMode(mode) {
        const panel = document.getElementById('row-headers-panel');
        if (mode === 'rt_rw') {
            panel.classList.add('hidden');
        } else {
            panel.classList.remove('hidden');
        }
        renderPreview();
    }

    function dummyRtRowPreview() {
        // Hanya untuk PREVIEW visual di halaman BPS. Baris asli (jumlah & nomor RT/RW sesungguhnya)
        // baru dibuat oleh GenerateRtRowsForVillage saat Kelurahan membuka template ini.
        return [
            { label: 'RT 001 RW 001', _children: [] },
            { label: 'RT 002 RW 001', _children: [] },
            { label: '...', _children: [] },
        ];
    }
</script>