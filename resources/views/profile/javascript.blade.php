@push('after-script')
    <script type="text/javascript">
        $(() => {
            // HELPER.fields = ['asas_id'];

            // HELPER.api = {
            // 	table: APP_URL + 'asas/table',
            // 	create: APP_URL + 'asas/create',
            // 	show: APP_URL + 'asas/show',
            // 	update: APP_URL + 'asas/update',
            // 	delete: APP_URL + 'asas/delete'
            // };

            // // HELPER.handleValidation({
            // // 	el: form,
            // // 	declarative: true
            // // });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            HELPER.disableInput('#formBiodata');
            $("#role_id").select2();
            init();
        });

        init = async () => {
            await HELPER.unblock(100);
        }

        onEdit = () => {
            HELPER.enableInput('#formBiodata');
            $("#btnSaveBio").show();
            $("#btnEdit").hide();
            $("#btnCancel").show();
        }

        onSaveBiodata = () => {
            HELPER.block();
            $.ajax({
                data: $('#formBiodata').serialize(),
                url: "{{ route('profile.update') }}",
                type: "PATCH",
                dataType: 'json',
                success: function(data) {
                    $('#formBiodata').trigger("reset");
                    $("#btnSaveBio").hide();
                    HELPER.showMessage({
                        success: true,
                        title: 'Success',
                        message: 'Update profile successfully',
                        callback: function() {
                            window.location.reload();
                        }
                    });
                },
                error: function(data) {
                    console.log('Error:', data);
                    Swal.fire({
                        success: false,
                        title: "Error",
                        message: "System error!"
                    });
                }
            });
        }

        onCancel = () => {
            $("#btnEdit").show();
            $("#btnCancel").hide();
            $("#btnSaveBio").hide();
            HELPER.disableInput('#formBiodata');

        }
        onChangePasword = () => {
            $.ajax({
                data: $('#formUpdatePassword').serialize(),
                url: "{{ route('profile.updatePassword') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    HELPER.showMessage({
                        success: true,
                        title: 'Success',
                        message: 'Password changed successfull',
                        callback: function() {
                            window.location.reload();
                        }
                    });
                },
                error: function(data) {
                    Swal.fire({
                        success: false,
                        title: "Error",
                        text: "Failed!"
                    });
                }
            });
        }
    </script>
@endpush
