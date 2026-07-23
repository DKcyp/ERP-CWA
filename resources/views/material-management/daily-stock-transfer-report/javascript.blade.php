@push('after-style')
<style>
    #table-daily thead th { font-weight: 600; }
    .badge { font-size: .75rem; }
    #summary-cards .card { border-radius: .5rem; }
    #summary-cards .card-body { padding: 1rem .75rem; }
</style>
@endpush

@push('after-script')
<script>
    const tableUrl   = "{{ route('daily-stock-transfer.table') }}";
    const summaryUrl = "{{ route('daily-stock-transfer.summary') }}";
    const showUrl    = "{{ route('daily-stock-transfer.show', '__ID__') }}";
    const csrfToken  = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

    function getFilterData() {
        return {
            start_date: $('#filter-start-date').val(),
            end_date:   $('#filter-end-date').val(),
            filter_search: $('#filter-search').val(),
            filter_status: $('#filter-status').val(),
        };
    }

    function loadSummary() {
        $.get(summaryUrl, getFilterData())
            .done(function (res) {
                if (!res.success) return;
                $('#summary-total-transfers').text(res.total_transfers || 0);
                $('#summary-total-items').text(res.total_items || 0);
                $('#summary-total-qty').text(res.total_qty || 0);

                const statusLabels = {
                    'PREPARATION': '<span class="badge bg-secondary">Preparation: 0</span>',
                    'SHIPMENT':    '<span class="badge bg-info text-dark">Shipment: 0</span>',
                    'TRANSFER':    '<span class="badge bg-success">Transfer: 0</span>',
                };
                const counts = res.status_counts || {};
                let html = '';
                Object.keys(statusLabels).forEach(function (s) {
                    const cnt = counts[s] || 0;
                    html += statusLabels[s].replace(': 0', ': ' + cnt) + ' ';
                });
                $('#summary-status').html(html || '-');
            });
    }

    const tableDaily = $('#table-daily').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: tableUrl,
            data: function(d) { $.extend(d, getFilterData()); }
        },
        columns: [
            { data: 'DT_RowIndex',     name: 'DT_RowIndex',     orderable: false, searchable: false, className: 'text-center' },
            { data: 'transfer_number', name: 'transfer_number' },
            { data: 'transfer_date_fmt', name: 'transfer_date', className: 'text-center' },
            { data: 'from_warehouse',  name: 'from_warehouse' },
            { data: 'to_warehouse',    name: 'to_warehouse' },
            { data: 'pic',             name: 'pic' },
            { data: 'total_items',     name: 'total_items',     className: 'text-center' },
            { data: 'total_qty',       name: 'total_qty',       className: 'text-center' },
            { data: 'status_badge',    name: 'status',          orderable: false, searchable: false, className: 'text-center' },
            { data: 'action',          name: 'action',          orderable: false, searchable: false, className: 'text-center' },
        ],
    });

    tableDaily.on('draw', function () { loadSummary(); });

    $('#btn-filter').on('click', function () { tableDaily.ajax.reload(); });
    $('#btn-reset-filter').on('click', function () {
        $('#filter-start-date').val('');
        $('#filter-end-date').val('');
        $('#filter-search').val('');
        $('#filter-status').val('all');
        tableDaily.ajax.reload();
    });

    $('#table-daily').on('click', '.btn-detail', function () {
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
                let totalQty = 0;
                (d.items || []).forEach(function (item, idx) {
                    const qty = parseFloat(item.qty) || 0;
                    totalQty += qty;
                    tbody.append(`
                        <tr>
                            <td class="text-center">${idx + 1}</td>
                            <td>${item.material ?? '-'}</td>
                            <td class="text-center">${qty}</td>
                            <td>${item.unit ?? '-'}</td>
                            <td>${item.notes ?? '-'}</td>
                        </tr>
                    `);
                });
                $('#detail-total-qty').text(totalQty);
                $('#modal-detail').modal('show');
            })
            .fail(function () { Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat mengambil data.' }); });
    });

    loadSummary();
</script>
@endpush
