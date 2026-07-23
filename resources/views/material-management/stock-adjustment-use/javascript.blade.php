@push('after-style')
<style>
    #table-sau thead th { font-weight: 600; }
    .badge { font-size: .75rem; }
    #table-items tbody tr { vertical-align: middle; }
</style>
@endpush

@push('after-script')
<script>
    const sauTableUrl  = "{{ route('stock-adjustment-use.table') }}";
    const sauStoreUrl  = "{{ route('stock-adjustment-use.store') }}";
    const sauShowUrl   = "{{ route('stock-adjustment-use.show', '__ID__') }}";
    const sauUpdateUrl = "{{ route('stock-adjustment-use.update', '__ID__') }}";
    const sauDeleteUrl = "{{ route('stock-adjustment-use.destroy', ['id' => '__ID__']) }}";
    const sauStatusUrl = "{{ route('stock-adjustment-use.status', ['id' => '__ID__']) }}";
    const csrfToken    = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

    const tableSAU = $('#table-sau').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: sauTableUrl,
            data: function(d) {
                d.filter_search = $('#filter-search').val();
                d.filter_status = $('#filter-status').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',         name: 'DT_RowIndex',      orderable: false, searchable: false, className: 'text-center' },
            { data: 'adjustment_number',   name: 'adjustment_number' },
            { data: 'adjustment_date_fmt', name: 'adjustment_date',  className: 'text-center' },
            { data: 'warehouse',           name: 'warehouse' },
            { data: 'total_items',         name: 'total_items',      className: 'text-center' },
            { data: 'total_cost_diff',     name: 'total_cost_diff',  className: 'text-end' },
            { data: 'reason',              name: 'reason' },
            { data: 'status_badge',        name: 'status',           orderable: false, searchable: false, className: 'text-center' },
            { data: 'action',              name: 'action',           orderable: false, searchable: false, className: 'text-end' },
        ],
    });

    $('#filter-search').on('keyup', function () { tableSAU.ajax.reload(); });
    $('#filter-status').on('change', function () { tableSAU.ajax.reload(); });

    $('#btn-reset-filter').on('click', function () {
        $('#filter-search').val('');
        $('#filter-status').val('all');
        tableSAU.ajax.reload();
    });

    // ─── MODAL ──────────────────────────────────
    const modalSAU   = $('#modal-sau');
    const formSAU    = $('#form-sau');
    const idInputSAU = $('#sau_id');

    function resetFormSAU() {
        formSAU[0].reset();
        idInputSAU.val('');
        formSAU.find('.is-invalid').removeClass('is-invalid');
        formSAU.find('.invalid-feedback').remove();
        modalSAU.find('.modal-title').text('Tambah Adjustment (Internal Use)');
        $('#items-tbody').html('<tr class="empty-row"><td colspan="8" class="text-center text-muted py-3"><i class="bi bi-inbox me-1"></i> Belum ada item. Klik (+) untuk menambah.</td></tr>');
        updateTotal();
    }

    $('#btn-add-sau').on('click', function () { resetFormSAU(); modalSAU.modal('show'); });
    modalSAU.on('hidden.bs.modal', function () { resetFormSAU(); });

    function handleErrors(errors) {
        Object.entries(errors).forEach(([key, messages]) => {
            const input = formSAU.find('[name="' + key + '"]').first();
            if (!input.length) return;
            const container = input.closest('.col-md-4, .col-md-6, .col-12');
            input.addClass('is-invalid');
            container.append('<div class="invalid-feedback">' + messages[0] + '</div>');
        });
    }

    // ─── ITEMS TABLE ─────────────────────────────
    function updateTotal() {
        let total = 0;
        $('#items-tbody tr').not('.empty-row').each(function () {
            const sysQty = parseFloat($(this).find('.item-sys-qty').val()) || 0;
            const physQty = parseFloat($(this).find('.item-phys-qty').val()) || 0;
            const cost = parseInt($(this).find('.item-cost').val()) || 0;
            const diff = sysQty - physQty;
            const costDiff = diff * cost;
            $(this).find('.item-qty-diff').text(diff);
            $(this).find('.item-cost-diff').text('Rp ' + costDiff.toLocaleString('id-ID'));
            total += costDiff;
        });
        $('#total-cost-diff').text('Rp ' + total.toLocaleString('id-ID'));
    }

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
        updateTotal();
        $(row).find('.item-sys-qty, .item-phys-qty, .item-cost').on('input', updateTotal);
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
        updateTotal();
    });

    // ─── SAVE ────────────────────────────────────
    window.onSaveSAU = () => {
        const id = idInputSAU.val();
        const url = id ? sauUpdateUrl.replace('__ID__', id) : sauStoreUrl;

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

        const formData = formSAU.serializeArray();
        formData.push({ name: 'items', value: JSON.stringify(items) });
        if (id) formData.push({ name: '_method', value: 'PUT' });

        $.ajax({
            url, type: 'POST', data: formData, dataType: 'json',
            success: function (data) {
                Swal.fire({ title: 'Sukses!', text: data.message, icon: 'success', confirmButtonText: 'OK' })
                    .then(() => { resetFormSAU(); modalSAU.modal('hide'); tableSAU.ajax.reload(null, false); });
            },
            error: function (xhr) {
                const res = xhr.responseJSON || {};
                if (xhr.status === 422 && res.errors) { handleErrors(res.errors); }
                else { Swal.fire({ icon: 'error', title: 'Error', text: res.message || 'Terjadi kesalahan sistem.' }); }
            }
        });
    };

    // ─── EDIT ────────────────────────────────────
    $('#table-sau').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        resetFormSAU();
        $.get(sauShowUrl.replace('__ID__', id)).done(function (response) {
            const d = response.data || {};
            idInputSAU.val(d.id);
            $('#sau_number').val(d.adjustment_number ?? '');
            $('#sau_date').val(d.adjustment_date ?? '');
            $('#sau_warehouse').val(d.warehouse ?? '');
            $('#sau_reason').val(d.reason ?? '');
            $('#sau_status').val(d.status ?? 'DRAFT');

            (d.items || []).forEach(function (item) { addItemRow(item); });

            modalSAU.find('.modal-title').text('Edit Adjustment (Internal Use)');
            modalSAU.modal('show');
        }).fail(function () { Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat mengambil data.' }); });
    });

    // ─── APPROVE ──────────────────────────────────
    function updateStatusSAU(id, status) {
        const label = status === 'APPROVED' ? 'approve' : 'complete';
        Swal.fire({
            title: 'Yakin akan ' + label + ' adjustment ini?', icon: 'question', showCancelButton: true,
            confirmButtonColor: status === 'APPROVED' ? '#198754' : '#0d6efd',
            confirmButtonText: 'Ya, ' + label, cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: sauStatusUrl.replace('__ID__', id), method: 'POST', data: { _method: 'PUT', status: status },
                success: function (response) { Swal.fire({ icon: 'success', title: response.message, timer: 1500, showConfirmButton: false }); tableSAU.ajax.reload(null, false); },
                error: function (xhr) { Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan.' }); }
            });
        });
    }

    $('#table-sau').on('click', '.btn-approve', function () { updateStatusSAU($(this).data('id'), 'APPROVED'); });

    // ─── DELETE ──────────────────────────────────
    $('#table-sau').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus adjustment ini?', text: 'Data yang dihapus tidak dapat dikembalikan.', icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: sauDeleteUrl.replace('__ID__', id), method: 'POST', data: { _method: 'DELETE' },
                success: function (response) { Swal.fire({ icon: 'success', title: response.message || 'Data dihapus', timer: 1500, showConfirmButton: false }); tableSAU.ajax.reload(null, false); },
                error: function (xhr) { const res = xhr.responseJSON || {}; Swal.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Tidak dapat menghapus data.' }); }
            });
        });
    });
</script>
@endpush
