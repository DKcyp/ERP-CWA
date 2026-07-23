@push('after-style')
<style>
    #table-supplier-group thead th { font-weight: 600; }
    .badge { font-size: .75rem; }
</style>
@endpush

@push('after-script')
<script>
    // ─────────────────────────────────────────────
    // URLS & REFERENCES
    // ─────────────────────────────────────────────
    const sgTableUrl        = "{{ route('supplier-group.table') }}";
    const sgStoreUrl        = "{{ route('supplier-group.store') }}";
    const sgShowUrl         = "{{ route('supplier-group.show', '__ID__') }}";
    const sgUpdateUrl       = "{{ route('supplier-group.update', '__ID__') }}";
    const sgDeleteUrl       = "{{ route('supplier-group.destroy', ['id' => '__ID__']) }}";
    const csrfToken         = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

    // ─────────────────────────────────────────────
    // DATATABLE
    // ─────────────────────────────────────────────
    const tableSupplierGroup = $('#table-supplier-group').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: sgTableUrl,
            data: function(d) {
                d.filter_search = $('#filter-search').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'code',        name: 'code' },
            { data: 'name',        name: 'name' },
            { data: 'description', name: 'description' },
            { data: 'action',      name: 'action', orderable: false, searchable: false, className: 'text-end' },
        ],
    });

    // ─────────────────────────────────────────────
    // FILTER
    // ─────────────────────────────────────────────
    $('#filter-search').on('keyup', function () {
        tableSupplierGroup.ajax.reload();
    });

    $('#btn-reset-filter').on('click', function () {
        $('#filter-search').val('');
        tableSupplierGroup.ajax.reload();
    });

    // ─────────────────────────────────────────────
    // MODAL REFERENCES
    // ─────────────────────────────────────────────
    const modalSG    = $('#modal-supplier-group');
    const formSG     = $('#form-supplier-group');
    const idInputSG  = $('#supplier_group_id');

    // ─────────────────────────────────────────────
    // RESET FORM
    // ─────────────────────────────────────────────
    function resetFormSG() {
        formSG[0].reset();
        idInputSG.val('');
        formSG.find('.is-invalid').removeClass('is-invalid');
        formSG.find('.invalid-feedback').remove();
        modalSG.find('.modal-title').text('Tambah Supplier Group');
    }

    // ─────────────────────────────────────────────
    // OPEN MODAL (ADD)
    // ─────────────────────────────────────────────
    $('#btn-add-supplier-group').on('click', function () {
        resetFormSG();
        modalSG.modal('show');
    });

    modalSG.on('hidden.bs.modal', function () {
        resetFormSG();
    });

    // ─────────────────────────────────────────────
    // HANDLE VALIDATION ERRORS
    // ─────────────────────────────────────────────
    function handleErrors(errors) {
        Object.entries(errors).forEach(([key, messages]) => {
            const input = formSG.find('[name="' + key + '"]').first();
            if (!input.length) return;
            const container = input.closest('.col-md-4, .col-md-8, .col-12');
            input.addClass('is-invalid');
            container.append('<div class="invalid-feedback">' + messages[0] + '</div>');
        });
    }

    // ─────────────────────────────────────────────
    // SAVE
    // ─────────────────────────────────────────────
    window.onSaveSupplierGroup = () => {
        const id     = idInputSG.val();
        const url    = id ? sgUpdateUrl.replace('__ID__', id) : sgStoreUrl;
        const method = id ? 'PUT' : 'POST';

        const formData = formSG.serializeArray();
        if (id) formData.push({ name: '_method', value: 'PUT' });

        $.ajax({
            url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (data) {
                Swal.fire({ title: 'Sukses!', text: data.message, icon: 'success', confirmButtonText: 'OK' })
                    .then(() => {
                        resetFormSG();
                        modalSG.modal('hide');
                        tableSupplierGroup.ajax.reload(null, false);
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
    $('#table-supplier-group').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        resetFormSG();
        $.get(sgShowUrl.replace('__ID__', id))
            .done(function (response) {
                const d = response.data || {};
                idInputSG.val(d.id);

                $('#sg_code').val(d.code ?? '');
                $('#sg_name').val(d.name ?? '');
                $('#sg_description').val(d.description ?? '');

                modalSG.find('.modal-title').text('Edit Supplier Group');
                modalSG.modal('show');
            })
            .fail(function () {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat mengambil data.' });
            });
    });

    // ─────────────────────────────────────────────
    // DELETE
    // ─────────────────────────────────────────────
    $('#table-supplier-group').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus group ini?',
            text: 'Data yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: sgDeleteUrl.replace('__ID__', id),
                method: 'POST',
                data: { _method: 'DELETE' },
                success: function (response) {
                    Swal.fire({ icon: 'success', title: response.message || 'Data dihapus', timer: 1500, showConfirmButton: false });
                    tableSupplierGroup.ajax.reload(null, false);
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
