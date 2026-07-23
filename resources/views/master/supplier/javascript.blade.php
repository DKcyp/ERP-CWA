@push('after-style')
<style>
    #table-supplier thead th { font-weight: 600; }
    .badge { font-size: .75rem; }
</style>
@endpush

@push('after-script')
<script>
    // ─────────────────────────────────────────────
    // URLS & REFERENCES
    // ─────────────────────────────────────────────
    const supplierTableUrl      = "{{ route('supplier.table') }}";
    const supplierStoreUrl      = "{{ route('supplier.store') }}";
    const supplierShowUrl       = "{{ route('supplier.show', '__ID__') }}";
    const supplierUpdateUrl     = "{{ route('supplier.update', '__ID__') }}";
    const supplierDeleteUrl     = "{{ route('supplier.destroy', ['id' => '__ID__']) }}";
    const supplierGroupsUrl     = "{{ route('supplier.getSupplierGroups') }}";
    const supplierCentersUrl    = "{{ route('supplier.getSupplierCenters') }}";
    const csrfToken             = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

    // ─────────────────────────────────────────────
    // DATATABLE
    // ─────────────────────────────────────────────
    const tableSupplier = $('#table-supplier').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: supplierTableUrl,
            data: function(d) {
                d.filter_group  = $('#filter-group').val();
                d.filter_center = $('#filter-center').val();
                d.filter_status = $('#filter-status').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',   name: 'DT_RowIndex',   orderable: false, searchable: false, className: 'text-center' },
            { data: 'supplier_code', name: 'supplier_code' },
            { data: 'name',          name: 'name' },
            { data: 'group_name',    name: 'group_name',    orderable: false },
            { data: 'center_name',   name: 'center_name',   orderable: false },
            { data: 'phone',         name: 'phone' },
            { data: 'email',         name: 'email' },
            { data: 'term_of_payment', name: 'term_of_payment', className: 'text-center',
              render: d => d + ' hari' },
            { data: 'status_badge',  name: 'status_badge',  orderable: false, searchable: false, className: 'text-center' },
            { data: 'action',        name: 'action',        orderable: false, searchable: false, className: 'text-end' },
        ],
    });

    // ─────────────────────────────────────────────
    // FILTER SELECT2
    // ─────────────────────────────────────────────
    $('#filter-group').select2({
        placeholder: 'Semua Grup',
        allowClear: true,
        ajax: {
            url: supplierGroupsUrl,
            dataType: 'json',
            delay: 250,
            data: params => ({ q: params.term }),
            processResults: data => ({ results: data.data })
        }
    }).on('change', () => tableSupplier.ajax.reload());

    $('#filter-center').select2({
        placeholder: 'Semua Center',
        allowClear: true,
        ajax: {
            url: supplierCentersUrl,
            dataType: 'json',
            delay: 250,
            data: params => ({ q: params.term }),
            processResults: data => ({ results: data.data })
        }
    }).on('change', () => tableSupplier.ajax.reload());

    $('#filter-status').on('change', () => tableSupplier.ajax.reload());

    // Reset Filter
    $('#btn-reset-filter').on('click', function () {
        $('#filter-group').val(null).trigger('change');
        $('#filter-center').val(null).trigger('change');
        $('#filter-status').val('').trigger('change');
    });

    // ─────────────────────────────────────────────
    // SELECT2
    // ─────────────────────────────────────────────
    function initSelect2Supplier() {
        $('#supplier_group_id').select2({
            dropdownParent: $('#modal-supplier'),
            placeholder: '-- Pilih Grup --',
            allowClear: true,
            ajax: {
                url: supplierGroupsUrl,
                dataType: 'json',
                delay: 250,
                data: params => ({ q: params.term }),
                processResults: data => ({ results: data.data })
            }
        });

        $('#supplier_center_id').select2({
            dropdownParent: $('#modal-supplier'),
            placeholder: '-- Pilih Center --',
            allowClear: true,
            ajax: {
                url: supplierCentersUrl,
                dataType: 'json',
                delay: 250,
                data: params => ({ q: params.term }),
                processResults: data => ({ results: data.data })
            }
        });
    }
    initSelect2Supplier();

    // ─────────────────────────────────────────────
    // MODAL REFERENCES
    // ─────────────────────────────────────────────
    const modalSupplier = $('#modal-supplier');
    const formSupplier  = $('#form-supplier');
    const idInput       = $('#supplier_id');

    // ─────────────────────────────────────────────
    // RESET FORM
    // ─────────────────────────────────────────────
    function resetFormSupplier() {
        formSupplier[0].reset();
        idInput.val('');
        $('#supplier_group_id').val(null).trigger('change');
        $('#supplier_center_id').val(null).trigger('change');
        $('#supplier_status').prop('checked', true);
        formSupplier.find('.is-invalid').removeClass('is-invalid');
        formSupplier.find('.invalid-feedback').remove();
        modalSupplier.find('.modal-title').text('Tambah Supplier');
    }

    // ─────────────────────────────────────────────
    // OPEN MODAL (ADD)
    // ─────────────────────────────────────────────
    $('#btn-add-supplier').on('click', function () {
        resetFormSupplier();
        modalSupplier.modal('show');
    });

    modalSupplier.on('hidden.bs.modal', function () {
        resetFormSupplier();
    });

    // ─────────────────────────────────────────────
    // HANDLE VALIDATION ERRORS
    // ─────────────────────────────────────────────
    function handleErrors(errors) {
        Object.entries(errors).forEach(([key, messages]) => {
            const input = formSupplier.find('[name="' + key + '"]').first();
            if (!input.length) return;
            const container = input.closest('.col-md-4, .col-md-6, .col-md-8, .col-12, .input-group');
            input.addClass('is-invalid');
            container.append('<div class="invalid-feedback">' + messages[0] + '</div>');
        });
    }

    // ─────────────────────────────────────────────
    // SAVE
    // ─────────────────────────────────────────────
    window.onSaveSupplier = () => {
        const id     = idInput.val();
        const url    = id ? supplierUpdateUrl.replace('__ID__', id) : supplierStoreUrl;
        const method = id ? 'PUT' : 'POST';

        // handle checkbox boolean
        const formData = formSupplier.serializeArray();
        const statusChecked = $('#supplier_status').is(':checked') ? 1 : 0;
        const filtered = formData.filter(f => f.name !== 'status');
        filtered.push({ name: 'status', value: statusChecked });
        if (id) filtered.push({ name: '_method', value: 'PUT' });

        $.ajax({
            url,
            type: 'POST',
            data: filtered,
            dataType: 'json',
            success: function (data) {
                Swal.fire({ title: 'Sukses!', text: data.message, icon: 'success', confirmButtonText: 'OK' })
                    .then(() => {
                        resetFormSupplier();
                        modalSupplier.modal('hide');
                        tableSupplier.ajax.reload(null, false);
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
    $('#table-supplier').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        resetFormSupplier();
        $.get(supplierShowUrl.replace('__ID__', id))
            .done(function (response) {
                const d = response.data || {};
                idInput.val(d.id);

                $('#supplier_code').val(d.supplier_code ?? '');
                $('#supplier_name').val(d.name ?? '');
                $('#supplier_phone').val(d.phone ?? '');
                $('#supplier_email').val(d.email ?? '');
                $('#supplier_address').val(d.address ?? '');
                $('#supplier_top').val(d.term_of_payment ?? 0);
                $('#supplier_status').prop('checked', d.status == 1);

                // Group Select2
                if (d.supplier_group_id && d.supplier_group) {
                    const optGroup = new Option(d.supplier_group.name, d.supplier_group_id, true, true);
                    $('#supplier_group_id').append(optGroup).trigger('change');
                }
                // Center Select2
                if (d.supplier_center_id && d.supplier_center) {
                    const optCenter = new Option(d.supplier_center.name, d.supplier_center_id, true, true);
                    $('#supplier_center_id').append(optCenter).trigger('change');
                }

                modalSupplier.find('.modal-title').text('Edit Supplier');
                modalSupplier.modal('show');
            })
            .fail(function () {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat mengambil data.' });
            });
    });

    // ─────────────────────────────────────────────
    // DELETE
    // ─────────────────────────────────────────────
    $('#table-supplier').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus supplier ini?',
            text: 'Data yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: supplierDeleteUrl.replace('__ID__', id),
                method: 'POST',
                data: { _method: 'DELETE' },
                success: function (response) {
                    Swal.fire({ icon: 'success', title: response.message || 'Data dihapus', timer: 1500, showConfirmButton: false });
                    tableSupplier.ajax.reload(null, false);
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
