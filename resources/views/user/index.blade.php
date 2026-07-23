@extends('layouts.layout')

@section('title', 'User')

@section('content')
<div class="page-heading mb-4">
    <h3>User</h3>
</div>

<div class="page-content">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                    data-bs-target="#modalUser">+ Tambah</button>
        </div>

        <div class="card-body pt-2">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0 mt-2" id="data-table">
                    <thead style="background-color:#f8f9fb;">
                        <tr class="text-center">
                            <th class="text-center" style="width: 40px;">No</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Username</th>
                            <th class="text-center">Departemen</th>
                            <th class="text-center">Role</th>
                            <th class="text-center" style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('user.form')
@endsection

@include('user.javascript')