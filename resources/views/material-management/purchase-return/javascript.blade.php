@push('after-style')
<style>
    #table-pr thead th { font-weight: 600; font-size: .78rem; }
    .badge { font-size: .75rem; }
    #table-items tbody tr { vertical-align: middle; }
</style>
@endpush

@push('after-script')
<script>
    const prTableUrl  = "{{ route('purchase-return.table') }}";
    const prStoreUrl  = "{{ route('purchase-return.store') }}";
    const prShowUrl   = "{{ route('purchase-return.show', '__ID__') }}";
    const prUpdateUrl = "{{ route('purchase-return.update', '__ID__') }}";
    const prDeleteUrl = "{{ route('purchase-return.destroy', ['id' => '__ID__']) }}";
    const prStatusUrl = "{{ route('purchase-return.status', ['id' => '__ID__']) }}";
    const csrfToken   = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

    const tablePR = $('#table-pr').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: {
            url: prTableUrl,
            data: function(d) {
                d.filter_search = $('#filter-search').val();
                d.filter_status = $('#filter-status').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',            name: 'DT_RowIndex',         orderable: false, searchable: false, className: 'text-center' },
            { data: 'return_number',          name: 'return_number' },
            { data: 'return_date_fmt',        name: 'return_date',         className: 'text-center' },
            { data: 'warehouse',              name: 'warehouse' },
            { data: 'supplier_id',            name: 'supplier_id',         className: 'text-center' },
            { data: 'supplier_name',          name: 'supplier_name' },
            { data: 'note',                   name: 'note' },
            { data: 'currency',               name: 'currency',            className: 'text-center' },
            { data: 'term',                   name: 'term' },
            { data: 'discount_percent_fmt',   name: 'discount_percent',    className: 'text-center' },
            { data: 'discount_amount_fmt',    name: 'discount_amount',     className: 'text-end' },
            { data: 'total_return_amount_fmt', name: 'total_return_amount', className: 'text-end' },
            { data: 'status_badge',           name: 'status',              orderable: false, searchable: false, className: 'text-center' },
            { data: 'user_name',              name: 'user_name' },
            { data: 'account',                name: 'account' },
            { data: 'price_list',             name: 'price_list' },
            { data: 'action',                 name: 'action',              orderable: false, searchable: false, className: 'text-end' },
        ],
    });

    $('#filter-search').on('keyup', function () { tablePR.ajax.reload(); });
    $('#filter-status').on('change', function () { tablePR.ajax.reload(); });

    $('#btn-reset-filter').on('click', function () {
        $('#filter-search').val('');
        $('#filter-status').val('all');
        tablePR.ajax.reload();
    });

    // ─── MODAL ──────────────────────────────────
    const modalPR   = $('#modal-pr');
    const formPR    = $('#form-pr');
    const idInputPR = $('#pr_id');

    function resetFormPR() {
        formPR[0].reset();
        idInputPR.val('');
        formPR.find('.is-invalid').removeClass('is-invalid');
        formPR.find('.invalid-feedback').remove();
        modalPR.find('.modal-title').text('Tambah Purchase Return');
        $('#items-tbody').html('<tr class="empty-row"><td colspan="7" class="text-center text-muted py-3"><i class="bi bi-inbox me-1"></i> Belum ada item. Klik (+) untuk menambah.</td></tr>');
        updateTotal();
    }

    $('#btn-add-pr').on('click', function () { resetFormPR(); modalPR.modal('show'); });
    modalPR.on('hidden.bs.modal', function () { resetFormPR(); });

    function handleErrors(errors) {
        Object.entries(errors).forEach(([key, messages]) => {
            const input = formPR.find('[name="' + key + '"]').first();
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
            const qty = parseInt($(this).find('.item-qty').val()) || 0;
            const price = parseInt($(this).find('.item-price').val()) || 0;
            const subtotal = qty * price;
            $(this).find('.item-subtotal').text('Rp ' + subtotal.toLocaleString('id-ID'));
            total += subtotal;
        });
        const discAmount = parseInt($('#pr_disc_amount').val()) || 0;
        const totalAfterDisc = total - discAmount;
        $('#total-amount').text('Rp ' + totalAfterDisc.toLocaleString('id-ID'));
    }

    $('#pr_disc_amount').on('input', updateTotal);

    function addItemRow(data) {
        data = data || {};
        const no = $('#items-tbody tr').not('.empty-row').length + 1;
        $('#items-tbody .empty-row').remove();
        const row = '<tr>' +
            '<td class="text-center item-no">' + no + '</td>' +
            '<td><input type="text" class="form-control form-control-sm item-material" value="' + (data.material || '') + '" placeholder="Nama material" required></td>' +
            '<td><input type="number" class="form-control form-control-sm item-qty text-center" value="' + (data.qty || '') + '" min="1" required></td>' +
            '<td><input type="text" class="form-control form-control-sm item-unit" value="' + (data.unit || '') + '" placeholder="Unit"></td>' +
            '<td><input type="number" class="form-control form-control-sm item-price text-end" value="' + (data.price || '') + '" min="0" required></td>' +
            '<td class="text-end fw-semibold item-subtotal">Rp 0</td>' +
            '<td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-item"><i class="bi bi-x-lg"></i></button></td>' +
        '</tr>';
        $('#items-tbody').append(row);
        updateTotal();
        $(row).find('.item-qty, .item-price').on('input', function () { updateTotal(); });
    }

    $('#btn-add-item').on('click', function () { addItemRow(); });

    $(document).on('click', '.btn-remove-item', function () {
        $(this).closest('tr').remove();
        $('#items-tbody tr').not('.empty-row').each(function (i) {
            $(this).find('.item-no').text(i + 1);
        });
        if (!$('#items-tbody tr').not('.empty-row').length) {
            $('#items-tbody').html('<tr class="empty-row"><td colspan="7" class="text-center text-muted py-3"><i class="bi bi-inbox me-1"></i> Belum ada item. Klik (+) untuk menambah.</td></tr>');
        }
        updateTotal();
    });

    // ─── DISCOUNT AUTO-CALC ──────────────────────
    $('#pr_disc_percent').on('input', function () {
        const pct = parseInt($(this).val()) || 0;
        if (pct > 0) {
            let subtotal = 0;
            $('#items-tbody tr').not('.empty-row').each(function () {
                const qty = parseInt($(this).find('.item-qty').val()) || 0;
                const price = parseInt($(this).find('.item-price').val()) || 0;
                subtotal += qty * price;
            });
            const discAmount = Math.round(subtotal * pct / 100);
            $('#pr_disc_amount').val(discAmount);
            updateTotal();
        }
    });

    // ─── SAVE ────────────────────────────────────
    window.onSavePR = () => {
        const id = idInputPR.val();
        const url = id ? prUpdateUrl.replace('__ID__', id) : prStoreUrl;

        const items = [];
        $('#items-tbody tr').not('.empty-row').each(function () {
            const material = $(this).find('.item-material').val() || '';
            const qty = parseInt($(this).find('.item-qty').val()) || 0;
            const unit = $(this).find('.item-unit').val() || '';
            const price = parseInt($(this).find('.item-price').val()) || 0;
            if (material && qty > 0) {
                items.push({ material, qty, unit, price });
            }
        });

        const formData = formPR.serializeArray();
        formData.push({ name: 'items', value: JSON.stringify(items) });
        if (id) formData.push({ name: '_method', value: 'PUT' });

        $.ajax({
            url, type: 'POST', data: formData, dataType: 'json',
            success: function (data) {
                Swal.fire({ title: 'Sukses!', text: data.message, icon: 'success', confirmButtonText: 'OK' })
                    .then(() => { resetFormPR(); modalPR.modal('hide'); tablePR.ajax.reload(null, false); });
            },
            error: function (xhr) {
                const res = xhr.responseJSON || {};
                if (xhr.status === 422 && res.errors) { handleErrors(res.errors); }
                else { Swal.fire({ icon: 'error', title: 'Error', text: res.message || 'Terjadi kesalahan sistem.' }); }
            }
        });
    };

    // ─── EDIT ────────────────────────────────────
    $('#table-pr').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        resetFormPR();
        $.get(prShowUrl.replace('__ID__', id)).done(function (response) {
            const d = response.data || {};
            idInputPR.val(d.id);
            $('#pr_number').val(d.return_number ?? '');
            $('#pr_date').val(d.return_date ?? '');
            $('#pr_warehouse').val(d.warehouse ?? '');
            $('#pr_supplier').val(d.supplier_name ?? '');
            $('#pr_supplier_id').val(d.supplier_id ?? '');
            $('#pr_currency').val(d.currency ?? 'IDR');
            $('#pr_term').val(d.term ?? '');
            $('#pr_price_list').val(d.price_list ?? '');
            $('#pr_user').val(d.user_name ?? '');
            $('#pr_account').val(d.account ?? '');
            $('#pr_disc_percent').val(d.discount_percent ?? 0);
            $('#pr_disc_amount').val(d.discount_amount ?? 0);
            $('#pr_note').val(d.note ?? '');
            $('#pr_status').val(d.status ?? 'DRAFT');

            (d.items || []).forEach(function (item) { addItemRow(item); });

            modalPR.find('.modal-title').text('Edit Purchase Return');
            modalPR.modal('show');
        }).fail(function () { Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat mengambil data.' }); });
    });

    // ─── APPROVE ─────────────────────────
    function updateStatusPR(id, status) {
        const label = status === 'APPROVED' ? 'approve' : 'complete';
        Swal.fire({
            title: 'Yakin akan ' + label + ' return ini?', icon: 'question', showCancelButton: true,
            confirmButtonColor: status === 'APPROVED' ? '#198754' : '#0d6efd',
            confirmButtonText: 'Ya, ' + label, cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: prStatusUrl.replace('__ID__', id), method: 'POST', data: { _method: 'PUT', status: status },
                success: function (response) { Swal.fire({ icon: 'success', title: response.message, timer: 1500, showConfirmButton: false }); tablePR.ajax.reload(null, false); },
                error: function (xhr) { Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan.' }); }
            });
        });
    }

    $('#table-pr').on('click', '.btn-approve', function () { updateStatusPR($(this).data('id'), 'APPROVED'); });

    // ─── DELETE ──────────────────────────────────
    $('#table-pr').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus return ini?', text: 'Data yang dihapus tidak dapat dikembalikan.', icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: prDeleteUrl.replace('__ID__', id), method: 'POST', data: { _method: 'DELETE' },
                success: function (response) { Swal.fire({ icon: 'success', title: response.message || 'Data dihapus', timer: 1500, showConfirmButton: false }); tablePR.ajax.reload(null, false); },
                error: function (xhr) { const res = xhr.responseJSON || {}; Swal.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Tidak dapat menghapus data.' }); }
            });
        });
    });
</script>
@endpush
