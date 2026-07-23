@push('after-style')
<style>
    #table-fulfill thead th { font-weight: 600; }
    .badge { font-size: .75rem; }
</style>
@endpush

@push('after-script')
<script>
    const ffTableUrl = "{{ route('purchase-fulfill.table') }}";
    const ffShowUrl  = "{{ route('purchase-fulfill.show', '__ID__') }}";
    const csrfToken  = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

    // ─── DataTable ────────────────────────────────
    const tableFF = $('#table-fulfill').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: ffTableUrl,
            data: function(d) {
                d.filter_search = $('#filter-search').val();
                d.filter_status = $('#filter-status').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',   name: 'DT_RowIndex',  orderable: false, searchable: false, className: 'text-center' },
            { data: 'po_number',     name: 'po_number' },
            { data: 'po_date_fmt',   name: 'po_date',      className: 'text-center' },
            { data: 'supplier_name', name: 'supplier_name' },
            { data: 'status_badge',  name: 'status',       orderable: false, searchable: false, className: 'text-center' },
            { data: 'progress_bar',  name: 'progress_bar', orderable: false, searchable: false, className: 'text-center' },
            { data: 'total_amount',  name: 'total_amount', className: 'text-end' },
            { data: 'action',        name: 'action',       orderable: false, searchable: false, className: 'text-center' },
        ],
    });

    // ─── Filter ──────────────────────────────────
    $('#filter-search').on('keyup', function () { tableFF.ajax.reload(); });
    $('#filter-status').on('change', function () { tableFF.ajax.reload(); });

    $('#btn-reset-filter').on('click', function () {
        $('#filter-search').val('');
        $('#filter-status').val('all');
        tableFF.ajax.reload();
    });

    // ─── Detail Modal ────────────────────────────
    $('#table-fulfill').on('click', '.btn-detail', function () {
        const id = $(this).data('id');
        $.get(ffShowUrl.replace('__ID__', id))
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

                if (!d.items || !d.items.length) {
                    tbody.append('<tr><td colspan="6" class="text-center text-muted py-3">Tidak ada item</td></tr>');
                } else {
                    d.items.forEach(function (item, idx) {
                        const qty = item.qty || 0;
                        const fulfilled = item.qty_fulfilled || 0;
                        const pct = qty > 0 ? Math.round((fulfilled / qty) * 100) : 0;
                        const color = pct == 100 ? 'bg-success' : (pct >= 50 ? 'bg-warning' : 'bg-danger');
                        tbody.append(`
                            <tr>
                                <td class="text-center">${idx + 1}</td>
                                <td>${item.material ?? '-'}</td>
                                <td class="text-center">${qty}</td>
                                <td>${item.unit ?? '-'}</td>
                                <td class="text-center fw-semibold">${fulfilled}</td>
                                <td>
                                    <div class="progress" style="height:18px;">
                                        <div class="progress-bar ${color} fw-semibold" style="width:${pct}%">${pct}%</div>
                                    </div>
                                </td>
                            </tr>
                        `);
                    });
                }

                $('#modal-detail').modal('show');
            })
            .fail(function () {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat mengambil data.' });
            });
    });
</script>
@endpush
