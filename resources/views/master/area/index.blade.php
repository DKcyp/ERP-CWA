@extends('layouts.layout')

@section('title', 'Area')

@section('content')
<div class="page-heading mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h3>Area</h3>
    <button type="button" class="btn btn-primary" id="btn-add-area">
        <i class="bi bi-plus-lg me-1"></i> Tambah Area
    </button>
</div>

<div class="page-content">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="table-area">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th>Kategori Area</th>
                            <th>Nama Area</th>
                            <th style="width: 120px;" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('master.area.form')
@endsection

@include('master.area.javascript')