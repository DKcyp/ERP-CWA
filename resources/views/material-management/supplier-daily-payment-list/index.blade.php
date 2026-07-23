@extends('layouts.layout')

@section('title', 'Daily Supplier Payment List')

@section('content')
<div class="page-content">

    <div class="card border-0 shadow-sm hz-card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-2">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-calendar me-1"></i>Dari Tanggal
                    </label>
                    <input type="date" class="form-control" id="filter-start-date">
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-calendar me-1"></i>Sampai Tanggal
                    </label>
                    <input type="date" class="form-control" id="filter-end-date">
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-search me-1"></i>Cari Payment
                    </label>
                    <input type="text" class="form-control" id="filter-search" placeholder="Cari nomor, supplier, atau invoice...">
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

                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="button" class="btn btn-primary flex-fill" id="btn-filter">
                        <i class="bi bi-search me-1"></i>Cari
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm hz-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="table-dpl">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;" class="text-center">No</th>
                            <th>Payment ID</th>
                            <th class="text-center">Date</th>
                            <th>Supplier ID</th>
                            <th>Name</th>
                            <th class="text-end">Total</th>
                            <th>Acc. Id</th>
                            <th>Acc. Name</th>
                            <th>Note</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Curr.</th>
                            <th class="text-end">Rate</th>
                            <th>Purch. Inv ID</th>
                            <th class="text-center">Purch. Inv Date</th>
                            <th class="text-end">Sub Total</th>
                            <th class="text-center">Disc. %</th>
                            <th class="text-end">Disc. Amt</th>
                            <th class="text-end">Lain-Lain</th>
                            <th class="text-end">Total Payment</th>
                            <th>Note Detail</th>
                            <th style="width:110px;" class="text-center">Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@include('material-management.supplier-daily-payment-list.javascript')
