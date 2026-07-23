@push('after-style')
<style>
    #table-sal thead th { font-weight: 600; font-size: .78rem; }
    .badge { font-size: .75rem; }
    #table-items tbody tr { vertical-align: middle; }
</style>
@endpush

@push('after-script')
<script>
    const salTableUrl  = "{{ route('stock-adjustment-list.table') }}";
    const salStoreUrl  = "{{ route('stock-adjustment-list.store') }}";
    const salShowUrl   = "{{ route('stock-adjustment-list.show', '__ID__') }}";
    const salUpdateUrl = "{{ route('stock-adjustment-list.update', '__ID__') }}";
    const salDeleteUrl = "{{ route('stock-adjustment-list.destroy', ['id' => '__ID__']) }}";
    const salStatusUrl = "{{ route('stock-adjustment-list.status', ['id' => '__ID__']) }}";
    const csrfToken    = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

    const tableSAL = $('#table-sal').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: {
            url: salTableUrl,
            data: function(d) {
                d.filter_search = $('#filter-search').val();
                d.filter_status = $('#filter-status').val();
                d.type  = $('#adjustmentTabs .nav-link.active').data('type') || 'all';
            }
        },
        columns: [
            { data: 'DT_RowIndex',         name: 'DT_RowIndex',      orderable: false, searchable: false, className: 'text-center' },
            { data: 'adjustment_number',   name: 'adjustment_number' },
            { data: 'adjustment_date_fmt', name: 'adjustment_date',  className: 'text-center' },
            { data: 'warehouse',           name: 'warehouse' },
            { data: 'department',          name: 'department' },
            { data: 'type_badge',          name: 'adjustment_type',  orderable: false, searchable: false, className: 'text-center' },
            { data: 'use_for',             name: 'use_for' },
            { data: 'transfer_to_ta',      name: 'transfer_to_ta' },
            { data: 'product_group',       name: 'product_group' },
            { data: 'pic',                 name: 'pic' },
            { data: 'reason',              name: 'reason' },
            { data: 'status_badge',        name: 'status',           orderable: false, searchable: false, className: 'text-center' },
            { data: 'user_id',             name: 'user_id' },
            { data: 'action',              name: 'action',           orderable: false, searchable: false, className: 'text-end' },
        ],
    });

    $('#filter-search').on('keyup', function () { tableSAL.ajax.reload(); });
    $('#filter-status').on('change', function () { tableSAL.ajax.reload(); });

    $('#btn-reset-filter').on('click', function () {
        $('#filter-search').val('');
        $('#filter-status').val('all');
        $('#adjustmentTabs .nav-link').removeClass('active');
        $('#tab-all').addClass('active');
        tableSAL.ajax.reload();
    });

    // ─── TABS ────────────────────────────────────
    $('#adjustmentTabs .nav-link').on('click', function () {
        $('#adjustmentTabs .nav-link').removeClass('active');
        $(this).addClass('active');
        tableSAL.ajax.reload();
    });

    // ─── MODAL ──────────────────────────────────
    const modalSAL   = $('#modal-sal');
    const formSAL    = $('#form-sal');
    const idInputSAL = $('#sal_id');

    function resetFormSAL() {
        formSAL[0].reset();
        idInputSAL.val('');
        formSAL.find('.is-invalid').removeClass('is-invalid');
        formSAL.find('.invalid-feedback').remove();
        modalSAL.find('.modal-title').text('Tambah Stock Adjustment');
        $('#items-tbody').html('<tr class="empty-row"><td colspan="8" class="text-center text-muted py-3"><i class="bi bi-inbox me-1"></i> Belum ada item. Klik (+) untuk menambah.</td></tr>');
    }

    $('#btn-add-sal').on('click', function () { resetFormSAL(); modalSAL.modal('show'); });
    modalSAL.on('hidden.bs.modal', function () { resetFormSAL(); });

    function handleErrors(errors) {
        Object.entries(errors).forEach(([key, messages]) => {
            const input = formSAL.find('[name="' + key + '"]').first();
            if (!input.length) return;
            const container = input.closest('.col-md-4, .col-md-6, .col-12');
            input.addClass('is-invalid');
            container.append('<div class="invalid-feedback">' + messages[0] + '</div>');
        });
    }

    // ─── ITEMS TABLE ─────────────────────────────
    function addItemRow(data) {
        data = data || {};
        const no = $('#items-tbody tr').not('.empty-row').length + 1;
        $('#items-tbody .empty-row').remove();
        const row = '<tr>' +
            '<td class="text-center item-no">' + no + '</td>' +
            '<td><input type="text" class="form-control form-control-sm item-material" value="' + (data.material || '') + '" placeholder="Nama material" required></td>' +
            '<td><input type="number" class="form-control form-control-sm item-sys-qty text-center" value="' + (data.system_qty || '') + '" min="0" step="any" required></td>' +
            '<td><input type="number" class="form-control form-control-sm item-phys-qty text-center" value="' + (data.physical_qty || '') + '" min="0" step="any" required></td>' +
            '<td class="text-center fw-semibold item-qty-diff">0</td>' +
            '<td><input type="number" class="form-control form-control-sm item-cost text-end" value="' + (data.cost_per_unit || '') + '" min="0" required></td>' +
            '<td class="text-end fw-semibold item-cost-diff">Rp 0</td>' +
            '<td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-item"><i class="bi bi-x-lg"></i></button></td>' +
        '</tr>';
        $('#items-tbody').append(row);
        $(row).find('.item-sys-qty, .item-phys-qty, .item-cost').on('input', function () { updateRow($(this).closest('tr')); });
    }

    function updateRow(row) {
        const sysQty = parseFloat(row.find('.item-sys-qty').val()) || 0;
        const physQty = parseFloat(row.find('.item-phys-qty').val()) || 0;
        const cost = parseInt(row.find('.item-cost').val()) || 0;
        const diff = sysQty - physQty;
        const costDiff = diff * cost;
        row.find('.item-qty-diff').text(diff);
        row.find('.item-cost-diff').text('Rp ' + costDiff.toLocaleString('id-ID'));
    }

    $('#btn-add-item').on('click', function () { addItemRow(); });

    $(document).on('click', '.btn-remove-item', function () {
        $(this).closest('tr').remove();
        $('#items-tbody tr').not('.empty-row').each(function (i) {
            $(this).find('.item-no').text(i + 1);
        });
        if (!$('#items-tbody tr').not('.empty-row').length) {
            $('#items-tbody').html('<tr class="empty-row"><td colspan="8" class="text-center text-muted py-3"><i class="bi bi-inbox me-1"></i> Belum ada item. Klik (+) untuk menambah.</td></tr>');
        }
    });

    // ─── SAVE ────────────────────────────────────
    window.onSaveSAL = () => {
        const id = idInputSAL.val();
        const url = id ? salUpdateUrl.replace('__ID__', id) : salStoreUrl;

        const items = [];
        $('#items-tbody tr').not('.empty-row').each(function () {
            const material = $(this).find('.item-material').val() || '';
            const systemQty = parseFloat($(this).find('.item-sys-qty').val()) || 0;
            const physicalQty = parseFloat($(this).find('.item-phys-qty').val()) || 0;
            const costPerUnit = parseInt($(this).find('.item-cost').val()) || 0;
            if (material && systemQty >= 0 && physicalQty >= 0) {
                items.push({ material, system_qty: systemQty, physical_qty: physicalQty, cost_per_unit: costPerUnit });
            }
        });

        const formData = formSAL.serializeArray();
        formData.push({ name: 'items', value: JSON.stringify(items) });
        if (id) formData.push({ name: '_method', value: 'PUT' });

        $.ajax({
            url, type: 'POST', data: formData, dataType: 'json',
            success: function (data) {
                Swal.fire({ title: 'Sukses!', text: data.message, icon: 'success', confirmButtonText: 'OK' })
                    .then(() => { resetFormSAL(); modalSAL.modal('hide'); tableSAL.ajax.reload(null, false); });
            },
            error: function (xhr) {
                const res = xhr.responseJSON || {};
                if (xhr.status === 422 && res.errors) { handleErrors(res.errors); }
                else { Swal.fire({ icon: 'error', title: 'Error', text: res.message || 'Terjadi kesalahan sistem.' }); }
            }
        });
    };

    // ─── EDIT ────────────────────────────────────
    $('#table-sal').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        resetFormSAL();
        $.get(salShowUrl.replace('__ID__', id)).done(function (response) {
            const d = response.data || {};
            idInputSAL.val(d.id);
            $('#sal_number').val(d.adjustment_number ?? '');
            $('#sal_date').val(d.adjustment_date ?? '');
            $('#sal_warehouse').val(d.warehouse ?? '');
            $('#sal_department').val(d.department ?? '');
            $('#sal_type').val(d.adjustment_type ?? 'STANDARD');
            $('#sal_use_for').val(d.use_for ?? '');
            $('#sal_transfer_ta').val(d.transfer_to_ta ?? '');
            $('#sal_product_group').val(d.product_group ?? '');
            $('#sal_pic').val(d.pic ?? '');
            $('#sal_user_id').val(d.user_id ?? '');
            $('#sal_reason').val(d.reason ?? '');
            $('#sal_status').val(d.status ?? 'DRAFT');

            (d.items || []).forEach(function (item) { addItemRow(item); });

            modalSAL.find('.modal-title').text('Edit Stock Adjustment');
            modalSAL.modal('show');
        }).fail(function () { Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat mengambil data.' }); });
    });

    // ─── APPROVE ──────────────────────────────────
    function updateStatusSAL(id, status) {
        const label = status === 'APPROVED' ? 'approve' : 'complete';
        Swal.fire({
            title: 'Yakin akan ' + label + ' adjustment ini?', icon: 'question', showCancelButton: true,
            confirmButtonColor: status === 'APPROVED' ? '#198754' : '#0d6efd',
            confirmButtonText: 'Ya, ' + label, cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: salStatusUrl.replace('__ID__', id), method: 'POST', data: { _method: 'PUT', status: status },
                success: function (response) { Swal.fire({ icon: 'success', title: response.message, timer: 1500, showConfirmButton: false }); tableSAL.ajax.reload(null, false); },
                error: function (xhr) { Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan.' }); }
            });
        });
    }

    $('#table-sal').on('click', '.btn-approve', function () { updateStatusSAL($(this).data('id'), 'APPROVED'); });

    // ─── DELETE ──────────────────────────────────
    $('#table-sal').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus adjustment ini?', text: 'Data yang dihapus tidak dapat dikembalikan.', icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: salDeleteUrl.replace('__ID__', id), method: 'POST', data: { _method: 'DELETE' },
                success: function (response) { Swal.fire({ icon: 'success', title: response.message || 'Data dihapus', timer: 1500, showConfirmButton: false }); tableSAL.ajax.reload(null, false); },
                error: function (xhr) { const res = xhr.responseJSON || {}; Swal.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Tidak dapat menghapus data.' }); }
            });
        });
    });
</script>
@endpush
