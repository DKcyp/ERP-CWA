@push('after-style')
<style>
    #table-fulfilment thead th { font-weight: 600; }
    .badge { font-size: .75rem; }
    .progress { border-radius: .5rem; }
    .progress-bar { font-size: .7rem; line-height: 20px; }
</style>
@endpush

@push('after-script')
<script>
    const tableUrl   = "{{ route('stock-transfer-fulfilment.table') }}";
    const showUrl    = "{{ route('stock-transfer-fulfilment.show', '__ID__') }}";
    const csrfToken  = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

    const table = $('#table-fulfilment').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: tableUrl,
            data: function(d) {
                d.filter_search = $('#filter-search').val();
                d.filter_status = $('#filter-status').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',      name: 'DT_RowIndex',      orderable: false, searchable: false, className: 'text-center' },
            { data: 'transfer_number',  name: 'transfer_number' },
            { data: 'transfer_date_fmt', name: 'transfer_date',   className: 'text-center' },
            { data: 'from_warehouse',   name: 'from_warehouse' },
            { data: 'to_warehouse',     name: 'to_warehouse' },
            { data: 'pic',              name: 'pic' },
            { data: 'total_items',      name: 'total_items',      className: 'text-center' },
            { data: 'fulfilled_items',  name: 'fulfilled_items',  className: 'text-center' },
            { data: 'progress_bar',     name: 'progress_bar',     orderable: false, searchable: false, className: 'text-center' },
            { data: 'status_badge',     name: 'status',           orderable: false, searchable: false, className: 'text-center' },
            { data: 'action',           name: 'action',           orderable: false, searchable: false, className: 'text-center' },
        ],
    });

    $('#filter-search').on('keyup', function () { table.ajax.reload(); });
    $('#filter-status').on('change', function () { table.ajax.reload(); });

    $('#btn-reset-filter').on('click', function () {
        $('#filter-search').val('');
        $('#filter-status').val('all');
        table.ajax.reload();
    });

    $('#table-fulfilment').on('click', '.btn-detail', function () {
        const id = $(this).data('id');
        $.get(showUrl.replace('__ID__', id))
            .done(function (res) {
                const d = res.data || {};
                $('#detail-number').text(d.transfer_number ?? '-');
                $('#detail-date').text(d.transfer_date ?? '-');
                $('#detail-from').text(d.from_warehouse ?? '-');
                $('#detail-to').text(d.to_warehouse ?? '-');
                $('#detail-pic').text(d.pic ?? '-');
                $('#detail-note').text(d.reason ?? '-');

                const statusMap = {
                    'PREPARATION': '<span class="badge bg-secondary">Preparation</span>',
                    'SHIPMENT':    '<span class="badge bg-info text-dark">Shipment</span>',
                    'TRANSFER':    '<span class="badge bg-success">Transfer</span>',
                };
                $('#detail-status').html(statusMap[d.status] ?? d.status);

                const tbody = $('#detail-items-tbody');
                tbody.empty();
                let totalQty = 0, totalFulfilled = 0;
                (d.items || []).forEach(function (item, idx) {
                    const qty = parseFloat(item.qty) || 0;
                    const fulfilled = parseFloat(item.qty_fulfilled) || 0;
                    totalQty += qty;
                    totalFulfilled += fulfilled;
                    const pct = qty > 0 ? Math.round((fulfilled / qty) * 100) : 0;
                    const color = pct === 100 ? 'bg-success' : (pct >= 50 ? 'bg-warning' : 'bg-danger');
                    tbody.append(`
                        <tr>
                            <td class="text-center">${idx + 1}</td>
                            <td>${item.material ?? '-'}</td>
                            <td class="text-center">${qty}</td>
                            <td class="text-center fw-semibold">${fulfilled}</td>
                            <td>${item.unit ?? '-'}</td>
                            <td>
                                <div class="progress" style="height:18px;">
                                    <div class="progress-bar ${color} fw-semibold" style="width:${pct}%;font-size:.65rem;line-height:18px;">${pct}%</div>
                                </div>
                            </td>
                        </tr>
                    `);
                });
                $('#detail-total-qty').text(totalQty);
                $('#detail-total-fulfilled').text(totalFulfilled);
                $('#modal-detail').modal('show');
            })
            .fail(function () { Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat mengambil data.' }); });
    });
</script>
@endpush
