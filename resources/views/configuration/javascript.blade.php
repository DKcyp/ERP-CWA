@push('after-script')
<script type="text/javascript">
    const formSelector = '#form-configuration';
    const logoInput = '#logo';
    const logoPreview = '#img-logo';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(() => {
        loadConfiguration();
    });

    function loadConfiguration() {
        HELPER.block();
        HELPER.ajax({
            url: "{{ route('configuration.getConfig') }}",
            type: "GET",
            success: (response) => {
                const configs = response.config || [];

                configs.forEach((item) => {
                    const key = String(item.config_code).replace(/\./g, '_');
                    const value = item.config_value || '';
                    const input = document.getElementById(key);

                    if (input) {
                        input.value = value;
                    }

                    if (key === 'app_logo') {
                        const hasLogo = value !== '';
                        document.querySelector(logoPreview).src = hasLogo
                            ? "{{ asset('storage') }}/" + value
                            : "{{ asset('logo.png') }}";
                    }
                });
            },
            complete: () => HELPER.unblock(300),
            error: () => {
                HELPER.unblock();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal memuat konfigurasi',
                    text: 'Silakan coba beberapa saat lagi.'
                });
            }
        });
    }

    window.onSave = () => {
        const formData = $(formSelector).serialize();
        $.post("{{ route('configuration.store') }}", formData)
            .done(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Konfigurasi tersimpan',
                    timer: 1500,
                    showConfirmButton: false
                });
            })
            .fail((xhr) => {
                const message = xhr?.responseJSON?.message || 'Tidak dapat menyimpan konfigurasi.';
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal menyimpan',
                    text: message
                });
            });
    };

    window.onInputLogo = () => {
        document.querySelector(logoInput).click();
    };

    $(logoInput).on('change', function () {
        const file = this.files[0];
        if (!file) {
            return;
        }

        const data = new FormData();
        data.append('logo', file);

        HELPER.block();

        $.ajax({
            url: "{{ route('configuration.uploadLogo') }}",
            type: "POST",
            data,
            contentType: false,
            processData: false
        })
            .done((response) => {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Logo diperbarui',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    loadConfiguration();
                } else {
                    throw new Error(response.message || 'Gagal memperbarui logo.');
                }
            })
            .fail((xhr) => {
                const message = xhr?.responseJSON?.message || 'Tidak dapat mengunggah logo.';
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal unggah',
                    text: message
                });
            })
            .always(() => HELPER.unblock());
    });
</script>
@endpush
