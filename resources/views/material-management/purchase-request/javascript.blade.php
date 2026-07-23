@push('after-style')
<style>
    #table-pr thead th { font-weight: 600; }
    .badge { font-size: .75rem; }
    #table-items tbody tr { vertical-align: middle; }
    #table-items thead th {
        border-top: 0 !important;
        border-bottom-width: 2px;
    }
    #table-items tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
    #table-items .item-material,
    #table-items .item-qty,
    #table-items .item-unit {
        border-radius: 0.45rem;
    }
    #table-items .btn-remove-item {
        width: 38px;
        height: 38px;
        padding: 0;
    }
    #table-items .btn-remove-item i {
        font-size: 1rem;
    }
    #modal-pr .modal-content {
        border-radius: 0.9rem;
    }
    #modal-pr .modal-header {
        border-bottom: 1px solid #e9ecef;
    }
    #modal-pr .modal-footer {
        border-top: 1px solid #e9ecef;
    }
</style>
@endpush

@push('after-script')
<script>
    // ─────────────────────────────────────────────
    // URLS & REFERENCES
    // ─────────────────────────────────────────────
    const prTableUrl   = "{{ route('purchase-request.table') }}";
    const prStoreUrl   = "{{ route('purchase-request.store') }}";
    const prShowUrl    = "{{ route('purchase-request.show', '__ID__') }}";
    const prUpdateUrl  = "{{ route('purchase-request.update', '__ID__') }}";
    const prDeleteUrl  = "{{ route('purchase-request.destroy', ['id' => '__ID__']) }}";
    const prStatusUrl  = "{{ route('purchase-request.status', ['id' => '__ID__']) }}";
    const csrfToken    = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

    // ─────────────────────────────────────────────
    // DATATABLE
    // ─────────────────────────────────────────────
    const tablePR = $('#table-pr').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: prTableUrl,
            data: function(d) {
                d.filter_search = $('#filter-search').val();
                d.filter_status = $('#filter-status').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',   name: 'DT_RowIndex',   orderable: false, searchable: false, className: 'text-center' },
            { data: 'pr_number',     name: 'pr_number' },
            { data: 'pr_date_fmt',   name: 'pr_date',       className: 'text-center' },
            { data: 'requester',     name: 'requester' },
            { data: 'department',    name: 'department' },
            { data: 'total_items',   name: 'total_items',   className: 'text-center' },
            { data: 'status_badge',  name: 'status',        orderable: false, searchable: false, className: 'text-center' },
            { data: 'action',        name: 'action',        orderable: false, searchable: false, className: 'text-end' },
        ],
    });

    // ─────────────────────────────────────────────
    // FILTER
    // ─────────────────────────────────────────────
    $('#filter-search').on('keyup', function () { tablePR.ajax.reload(); });
    $('#filter-status').on('change', function () { tablePR.ajax.reload(); });

    $('#btn-reset-filter').on('click', function () {
        $('#filter-search').val('');
        $('#filter-status').val('all');
        tablePR.ajax.reload();
    });

    // ─────────────────────────────────────────────
    // DYNAMIC ITEMS
    // ─────────────────────────────────────────────
    let itemIndex = 0;

    function addItemRow(data) {
        const tbody = $('#items-tbody');
        $('#row-empty').hide();

        const i = itemIndex++;
        const material = data?.material ?? '';
        const qty      = data?.qty ?? '';
        const unit     = data?.unit ?? '';

        const row = `
            <tr>
                <td class="text-center item-no">${tbody.find('tr:visible').length + 1}</td>
                <td>
                    <input type="text" class="form-control form-control-sm item-material" name="items[${i}][material]"
                           value="${material}" placeholder="Nama material" maxlength="200">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm item-qty" name="items[${i}][qty]"
                           value="${qty}" placeholder="0" min="1" step="1">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm item-unit" name="items[${i}][unit]"
                           value="${unit}" placeholder="Cth: Pcs" maxlength="50">
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

    $('#btn-add-item').on('click', function () {
        addItemRow();
    });

    $('#items-tbody').on('click', '.btn-remove-item', function () {
        $(this).closest('tr').remove();
        const tbody = $('#items-tbody');
        if (tbody.find('tr:visible').length === 0) {
            $('#row-empty').show();
        }
        renumberItems();
    });

    function resetItems() {
        itemIndex = 0;
        $('#items-tbody tr:not(#row-empty)').remove();
        $('#row-empty').show();
    }

    function populateItems(items) {
        resetItems();
        if (items && items.length) {
            items.forEach(function (item) {
                addItemRow(item);
            });
        }
    }

    function collectItems() {
        const items = [];
        $('#items-tbody tr:visible').not('#row-empty').each(function () {
            const material = $(this).find('.item-material').val() || '';
            const qty      = parseInt($(this).find('.item-qty').val()) || 0;
            const unit     = $(this).find('.item-unit').val() || '';
            if (material) {
                items.push({ material, qty, unit });
            }
        });
        return items;
    }

    // ─────────────────────────────────────────────
    // MODAL REFERENCES
    // ─────────────────────────────────────────────
    const modalPR   = $('#modal-pr');
    const formPR    = $('#form-pr');
    const idInputPR = $('#pr_id');

    // ─────────────────────────────────────────────
    // RESET FORM
    // ─────────────────────────────────────────────
    function resetFormPR() {
        formPR[0].reset();
        idInputPR.val('');
        formPR.find('.is-invalid').removeClass('is-invalid');
        formPR.find('.invalid-feedback').remove();
        modalPR.find('.modal-title').text('Tambah Purchase Request');
        resetItems();
    }

    // ─────────────────────────────────────────────
    // OPEN MODAL (ADD)
    // ─────────────────────────────────────────────
    $('#btn-add-pr').on('click', function () {
        resetFormPR();
        modalPR.modal('show');
    });

    modalPR.on('hidden.bs.modal', function () {
        resetFormPR();
    });

    // ─────────────────────────────────────────────
    // HANDLE VALIDATION ERRORS
    // ─────────────────────────────────────────────
    function handleErrors(errors) {
        Object.entries(errors).forEach(([key, messages]) => {
            const input = formPR.find('[name="' + key + '"]').first();
            if (!input.length) return;
            const container = input.closest('.col-md-4, .col-md-6, .col-12');
            input.addClass('is-invalid');
            container.append('<div class="invalid-feedback">' + messages[0] + '</div>');
        });
    }

    // ─────────────────────────────────────────────
    // SAVE
    // ─────────────────────────────────────────────
    window.onSavePR = () => {
        const id     = idInputPR.val();
        const url    = id ? prUpdateUrl.replace('__ID__', id) : prStoreUrl;

        const formData = formPR.serializeArray();
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
                        resetFormPR();
                        modalPR.modal('hide');
                        tablePR.ajax.reload(null, false);
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

    // ─────────────────────────────────────────────
    // EDIT
    // ─────────────────────────────────────────────
    $('#table-pr').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        resetFormPR();
        $.get(prShowUrl.replace('__ID__', id))
            .done(function (response) {
                const d = response.data || {};
                idInputPR.val(d.id);

                $('#pr_number').val(d.pr_number ?? '');
                $('#pr_date').val(d.pr_date ?? '');
                $('#pr_requester').val(d.requester ?? '');
                $('#pr_department').val(d.department ?? '');
                $('#pr_status').val(d.status ?? 'DRAFT');
                $('#pr_note').val(d.note ?? '');

                populateItems(d.items ?? []);

                modalPR.find('.modal-title').text('Edit Purchase Request');
                modalPR.modal('show');
            })
            .fail(function () {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat mengambil data.' });
            });
    });

    // ─────────────────────────────────────────────
    // APPROVE / REJECT
    // ─────────────────────────────────────────────
    function updateStatusPR(id, status) {
        const label = status === 'APPROVED' ? 'approve' : 'reject';
        Swal.fire({
            title: 'Yakin akan ' + label + ' PR ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: status === 'APPROVED' ? '#198754' : '#dc3545',
            confirmButtonText: 'Ya, ' + label,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: prStatusUrl.replace('__ID__', id),
                method: 'POST',
                data: { _method: 'PUT', status: status },
                success: function (response) {
                    Swal.fire({ icon: 'success', title: response.message, timer: 1500, showConfirmButton: false });
                    tablePR.ajax.reload(null, false);
                },
                error: function (xhr) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan.' });
                }
            });
        });
    }

    $('#table-pr').on('click', '.btn-approve', function () { updateStatusPR($(this).data('id'), 'APPROVED'); });
    $('#table-pr').on('click', '.btn-reject', function () { updateStatusPR($(this).data('id'), 'REJECTED'); });

    // ─────────────────────────────────────────────
    // DELETE
    // ─────────────────────────────────────────────
    $('#table-pr').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus PR ini?',
            text: 'Data yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: prDeleteUrl.replace('__ID__', id),
                method: 'POST',
                data: { _method: 'DELETE' },
                success: function (response) {
                    Swal.fire({ icon: 'success', title: response.message || 'Data dihapus', timer: 1500, showConfirmButton: false });
                    tablePR.ajax.reload(null, false);
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
