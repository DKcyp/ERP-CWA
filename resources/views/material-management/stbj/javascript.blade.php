@push('after-style')
<style>
    #table-stbj thead th { font-weight: 600; }
    .badge { font-size: .75rem; }
    #table-items tbody tr { vertical-align: middle; }
</style>
@endpush

@push('after-script')
<script>
    const stbjTableUrl  = "{{ route('stbj.table') }}";
    const stbjStoreUrl  = "{{ route('stbj.store') }}";
    const stbjShowUrl   = "{{ route('stbj.show', '__ID__') }}";
    const stbjUpdateUrl = "{{ route('stbj.update', '__ID__') }}";
    const stbjDeleteUrl = "{{ route('stbj.destroy', ['id' => '__ID__']) }}";
    const stbjStatusUrl = "{{ route('stbj.status', ['id' => '__ID__']) }}";
    const csrfToken     = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

    const tableSTBJ = $('#table-stbj').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: stbjTableUrl,
            data: function(d) {
                d.filter_search = $('#filter-search').val();
                d.filter_status = $('#filter-status').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',   name: 'DT_RowIndex',   orderable: false, searchable: false, className: 'text-center' },
            { data: 'stbj_number',   name: 'stbj_number' },
            { data: 'stbj_date_fmt', name: 'stbj_date',     className: 'text-center' },
            { data: 'supplier_name', name: 'supplier_name' },
            { data: 'po_number', name: 'po_number' },
            { data: 'total_items',   name: 'total_items',   className: 'text-center' },
            { data: 'total_amount',  name: 'total_amount',  className: 'text-end' },
            { data: 'status_badge',  name: 'status',        orderable: false, searchable: false, className: 'text-center' },
            { data: 'action',        name: 'action',        orderable: false, searchable: false, className: 'text-end' },
        ],
    });

    $('#filter-search').on('keyup', function () { tableSTBJ.ajax.reload(); });
    $('#filter-status').on('change', function () { tableSTBJ.ajax.reload(); });

    $('#btn-reset-filter').on('click', function () {
        $('#filter-search').val('');
        $('#filter-status').val('all');
        tableSTBJ.ajax.reload();
    });

    let itemIndex = 0;

    function addItemRow(data) {
        const tbody = $('#items-tbody');
        $('#row-empty').hide();

        const i = itemIndex++;
        const description = data?.description ?? '';
        const amount      = data?.amount ?? '';

        const row = `
            <tr>
                <td class="text-center item-no">${tbody.find('tr:visible').length + 1}</td>
                <td>
                    <input type="text" class="form-control form-control-sm item-desc" name="items[${i}][description]"
                           value="${description}" placeholder="Deskripsi item" maxlength="200">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm text-end item-amount" name="items[${i}][amount]"
                           value="${amount}" placeholder="0" min="0" step="1">
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
            const description = $(this).find('.item-desc').val() || '';
            const amount      = parseInt($(this).find('.item-amount').val()) || 0;
            if (description) items.push({ description, amount });
        });
        return items;
    }

    const modalSTBJ   = $('#modal-stbj');
    const formSTBJ    = $('#form-stbj');
    const idInputSTBJ = $('#stbj_id');

    function resetFormSTBJ() {
        formSTBJ[0].reset();
        idInputSTBJ.val('');
        formSTBJ.find('.is-invalid').removeClass('is-invalid');
        formSTBJ.find('.invalid-feedback').remove();
        modalSTBJ.find('.modal-title').text('Tambah STBJ');
        resetItems();
    }

    $('#btn-add-stbj').on('click', function () { resetFormSTBJ(); modalSTBJ.modal('show'); });
    modalSTBJ.on('hidden.bs.modal', function () { resetFormSTBJ(); });

    function handleErrors(errors) {
        Object.entries(errors).forEach(([key, messages]) => {
            const input = formSTBJ.find('[name="' + key + '"]').first();
            if (!input.length) return;
            const container = input.closest('.col-md-4, .col-md-6, .col-12');
            input.addClass('is-invalid');
            container.append('<div class="invalid-feedback">' + messages[0] + '</div>');
        });
    }

    window.onSaveSTBJ = () => {
        const id     = idInputSTBJ.val();
        const url    = id ? stbjUpdateUrl.replace('__ID__', id) : stbjStoreUrl;

        const formData = formSTBJ.serializeArray();
        formData.push({ name: 'items', value: JSON.stringify(collectItems()) });
        if (id) formData.push({ name: '_method', value: 'PUT' });

        $.ajax({
            url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (data) {
                Swal.fire({ title: 'Sukses!', text: data.message, icon: 'success', confirmButtonText: 'OK' })
                    .then(() => { resetFormSTBJ(); modalSTBJ.modal('hide'); tableSTBJ.ajax.reload(null, false); });
            },
            error: function (xhr) {
                const res = xhr.responseJSON || {};
                if (xhr.status === 422 && res.errors) { handleErrors(res.errors); }
                else { Swal.fire({ icon: 'error', title: 'Error', text: res.message || 'Terjadi kesalahan sistem.' }); }
            }
        });
    };

    $('#table-stbj').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        resetFormSTBJ();
        $.get(stbjShowUrl.replace('__ID__', id))
            .done(function (response) {
                const d = response.data || {};
                idInputSTBJ.val(d.id);
                $('#stbj_number').val(d.stbj_number ?? '');
                $('#stbj_date').val(d.stbj_date ?? '');
                $('#stbj_supplier').val(d.supplier_name ?? '');
                $('#stbj_po').val(d.po_number ?? '');
                $('#stbj_status').val(d.status ?? 'DRAFT');
                $('#stbj_note').val(d.note ?? '');
                populateItems(d.items ?? []);
                modalSTBJ.find('.modal-title').text('Edit STBJ');
                modalSTBJ.modal('show');
            })
            .fail(function () { Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat mengambil data.' }); });
    });

    function updateStatusSTBJ(id, status) {
        const label = status === 'APPROVED' ? 'approve' : 'reject';
        Swal.fire({
            title: 'Yakin akan ' + label + ' STBJ ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: status === 'APPROVED' ? '#198754' : '#dc3545',
            confirmButtonText: 'Ya, ' + label,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: stbjStatusUrl.replace('__ID__', id),
                method: 'POST',
                data: { _method: 'PUT', status: status },
                success: function (response) {
                    Swal.fire({ icon: 'success', title: response.message, timer: 1500, showConfirmButton: false });
                    tableSTBJ.ajax.reload(null, false);
                },
                error: function (xhr) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan.' });
                }
            });
        });
    }

    $('#table-stbj').on('click', '.btn-approve', function () { updateStatusSTBJ($(this).data('id'), 'APPROVED'); });
    $('#table-stbj').on('click', '.btn-reject', function () { updateStatusSTBJ($(this).data('id'), 'REJECTED'); });

    $('#table-stbj').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus STBJ ini?',
            text: 'Data yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: stbjDeleteUrl.replace('__ID__', id),
                method: 'POST',
                data: { _method: 'DELETE' },
                success: function (response) {
                    Swal.fire({ icon: 'success', title: response.message || 'Data dihapus', timer: 1500, showConfirmButton: false });
                    tableSTBJ.ajax.reload(null, false);
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
