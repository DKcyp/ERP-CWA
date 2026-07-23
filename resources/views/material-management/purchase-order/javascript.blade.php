@push('after-style')
<style>
    #table-po thead th { font-weight: 600; }
    .badge { font-size: .75rem; }
</style>
@endpush

@push('after-script')
<script>
    const poTableUrl = "{{ route('purchase-order.table') }}";
    const poShowUrl  = "{{ route('purchase-order.show', '__ID__') }}";
    const poStatusUrl = "{{ route('purchase-order.status', ['id' => '__ID__']) }}";
    const csrfToken  = $('meta[name="csrf-token"]').attr('content');

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
                $('#detail-po-date').text(d.po_date ?? '-');
                $('#detail-po-supplier').text(d.supplier_name ?? '-');
                $('#detail-po-note').text(d.note ?? '-');

                const statusMap = {
                    'DRAFT':     '<span class="badge bg-secondary">Draft</span>',
                    'PENDING':   '<span class="badge bg-warning text-dark">Pending</span>',
                    'APPROVED':  '<span class="badge bg-info text-dark">Approved</span>',
                    'REJECTED':  '<span class="badge bg-danger">Rejected</span>',
                    'FULFILLED': '<span class="badge bg-success">Fulfilled</span>',
                };
                $('#detail-po-status').html(statusMap[d.status] ?? d.status);

                const tbody = $('#detail-items-tbody');
                tbody.empty();

                let grandTotal = 0;
                if (!d.items || !d.items.length) {
                    tbody.append('<tr><td colspan="6" class="text-center text-muted py-3">Tidak ada item</td></tr>');
                } else {
                    const fmt = (n) => 'Rp ' + n.toLocaleString('id-ID');
                    d.items.forEach(function (item, idx) {
                        const qty = item.qty || 0;
                        const price = item.price || 0;
                        const subtotal = qty * price;
                        grandTotal += subtotal;
                        tbody.append(`
                            <tr>
                                <td class="text-center">${idx + 1}</td>
                                <td>${item.material ?? '-'}</td>
                                <td class="text-center">${qty}</td>
                                <td>${item.unit ?? '-'}</td>
                                <td class="text-end">${fmt(price)}</td>
                                <td class="text-end">${fmt(subtotal)}</td>
                            </tr>
                        `);
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
</script>
@endpush
