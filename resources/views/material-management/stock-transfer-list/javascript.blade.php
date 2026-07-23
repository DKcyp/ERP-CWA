@push('after-style')
<style>
    #table-st thead th { font-weight: 600; }
    .badge { font-size: .75rem; }
    #table-items tbody tr { vertical-align: middle; }
</style>
@endpush

@push('after-script')
<script>
    const stTableUrl  = "{{ route('stock-transfer.table') }}";
    const stStoreUrl  = "{{ route('stock-transfer.store') }}";
    const stShowUrl   = "{{ route('stock-transfer.show', '__ID__') }}";
    const stUpdateUrl = "{{ route('stock-transfer.update', '__ID__') }}";
    const stDeleteUrl = "{{ route('stock-transfer.destroy', ['id' => '__ID__']) }}";
    const stStatusUrl = "{{ route('stock-transfer.status', ['id' => '__ID__']) }}";
    const csrfToken   = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

    const tableST = $('#table-st').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: stTableUrl,
            data: function(d) {
                d.filter_search = $('#filter-search').val();
                d.filter_status = $('#filter-status').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',     name: 'DT_RowIndex',     orderable: false, searchable: false, className: 'text-center' },
            { data: 'transfer_number', name: 'transfer_number' },
            { data: 'transfer_date_fmt', name: 'transfer_date', className: 'text-center' },
            { data: 'from_warehouse',  name: 'from_warehouse' },
            { data: 'to_warehouse',    name: 'to_warehouse' },
            { data: 'pic',             name: 'pic' },
            { data: 'total_items',     name: 'total_items',     className: 'text-center' },
            { data: 'total_qty',       name: 'total_qty',       className: 'text-center' },
            { data: 'status_badge',    name: 'status',          orderable: false, searchable: false, className: 'text-center' },
            { data: 'action',          name: 'action',          orderable: false, searchable: false, className: 'text-end' },
        ],
    });

    $('#filter-search').on('keyup', function () { tableST.ajax.reload(); });
    $('#filter-status').on('change', function () { tableST.ajax.reload(); });

    $('#btn-reset-filter').on('click', function () {
        $('#filter-search').val('');
        $('#filter-status').val('all');
        tableST.ajax.reload();
    });

    let itemIndex = 0;

    function addItemRow(data) {
        const tbody = $('#items-tbody');
        $('#row-empty').hide();

        const i = itemIndex++;
        const material = data?.material ?? '';
        const qty      = data?.qty ?? '';
        const unit     = data?.unit ?? '';
        const notes    = data?.notes ?? '';

        const row = `
            <tr>
                <td class="text-center item-no">${tbody.find('tr:visible').length + 1}</td>
                <td>
                    <input type="text" class="form-control form-control-sm item-material" name="items[${i}][material]"
                           value="${material}" placeholder="Nama material" maxlength="200">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm text-center item-qty" name="items[${i}][qty]"
                           value="${qty}" placeholder="0" min="0" step="any">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm item-unit" name="items[${i}][unit]"
                           value="${unit}" placeholder="Satuan" maxlength="50">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm item-notes" name="items[${i}][notes]"
                           value="${notes}" placeholder="Catatan item" maxlength="200">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-item">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.append(row);
        renumberItems();
    }

    function renumberItems() {
        $('#items-tbody tr:visible').each(function (idx) {
            $(this).find('.item-no').text(idx + 1);
        });
    }

    $('#btn-add-item').on('click', function () { addItemRow(); });

    $('#items-tbody').on('click', '.btn-remove-item', function () {
        $(this).closest('tr').remove();
        if ($('#items-tbody tr:visible').length === 0) $('#row-empty').show();
        renumberItems();
    });

    function resetItems() {
        itemIndex = 0;
        $('#items-tbody tr:not(#row-empty)').remove();
        $('#row-empty').show();
    }

    function populateItems(items) {
        resetItems();
        if (items && items.length) items.forEach(function (item) { addItemRow(item); });
    }

    function collectItems() {
        const items = [];
        $('#items-tbody tr:visible').not('#row-empty').each(function () {
            const material = $(this).find('.item-material').val() || '';
            const qty      = parseFloat($(this).find('.item-qty').val()) || 0;
            const unit     = $(this).find('.item-unit').val() || '';
            const notes    = $(this).find('.item-notes').val() || '';
            if (material) items.push({ material, qty, unit, notes });
        });
        return items;
    }

    const modalST   = $('#modal-st');
    const formST    = $('#form-st');
    const idInputST = $('#st_id');

    function resetFormST() {
        formST[0].reset();
        idInputST.val('');
        $('#st_status').val('PREPARATION');
        formST.find('.is-invalid').removeClass('is-invalid');
        formST.find('.invalid-feedback').remove();
        modalST.find('.modal-title').text('Tambah Stock Transfer');
        resetItems();
    }

    $('#btn-add-st').on('click', function () { resetFormST(); modalST.modal('show'); });
    modalST.on('hidden.bs.modal', function () { resetFormST(); });

    function handleErrors(errors) {
        Object.entries(errors).forEach(([key, messages]) => {
            const input = formST.find('[name="' + key + '"]').first();
            if (!input.length) return;
            const container = input.closest('.col-md-4, .col-md-6, .col-12');
            input.addClass('is-invalid');
            container.append('<div class="invalid-feedback">' + messages[0] + '</div>');
        });
    }

    window.onSaveST = () => {
        const id  = idInputST.val();
        const url = id ? stUpdateUrl.replace('__ID__', id) : stStoreUrl;

        const formData = formST.serializeArray();
        formData.push({ name: 'items', value: JSON.stringify(collectItems()) });
        if (id) formData.push({ name: '_method', value: 'PUT' });

        $.ajax({
            url, type: 'POST', data: formData, dataType: 'json',
            success: function (data) {
                Swal.fire({ title: 'Sukses!', text: data.message, icon: 'success', confirmButtonText: 'OK' })
                    .then(() => { resetFormST(); modalST.modal('hide'); tableST.ajax.reload(null, false); });
            },
            error: function (xhr) {
                const res = xhr.responseJSON || {};
                if (xhr.status === 422 && res.errors) { handleErrors(res.errors); }
                else { Swal.fire({ icon: 'error', title: 'Error', text: res.message || 'Terjadi kesalahan sistem.' }); }
            }
        });
    };

    $('#table-st').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        resetFormST();
        $.get(stShowUrl.replace('__ID__', id))
            .done(function (response) {
                const d = response.data || {};
                idInputST.val(d.id);
                $('#st_number').val(d.transfer_number ?? '');
                $('#st_date').val(d.transfer_date ?? '');
                $('#st_from').val(d.from_warehouse ?? '');
                $('#st_to').val(d.to_warehouse ?? '');
                $('#st_pic').val(d.pic ?? '');
                $('#st_user_id').val(d.user_id ?? '');
                $('#st_status').val(d.status ?? 'PREPARATION');
                $('#st_reason').val(d.reason ?? '');
                populateItems(d.items ?? []);
                modalST.find('.modal-title').text('Edit Stock Transfer');
                modalST.modal('show');
            })
            .fail(function () { Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat mengambil data.' }); });
    });

    $('#table-st').on('click', '.btn-approve', function () {
        const id = $(this).data('id');
        const status = $(this).data('status');
        const label = status === 'SHIPMENT' ? 'Shipment' : 'Transfer';
        Swal.fire({
            title: 'Yakin akan mengubah ke ' + label + '?', icon: 'question', showCancelButton: true,
            confirmButtonColor: '#198754', confirmButtonText: 'Ya, ' + label, cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: stStatusUrl.replace('__ID__', id), method: 'POST', data: { _method: 'PUT', status: status },
                success: function (response) { Swal.fire({ icon: 'success', title: response.message, timer: 1500, showConfirmButton: false }); tableST.ajax.reload(null, false); },
                error: function (xhr) { Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan.' }); }
            });
        });
    });

    $('#table-st').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus transfer ini?', text: 'Data yang dihapus tidak dapat dikembalikan.', icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: stDeleteUrl.replace('__ID__', id), method: 'POST', data: { _method: 'DELETE' },
                success: function (response) { Swal.fire({ icon: 'success', title: response.message || 'Data dihapus', timer: 1500, showConfirmButton: false }); tableST.ajax.reload(null, false); },
                error: function (xhr) { const res = xhr.responseJSON || {}; Swal.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Tidak dapat menghapus data.' }); }
            });
        });
    });
</script>
@endpush
