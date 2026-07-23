@push('after-style')
<style>
    #table-supplier-center thead th { font-weight: 600; }
    .badge { font-size: .75rem; }
</style>
@endpush

@push('after-script')
<script>
    // ─────────────────────────────────────────────
    // URLS & REFERENCES
    // ─────────────────────────────────────────────
    const scTableUrl        = "{{ route('supplier-center.table') }}";
    const scStoreUrl        = "{{ route('supplier-center.store') }}";
    const scShowUrl         = "{{ route('supplier-center.show', '__ID__') }}";
    const scUpdateUrl       = "{{ route('supplier-center.update', '__ID__') }}";
    const scDeleteUrl       = "{{ route('supplier-center.destroy', ['id' => '__ID__']) }}";
    const csrfToken         = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

    // ─────────────────────────────────────────────
    // DATATABLE
    // ─────────────────────────────────────────────
    const tableSupplierCenter = $('#table-supplier-center').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: scTableUrl,
            data: function(d) {
                d.filter_search = $('#filter-search').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'code',        name: 'code' },
            { data: 'name',        name: 'name' },
            { data: 'action',      name: 'action', orderable: false, searchable: false, className: 'text-end' },
        ],
    });

    // ─────────────────────────────────────────────
    // FILTER
    // ─────────────────────────────────────────────
    $('#filter-search').on('keyup', function () {
        tableSupplierCenter.ajax.reload();
    });

    $('#btn-reset-filter').on('click', function () {
        $('#filter-search').val('');
        tableSupplierCenter.ajax.reload();
    });

    // ─────────────────────────────────────────────
    // MODAL REFERENCES
    // ─────────────────────────────────────────────
    const modalSC    = $('#modal-supplier-center');
    const formSC     = $('#form-supplier-center');
    const idInputSC  = $('#supplier_center_id');

    // ─────────────────────────────────────────────
    // RESET FORM
    // ─────────────────────────────────────────────
    function resetFormSC() {
        formSC[0].reset();
        idInputSC.val('');
        formSC.find('.is-invalid').removeClass('is-invalid');
        formSC.find('.invalid-feedback').remove();
        modalSC.find('.modal-title').text('Tambah Supplier Center');
    }

    // ─────────────────────────────────────────────
    // OPEN MODAL (ADD)
    // ─────────────────────────────────────────────
    $('#btn-add-supplier-center').on('click', function () {
        resetFormSC();
        modalSC.modal('show');
    });

    modalSC.on('hidden.bs.modal', function () {
        resetFormSC();
    });

    // ─────────────────────────────────────────────
    // HANDLE VALIDATION ERRORS
    // ─────────────────────────────────────────────
    function handleErrors(errors) {
        Object.entries(errors).forEach(([key, messages]) => {
            const input = formSC.find('[name="' + key + '"]').first();
            if (!input.length) return;
            const container = input.closest('.col-md-4, .col-md-8, .col-12');
            input.addClass('is-invalid');
            container.append('<div class="invalid-feedback">' + messages[0] + '</div>');
        });
    }

    // ─────────────────────────────────────────────
    // SAVE
    // ─────────────────────────────────────────────
    window.onSaveSupplierCenter = () => {
        const id     = idInputSC.val();
        const url    = id ? scUpdateUrl.replace('__ID__', id) : scStoreUrl;
        const method = id ? 'PUT' : 'POST';

        const formData = formSC.serializeArray();
        if (id) formData.push({ name: '_method', value: 'PUT' });

        $.ajax({
            url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (data) {
                Swal.fire({ title: 'Sukses!', text: data.message, icon: 'success', confirmButtonText: 'OK' })
                    .then(() => {
                        resetFormSC();
                        modalSC.modal('hide');
                        tableSupplierCenter.ajax.reload(null, false);
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
    $('#table-supplier-center').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        resetFormSC();
        $.get(scShowUrl.replace('__ID__', id))
            .done(function (response) {
                const d = response.data || {};
                idInputSC.val(d.id);

                $('#sc_code').val(d.code ?? '');
                $('#sc_name').val(d.name ?? '');

                modalSC.find('.modal-title').text('Edit Supplier Center');
                modalSC.modal('show');
            })
            .fail(function () {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat mengambil data.' });
            });
    });

    // ─────────────────────────────────────────────
    // DELETE
    // ─────────────────────────────────────────────
    $('#table-supplier-center').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus center ini?',
            text: 'Data yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: scDeleteUrl.replace('__ID__', id),
                method: 'POST',
                data: { _method: 'DELETE' },
                success: function (response) {
                    Swal.fire({ icon: 'success', title: response.message || 'Data dihapus', timer: 1500, showConfirmButton: false });
                    tableSupplierCenter.ajax.reload(null, false);
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
