@extends('layouts.layout')

@section('title', 'Supplier Payment List')

@section('content')
<div class="page-content">

    <div class="card border-0 shadow-sm hz-card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-search me-1"></i>Cari Pembayaran
                    </label>
                    <input type="text" class="form-control" id="filter-search" placeholder="Cari nomor payment, supplier, atau invoice...">
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
                    <button type="button" class="btn btn-primary" id="btn-add-sp">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-3" id="paymentTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-all" data-type="all" type="button" role="tab">
                Semua
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-regular" data-type="Regular" type="button" role="tab">
                Regular
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-down" data-type="Down" type="button" role="tab">
                Down
            </button>
        </li>
    </ul>

    <div class="card border-0 shadow-sm hz-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="table-sp">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;" class="text-center">No</th>
                            <th>Payment Id</th>
                            <th class="text-center">Date</th>
                            <th>Supplier Id</th>
                            <th>Name</th>
                            <th class="text-center">Curr.</th>
                            <th class="text-center">Type</th>
                            <th class="text-end">Total</th>
                            <th>Account</th>
                            <th>Note</th>
                            <th style="width:110px;" class="text-center">Status</th>
                            <th>User</th>
                            <th class="text-center">Complete Date</th>
                            <th style="width:100px;" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('material-management.supplier-payment.form')
@endsection

@include('material-management.supplier-payment.javascript')
