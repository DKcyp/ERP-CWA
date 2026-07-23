@push('after-style')
<style>
    #table-sjbb thead th { font-weight: 600; }
    .badge { font-size: .75rem; }
</style>
@endpush

@push('after-script')
<script>
    const sjbbTableUrl  = "{{ route('sjbb.table') }}";
    const sjbbStoreUrl  = "{{ route('sjbb.store') }}";
    const sjbbShowUrl   = "{{ route('sjbb.show', '__ID__') }}";
    const sjbbUpdateUrl = "{{ route('sjbb.update', '__ID__') }}";
    const sjbbDeleteUrl = "{{ route('sjbb.destroy', ['id' => '__ID__']) }}";
    const sjbbStatusUrl = "{{ route('sjbb.status', ['id' => '__ID__']) }}";
    const csrfToken     = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

    const tableSJBB = $('#table-sjbb').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: sjbbTableUrl,
            data: function(d) {
                d.filter_search = $('#filter-search').val();
                d.filter_status = $('#filter-status').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',     name: 'DT_RowIndex',   orderable: false, searchable: false, className: 'text-center' },
            { data: 'sjbb_number',     name: 'sjbb_number' },
            { data: 'sjbb_date_fmt',   name: 'sjbb_date',    className: 'text-center' },
            { data: 'supplier_name',   name: 'supplier_name' },
            { data: 'type_badge',      name: 'type',          orderable: false, searchable: false, className: 'text-center' },
            { data: 'notes',           name: 'notes' },
            { data: 'status_badge',    name: 'status',        orderable: false, searchable: false, className: 'text-center' },
            { data: 'action',          name: 'action',        orderable: false, searchable: false, className: 'text-end' },
        ],
    });

    $('#filter-search').on('keyup', function () { tableSJBB.ajax.reload(); });
    $('#filter-status').on('change', function () { tableSJBB.ajax.reload(); });

    $('#btn-reset-filter').on('click', function () {
        $('#filter-search').val('');
        $('#filter-status').val('all');
        tableSJBB.ajax.reload();
    });

    // ─── MODAL ──────────────────────────────────
    const modalSJBB   = $('#modal-sjbb');
    const formSJBB    = $('#form-sjbb');
    const idInputSJBB = $('#sjbb_id');

    function resetFormSJBB() {
        formSJBB[0].reset();
        idInputSJBB.val('');
        formSJBB.find('.is-invalid').removeClass('is-invalid');
        formSJBB.find('.invalid-feedback').remove();
        modalSJBB.find('.modal-title').text('Tambah SJBB');
    }

    $('#btn-add-sjbb').on('click', function () { resetFormSJBB(); modalSJBB.modal('show'); });
    modalSJBB.on('hidden.bs.modal', function () { resetFormSJBB(); });

    function handleErrors(errors) {
        Object.entries(errors).forEach(([key, messages]) => {
            const input = formSJBB.find('[name="' + key + '"]').first();
            if (!input.length) return;
            const container = input.closest('.col-md-4, .col-md-6, .col-12');
            input.addClass('is-invalid');
            container.append('<div class="invalid-feedback">' + messages[0] + '</div>');
        });
    }

    window.onSaveSJBB = () => {
        const id = idInputSJBB.val();
        const url = id ? sjbbUpdateUrl.replace('__ID__', id) : sjbbStoreUrl;
        const formData = formSJBB.serializeArray();
        if (id) formData.push({ name: '_method', value: 'PUT' });
        $.ajax({
            url, type: 'POST', data: formData, dataType: 'json',
            success: function (data) {
                Swal.fire({ title: 'Sukses!', text: data.message, icon: 'success', confirmButtonText: 'OK' })
                    .then(() => { resetFormSJBB(); modalSJBB.modal('hide'); tableSJBB.ajax.reload(null, false); });
            },
            error: function (xhr) {
                const res = xhr.responseJSON || {};
                if (xhr.status === 422 && res.errors) { handleErrors(res.errors); }
                else { Swal.fire({ icon: 'error', title: 'Error', text: res.message || 'Terjadi kesalahan sistem.' }); }
            }
        });
    };

    $('#table-sjbb').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        resetFormSJBB();
        $.get(sjbbShowUrl.replace('__ID__', id)).done(function (response) {
            const d = response.data || {};
            idInputSJBB.val(d.id);
            $('#sjbb_number').val(d.sjbb_number ?? '');
            $('#sjbb_date').val(d.sjbb_date ?? '');
            $('#sjbb_supplier').val(d.supplier_name ?? '');
            $('#sjbb_supplier_id').val(d.supplier_id ?? '');
            $('#sjbb_type').val(d.type ?? 'IN');
            $('#sjbb_status').val(d.status ?? 'DRAFT');
            $('#sjbb_notes').val(d.notes ?? '');
            modalSJBB.find('.modal-title').text('Edit SJBB');
            modalSJBB.modal('show');
        }).fail(function () { Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat mengambil data.' }); });
    });

    function updateStatusSJBB(id, status) {
        const label = status === 'APPROVED' ? 'approve' : 'complete';
        Swal.fire({
            title: 'Yakin akan ' + label + ' SJBB ini?', icon: 'question', showCancelButton: true,
            confirmButtonColor: status === 'APPROVED' ? '#198754' : '#0d6efd',
            confirmButtonText: 'Ya, ' + label, cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: sjbbStatusUrl.replace('__ID__', id), method: 'POST', data: { _method: 'PUT', status: status },
                success: function (response) { Swal.fire({ icon: 'success', title: response.message, timer: 1500, showConfirmButton: false }); tableSJBB.ajax.reload(null, false); },
                error: function (xhr) { Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan.' }); }
            });
        });
    }

    $('#table-sjbb').on('click', '.btn-approve', function () { updateStatusSJBB($(this).data('id'), 'APPROVED'); });

    $('#table-sjbb').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus SJBB ini?', text: 'Data yang dihapus tidak dapat dikembalikan.', icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: sjbbDeleteUrl.replace('__ID__', id), method: 'POST', data: { _method: 'DELETE' },
                success: function (response) { Swal.fire({ icon: 'success', title: response.message || 'Data dihapus', timer: 1500, showConfirmButton: false }); tableSJBB.ajax.reload(null, false); },
                error: function (xhr) { const res = xhr.responseJSON || {}; Swal.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Tidak dapat menghapus data.' }); }
            });
        });
    });
</script>
@endpush
