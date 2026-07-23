@extends('layouts.layout')

@section('title', 'Generator CRUD')

@section('content')
<div class="page-heading mb-4">
    <h3>Generator CRUD</h3>
    <p class="text-muted">Isi form berikut untuk menghasilkan migration, model, controller, view, dan route secara otomatis.</p>
</div>

<div class="page-content">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form id="scaffoldForm">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Resource <span class="text-danger">*</span></label>
                        <input type="text" name="resource_name" id="resource_name" class="form-control" placeholder="Contoh: Produk" required>
                        <small class="text-muted">Digunakan untuk nama Model & Controller.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Tabel</label>
                        <input type="text" name="table_name" id="table_name" class="form-control" placeholder="Kosongkan untuk otomatis" autocomplete="off">
                        <small class="text-muted">Default mengikuti jamak resource (snake case).</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Parent Menu</label>
                        <select name="menu_parent" id="menu_parent" class="form-select">
                            @foreach($menuOptions as $menu)
                                <option value="{{ $menu['id'] }}">{{ $menu['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Icon Menu</label>
                        <input type="text" name="menu_icon" id="menu_icon" class="form-control" value="bi bi-circle" autocomplete="off">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Urutan Menu</label>
                        <input type="number" name="menu_sort" id="menu_sort" class="form-control" value="0">
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Kolom Tabel</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btnAddField">
                        <i class="bi bi-plus-lg"></i> Tambah Kolom
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="fieldsTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 25%">Nama Kolom</th>
                                <th style="width: 20%">Tipe</th>
                                <th style="width: 10%">Nullable</th>
                                <th style="width: 10%">Unique</th>
                                <th style="width: 20%">Default Value</th>
                                <th style="width: 15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" class="form-control field-name" placeholder="Contoh: title" required>
                                </td>
                                <td>
                                    <select class="form-select field-type">
                                        @foreach($fieldTypes as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input field-nullable">
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input field-unique">
                                </td>
                                <td>
                                    <input type="text" class="form-control field-default" placeholder="Opsional">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger btnRemoveField">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mt-3">
                    Kolom <code>id</code>, <code>created_at</code>, <code>updated_at</code>, dan <code>deleted_at</code> dibuat otomatis.
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary" id="btnGenerate">
                        <i class="bi bi-hammer"></i> Generate
                    </button>
                    <button type="button" class="btn btn-light" id="btnReset">Reset</button>
                </div>
            </form>

            <div class="mt-4" id="resultContainer" style="display:none;">
                <div class="alert alert-success">
                    <strong>Berhasil!</strong> File scaffold telah dibuat.
                </div>
                <ul class="list-group" id="resultList"></ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-style')
<style>
    #fieldsTable thead th { font-weight: 600; }
    #fieldsTable tbody td { vertical-align: middle; }
</style>
@endpush

@push('after-script')
<script>
    const FIELD_TYPES = @json($fieldTypes);
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });

    function humanizeKey(key) {
        return key
            .replace(/[_\-]+/g, ' ')
            .replace(/\s+/g, ' ')
            .trim()
            .replace(/\b\w/g, (char) => char.toUpperCase());
    }

    function renderResultList(data) {
        const list = $('#resultList');
        list.empty();
        Object.entries(data || {}).forEach(([key, value]) => {
            const label = humanizeKey(key);
            if (Array.isArray(value)) {
                value.forEach((item) => {
                    list.append(`<li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>${label}</span>
                        <code class="small mb-0">${item}</code>
                    </li>`);
                });
            } else {
                list.append(`<li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>${label}</span>
                    <code class="small mb-0">${value}</code>
                </li>`);
            }
        });
    }

    function toSnakeCase(str) {
        return str
            .replace(/([a-z0-9])([A-Z])/g, '$1_$2')
            .replace(/[\s\-]+/g, '_')
            .toLowerCase();
    }

    function simplePlural(str) {
        if (!str) { return str; }
        if (str.endsWith('y')) { return str.slice(0, -1) + 'ies'; }
        if (str.endsWith('s')) { return str + 'es'; }
        return str + 's';
    }

    function buildFieldRow(name = '', type = 'string', nullable = false, unique = false, defaultValue = '') {
        const typeOptions = Object.entries(FIELD_TYPES)
            .map(([value, label]) => `<option value="${value}" ${value === type ? 'selected' : ''}>${label}</option>`)
            .join('');

        const nullableChecked = nullable ? 'checked' : '';
        const uniqueChecked = unique ? 'checked' : '';

        return `
            <tr>
                <td>
                    <input type="text" class="form-control field-name" value="${name}" placeholder="Contoh: title" required>
                </td>
                <td>
                    <select class="form-select field-type">${typeOptions}</select>
                </td>
                <td class="text-center">
                    <input type="checkbox" class="form-check-input field-nullable" ${nullableChecked}>
                </td>
                <td class="text-center">
                    <input type="checkbox" class="form-check-input field-unique" ${uniqueChecked}>
                </td>
                <td>
                    <input type="text" class="form-control field-default" value="${defaultValue}" placeholder="Opsional">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger btnRemoveField">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    }

    $('#btnAddField').on('click', function () {
        $('#fieldsTable tbody').append(buildFieldRow());
    });

    $('#fieldsTable').on('click', '.btnRemoveField', function () {
        const rows = $('#fieldsTable tbody tr').length;
        if (rows === 1) {
            Swal.fire({ icon: 'warning', title: 'Minimal 1 kolom diperlukan.' });
            return;
        }
        $(this).closest('tr').remove();
    });

    $('#btnReset').on('click', function () {
        $('#scaffoldForm')[0].reset();
        $('#fieldsTable tbody').html(buildFieldRow());
        $('#resultContainer').hide();
    });

    $('#resource_name').on('blur', function () {
        const value = $(this).val().trim();
        if (value && !$('#table_name').val()) {
            $('#table_name').val(toSnakeCase(simplePlural(value)));
        }
    });

    $('#scaffoldForm').on('submit', function (e) {
        e.preventDefault();

        const fields = [];
        $('#fieldsTable tbody tr').each(function () {
            const name = $(this).find('.field-name').val().trim();
            const type = $(this).find('.field-type').val();
            if (name === '') {
                return;
            }

            fields.push({
                name,
                type,
                nullable: $(this).find('.field-nullable').is(':checked') ? 1 : 0,
                unique: $(this).find('.field-unique').is(':checked') ? 1 : 0,
                default: $(this).find('.field-default').val().trim()
            });
        });

        if (fields.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Tambahkan minimal satu kolom.' });
            return;
        }

        const seenNames = new Set();
        const duplicateNames = new Set();
        fields.forEach((field) => {
            const key = field.name.toLowerCase();
            if (seenNames.has(key)) {
                duplicateNames.add(key);
            } else {
                seenNames.add(key);
            }
        });

        const feedbackClass = 'duplicate-feedback';
        $('#fieldsTable tbody tr').each(function () {
            const input = $(this).find('.field-name');
            const value = input.val().trim().toLowerCase();
            if (duplicateNames.has(value)) {
                input.addClass('is-invalid');
                if (!input.next('.' + feedbackClass).length) {
                    input.after(`<div class="invalid-feedback ${feedbackClass}">Nama kolom harus unik.</div>`);
                }
            } else {
                input.removeClass('is-invalid');
                input.next('.' + feedbackClass).remove();
            }
        });

        if (duplicateNames.size) {
            Swal.fire({ icon: 'warning', title: 'Nama kolom harus unik.' });
            return;
        }

        const payload = {
            resource_name: $('#resource_name').val().trim(),
            table_name: $('#table_name').val().trim(),
            menu_parent: $('#menu_parent').val() || null,
            menu_icon: $('#menu_icon').val().trim() || 'bi bi-circle',
            menu_sort: Number($('#menu_sort').val() || 0),
            fields
        };

        $('#btnGenerate').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');

        $.ajax({
            url: "{{ route('generator.store') }}",
            method: 'POST',
            data: payload,
            success: function (response) {
                renderResultList(response.data);
                $('#resultContainer').show();
                Swal.fire({ icon: 'success', title: response.message || 'Scaffold berhasil dibuat!' });
            },
            error: function (xhr) {
                const res = xhr.responseJSON || {};
                let message = res.message || 'Terjadi kesalahan.';
                if (res.errors) {
                    const firstError = Object.values(res.errors)[0];
                    if (Array.isArray(firstError) && firstError.length) {
                        message = firstError[0];
                    }
                }
                Swal.fire({ icon: 'error', title: 'Gagal', text: message });
            },
            complete: function () {
                $('#btnGenerate').prop('disabled', false).html('<i class="bi bi-hammer"></i> Generate');
            }
        });
    });
</script>
@endpush
