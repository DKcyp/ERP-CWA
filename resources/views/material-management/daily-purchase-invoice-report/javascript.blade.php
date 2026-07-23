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
    const dailyTableUrl   = "{{ route('daily-invoice.table') }}";
    const dailySummaryUrl = "{{ route('daily-invoice.summary') }}";
    const dailyShowUrl    = "{{ route('daily-invoice.show', '__ID__') }}";
    const csrfToken       = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

    function getFilterData() {
        return {
            start_date:    $('#filter-start-date').val(),
            end_date:      $('#filter-end-date').val(),
            filter_search: $('#filter-search').val(),
            filter_status: $('#filter-status').val(),
        };
    }

    function loadSummary() {
        $.get(dailySummaryUrl, getFilterData())
            .done(function (res) {
                if (!res.success) return;
                $('#summary-total-invoices').text(res.total_invoices || 0);
                $('#summary-total-items').text(res.total_items || 0);
                $('#summary-total-amount').text(res.total_amount || 'Rp 0');

                const statusLabels = {
                    'DRAFT':     '<span class="badge bg-secondary">Draft: 0</span>',
                    'PENDING':   '<span class="badge bg-warning text-dark">Pending: 0</span>',
                    'APPROVED':  '<span class="badge bg-info text-dark">Approved: 0</span>',
                    'REJECTED':  '<span class="badge bg-danger">Rejected: 0</span>',
                    'PAID':      '<span class="badge bg-success">Paid: 0</span>',
                };
                const counts = res.status_counts || {};
                let html = '';
                Object.keys(statusLabels).forEach(function (s) {
                    const cnt = counts[s] || 0;
                    html += statusLabels[s].replace('0', cnt) + ' ';
                });
                $('#summary-status').html(html || '-');
            });
    }

    const tableDaily = $('#table-daily').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dailyTableUrl,
            data: function(d) { $.extend(d, getFilterData()); }
        },
        columns: [
            { data: 'DT_RowIndex',     name: 'DT_RowIndex',   orderable: false, searchable: false, className: 'text-center' },
            { data: 'invoice_number',  name: 'invoice_number' },
            { data: 'invoice_date_fmt', name: 'invoice_date', className: 'text-center' },
            { data: 'po_number',       name: 'po_number' },
            { data: 'supplier_name',   name: 'supplier_name' },
            { data: 'total_items',     name: 'total_items',   className: 'text-center' },
            { data: 'total_amount',    name: 'total_amount',  className: 'text-end' },
            { data: 'status_badge',    name: 'status',        orderable: false, searchable: false, className: 'text-center' },
            { data: 'action',          name: 'action',        orderable: false, searchable: false, className: 'text-center' },
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
        $.get(dailyShowUrl.replace('__ID__', id))
            .done(function (res) {
                const d = res.data || {};
                $('#detail-inv-number').text(d.invoice_number ?? '-');
                $('#detail-inv-date').text(d.invoice_date ?? '-');
                $('#detail-inv-po').text(d.po_number ?? '-');
                $('#detail-inv-supplier').text(d.supplier_name ?? '-');
                $('#detail-inv-note').text(d.note ?? '-');

                const statusMap = {
                    'DRAFT':     '<span class="badge bg-secondary">Draft</span>',
                    'PENDING':   '<span class="badge bg-warning text-dark">Pending</span>',
                    'APPROVED':  '<span class="badge bg-info text-dark">Approved</span>',
                    'REJECTED':  '<span class="badge bg-danger">Rejected</span>',
                    'PAID':      '<span class="badge bg-success">Paid</span>',
                };
                $('#detail-inv-status').html(statusMap[d.status] ?? d.status);

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
                        tbody.append('<tr><td class="text-center">' + (idx + 1) + '</td><td>' + (item.material ?? '-') + '</td><td class="text-center">' + qty + '</td><td>' + (item.unit ?? '-') + '</td><td class="text-end">' + fmt(price) + '</td><td class="text-end">' + fmt(subtotal) + '</td></tr>');
                    });
                }
                $('#detail-total-amount').text('Rp ' + grandTotal.toLocaleString('id-ID'));
                $('#modal-detail').modal('show');
            })
            .fail(function () { Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat mengambil data.' }); });
    });

    loadSummary();
</script>
@endpush
