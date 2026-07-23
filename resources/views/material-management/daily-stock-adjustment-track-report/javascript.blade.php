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
    const tableUrl = "{{ route('daily-stock-adjustment-track.table') }}";
    const summaryUrl = "{{ route('daily-stock-adjustment-track.summary') }}";
    const showUrl  = "{{ route('daily-stock-adjustment-track.show', '__ID__') }}";
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
                $('#summary-total-adjustments').text(res.total_adjustments || 0);
                $('#summary-total-items').text(res.total_items || 0);
                $('#summary-total-qty-diff').text(res.total_qty_diff ?? 0);

                const statusLabels = {
                    'DRAFT':     '<span class="badge bg-secondary">Draft: 0</span>',
                    'APPROVED':  '<span class="badge bg-info text-dark">Approved: 0</span>',
                    'COMPLETED': '<span class="badge bg-success">Completed: 0</span>',
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
        scrollX: true,
        ajax: {
            url: tableUrl,
            data: function(d) { $.extend(d, getFilterData()); }
        },
        columns: [
            { data: 'DT_RowIndex',      name: 'DT_RowIndex',      orderable: false, searchable: false, className: 'text-center' },
            { data: 'adjustment_number',name: 'adjustment_number' },
            { data: 'adjustment_date_fmt', name: 'adjustment_date', className: 'text-center' },
            { data: 'warehouse',        name: 'warehouse' },
            { data: 'department',       name: 'department' },
            { data: 'type_badge',       name: 'adjustment_type',  orderable: false, searchable: false, className: 'text-center' },
            { data: 'line_material',    name: 'line_material' },
            { data: 'line_sys_qty',     name: 'line_sys_qty',    className: 'text-center' },
            { data: 'line_phys_qty',    name: 'line_phys_qty',   className: 'text-center' },
            { data: 'line_qty_diff',    name: 'line_qty_diff',   className: 'text-center' },
            { data: 'pic',              name: 'pic' },
            { data: 'use_for',          name: 'use_for' },
            { data: 'status_badge',     name: 'status',          orderable: false, searchable: false, className: 'text-center' },
            { data: 'action',           name: 'action',          orderable: false, searchable: false, className: 'text-center' },
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
                $('#detail-number').text(d.adjustment_number ?? '-');
                $('#detail-date').text(d.adjustment_date ?? '-');
                $('#detail-warehouse').text(d.warehouse ?? '-');
                $('#detail-department').text(d.department ?? '-');
                $('#detail-pic').text(d.pic ?? '-');
                $('#detail-use-for').text(d.use_for ?? '-');
                $('#detail-note').text(d.reason ?? '-');

                const typeLabel = d.adjustment_type === 'INTERNAL_USE' ? '<span class="badge bg-warning text-dark">Internal Use</span>' : '<span class="badge bg-primary">Standard</span>';
                $('#detail-type').html(typeLabel);

                const statusMap = {
                    'DRAFT':     '<span class="badge bg-secondary">Draft</span>',
                    'APPROVED':  '<span class="badge bg-info text-dark">Approved</span>',
                    'COMPLETED': '<span class="badge bg-success">Completed</span>',
                };
                $('#detail-status').html(statusMap[d.status] ?? d.status);

                const tbody = $('#detail-items-tbody');
                tbody.empty();
                let totalDiff = 0;
                (d.items || []).forEach(function (item, idx) {
                    const sysQty = parseFloat(item.system_qty) || 0;
                    const physQty = parseFloat(item.physical_qty) || 0;
                    const diff = sysQty - physQty;
                    totalDiff += diff;
                    tbody.append(`
                        <tr>
                            <td class="text-center">${idx + 1}</td>
                            <td>${item.material ?? '-'}</td>
                            <td class="text-center">${sysQty}</td>
                            <td class="text-center">${physQty}</td>
                            <td class="text-center">${diff}</td>
                        </tr>
                    `);
                });
                $('#detail-total-diff').text(totalDiff);
                $('#modal-detail').modal('show');
            })
            .fail(function () { Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat mengambil data.' }); });
    });

    loadSummary();
</script>
@endpush
