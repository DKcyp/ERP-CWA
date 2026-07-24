@extends('layouts.layout')

@section('title', 'Customer Group')

@section('content')
<div class="page-content">

    <div class="card border-0 shadow-sm hz-card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-search me-1"></i>Cari Group
                    </label>
                    <input type="text" class="form-control" id="filter-search" placeholder="Cari nama atau AR Account...">
                </div>

                <div class="col-12 col-md-6 d-flex gap-2 justify-content-md-end">
                    <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-add">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Group
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm hz-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="table-group">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;" class="text-center">No</th>
                            <th>Nama Group</th>
                            <th>Deskripsi</th>
                            <th>AR Account</th>
                            <th style="width:100px;" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-group" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Customer Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="form-group" action="javascript:onSave()">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="group_id">

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="g_name" class="form-label fw-semibold">Nama Group <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="g_name" name="name" placeholder="Nama group customer" maxlength="100">
                        </div>

                        <div class="col-12">
                            <label for="g_desc" class="form-label fw-semibold">Deskripsi</label>
                            <textarea class="form-control" id="g_desc" name="description" rows="2" placeholder="Deskripsi group"></textarea>
                        </div>

                        <div class="col-12">
                            <label for="g_ar" class="form-label fw-semibold">AR Account</label>
                            <input type="text" class="form-control" id="g_ar" name="ar_account" placeholder="Cth: 1-1100" maxlength="50">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('after-script')
<script>
    const tableUrl  = "{{ route('customer-group.table') }}";
    const storeUrl  = "{{ route('customer-group.store') }}";
    const showUrl   = "{{ route('customer-group.show', '__ID__') }}";
    const updateUrl = "{{ route('customer-group.update', '__ID__') }}";
    const deleteUrl = "{{ route('customer-group.destroy', ['id' => '__ID__']) }}";
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

    const table = $('#table-group').DataTable({
        processing: true, serverSide: true,
        ajax: { url: tableUrl, data: function(d) { d.filter_search = $('#filter-search').val(); } },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'name',        name: 'name' },
            { data: 'description', name: 'description' },
            { data: 'ar_account',  name: 'ar_account' },
            { data: 'action',      name: 'action',      orderable: false, searchable: false, className: 'text-end' },
        ],
    });

    $('#filter-search').on('keyup', function () { table.ajax.reload(); });
    $('#btn-reset-filter').on('click', function () { $('#filter-search').val(''); table.ajax.reload(); });

    const modal   = $('#modal-group');
    const form    = $('#form-group');
    const idInput = $('#group_id');

    function resetForm() {
        form[0].reset(); idInput.val('');
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();
        modal.find('.modal-title').text('Tambah Customer Group');
    }

    $('#btn-add').on('click', function () { resetForm(); modal.modal('show'); });
    modal.on('hidden.bs.modal', function () { resetForm(); });

    window.onSave = function () {
        const id  = idInput.val();
        const url = id ? updateUrl.replace('__ID__', id) : storeUrl;
        const formData = form.serializeArray();
        if (id) formData.push({ name: '_method', value: 'PUT' });

        $.ajax({
            url, type: 'POST', data: formData, dataType: 'json',
            success: function (data) {
                Swal.fire({ title: 'Sukses!', text: data.message, icon: 'success', confirmButtonText: 'OK' })
                    .then(() => { resetForm(); modal.modal('hide'); table.ajax.reload(null, false); });
            },
            error: function (xhr) {
                const res = xhr.responseJSON || {};
                if (xhr.status === 422 && res.errors) { /* handle client-side */ }
                else { Swal.fire({ icon: 'error', title: 'Error', text: res.message || 'Terjadi kesalahan.' }); }
            }
        });
    };

    $('#table-group').on('click', '.btn-edit', function () {
        const id = $(this).data('id'); resetForm();
        $.get(showUrl.replace('__ID__', id)).done(function (r) {
            const d = r.data || {};
            idInput.val(d.id); $('#g_name').val(d.name ?? '');
            $('#g_desc').val(d.description ?? ''); $('#g_ar').val(d.ar_account ?? '');
            modal.find('.modal-title').text('Edit Customer Group'); modal.modal('show');
        }).fail(function () { Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat mengambil data.' }); });
    });

    $('#table-group').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus group ini?', text: 'Data yang dihapus tidak dapat dikembalikan.', icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: deleteUrl.replace('__ID__', id), method: 'POST', data: { _method: 'DELETE' },
                success: function (response) { Swal.fire({ icon: 'success', title: response.message || 'Data dihapus', timer: 1500, showConfirmButton: false }); table.ajax.reload(null, false); },
                error: function (xhr) { const res = xhr.responseJSON || {}; Swal.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Tidak dapat menghapus data.' }); }
            });
        });
    });
</script>
@endpush
