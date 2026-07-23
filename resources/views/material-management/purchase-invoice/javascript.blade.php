@push('after-style')
<style>
    #table-inv thead th { font-weight: 600; }
    .badge { font-size: .75rem; }
    #table-items tbody tr { vertical-align: middle; }
</style>
@endpush

@push('after-script')
<script>
    const invTableUrl  = "{{ route('purchase-invoice.table') }}";
    const invStoreUrl  = "{{ route('purchase-invoice.store') }}";
    const invShowUrl   = "{{ route('purchase-invoice.show', '__ID__') }}";
    const invUpdateUrl = "{{ route('purchase-invoice.update', '__ID__') }}";
    const invDeleteUrl = "{{ route('purchase-invoice.destroy', ['id' => '__ID__']) }}";
    const invStatusUrl = "{{ route('purchase-invoice.status', ['id' => '__ID__']) }}";
    const csrfToken    = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

    // ─── DATATABLE ─────────────────────────────────
    const tableINV = $('#table-inv').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: invTableUrl,
            data: function(d) {
                d.filter_search = $('#filter-search').val();
                d.filter_status = $('#filter-status').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',     name: 'DT_RowIndex',     orderable: false, searchable: false, className: 'text-center' },
            { data: 'invoice_number',  name: 'invoice_number' },
            { data: 'invoice_date_fmt', name: 'invoice_date',   className: 'text-center' },
            { data: 'po_number',       name: 'po_number' },
            { data: 'supplier_name',   name: 'supplier_name' },
            { data: 'total_items',     name: 'total_items',     className: 'text-center' },
            { data: 'total_amount',    name: 'total_amount',    className: 'text-end' },
            { data: 'status_badge',    name: 'status',          orderable: false, searchable: false, className: 'text-center' },
            { data: 'action',          name: 'action',          orderable: false, searchable: false, className: 'text-end' },
        ],
    });

    // ─── FILTER ────────────────────────────────────
    $('#filter-search').on('keyup', function () { tableINV.ajax.reload(); });
    $('#filter-status').on('change', function () { tableINV.ajax.reload(); });

    $('#btn-reset-filter').on('click', function () {
        $('#filter-search').val('');
        $('#filter-status').val('all');
        tableINV.ajax.reload();
    });

    // ─── DYNAMIC ITEMS ─────────────────────────────
    let itemIndex = 0;

    function addItemRow(data) {
        const tbody = $('#items-tbody');
        $('#row-empty').hide();

        const i = itemIndex++;
        const material = data?.material ?? '';
        const qty      = data?.qty ?? '';
        const unit     = data?.unit ?? '';
        const price    = data?.price ?? '';

        const row = `
            <tr>
                <td class="text-center item-no">${tbody.find('tr:visible').length + 1}</td>
                <td>
                    <input type="text" class="form-control form-control-sm item-material" name="items[${i}][material]"
                           value="${material}" placeholder="Nama material" maxlength="200">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm text-center item-qty" name="items[${i}][qty]"
                           value="${qty}" placeholder="0" min="0" step="1">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm item-unit" name="items[${i}][unit]"
                           value="${unit}" placeholder="Cth: Pcs" maxlength="50">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm text-end item-price" name="items[${i}][price]"
                           value="${price}" placeholder="0" min="0" step="1">
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
            const qty      = parseInt($(this).find('.item-qty').val()) || 0;
            const unit     = $(this).find('.item-unit').val() || '';
            const price    = parseInt($(this).find('.item-price').val()) || 0;
            if (material) items.push({ material, qty, unit, price });
        });
        return items;
    }

    // ─── MODAL REFERENCES ──────────────────────────
    const modalINV   = $('#modal-inv');
    const formINV    = $('#form-inv');
    const idInputINV = $('#inv_id');

    function resetFormINV() {
        formINV[0].reset();
        idInputINV.val('');
        formINV.find('.is-invalid').removeClass('is-invalid');
        formINV.find('.invalid-feedback').remove();
        modalINV.find('.modal-title').text('Tambah Purchase Invoice');
        resetItems();
    }

    // ─── OPEN MODAL (ADD) ──────────────────────────
    $('#btn-add-inv').on('click', function () {
        resetFormINV();
        modalINV.modal('show');
    });

    modalINV.on('hidden.bs.modal', function () { resetFormINV(); });

    // ─── HANDLE ERRORS ─────────────────────────────
    function handleErrors(errors) {
        Object.entries(errors).forEach(([key, messages]) => {
            const input = formINV.find('[name="' + key + '"]').first();
            if (!input.length) return;
            const container = input.closest('.col-md-4, .col-md-6, .col-12');
            input.addClass('is-invalid');
            container.append('<div class="invalid-feedback">' + messages[0] + '</div>');
        });
    }

    // ─── SAVE ──────────────────────────────────────
    window.onSaveINV = () => {
        const id     = idInputINV.val();
        const url    = id ? invUpdateUrl.replace('__ID__', id) : invStoreUrl;

        const formData = formINV.serializeArray();
        formData.push({ name: 'items', value: JSON.stringify(collectItems()) });
        if (id) formData.push({ name: '_method', value: 'PUT' });

        $.ajax({
            url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (data) {
                Swal.fire({ title: 'Sukses!', text: data.message, icon: 'success', confirmButtonText: 'OK' })
                    .then(() => {
                        resetFormINV();
                        modalINV.modal('hide');
                        tableINV.ajax.reload(null, false);
                    });
            },
            error: function (xhr) {
                const res = xhr.responseJSON || {};
                if (xhr.status === 422 && res.errors) {
                    handleErrors(res.errors);
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: res.message || 'Terjadi kesalahan sistem.' });
                }
            }
        });
    };

    // ─── EDIT ──────────────────────────────────────
    $('#table-inv').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        resetFormINV();
        $.get(invShowUrl.replace('__ID__', id))
            .done(function (response) {
                const d = response.data || {};
                idInputINV.val(d.id);
                $('#inv_number').val(d.invoice_number ?? '');
                $('#inv_date').val(d.invoice_date ?? '');
                $('#inv_po').val(d.po_number ?? '');
                $('#inv_supplier').val(d.supplier_name ?? '');
                $('#inv_status').val(d.status ?? 'DRAFT');
                $('#inv_note').val(d.note ?? '');
                populateItems(d.items ?? []);
                modalINV.find('.modal-title').text('Edit Purchase Invoice');
                modalINV.modal('show');
            })
            .fail(function () { Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat mengambil data.' }); });
    });

    // ─── APPROVE / REJECT ─────────────────────────
    function updateStatusINV(id, status) {
        const label = status === 'APPROVED' ? 'approve' : 'reject';
        Swal.fire({
            title: 'Yakin akan ' + label + ' invoice ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: status === 'APPROVED' ? '#198754' : '#dc3545',
            confirmButtonText: 'Ya, ' + label,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: invStatusUrl.replace('__ID__', id),
                method: 'POST',
                data: { _method: 'PUT', status: status },
                success: function (response) {
                    Swal.fire({ icon: 'success', title: response.message, timer: 1500, showConfirmButton: false });
                    tableINV.ajax.reload(null, false);
                },
                error: function (xhr) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan.' });
                }
            });
        });
    }

    $('#table-inv').on('click', '.btn-approve', function () { updateStatusINV($(this).data('id'), 'APPROVED'); });
    $('#table-inv').on('click', '.btn-reject', function () { updateStatusINV($(this).data('id'), 'REJECTED'); });

    // ─── DELETE ────────────────────────────────────
    $('#table-inv').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus invoice ini?',
            text: 'Data yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: invDeleteUrl.replace('__ID__', id),
                method: 'POST',
                data: { _method: 'DELETE' },
                success: function (response) {
                    Swal.fire({ icon: 'success', title: response.message || 'Data dihapus', timer: 1500, showConfirmButton: false });
                    tableINV.ajax.reload(null, false);
                },
                error: function (xhr) {
                    const res = xhr.responseJSON || {};
                    Swal.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Tidak dapat menghapus data.' });
                }
            });
        });
    });
</script>
@endpush
