@push('after-script')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
    $(function () {
        const ROOT_SELECTOR = '#sortable-menu';
        const CHILD_SELECTOR = '.sortable-submenu';
        const $modal = $('#modalMenu');
        const $form = $('#formMenu');
        const $modalTitle = $('#modalMenuLabel');
        const $submitBtn = $('#btnSubmitMenu');
        const $menuId = $('#menu_id');
        const $mainMenu = $('#main_menu');
        const $active = $('#active');
        const addButton = document.getElementById('btnAddMenu');
        let lastHover = null;

        function refreshEmptyState() {
            $(CHILD_SELECTOR).each(function () {
                const $list = $(this);
                $list.toggleClass('sortable-empty', $list.children('li').length === 0);
            });
        }

        function serializeList($list, parentId = null, accumulator = []) {
            $list.children('li').each(function (index) {
                const $item = $(this);
                const id = $item.data('id');
                if (!id) {
                    return;
                }

                accumulator.push({
                    id,
                    sort: index,
                    parent_id: parentId
                });

                const $childList = $item.children('ul.sortable-submenu').first();
                if ($childList.length) {
                    serializeList($childList, id, accumulator);
                }
            });

            return accumulator;
        }

        function clearHover() {
            if (!lastHover) {
                return;
            }
            lastHover.removeClass('drag-target');
            lastHover.children(CHILD_SELECTOR).removeClass('sortable-hover');
            lastHover = null;
        }

        function bindSortable(element) {
            if (!element || element.dataset.sortableBound === '1') {
                return;
            }

            element.dataset.sortableBound = '1';

            new Sortable(element, {
                group: { name: 'menus', pull: true, put: true },
                handle: '.drag-handle',
                animation: 180,
                swapThreshold: 0.6,
                fallbackOnBody: true,
                dragClass: 'sortable-drag',
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                onStart: function () {
                    refreshEmptyState();
                },
                onAdd: function (evt) {
                    ensureNestedSortables(evt.item);
                    refreshEmptyState();
                },
                onUpdate: function (evt) {
                    ensureNestedSortables(evt.item);
                    refreshEmptyState();
                },
                onMove: function (evt) {
                    const $relatedLi = $(evt.related).closest('li.list-group-item');

                    if (!$relatedLi.length) {
                        clearHover();
                        return true;
                    }

                    if (!lastHover || lastHover[0] !== $relatedLi[0]) {
                        clearHover();
                        lastHover = $relatedLi.addClass('drag-target');
                        lastHover.children(CHILD_SELECTOR).addClass('sortable-hover');
                    }

                    return true;
                },
                onEnd: function () {
                    clearHover();
                    refreshEmptyState();
                }
            });
        }

        function ensureNestedSortables(item) {
            $(item).find(CHILD_SELECTOR).each(function () {
                bindSortable(this);
            });
        }

        function resetForm() {
            $form[0].reset();
            $menuId.val('');
            $mainMenu.val('');
            $active.val('1');
            $modalTitle.text('Tambah Menu');
            $submitBtn.text('Simpan');
        }

        function fillFormFromDataset(dataset) {
            resetForm();
            $modalTitle.text('Edit Menu');
            $submitBtn.text('Perbarui');
            $menuId.val(dataset.id || '');
            $('#code').val(dataset.code || '');
            $('#name').val(dataset.name || '');
            $('#url').val(dataset.url || '');
            $('#icon').val(dataset.icon || '');
            $mainMenu.val(dataset.mainMenu || '');
            $('#sort').val(dataset.sort || 0);
            $active.val(dataset.active || '1');
        }

        bindSortable(document.querySelector(ROOT_SELECTOR));
        $(CHILD_SELECTOR).each(function () {
            bindSortable(this);
        });
        refreshEmptyState();

        if (addButton) {
            addButton.addEventListener('click', function () {
                resetForm();
                $modal.modal('show');
            });
        }

        $(document).on('click', '.btn-edit-menu', function () {
            const dataset = this.closest('li.list-group-item').dataset;
            fillFormFromDataset(dataset);
            $modal.modal('show');
        });

        $modal.on('hidden.bs.modal', function () {
            resetForm();
        });

        $(document).on('click', '.btn-delete-menu', function () {
            const dataset = this.closest('li.list-group-item').dataset;
            const id = dataset.id;
            const name = dataset.name || 'menu';

            Swal.fire({
                icon: 'warning',
                title: 'Hapus menu?',
                text: `Menu "${name}" akan dihapus.`,
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (!result.isConfirmed) return;

                $.ajax({
                    url: '{{ route("menu.destroy") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: response.message || 'Menu dihapus',
                            timer: 1400,
                            showConfirmButton: false
                        }).then(() => window.location.reload());
                    },
                    error: function (xhr) {
                        const message = xhr?.responseJSON?.message || 'Gagal menghapus menu.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message
                        });
                    }
                });
            });
        });

        $form.on('submit', function (e) {
            e.preventDefault();

            $submitBtn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: $form.serialize(),
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: response.message || 'Menu tersimpan',
                        timer: 1400,
                        showConfirmButton: false
                    }).then(() => window.location.reload());
                },
                error: function (xhr) {
                    const errors = xhr?.responseJSON?.errors;
                    let message = xhr?.responseJSON?.message || 'Gagal menyimpan menu.';

                    if (errors) {
                        message = Object.values(errors).flat().join('\n');
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi gagal',
                        text: message
                    });
                },
                complete: function () {
                    $submitBtn.prop('disabled', false).text($menuId.val() ? 'Perbarui' : 'Simpan');
                }
            });
        });

        $('#save-order').on('click', function () {
            const $btn = $(this);
            const payload = serializeList($(ROOT_SELECTOR));

            Swal.fire({
                title: 'Simpan urutan menu?',
                text: 'Perubahan urutan akan diterapkan.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, simpan',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (!result.isConfirmed) return;

                Swal.fire({
                    title: 'Menyimpan...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => Swal.showLoading(),
                });
                $btn.prop('disabled', true);

                $.ajax({
                    url: '{{ route("menu.sort") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order: payload,
                    },
                    success: function () {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Urutan menu berhasil disimpan.',
                            timer: 1600,
                            showConfirmButton: false,
                        }).then(() => window.location.reload());
                    },
                    error: function (xhr) {
                        const message = xhr?.responseJSON?.message || 'Gagal menyimpan urutan menu.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: message,
                        });
                    },
                    complete: function () {
                        $btn.prop('disabled', false);
                    },
                });
            });
        });
    });
</script>

{{-- ICON PICKER SCRIPT --}}
<script>
    const icons = [
        'bi-0-circle', 'bi-0-circle-fill', 'bi-0-square', 'bi-0-square-fill',
        'bi-1-circle', 'bi-1-circle-fill', 'bi-1-square', 'bi-1-square-fill',
        'bi-123', 'bi-2-circle', 'bi-2-circle-fill', 'bi-2-square', 'bi-2-square-fill',
        'bi-3-circle', 'bi-3-circle-fill', 'bi-3-square', 'bi-3-square-fill',
        'bi-4-circle', 'bi-4-circle-fill', 'bi-4-square', 'bi-4-square-fill',
        'bi-5-circle', 'bi-5-circle-fill', 'bi-5-square', 'bi-5-square-fill',
        'bi-6-circle', 'bi-6-circle-fill', 'bi-6-square', 'bi-6-square-fill',
        'bi-7-circle', 'bi-7-circle-fill', 'bi-7-square', 'bi-7-square-fill',
        'bi-8-circle', 'bi-8-circle-fill', 'bi-8-square', 'bi-8-square-fill',
        'bi-9-circle', 'bi-9-circle-fill', 'bi-9-square', 'bi-9-square-fill',
        'bi-activity', 'bi-airplane', 'bi-airplane-engines', 'bi-airplane-engines-fill',
        'bi-airplane-fill', 'bi-alarm', 'bi-alarm-fill', 'bi-alexa', 'bi-align-bottom',
        'bi-align-center', 'bi-align-end', 'bi-align-middle', 'bi-align-start',
        'bi-align-top', 'bi-alphabet', 'bi-alphabet-uppercase', 'bi-alt',
        'bi-amazon', 'bi-amd', 'bi-android', 'bi-android2', 'bi-app',
        'bi-app-indicator', 'bi-apple', 'bi-archive', 'bi-archive-fill',
        'bi-arrow-90deg-down', 'bi-arrow-90deg-left', 'bi-arrow-90deg-right',
        'bi-arrow-90deg-up', 'bi-arrow-bar-down', 'bi-arrow-bar-left',
        'bi-arrow-bar-right', 'bi-arrow-bar-up', 'bi-arrow-clockwise',
        'bi-arrow-counterclockwise', 'bi-arrow-down', 'bi-arrow-down-circle',
        'bi-arrow-down-circle-fill', 'bi-arrow-down-left', 'bi-arrow-down-left-circle',
        'bi-arrow-down-left-circle-fill', 'bi-arrow-down-left-square',
        'bi-arrow-down-left-square-fill', 'bi-arrow-down-right',
        'bi-arrow-down-right-circle', 'bi-arrow-down-right-circle-fill',
        'bi-arrow-down-right-square', 'bi-arrow-down-right-square-fill',
        'bi-arrow-down-short', 'bi-arrow-down-square', 'bi-arrow-down-square-fill',
        'bi-arrow-down-up', 'bi-arrow-left', 'bi-arrow-left-circle',
        'bi-arrow-left-circle-fill', 'bi-arrow-left-right', 'bi-arrow-left-short',
        'bi-arrow-left-square', 'bi-arrow-left-square-fill', 'bi-arrow-repeat',
        'bi-arrow-return-left', 'bi-arrow-return-right', 'bi-arrow-right',
        'bi-arrow-right-circle', 'bi-arrow-right-circle-fill', 'bi-arrow-right-short',
        'bi-arrow-right-square', 'bi-arrow-right-square-fill', 'bi-arrow-through-heart',
        'bi-arrow-through-heart-fill', 'bi-arrow-up', 'bi-arrow-up-circle',
        'bi-arrow-up-circle-fill', 'bi-arrow-up-left', 'bi-arrow-up-left-circle',
        'bi-arrow-up-left-circle-fill', 'bi-arrow-up-left-square',
        'bi-arrow-up-left-square-fill', 'bi-arrow-up-right',
        'bi-arrow-up-right-circle', 'bi-arrow-up-right-circle-fill',
        'bi-arrow-up-right-square', 'bi-arrow-up-right-square-fill',
        'bi-arrow-up-short', 'bi-arrow-up-square', 'bi-arrow-up-square-fill',
        'bi-arrows-angle-contract', 'bi-arrows-angle-expand', 'bi-arrows-collapse',
        'bi-arrows-expand', 'bi-arrows-fullscreen', 'bi-arrows-move',
        'bi-aspect-ratio', 'bi-aspect-ratio-fill', 'bi-asterisk', 'bi-at',
        'bi-award', 'bi-award-fill', 'bi-back', 'bi-backspace', 'bi-backspace-fill',
        'bi-backspace-reverse', 'bi-backspace-reverse-fill', 'bi-badge-3d',
        'bi-badge-3d-fill', 'bi-badge-4k', 'bi-badge-4k-fill', 'bi-badge-8k',
        'bi-badge-8k-fill', 'bi-badge-ad', 'bi-badge-ad-fill', 'bi-badge-ar',
        'bi-badge-ar-fill', 'bi-badge-cc', 'bi-badge-cc-fill', 'bi-badge-hd',
        'bi-badge-hd-fill', 'bi-badge-sd', 'bi-badge-sd-fill', 'bi-badge-tm',
        'bi-badge-tm-fill', 'bi-badge-vo', 'bi-badge-vo-fill', 'bi-badge-vr',
        'bi-badge-vr-fill', 'bi-bag', 'bi-bag-check', 'bi-bag-check-fill',
        'bi-bag-dash', 'bi-bag-dash-fill', 'bi-bag-fill', 'bi-bag-heart',
        'bi-bag-heart-fill', 'bi-bag-plus', 'bi-bag-plus-fill', 'bi-bag-x',
        'bi-bag-x-fill', 'bi-balloon', 'bi-balloon-fill', 'bi-balloon-heart',
        'bi-balloon-heart-fill', 'bi-ban', 'bi-ban-fill', 'bi-bank', 'bi-bank2',
        'bi-bar-chart', 'bi-bar-chart-fill', 'bi-bar-chart-line',
        'bi-bar-chart-line-fill', 'bi-bar-chart-steps', 'bi-basket',
        'bi-basket-fill', 'bi-basket2', 'bi-basket2-fill', 'bi-basket3',
        'bi-basket3-fill', 'bi-battery', 'bi-battery-charging', 'bi-battery-full',
        'bi-battery-half', 'bi-battery-low', 'bi-behance', 'bi-bell', 'bi-bell-fill',
        'bi-bell-slash', 'bi-bell-slash-fill', 'bi-bing', 'bi-binoculars',
        'bi-binoculars-fill', 'bi-blockquote-left', 'bi-blockquote-right',
        'bi-bluetooth', 'bi-body-text', 'bi-bootstrap', 'bi-bootstrap-fill',
        'bi-bootstrap-icons', 'bi-bootstrap-reboot', 'bi-border', 'bi-border-all',
        'bi-border-bottom', 'bi-border-center', 'bi-border-inner', 'bi-border-left',
        'bi-border-middle', 'bi-border-outer', 'bi-border-right', 'bi-border-style',
        'bi-border-top', 'bi-border-width', 'bi-bounding-box', 'bi-bounding-box-circles',
        'bi-box', 'bi-box-arrow-down', 'bi-box-arrow-down-left', 'bi-box-arrow-down-right',
        'bi-box-arrow-in-down', 'bi-box-arrow-in-down-left', 'bi-box-arrow-in-down-right',
        'bi-box-arrow-in-left', 'bi-box-arrow-in-right', 'bi-box-arrow-in-up',
        'bi-box-arrow-in-up-left', 'bi-box-arrow-in-up-right', 'bi-box-arrow-left',
        'bi-box-arrow-right', 'bi-box-arrow-up', 'bi-box-arrow-up-left',
        'bi-box-arrow-up-right', 'bi-box-fill', 'bi-box-seam', 'bi-box-seam-fill',
        'bi-box2', 'bi-box2-fill', 'bi-box2-heart', 'bi-box2-heart-fill',
        'bi-boxes', 'bi-braces', 'bi-braces-asterisk', 'bi-bricks', 'bi-briefcase',
        'bi-briefcase-fill', 'bi-briefcase-medical', 'bi-brightness-alt-high',
        'bi-brightness-alt-high-fill', 'bi-brightness-alt-low',
        'bi-brightness-alt-low-fill', 'bi-brightness-high', 'bi-brightness-high-fill',
        'bi-brightness-low', 'bi-brightness-low-fill', 'bi-broadcast', 'bi-broadcast-pin',
        'bi-browser-chrome', 'bi-browser-edge', 'bi-browser-firefox', 'bi-browser-safari',
        'bi-brush', 'bi-brush-fill', 'bi-bucket', 'bi-bucket-fill', 'bi-bug',
        'bi-bug-fill', 'bi-building', 'bi-building-add', 'bi-building-check',
        'bi-building-dash', 'bi-building-down', 'bi-building-exclamation',
        'bi-building-fill', 'bi-building-fill-add', 'bi-building-fill-check',
        'bi-building-fill-dash', 'bi-building-fill-down', 'bi-building-fill-exclamation',
        'bi-building-fill-gear', 'bi-building-fill-lock', 'bi-building-fill-slash',
        'bi-building-fill-up', 'bi-building-fill-x', 'bi-building-gear',
        'bi-building-lock', 'bi-building-slash', 'bi-building-up', 'bi-building-x',
        'bi-buildings', 'bi-buildings-fill', 'bi-bullseye', 'bi-bullseye',
        'bi-bus-front', 'bi-bus-front-fill', 'bi-c-circle', 'bi-c-circle-fill',
        'bi-c-square', 'bi-c-square-fill', 'bi-cake', 'bi-cake-fill', 'bi-calculator',
        'bi-calculator-fill', 'bi-calendar', 'bi-calendar-check', 'bi-calendar-check-fill',
        'bi-calendar-date', 'bi-calendar-date-fill', 'bi-calendar-day', 'bi-calendar-day-fill',
        'bi-calendar-event', 'bi-calendar-event-fill', 'bi-calendar-fill',
        'bi-calendar-heart', 'bi-calendar-heart-fill', 'bi-calendar-minus',
        'bi-calendar-minus-fill', 'bi-calendar-month', 'bi-calendar-month-fill',
        'bi-calendar-plus', 'bi-calendar-plus-fill', 'bi-calendar-range',
        'bi-calendar-range-fill', 'bi-calendar-week', 'bi-calendar-week-fill',
        'bi-calendar-x', 'bi-calendar-x-fill', 'bi-calendar2', 'bi-calendar2-check',
        'bi-calendar2-check-fill', 'bi-calendar2-date', 'bi-calendar2-date-fill',
        'bi-calendar2-day', 'bi-calendar2-day-fill', 'bi-calendar2-event',
        'bi-calendar2-event-fill', 'bi-calendar2-fill', 'bi-calendar2-heart',
        'bi-calendar2-heart-fill', 'bi-calendar2-minus', 'bi-calendar2-minus-fill',
        'bi-calendar2-month', 'bi-calendar2-month-fill', 'bi-calendar2-plus',
        'bi-calendar2-plus-fill', 'bi-calendar2-range', 'bi-calendar2-range-fill',
        'bi-calendar2-week', 'bi-calendar2-week-fill', 'bi-calendar2-x', 'bi-calendar2-x-fill',
        'bi-calendar3', 'bi-calendar3-event', 'bi-calendar3-event-fill', 'bi-calendar3-fill',
        'bi-calendar3-range', 'bi-calendar3-range-fill', 'bi-calendar3-week',
        'bi-calendar3-week-fill', 'bi-calendar4', 'bi-calendar4-event', 'bi-calendar4-range',
        'bi-calendar4-week', 'bi-camera', 'bi-camera-fill', 'bi-camera-reels',
        'bi-camera-reels-fill', 'bi-camera-video', 'bi-camera-video-fill', 'bi-camera-video-off',
        'bi-camera-video-off-fill', 'bi-camera2', 'bi-capslock', 'bi-capslock-fill'
    ];

    function renderIcons(filter = '') {
        const iconList = document.getElementById('iconList');
        iconList.innerHTML = '';

        const filtered = icons.filter(icon => icon.includes(filter));
        if (filtered.length === 0) {
            iconList.innerHTML = '<p class="text-muted">Tidak ada icon ditemukan.</p>';
            return;
        }

        const chunkSize = 4;
        for (let i = 0; i < filtered.length; i += chunkSize) {
            const row = document.createElement('div');
            row.className = 'row mb-2';

            for (let j = i; j < i + chunkSize && j < filtered.length; j++) {
                const icon = filtered[j];

                const col = document.createElement('div');
                col.className = 'col-md-3';

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-light border w-100 d-flex align-items-center justify-content-start';
                btn.innerHTML = `<i class="bi ${icon} me-2"></i> ${icon}`;
                btn.onclick = () => {
                    document.getElementById('icon').value = `bi ${icon}`;
                    const pickerModal = bootstrap.Modal.getInstance(document.getElementById('iconPickerModal'));
                    pickerModal.hide();

                    const modalMenu = new bootstrap.Modal(document.getElementById('modalMenu'));
                    modalMenu.show();
                };

                col.appendChild(btn);
                row.appendChild(col);
            }

            iconList.appendChild(row);
        }
    }

    document.getElementById('iconSearch').addEventListener('input', (e) => {
        renderIcons(e.target.value.toLowerCase());
    });

    document.getElementById('iconPickerModal').addEventListener('shown.bs.modal', () => {
        renderIcons();
        document.getElementById('iconSearch').focus();
    });
</script>
@endpush
