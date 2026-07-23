@push('after-style')
    <style>
    #data-table thead th { font-weight:600; }
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input { height:34px; }
    </style>
@endpush

@push('after-script')
    <script type="text/javascript">
        let tableSel = "#data-table";
        let dt;
        let currentDepartmentId = null;

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // Select2: Roles
        $('#role_id').select2({
            dropdownParent: $("#modalUser"),
            placeholder: "Select",
            allowClear: true,
            ajax: {
                url: "{{ route('user.getRoles') }}",
                dataType: 'json',
                delay: 250,
                data: params => ({ q: params.term }),
                processResults: data => ({ results: data.data })
            }
        });

        // Select2: Department
        $('#department').select2({
            dropdownParent: $("#modalUser"),
            placeholder: "Select",
            allowClear: true,
            ajax: {
                url: "{{ route('user.getDepartment') }}",
                dataType: 'json',
                delay: 250,
                data: params => ({ q: params.term }),
                processResults: data => ({ results: data.data })
            }
        });

        // DataTable
        $(function() {
            dt = $(tableSel).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('user.table') }}",
                    dataSrc: 'data',
                    error: function (xhr) {
                        console.error('DT AJAX error:', xhr.status, xhr.responseText);
                        Swal.fire({icon:'error', title:'Gagal load data', text:'Cek console devtools ya'});
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable:false, orderable:false, className: 'text-center' },
                    { data: 'name', name: 'name' },
                    { data: 'username', name: 'username' },
                    { 
                        data: 'get_area.nama',
                        name: 'department',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    { 
                        data: 'get_roles.role_code',
                        name: 'role_id',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    { data: 'action', name: 'action', orderable:false, searchable:false },
                ],
                order: [[1, 'asc']]
            });
        });

        // Helpers
        function clearPasswordRequired(isRequired) {
            $('#password, #password2').prop('required', !!isRequired).val('');
        }

        // Fungsi untuk set Select2 value dengan benar
        function setSelect2Value(selectId, value, displayText = null) {
            const $select = $(`#${selectId}`);
            
            if (!value) {
                $select.val(null).trigger('change');
                return;
            }
            
            if (displayText) {
                const newOption = new Option(displayText, value, true, true);
                
                if ($select.hasClass("select2-hidden-accessible")) {
                    $select.empty().append(newOption).trigger('change');
                } else {
                    $select.val(value);
                }
            } else {
                $select.val(value).trigger('change');
            }
        }

        // Edit Function
        window.onEdit = (id) => {
            $("#user_id").val(id);
            $.ajax({
                url: "{{ route('user.show') }}",
                method: "POST",
                data: { id },
                success: function(resp) {
                    const u = resp.data || {};
                    $("#name").val(u.name || '');
                    $("#username").val(u.username || '');
                    clearPasswordRequired(false);

                    // Set department
                    if (u.department && u.get_area && u.get_area.nama) {
                        setSelect2Value('department', u.department, u.get_area.nama);
                        currentDepartmentId = u.department; // Simpan department ID
                    } else if (u.department) {
                        setSelect2Value('department', u.department);
                        currentDepartmentId = u.department;
                    } else {
                        $('#department').val(null).trigger('change');
                        currentDepartmentId = null;
                    }

                    // Set role
                    if (u.role_id) {
                        if (resp.role && resp.role.text) {
                            const optRole = new Option(resp.role.text, u.role_id, true, true);
                            $('#role_id').empty().append(optRole).trigger('change');
                        } else if (u.get_roles && u.get_roles.role_name) {
                            const optRole = new Option(u.get_roles.role_name, u.role_id, true, true);
                            $('#role_id').empty().append(optRole).trigger('change');
                        } else {
                            $('#role_id').val(u.role_id).trigger('change');
                        }
                    } else {
                        $('#role_id').val(null).trigger('change');
                    }

                    $("#modalUser").modal('show');
                },
                error: function(xhr) {
                    console.error('Edit error:', xhr);
                    Swal.fire({ icon:'error', title:"Error", text:"System error!" });
                }
            });
        };

        // Save Function
        window.onSave = () => {

            $.ajax({
                data: $('#form-user').serialize(),
                url: "{{ route('user.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if (data.status === 'success') {
                        Swal.fire({ title:'Sukses!', text:data.message, icon:'success', confirmButtonText:'OK' })
                        .then(() => {
                            $('#form-user').trigger("reset");
                            $("#modalUser").modal('hide');
                            $("#user_id").val('');
                            $('#role_id').val(null).trigger('change');
                            $('#department').val(null).trigger('change');
                            currentDepartmentId = null;
                            dt.draw();
                        });
                    } else if (data.status === 'info') {
                        Swal.fire({ icon:'info', title:'Password tidak sama', text:'' })
                        .then(() => dt.draw());
                    }
                },
                error: function(xhr) {
                    console.log('Error:', xhr.responseJSON || xhr);
                    let msg = 'System error!';
                    if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    Swal.fire({ icon:'error', title:"Error", text: msg });
                    dt.draw();
                }
            });
        };

        // Delete (tetap sama)
        window.onDelete = (id) => {
            Swal.fire({
                icon: 'warning',
                title: 'Hapus data ini?',
                showCancelButton: true,
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('user.destroy') }}",
                        type: "POST",
                        data: { id },
                        success: function() {
                            Swal.fire('Deleted data successfully');
                            dt.draw();
                        }
                    });
                }
            })
        };

        // Saat modal dibuka untuk create
        $('#modalUser').on('show.bs.modal', function () {
            const id = $('#user_id').val();
            if (!id) {
                clearPasswordRequired(true);
                $('#role_id').val(null).trigger('change');
                $('#department').val(null).trigger('change');
                currentDepartmentId = null;
            }
        });

        // Saat modal ditutup
        $('#modalUser').on('hidden.bs.modal', function () {
            currentDepartmentId = null;
        });
    </script>
@endpush