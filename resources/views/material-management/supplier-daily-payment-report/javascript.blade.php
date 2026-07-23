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
    const dailyTableUrl   = "{{ route('daily-supplier-payment.table') }}";
    const dailySummaryUrl = "{{ route('daily-supplier-payment.summary') }}";
    const dailyShowUrl    = "{{ route('daily-supplier-payment.show', '__ID__') }}";
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
        const params = getFilterData();
        const hasDate = params.start_date || params.end_date;
        $.get(dailySummaryUrl, params)
            .done(function (res) {
                if (!res.success) return;
                $('#summary-total-payments').text(res.total_payments || 0);
                $('#summary-total-amount').text(res.total_amount || 'Rp 0');

                let totalItems = 0;
                $('#summary-total-items').text(totalItems);

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
            { data: 'DT_RowIndex',       name: 'DT_RowIndex',   orderable: false, searchable: false, className: 'text-center' },
            { data: 'payment_number',    name: 'payment_number' },
            { data: 'payment_date_fmt',  name: 'payment_date',  className: 'text-center' },
            { data: 'supplier_name',     name: 'supplier_name' },
            { data: 'currency',          name: 'currency',      className: 'text-center' },
            { data: 'total_paid_fmt',    name: 'total_paid',    className: 'text-end' },
            { data: 'account',           name: 'account' },
            { data: 'status_badge',      name: 'status',        orderable: false, searchable: false, className: 'text-center' },
            { data: 'action',            name: 'action',        orderable: false, searchable: false, className: 'text-center' },
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
                $('#detail-payment-number').text(d.payment_number ?? '-');
                $('#detail-payment-date').text(d.payment_date ?? '-');
                $('#detail-payment-supplier').text(d.supplier_name ?? '-');
                $('#detail-payment-currency').text(d.currency ?? 'IDR');
                $('#detail-payment-account').text(d.account ?? '-');
                $('#detail-payment-user').text(d.user_name ?? '-');
                $('#detail-payment-invoice').text(d.invoice_number ?? '-');
                $('#detail-payment-stbj').text(d.stbj_number ?? '-');
                $('#detail-payment-type').text(d.payment_type ?? '-');
                $('#detail-payment-complete').text(d.complete_date ?? '-');
                $('#detail-payment-note').text(d.note ?? '-');

                const statusMap = {
                    'DRAFT':     '<span class="badge bg-secondary">Draft</span>',
                    'PENDING':   '<span class="badge bg-warning text-dark">Pending</span>',
                    'APPROVED':  '<span class="badge bg-info text-dark">Approved</span>',
                    'REJECTED':  '<span class="badge bg-danger">Rejected</span>',
                    'PAID':      '<span class="badge bg-success">Paid</span>',
                };
                $('#detail-payment-status').html(statusMap[d.status] ?? d.status);

                const tbody = $('#detail-items-tbody');
                tbody.empty();
                let grandTotal = 0;
                if (!d.items || !d.items.length) {
                    tbody.append('<tr><td colspan="3" class="text-center text-muted py-3">Tidak ada item</td></tr>');
                } else {
                    const fmt = (n) => 'Rp ' + n.toLocaleString('id-ID');
                    d.items.forEach(function (item, idx) {
                        const amount = item.amount || 0;
                        grandTotal += amount;
                        tbody.append('<tr><td class="text-center">' + (idx + 1) + '</td><td>' + (item.description ?? '-') + '</td><td class="text-end">' + fmt(amount) + '</td></tr>');
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
