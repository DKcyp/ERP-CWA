@push('after-style')
<style>
    #table-sp thead th { font-weight: 600; }
    .badge { font-size: .75rem; }
    #table-items tbody tr { vertical-align: middle; }
</style>
@endpush

@push('after-script')
<script>
    const spTableUrl  = "{{ route('supplier-payment.table') }}";
    const spStoreUrl  = "{{ route('supplier-payment.store') }}";
    const spShowUrl   = "{{ route('supplier-payment.show', '__ID__') }}";
    const spUpdateUrl = "{{ route('supplier-payment.update', '__ID__') }}";
    const spDeleteUrl = "{{ route('supplier-payment.destroy', ['id' => '__ID__']) }}";
    const spStatusUrl = "{{ route('supplier-payment.status', ['id' => '__ID__']) }}";
    const csrfToken   = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

    const tableSP = $('#table-sp').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: spTableUrl,
            data: function(d) {
                d.filter_search = $('#filter-search').val();
                d.filter_status = $('#filter-status').val();
                d.payment_type  = $('#paymentTabs .nav-link.active').data('type') || 'all';
            }
        },
        columns: [
            { data: 'DT_RowIndex',       name: 'DT_RowIndex',       orderable: false, searchable: false, className: 'text-center' },
            { data: 'payment_number',    name: 'payment_number' },
            { data: 'payment_date_fmt',  name: 'payment_date',      className: 'text-center' },
            { data: 'supplier_id',       name: 'supplier_id',       className: 'text-center' },
            { data: 'supplier_name',     name: 'supplier_name' },
            { data: 'currency',          name: 'currency',          className: 'text-center' },
            { data: 'payment_type',       name: 'payment_type',       orderable: false, searchable: false, className: 'text-center' },
            { data: 'total_amount',      name: 'total_amount',      className: 'text-end' },
            { data: 'account',           name: 'account' },
            { data: 'note',              name: 'note' },
            { data: 'status_badge',      name: 'status',            orderable: false, searchable: false, className: 'text-center' },
            { data: 'user_name',         name: 'user_name' },
            { data: 'complete_date_fmt', name: 'complete_date',     className: 'text-center' },
            { data: 'action',            name: 'action',            orderable: false, searchable: false, className: 'text-end' },
        ],
    });

    $('#filter-search').on('keyup', function () { tableSP.ajax.reload(); });
    $('#filter-status').on('change', function () { tableSP.ajax.reload(); });

    $('#btn-reset-filter').on('click', function () {
        $('#filter-search').val('');
        $('#filter-status').val('all');
        $('#paymentTabs .nav-link').removeClass('active');
        $('#tab-all').addClass('active');
        tableSP.ajax.reload();
    });

    // ─── TABS ────────────────────────────────────
    $('#paymentTabs .nav-link').on('click', function () {
        $('#paymentTabs .nav-link').removeClass('active');
        $(this).addClass('active');
        tableSP.ajax.reload();
    });

    // ─── MODAL REFERENCES ──────────────────────────
    const modalSP   = $('#modal-sp');
    const formSP    = $('#form-sp');
    const idInputSP = $('#sp_id');

    function resetFormSP() {
        formSP[0].reset();
        idInputSP.val('');
        formSP.find('.is-invalid').removeClass('is-invalid');
        formSP.find('.invalid-feedback').remove();
        modalSP.find('.modal-title').text('Tambah Supplier Payment');
    }

    $('#btn-add-sp').on('click', function () { resetFormSP(); modalSP.modal('show'); });
    modalSP.on('hidden.bs.modal', function () { resetFormSP(); });

    function handleErrors(errors) {
        Object.entries(errors).forEach(([key, messages]) => {
            const input = formSP.find('[name="' + key + '"]').first();
            if (!input.length) return;
            const container = input.closest('.col-md-4, .col-md-6, .col-12');
            input.addClass('is-invalid');
            container.append('<div class="invalid-feedback">' + messages[0] + '</div>');
        });
    }

    window.onSaveSP = () => {
        const id = idInputSP.val();
        const url = id ? spUpdateUrl.replace('__ID__', id) : spStoreUrl;
        const formData = formSP.serializeArray();
        if (id) formData.push({ name: '_method', value: 'PUT' });
        $.ajax({
            url, type: 'POST', data: formData, dataType: 'json',
            success: function (data) {
                Swal.fire({ title: 'Sukses!', text: data.message, icon: 'success', confirmButtonText: 'OK' })
                    .then(() => { resetFormSP(); modalSP.modal('hide'); tableSP.ajax.reload(null, false); });
            },
            error: function (xhr) {
                const res = xhr.responseJSON || {};
                if (xhr.status === 422 && res.errors) { handleErrors(res.errors); }
                else { Swal.fire({ icon: 'error', title: 'Error', text: res.message || 'Terjadi kesalahan sistem.' }); }
            }
        });
    };

    $('#table-sp').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        resetFormSP();
        $.get(spShowUrl.replace('__ID__', id)).done(function (response) {
            const d = response.data || {};
            idInputSP.val(d.id);
            $('#sp_number').val(d.payment_number ?? '');
            $('#sp_date').val(d.payment_date ?? '');
            $('#sp_supplier').val(d.supplier_name ?? '');
            $('#sp_supplier_id').val(d.supplier_id ?? '');
            $('#sp_currency').val(d.currency ?? 'IDR');
            $('#sp_payment_type').val(d.payment_type ?? 'Regular');
            $('#sp_account').val(d.account ?? '');
            $('#sp_user').val(d.user_name ?? '');
            $('#sp_complete_date').val(d.complete_date ?? '');
            $('#sp_stbj').val(d.stbj_number ?? '');
            $('#sp_invoice').val(d.invoice_number ?? '');
            $('#sp_status').val(d.status ?? 'DRAFT');
            $('#sp_note').val(d.note ?? '');
            modalSP.find('.modal-title').text('Edit Supplier Payment');
            modalSP.modal('show');
        }).fail(function () { Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat mengambil data.' }); });
    });

    function updateStatusSP(id, status) {
        const label = status === 'APPROVED' ? 'approve' : 'reject';
        Swal.fire({
            title: 'Yakin akan ' + label + ' payment ini?', icon: 'question', showCancelButton: true,
            confirmButtonColor: status === 'APPROVED' ? '#198754' : '#dc3545',
            confirmButtonText: 'Ya, ' + label, cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: spStatusUrl.replace('__ID__', id), method: 'POST', data: { _method: 'PUT', status: status },
                success: function (response) { Swal.fire({ icon: 'success', title: response.message, timer: 1500, showConfirmButton: false }); tableSP.ajax.reload(null, false); },
                error: function (xhr) { Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan.' }); }
            });
        });
    }

    $('#table-sp').on('click', '.btn-approve', function () { updateStatusSP($(this).data('id'), 'APPROVED'); });
    $('#table-sp').on('click', '.btn-reject', function () { updateStatusSP($(this).data('id'), 'REJECTED'); });

    $('#table-sp').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus payment ini?', text: 'Data yang dihapus tidak dapat dikembalikan.', icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: spDeleteUrl.replace('__ID__', id), method: 'POST', data: { _method: 'DELETE' },
                success: function (response) { Swal.fire({ icon: 'success', title: response.message || 'Data dihapus', timer: 1500, showConfirmButton: false }); tableSP.ajax.reload(null, false); },
                error: function (xhr) { const res = xhr.responseJSON || {}; Swal.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Tidak dapat menghapus data.' }); }
            });
        });
    });
</script>
@endpush
