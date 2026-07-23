@push('after-style')
<style>
    #table-area thead th { font-weight: 600; }
</style>
@endpush

@push('after-script')
<script>
    const tableArea = $('#table-area').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('area.table') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'lookupdesc', name: 'kategori_area' },
            { data: 'nama', name: 'nama' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
    });

    const modalArea         = $('#modal-area');
    const formArea          = $('#form-area');
    const methodInputArea   = $('#form-method-area');
    const idInputArea       = $('#area_id');
    const btnAddArea        = $('#btn-add-area');
    const storeUrlArea      = "{{ route('area.store') }}";
    const showUrlArea       = "{{ route('area.show', '__ID__') }}";
    const updateUrlArea     = "{{ route('area.update', '__ID__') }}";
    const deleteUrlArea     = "{{ route('area.destroy', ['area' => '__ID__']) }}";
    const csrfToken         = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': csrfToken }
    });

    // Select2: Kategori Area
    $('#kategori_areaid').select2({
        dropdownParent: $("#modal-area"),
        placeholder: "Select",
        allowClear: true,
        ajax: {
            url: "{{ route('area.getKategoriArea') }}",
            dataType: 'json',
            delay: 250,
            data: params => ({ q: params.term }),
            processResults: data => ({ results: data.data })
        }
    });

    function resetFormArea() {
        formArea[0].reset();
        methodInputArea.val('POST');
        idInputArea.val('');
        $('#kategori_areaid').val(null).trigger('change');
        formArea.find('.is-invalid').removeClass('is-invalid');
        formArea.find('.invalid-feedback').remove();
        modalArea.find('.modal-title').text('Tambah Data Customers');
    }

    function fillFormArea(data) {
        $('#field_title').val(data.nama ?? '');
    }

    function handleErrorsArea(errors) {
        Object.entries(errors).forEach(function ([key, messages]) {
            const inputs = formTes2.find('[name="' + key + '"]');
            if (!inputs.length) {
                return;
            }
            let input = inputs.first();
            if (input.attr('type') === 'hidden' && inputs.length > 1) {
                input = inputs.not('[type="hidden"]').first();
            }
            const container = input.closest(input.hasClass('form-check-input') ? '.form-check' : '.mb-3');
            input.addClass('is-invalid');
            container.append('<div class="invalid-feedback">' + messages[0] + '</div>');
        });
    }

    btnAddArea.on('click', function () {
        resetFormArea();
        modalArea.modal('show');
    });

    modalArea.on('hidden.bs.modal', function () {
        resetFormArea();
    });

    // Edit
    // window.onEdit = (id) => {
    //     $("#user_id").val(id);
    //     $.ajax({
    //         url: "{{ route('user.show') }}",
    //         method: "POST",
    //         data: { id },
    //         success: function(resp) {
    //         const u = resp.data || {};
    //         $("#name").val(u.name || '');
    //         $("#username").val(u.username || '');
    //         clearPasswordRequired(false);

    //         // Departemen
    //         $('#department').val(u.department || '');

    //         // Role
    //         $('#role_id').empty();
    //         if (u.role_id && u.get_roles && u.get_roles.role_code) {
    //             const optRole = new Option(u.get_roles.role_code, u.role_id, true, true);
    //             $('#role_id').append(optRole).trigger('change');
    //         } else if (u.role_id) {
    //             const optRole = new Option('Current Role', u.role_id, true, true);
    //             $('#role_id').append(optRole).trigger('change');
    //         } else {
    //             $('#role_id').val(null).trigger('change');
    //         }

    //         $("#modalUser").modal('show');
    //         },
    //         error: function() {
    //         Swal.fire({ icon:'error', title:"Error", text:"System error!" });
    //         }
    //     });
    // };

    // Save
    window.onSave = () => {
        $.ajax({
            data: formArea.serialize(),
            url: "{{ route('area.store') }}",
            type: "POST",
            dataType: 'json',
            success: function(data) {
                Swal.fire({ title:'Sukses!', text:data.message, icon:'success', confirmButtonText:'OK' })
                .then(() => {
                    resetFormArea()
                    modalArea.modal('hide');
                    tableArea.ajax.reload(null, false);
                });
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseJSON || xhr);
                let msg = 'System error!';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                Swal.fire({ icon:'error', title:"Error", text: msg });
                tableArea.ajax.reload(null, false);
            }
        });
    };

    // Edit
    $('#table-area').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        resetFormArea();
        $.get(showUrlArea.replace('__ID__', id))
            .done(function (response) {
                if (response.data) {
                    fillFormArea(response.data);
                    idInputArea.val(id);
                    methodInputArea.val('PUT');
                    modalArea.find('.modal-title').text('Edit Area');
                    modalArea.modal('show');
                }
            })
            .fail(function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Tidak dapat mengambil data.'
                });
            });
    });

    // Delete
    $('#table-area').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus data ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                url: deleteUrlArea.replace('__ID__', id),
                method: 'POST',
                data: { _method: 'DELETE' },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: response.message || 'Data dihapus',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    tableArea.ajax.reload(null, false);
                },
                error: function (xhr) {
                    const res = xhr.responseJSON || {};
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: res.message || 'Tidak dapat menghapus data.'
                    });
                }
            });
        });
    });
</script>
@endpush