@extends('layouts.layout')

@section('title', 'Customer Master')

@section('content')
<div class="page-content">

    <div class="card border-0 shadow-sm hz-card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-search me-1"></i>Cari Customer
                    </label>
                    <input type="text" class="form-control" id="filter-search" placeholder="Cari nama, NIK, kota, atau telp...">
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-shop me-1"></i>Channel Outlet
                    </label>
                    <select id="filter-channel" class="form-select">
                        <option value="all">Semua Channel</option>
                        <option value="Distributor">Distributor</option>
                        <option value="Sub-Distributor">Sub-Distributor</option>
                        <option value="Retail">Retail</option>
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-toggle-on me-1"></i>Status
                    </label>
                    <select id="filter-active" class="form-select">
                        <option value="all">Semua</option>
                        <option value="1">Aktif</option>
                        <option value="0">Non-Aktif</option>
                    </select>
                </div>

                <div class="col-12 col-md-3 d-flex gap-2 justify-content-md-end">
                    <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-add-customer">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Customer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm hz-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="table-customer">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;" class="text-center">No</th>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>NPWP</th>
                            <th>Marketing</th>
                            <th class="text-end">Credit Limit</th>
                            <th>Kota</th>
                            <th>Channel</th>
                            <th class="text-center">Term</th>
                            <th style="width:90px;" class="text-center">Status</th>
                            <th style="width:100px;" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('master.customer-master.form')
@endsection

@include('master.customer-master.javascript')
