@push('after-style')
<style>
    #table-supplier-balance thead th { font-weight: 600; }
    .badge { font-size: .75rem; }
</style>
@endpush

@push('after-script')
<script>
    // ─────────────────────────────────────────────
    // URLS & REFERENCES
    // ─────────────────────────────────────────────
    const sbsTableUrl = "{{ route('supplier-balance-summary.table') }}";
    const csrfToken   = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

    // ─────────────────────────────────────────────
    // DATATABLE
    // ─────────────────────────────────────────────
    const tableSBS = $('#table-supplier-balance').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: sbsTableUrl,
            data: function(d) {
                d.filter_search = $('#filter-search').val();
                d.filter_status = $('#filter-status').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',      name: 'DT_RowIndex',      orderable: false, searchable: false, className: 'text-center' },
            { data: 'supplier_code',    name: 'supplier_code' },
            { data: 'supplier_name',    name: 'supplier_name' },
            { data: 'total_invoice_fmt', name: 'total_invoice',   className: 'text-end', orderable: false },
            { data: 'total_paid_fmt',   name: 'total_paid',      className: 'text-end', orderable: false },
            { data: 'balance_fmt',      name: 'balance',         className: 'text-end', orderable: false },
            { data: 'status_badge',     name: 'status',          orderable: false, searchable: false, className: 'text-center' },
        ],
        footerCallback: function (row, data, start, end, display) {
            let api = this.api();
            let intVal = function (i) { return typeof i === 'string' ? i.replace(/[^0-9]/g, '') * 1 : typeof i === 'number' ? i : 0; };

            let invTotal = api.column(3, { page: 'current' }).data().reduce((a, b) => a + intVal(b), 0);
            let paidTotal = api.column(4, { page: 'current' }).data().reduce((a, b) => a + intVal(b), 0);
            let balTotal = api.column(5, { page: 'current' }).data().reduce((a, b) => a + intVal(b), 0);

            let fmt = (v) => 'Rp ' + v.toLocaleString('id-ID');

            $('#grand-invoice').text(fmt(invTotal));
            $('#grand-paid').text(fmt(paidTotal));
            $('#grand-balance').text(fmt(balTotal));

            // Also update summary cards
            let allData = api.ajax.json().data || [];
            let sumInv = allData.reduce((a, b) => a + (parseInt(b.total_invoice) || 0), 0);
            let sumPaid = allData.reduce((a, b) => a + (parseInt(b.total_paid) || 0), 0);
            let sumBal = allData.reduce((a, b) => a + (parseInt(b.balance) || 0), 0);

            $('#total-invoice').text(fmt(sumInv));
            $('#total-paid').text(fmt(sumPaid));
            $('#total-balance').text(fmt(sumBal));
            $('#total-suppliers').text(allData.length);
        }
    });

    // ─────────────────────────────────────────────
    // FILTER
    // ─────────────────────────────────────────────
    $('#filter-search').on('keyup', function () {
        tableSBS.ajax.reload();
    });

    $('#filter-status').on('change', function () {
        tableSBS.ajax.reload();
    });

    $('#btn-reset-filter').on('click', function () {
        $('#filter-search').val('');
        $('#filter-status').val('all');
        tableSBS.ajax.reload();
    });

    // ─────────────────────────────────────────────
    // EXPORT (dummy)
    // ─────────────────────────────────────────────
    $('#btn-export').on('click', function () {
        Swal.fire({
            icon: 'info',
            title: 'Export',
            text: 'Fitur export akan terhubung ke backend.',
            confirmButtonText: 'OK'
        });
    });
</script>
@endpush
