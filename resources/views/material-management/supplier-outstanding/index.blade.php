@extends('layouts.layout')

@section('title', 'Supplier Outstanding List')

@section('content')
<div class="page-content">

    <div class="card border-0 shadow-sm hz-card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-search me-1"></i>Cari
                    </label>
                    <input type="text" class="form-control" id="filter-search" placeholder="Cari invoice, supplier, atau note...">
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-toggle-on me-1"></i>Status
                    </label>
                    <select id="filter-status" class="form-select">
                        <option value="all">Semua Status</option>
                        <option value="DRAFT">Draft</option>
                        <option value="PENDING">Pending</option>
                        <option value="APPROVED">Approved</option>
                        <option value="REJECTED">Rejected</option>
                        <option value="PAID">Paid</option>
                    </select>
                </div>

                <div class="col-12 col-md-5 d-flex gap-2 justify-content-md-end">
                    <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm hz-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="table-os">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;" class="text-center">No</th>
                            <th>Invoice Id</th>
                            <th>Supplier</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Due Date</th>
                            <th class="text-center">Age (Days)</th>
                            <th class="text-center">Curr</th>
                            <th class="text-end">Rate</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Total IDR</th>
                            <th class="text-end">Outstanding</th>
                            <th>Term</th>
                            <th>Note</th>
                            <th style="width:110px;" class="text-center">Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-script')
<script>
    const osTableUrl = "{{ route('supp-outstanding.table') }}";
    const csrfToken  = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

    const tableOS = $('#table-os').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: osTableUrl,
            data: function(d) {
                d.filter_search = $('#filter-search').val();
                d.filter_status = $('#filter-status').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',        name: 'DT_RowIndex',      orderable: false, searchable: false, className: 'text-center' },
            { data: 'invoice_number',     name: 'invoice_number' },
            { data: 'supplier_name',      name: 'supplier_name' },
            { data: 'date_fmt',           name: 'date',             className: 'text-center' },
            { data: 'due_date_fmt',       name: 'due_date',         className: 'text-center' },
            { data: 'age_days',           name: 'age_days',         className: 'text-center', searchable: false },
            { data: 'currency',           name: 'currency',         className: 'text-center' },
            { data: 'rate',               name: 'rate',             className: 'text-end' },
            { data: 'total_fmt',          name: 'total',            className: 'text-end' },
            { data: 'total_idr_fmt',      name: 'total_idr',        className: 'text-end', searchable: false },
            { data: 'outstanding_fmt',    name: 'outstanding',      className: 'text-end' },
            { data: 'term',               name: 'term' },
            { data: 'note',               name: 'note' },
            { data: 'status_badge',       name: 'status',           orderable: false, searchable: false, className: 'text-center' },
        ],
        order: [[5, 'desc']],
    });

    $('#filter-search').on('keyup', function () { tableOS.ajax.reload(); });
    $('#filter-status').on('change', function () { tableOS.ajax.reload(); });

    $('#btn-reset-filter').on('click', function () {
        $('#filter-search').val('');
        $('#filter-status').val('all');
        tableOS.ajax.reload();
    });
</script>
@endpush
