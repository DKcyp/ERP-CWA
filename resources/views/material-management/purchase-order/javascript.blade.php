@push('after-style')
<style>
    #table-po thead th { font-weight: 600; }
    .badge { font-size: .75rem; }
    #table-items tbody tr { vertical-align: middle; }
    #table-items thead th { border-top: 0 !important; border-bottom-width: 2px; }
    #table-items tbody tr:hover { background-color: rgba(13, 110, 253, 0.05); }
    #table-items .btn-remove-item { width: 38px; height: 38px; padding: 0; }
    #table-items .btn-remove-item i { font-size: 1rem; }
    #modal-po .modal-content { border-radius: 0.9rem; }
    #modal-po .modal-header { border-bottom: 1px solid #e9ecef; }
    #modal-po .modal-footer { border-top: 1px solid #e9ecef; }
</style>
@endpush

@push('after-script')
<script>
    const poTableUrl   = "{{ route('purchase-order.table') }}";
    const poStoreUrl   = "{{ route('purchase-order.store') }}";
    const poShowUrl    = "{{ route('purchase-order.show', '__ID__') }}";
    const poStatusUrl  = "{{ route('purchase-order.status', ['id' => '__ID__']) }}";
    const csrfToken    = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

    // ─── DATATABLE ─────────────────────────────────
    const tablePO = $('#table-po').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: poTableUrl,
            data: function(d) {
                d.filter_search = $('#filter-search').val();
                d.filter_status = $('#filter-status').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',   name: 'DT_RowIndex',   orderable: false, searchable: false, className: 'text-center' },
            { data: 'pr_number',     name: 'pr_number' },
            { data: 'po_number',     name: 'po_number' },
            { data: 'po_date_fmt',   name: 'po_date',       className: 'text-center' },
            { data: 'supplier_name', name: 'supplier_name' },
            { data: 'total_items',   name: 'total_items',   className: 'text-center' },
            { data: 'total_amount',  name: 'total_amount',  className: 'text-end' },
            { data: 'status_badge',  name: 'status',        orderable: false, searchable: false, className: 'text-center' },
            { data: 'action',        name: 'action',        orderable: false, searchable: false, className: 'text-center' },
        ],
    });

    // ─── FILTER ────────────────────────────────────
    $('#filter-search').on('keyup', function () { tablePO.ajax.reload(); });
    $('#filter-status').on('change', function () { tablePO.ajax.reload(); });

    $('#btn-reset-filter').on('click', function () {
        $('#filter-search').val('');
        $('#filter-status').val('all');
        tablePO.ajax.reload();
    });

    // ─── DETAIL MODAL ─────────────────────────────
    $('#table-po').on('click', '.btn-detail', function () {
        const id = $(this).data('id');
        $.get(poShowUrl.replace('__ID__', id))
            .done(function (res) {
                const d = res.data || {};
                $('#detail-po-number').text(d.po_number ?? '-');
                $('#detail-po-pr').text(d.pr_number ?? '-');
                $('#detail-po-date').text(d.po_date ?? '-');
                $('#detail-po-supplier').text(d.supplier_name ?? '-');
                $('#detail-po-note').text(d.note ?? '-');
                const statusMap = {
                    'DRAFT': '<span class="badge bg-secondary">Draft</span>',
                    'PENDING': '<span class="badge bg-warning text-dark">Pending</span>',
                    'APPROVED': '<span class="badge bg-info text-dark">Approved</span>',
                    'REJECTED': '<span class="badge bg-danger">Rejected</span>',
                    'FULFILLED': '<span class="badge bg-success">Fulfilled</span>',
                };
                $('#detail-po-status').html(statusMap[d.status] ?? d.status);
                const tbody = $('#detail-items-tbody');
                tbody.empty();
                let grandTotal = 0;
                const fmt = (n) => 'Rp ' + n.toLocaleString('id-ID');
                if (!d.items || !d.items.length) {
                    tbody.append('<tr><td colspan="6" class="text-center text-muted py-3">Tidak ada item</td></tr>');
                } else {
                    d.items.forEach(function (item, idx) {
                        const qty = item.qty || 0;
                        const price = item.price || 0;
                        const subtotal = qty * price;
                        grandTotal += subtotal;
                        tbody.append('<tr><td class="text-center">'+(idx+1)+'</td><td>'+(item.material??'-')+'</td><td class="text-center">'+qty+'</td><td>'+(item.unit??'-')+'</td><td class="text-end">'+fmt(price)+'</td><td class="text-end">'+fmt(subtotal)+'</td></tr>');
                    });
                }
                $('#detail-total-amount').text('Rp ' + grandTotal.toLocaleString('id-ID'));
                $('#modal-detail').modal('show');
            })
            .fail(function () {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat mengambil data.' });
            });
    });

    // ─── APPROVE / REJECT ─────────────────────────
    function updateStatusPO(id, status) {
        const label = status === 'APPROVED' ? 'approve' : 'reject';
        Swal.fire({
            title: 'Yakin akan ' + label + ' PO ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: status === 'APPROVED' ? '#198754' : '#dc3545',
            confirmButtonText: 'Ya, ' + label,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: poStatusUrl.replace('__ID__', id),
                method: 'POST',
                data: { _method: 'PUT', status: status },
                success: function (response) {
                    Swal.fire({ icon: 'success', title: response.message, timer: 1500, showConfirmButton: false });
                    tablePO.ajax.reload(null, false);
                },
                error: function (xhr) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan.' });
                }
            });
        });
    }

    $('#table-po').on('click', '.btn-approve', function () { updateStatusPO($(this).data('id'), 'APPROVED'); });
    $('#table-po').on('click', '.btn-reject', function () { updateStatusPO($(this).data('id'), 'REJECTED'); });

    // ─── DYNAMIC ITEMS ────────────────────────────
    let itemIndex = 0;

    function calcSubtotal(row) {
        const qty = parseInt($(row).find('.item-qty').val()) || 0;
        const price = parseInt($(row).find('.item-price').val()) || 0;
        $(row).find('.item-subtotal').text('Rp ' + (qty * price).toLocaleString('id-ID'));
    }

    function calcTotal() {
        let total = 0;
        $('#items-tbody tr:visible').not('#row-empty').each(function () {
            const qty = parseInt($(this).find('.item-qty').val()) || 0;
            const price = parseInt($(this).find('.item-price').val()) || 0;
            total += qty * price;
        });
        $('#po-total-items').text('Rp ' + total.toLocaleString('id-ID'));
    }

    function addItemRow(data) {
        const tbody = $('#items-tbody');
        $('#row-empty').hide();
        const i = itemIndex++;
        const material = data?.material ?? '';
        const qty = data?.qty ?? '';
        const unit = data?.unit ?? '';
        const price = data?.price ?? '';
        const subtotal = (parseInt(qty) || 0) * (parseInt(price) || 0);
        const row = '<tr><td class="text-center item-no">'+(tbody.find('tr:visible').length+1)+
            '</td><td><input type="text" class="form-control form-control-sm item-material" name="items['+i+'][material]" value="'+material+'" placeholder="Nama material" maxlength="200"></td>'+
            '<td><input type="number" class="form-control form-control-sm item-qty" name="items['+i+'][qty]" value="'+qty+'" placeholder="0" min="1" step="1" oninput="calcSubtotal(this.closest(\'tr\'));calcTotal()"></td>'+
            '<td><input type="text" class="form-control form-control-sm item-unit" name="items['+i+'][unit]" value="'+unit+'" placeholder="Pcs" maxlength="50"></td>'+
            '<td><input type="number" class="form-control form-control-sm item-price" name="items['+i+'][price]" value="'+price+'" placeholder="0" min="0" step="1" oninput="calcSubtotal(this.closest(\'tr\'));calcTotal()"></td>'+
            '<td class="text-end fw-semibold item-subtotal">Rp ' + subtotal.toLocaleString('id-ID') + '</td>'+
            '<td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-item"><i class="bi bi-x-lg"></i></button></td></tr>';
        tbody.append(row);
        renumberItems();
        calcTotal();
    }

    function renumberItems() {
        $('#items-tbody tr:visible').each(function (idx) { $(this).find('.item-no').text(idx + 1); });
    }

    $('#btn-add-item').on('click', function () { addItemRow(); });

    $('#items-tbody').on('click', '.btn-remove-item', function () {
        $(this).closest('tr').remove();
        if ($('#items-tbody').find('tr:visible').length === 0) $('#row-empty').show();
        renumberItems();
        calcTotal();
    });

    function resetItems() {
        itemIndex = 0;
        $('#items-tbody tr:not(#row-empty)').remove();
        $('#row-empty').show();
        calcTotal();
    }

    function populateItems(items) {
        resetItems();
        if (items && items.length) items.forEach(function (item) { addItemRow(item); });
    }

    function collectItems() {
        const items = [];
        $('#items-tbody tr:visible').not('#row-empty').each(function () {
            const material = $(this).find('.item-material').val() || '';
            const qty = parseInt($(this).find('.item-qty').val()) || 0;
            const unit = $(this).find('.item-unit').val() || '';
            const price = parseInt($(this).find('.item-price').val()) || 0;
            if (material) items.push({ material, qty, unit, price });
        });
        return items;
    }

    // ─── MODAL ─────────────────────────────────────
    const modalPO = $('#modal-po'), formPO = $('#form-po'), idInputPO = $('#po_id');

    function resetFormPO() {
        formPO[0].reset();
        idInputPO.val('');
        formPO.find('.is-invalid').removeClass('is-invalid');
        formPO.find('.invalid-feedback').remove();
        modalPO.find('.modal-title').text('Tambah PO');
        resetItems();
    }

    $('#btn-add').on('click', function () { resetFormPO(); modalPO.modal('show'); });
    modalPO.on('hidden.bs.modal', resetFormPO);

    function handleErrors(errors) {
        Object.entries(errors).forEach(([key, messages]) => {
            const input = formPO.find('[name="' + key + '"]').first();
            if (!input.length) return;
            input.addClass('is-invalid');
            input.closest('.col-4, .col-12').append('<div class="invalid-feedback">' + messages[0] + '</div>');
        });
    }

    window.onSave = function () {
        const id = idInputPO.val();
        const url = id ? "{{ route('purchase-order.update', '__ID__') }}".replace('__ID__', id) : poStoreUrl;
        const formData = formPO.serializeArray();
        formData.push({ name: 'items', value: JSON.stringify(collectItems()) });
        if (id) formData.push({ name: '_method', value: 'PUT' });

        $.ajax({
            url, type: 'POST', data: formData, dataType: 'json',
            success: function (d) {
                Swal.fire({ title: 'Sukses!', text: d.message, icon: 'success', confirmButtonText: 'OK' })
                    .then(function () { resetFormPO(); modalPO.modal('hide'); tablePO.ajax.reload(null, false); });
            },
            error: function (x) {
                const r = x.responseJSON || {};
                if (x.status === 422 && r.errors) handleErrors(r.errors);
                else Swal.fire({ icon: 'error', title: 'Error', text: r.message || 'Terjadi kesalahan.' });
            }
        });
    };

    // ─── EDIT ──────────────────────────────────────
    const poUpdateUrl = "{{ route('purchase-order.update', '__ID__') }}";

    $('#table-po').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        resetFormPO();
        $.get(poShowUrl.replace('__ID__', id)).done(function (res) {
            const d = res.data || {};
            idInputPO.val(d.id);
            $('#f_pr_number').val(d.pr_number ?? '');
            $('#f_po_number').val(d.po_number ?? '');
            $('#f_po_date').val(d.po_date ?? '');
            $('#f_supplier').val(d.supplier_name ?? '');
            $('#f_supplier_code').val(d.supplier_code ?? '');
            $('#f_note').val(d.note ?? '');
            $('#f_status').val(d.status ?? 'DRAFT');
            populateItems(d.items ?? []);
            modalPO.find('.modal-title').text('Edit PO');
            modalPO.modal('show');
        }).fail(function () { Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat mengambil data.' }); });
    });

    // ─── DELETE ────────────────────────────────────
    const poDeleteUrl = "{{ route('purchase-order.destroy', ['id' => '__ID__']) }}";

    $('#table-po').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus PO ini?', text: 'Data yang dihapus tidak dapat dikembalikan.', icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal'
        }).then((r) => {
            if (!r.isConfirmed) return;
            $.ajax({
                url: poDeleteUrl.replace('__ID__', id), method: 'POST', data: { _method: 'DELETE' },
                success: function (r2) { Swal.fire({ icon: 'success', title: r2.message || 'Data dihapus', timer: 1500, showConfirmButton: false }); tablePO.ajax.reload(null, false); },
                error: function (x) { const r3 = x.responseJSON || {}; Swal.fire({ icon: 'error', title: 'Gagal', text: r3.message || 'Tidak dapat menghapus.' }); }
            });
        });
    });
</script>
@endpush