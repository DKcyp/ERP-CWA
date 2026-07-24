@push('after-style')
<style>
    #table-customer thead th { font-weight: 600; }
    .badge { font-size: .75rem; }
    .nav-tabs .nav-link { font-weight: 500; }
</style>
@endpush

@push('after-script')
<script>
    const tableUrl   = "{{ route('customer-master.table') }}";
    const storeUrl   = "{{ route('customer-master.store') }}";
    const showUrl    = "{{ route('customer-master.show', '__ID__') }}";
    const updateUrl  = "{{ route('customer-master.update', '__ID__') }}";
    const deleteUrl  = "{{ route('customer-master.destroy', ['id' => '__ID__']) }}";
    const csrfToken  = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

    const table = $('#table-customer').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: {
            url: tableUrl,
            data: function(d) {
                d.filter_search = $('#filter-search').val();
                d.filter_channel = $('#filter-channel').val();
                d.filter_active  = $('#filter-active').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',   name: 'DT_RowIndex',   orderable: false, searchable: false, className: 'text-center' },
            { data: 'name',          name: 'name' },
            { data: 'nik',           name: 'nik' },
            { data: 'npwp',          name: 'npwp' },
            { data: 'marketing',     name: 'marketing' },
            { data: 'credit_limit_fmt', name: 'credit_limit', className: 'text-end' },
            { data: 'city',          name: 'city' },
            { data: 'channel_outlet',name: 'channel_outlet' },
            { data: 'term',          name: 'term',         className: 'text-center' },
            { data: 'active_badge',  name: 'active',       orderable: false, searchable: false, className: 'text-center' },
            { data: 'action',        name: 'action',       orderable: false, searchable: false, className: 'text-end' },
        ],
    });

    $('#filter-search').on('keyup', function () { table.ajax.reload(); });
    $('#filter-channel').on('change', function () { table.ajax.reload(); });
    $('#filter-active').on('change', function () { table.ajax.reload(); });

    $('#btn-reset-filter').on('click', function () {
        $('#filter-search').val('');
        $('#filter-channel').val('all');
        $('#filter-active').val('all');
        table.ajax.reload();
    });

    const modal    = $('#modal-customer');
    const form     = $('#form-customer');
    const idInput  = $('#customer_id');

    function resetForm() {
        form[0].reset();
        idInput.val('');
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();
        modal.find('.modal-title').text('Tambah Customer');
        $('#c_active').prop('checked', true);
        $('#customerTabs a:first').tab('show');
    }

    $('#btn-add-customer').on('click', function () { resetForm(); modal.modal('show'); });
    modal.on('hidden.bs.modal', function () { resetForm(); });

    function handleErrors(errors) {
        Object.entries(errors).forEach(([key, messages]) => {
            const input = form.find('[name="' + key + '"]').first();
            if (!input.length) return;
            const container = input.closest('.col-md-4, .col-md-6, .col-12');
            input.addClass('is-invalid');
            container.append('<div class="invalid-feedback">' + messages[0] + '</div>');
        });
    }

    window.onSaveCustomer = () => {
        const id  = idInput.val();
        const url = id ? updateUrl.replace('__ID__', id) : storeUrl;
        if (!$('#c_active').is(':checked')) $('#c_active').val('');

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
                if (xhr.status === 422 && res.errors) { handleErrors(res.errors); }
                else { Swal.fire({ icon: 'error', title: 'Error', text: res.message || 'Terjadi kesalahan.' }); }
            }
        });
    };

    $('#table-customer').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        resetForm();
        $.get(showUrl.replace('__ID__', id))
            .done(function (response) {
                const d = response.data || {};
                idInput.val(d.id);
                $('#c_name').val(d.name ?? '');
                $('#c_nik').val(d.nik ?? '');
                $('#c_nik_name').val(d.nik_name ?? '');
                $('#c_npwp').val(d.npwp ?? '');
                $('#c_sim').val(d.sim ?? '');
                $('#c_marketing').val(d.marketing ?? '');
                $('#c_credit_limit').val(d.credit_limit ?? 0);
                $('#c_due_date').val(d.due_date_warning ?? 0);
                $('#c_warehouse').val(d.warehouse ?? '');
                $('#c_active').prop('checked', !!d.active);
                $('#c_contact').val(d.contact ?? '');
                $('#c_position').val(d.position ?? '');
                $('#c_address1').val(d.address1 ?? '');
                $('#c_address2').val(d.address2 ?? '');
                $('#c_kecamatan').val(d.kecamatan ?? '');
                $('#c_kabupaten').val(d.kabupaten ?? '');
                $('#c_city').val(d.city ?? '');
                $('#c_zip').val(d.zip ?? '');
                $('#c_channel').val(d.channel_outlet ?? '');
                $('#c_rayon').val(d.rayon_sales ?? '');
                $('#c_province').val(d.province ?? '');
                $('#c_country').val(d.country ?? '');
                $('#c_phone').val(d.phone ?? '');
                $('#c_mobile').val(d.mobile_phone ?? '');
                $('#c_email').val(d.email ?? '');
                $('#c_note').val(d.note ?? '');
                $('#c_price_list').val(d.price_list_id ?? '');
                $('#c_term').val(d.term ?? 0);
                modal.find('.modal-title').text('Edit Customer');
                modal.modal('show');
            })
            .fail(function () { Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat mengambil data.' }); });
    });

    $('#table-customer').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus customer ini?', text: 'Data yang dihapus tidak dapat dikembalikan.', icon: 'warning',
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
