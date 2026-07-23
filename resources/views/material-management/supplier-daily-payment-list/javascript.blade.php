@push('after-style')
<style>
    #table-dpl thead th { font-weight: 600; font-size: .75rem; }
    .badge { font-size: .75rem; }
    #table-dpl td { font-size: .8rem; }
</style>
@endpush

@push('after-script')
<script>
    const dplTableUrl = "{{ route('daily-supplier-payment-list.table') }}";
    const csrfToken   = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

    function getFilterData() {
        return {
            start_date:    $('#filter-start-date').val(),
            end_date:      $('#filter-end-date').val(),
            filter_search: $('#filter-search').val(),
            filter_status: $('#filter-status').val(),
        };
    }

    const tableDPL = $('#table-dpl').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: {
            url: dplTableUrl,
            data: function(d) { $.extend(d, getFilterData()); }
        },
        columns: [
            { data: 'DT_RowIndex',          name: 'DT_RowIndex',    orderable: false, searchable: false, className: 'text-center' },
            { data: 'payment_number',       name: 'payment_number' },
            { data: 'payment_date_fmt',     name: 'payment_date',   className: 'text-center' },
            { data: 'supplier_id',          name: 'supplier_id',    className: 'text-center' },
            { data: 'supplier_name',        name: 'supplier_name' },
            { data: 'total_fmt',            name: 'total_paid',     className: 'text-end' },
            { data: 'account_id',           name: 'account_id' },
            { data: 'account',              name: 'account' },
            { data: 'note',                 name: 'note' },
            { data: 'payment_type_badge',   name: 'payment_type',   orderable: false, searchable: false, className: 'text-center' },
            { data: 'currency',             name: 'currency',       className: 'text-center' },
            { data: 'rate_fmt',             name: 'rate',           className: 'text-end' },
            { data: 'invoice_number',       name: 'invoice_number' },
            { data: 'invoice_date_fmt',     name: 'invoice_date',   className: 'text-center' },
            { data: 'subtotal_fmt',         name: 'subtotal',       className: 'text-end' },
            { data: 'discount_percent_fmt', name: 'discount_percent', className: 'text-center' },
            { data: 'discount_amount_fmt',  name: 'discount_amount',  className: 'text-end' },
            { data: 'lain_lain_fmt',        name: 'lain_lain',      className: 'text-end' },
            { data: 'total_payment_fmt',    name: 'total_payment',  className: 'text-end' },
            { data: 'note_detail',          name: 'note_detail' },
            { data: 'status_badge',         name: 'status',         orderable: false, searchable: false, className: 'text-center' },
        ],
    });

    $('#btn-filter').on('click', function () { tableDPL.ajax.reload(); });
    $('#btn-reset-filter').on('click', function () {
        $('#filter-start-date').val('');
        $('#filter-end-date').val('');
        $('#filter-search').val('');
        $('#filter-status').val('all');
        tableDPL.ajax.reload();
    });
</script>
@endpush
