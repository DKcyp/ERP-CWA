@push('after-style')
<style>
    #table-monthly thead th { font-weight: 600; }
    .badge { font-size: .75rem; }
    #summary-cards .card { border-radius: .5rem; }
    #summary-cards .card-body { padding: 1rem .75rem; }
</style>
@endpush

@push('after-script')
<script>
    const monthlyTableUrl = "{{ route('monthly-supplier.table') }}";
    const monthlySummaryUrl = "{{ route('monthly-supplier.summary') }}";
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

    function getFilterData() {
        return {
            month: $('#filter-month').val(),
            year:  $('#filter-year').val(),
        };
    }

    function loadSummary() {
        $.get(monthlySummaryUrl, getFilterData())
            .done(function (res) {
                if (!res.success) return;
                $('#summary-period').text(res.period || '-');
                $('#summary-suppliers').text(res.total_suppliers || 0);
                $('#summary-amount').text(res.total_amount || 'Rp 0');
            });
    }

    const tableMonthly = $('#table-monthly').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: monthlyTableUrl,
            data: function(d) { $.extend(d, getFilterData()); }
        },
        columns: [
            { data: 'DT_RowIndex',      name: 'DT_RowIndex',     orderable: false, searchable: false, className: 'text-center' },
            { data: 'supplier_name',    name: 'supplier_name' },
            { data: 'total_invoices',   name: 'total_invoices',  className: 'text-center' },
            { data: 'total_items',      name: 'total_items',     className: 'text-center' },
            { data: 'total_amount_fmt', name: 'total_amount',   className: 'text-end' },
            { data: 'status_summary',   name: 'status_summary',  orderable: false, searchable: false },
        ],
    });

    tableMonthly.on('draw', function () { loadSummary(); });

    $('#btn-filter').on('click', function () { tableMonthly.ajax.reload(); });
    $('#btn-reset-filter').on('click', function () {
        $('#filter-month').val('07');
        $('#filter-year').val('2026');
        tableMonthly.ajax.reload();
    });

    loadSummary();
</script>
@endpush
