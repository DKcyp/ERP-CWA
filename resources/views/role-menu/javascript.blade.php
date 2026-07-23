<script type="text/javascript">
  let table = "#data-table";

  $.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
  });

  $(function () {
    table = $(table).DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('role.table') }}",
      columns: [
        { data: 'DT_RowIndex', name: 'id', searchable: false, orderable: false, className: 'text-center' },
        { data: 'role_name', name: 'role_name', className: 'text-center' },
        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
      ]
    });
  });

  // ===================== TREE RENDERING =====================
function sortFlatMenus(flat) {
  // Server sudah mengurutkan; ini hanya fallback kecil aja
  flat.sort((a, b) => {
    const pa = a.main_menu ?? null;
    const pb = b.main_menu ?? null;
    if (pa === null && pb !== null) return -1;
    if (pa !== null && pb === null) return 1;
    return String(a.name).localeCompare(String(b.name));
  });
  return flat;
}


  function buildTree(flat) {
    const byId = new Map();
    flat.forEach(item => {
      byId.set(item.id, { ...item, children: [] });
    });
    const roots = [];
    byId.forEach(node => {
      const parentId = node.main_menu ?? null;
      if (parentId && byId.has(parentId)) byId.get(parentId).children.push(node);
      else roots.push(node);
    });
    return roots;
  }

  function renderTree(nodes, depth = 0) {
    const $ul = $('<ul class="list-unstyled mb-1"></ul>');
    nodes.forEach(node => {
      const checked = node.menu_selected ? 'checked' : '';
      const hasChildren = node.children && node.children.length > 0;
      const idAttr = `menu-${node.id}`;
      const indentClass = depth === 0 ? "" : `ms-${Math.min(depth * 3, 5)}`;

      const $li = $('<li class="mb-1"></li>');
      const $row = $(`
        <div class="d-flex align-items-center ${indentClass}">
          ${hasChildren
            ? `<button type="button" class="btn btn-sm btn-outline-secondary me-2 toggle-branch" aria-expanded="true">
                 <i class="bi bi-caret-down-fill"></i>
               </button>`
            : `<span class="me-2" style="width:28px;"></span>`
          }
          <div class="form-check form-switch flex-grow-1">
            <input class="form-check-input menu-check" type="checkbox" id="${idAttr}" name="menu_id[]" value="${node.id}" ${checked}>
            <label class="form-check-label fw-semibold" for="${idAttr}">
              ${node.name}
            </label>
          </div>
        </div>
      `);
      $li.append($row);

      if (hasChildren) {
        const $child = renderTree(node.children, depth + 1);
        $li.append($child);
      }

      $ul.append($li);
    });
    return $ul;
  }

  function initializeIndeterminate($scope) {
    // proses dari node terdalam ke atas
    $scope.find('li').get().reverse().forEach(li => {
      const $li = $(li);
      const $childChecks = $li.children('ul').first().find('> li > .d-flex .menu-check');
      if ($childChecks.length) {
        const total = $childChecks.length;
        const checkedCount = $childChecks.filter((i, el) => el.checked).length;
        const indCount = $childChecks.filter((i, el) => el.indeterminate).length;
        const $self = $li.find('> .d-flex .menu-check');

        if (checkedCount === total) {
          $self.prop('checked', true).prop('indeterminate', false);
        } else if (checkedCount === 0 && indCount === 0) {
          $self.prop('indeterminate', false);
        } else {
          $self.prop('checked', false).prop('indeterminate', true);
        }
      }
    });
  }

  function propagateDown($chk, checked) {
    const $li = $chk.closest('li');
    $li.find('input.menu-check').prop('checked', checked).prop('indeterminate', false);
  }

  function updateParentState($chk) {
    const $li = $chk.closest('li');
    const $parentUl = $li.parent('ul');
    const $parentLi = $parentUl.closest('li');
    if ($parentLi.length === 0) return;

    const $siblings = $parentUl.find('> li > .d-flex .menu-check');
    const total = $siblings.length;
    const checkedCount = $siblings.filter((i, el) => el.checked).length;
    const indCount = $siblings.filter((i, el) => el.indeterminate).length;
    const $parentChk = $parentLi.find('> .d-flex .menu-check');

    if (checkedCount === total) {
      $parentChk.prop('checked', true).prop('indeterminate', false);
    } else if (checkedCount === 0 && indCount === 0) {
      $parentChk.prop('checked', false).prop('indeterminate', false);
    } else {
      $parentChk.prop('checked', false).prop('indeterminate', true);
    }
    updateParentState($parentChk);
  }

  // ===================== AJAX HANDLERS =====================
  function bindTreeInteractions() {
    $('#formContent')
      .off('click', '.toggle-branch')
      .on('click', '.toggle-branch', function () {
        const $btn = $(this);
        const $li = $btn.closest('li');
        const expanded = $btn.attr('aria-expanded') === 'true';
        $btn.attr('aria-expanded', expanded ? 'false' : 'true');
        $btn.find('i').toggleClass('bi-caret-down-fill bi-caret-right-fill');
        $li.children('ul').first().toggleClass('d-none', expanded);
      });

    $('#formContent')
      .off('change', 'input.menu-check')
      .on('change', 'input.menu-check', function () {
        propagateDown($(this), this.checked);
        updateParentState($(this));
      });
  }

  onShowRole = (id) => {
    $("#role_id").val(id);
    $.ajax({
      url: "role-menu/show",
      method: "POST",
      data: { role_id: id },
      success: function (data) {
        const flat = Array.isArray(data.menu) ? data.menu : [];
        sortFlatMenus(flat);
        const tree = buildTree(flat);
        const $ui = renderTree(tree, 0);

        $('#formContent').empty().append($ui);
        initializeIndeterminate($('#formContent'));
        bindTreeInteractions();
        $("#modalRole").modal("show");
      }
    });
  };

  onSaveRoleMenu = () => {
    if (typeof HELPER?.block === 'function') HELPER.block();
    $.ajax({
      data: $('#form-role').serialize(),
      url: "{{ route('role.saveRoleMenu') }}",
      type: "POST",
      dataType: 'json',
      success: function () {
        $('#form-role').trigger("reset");
        $("#modalRole").modal('hide');
        $("#role_id").val('');
        table.draw();
        if (typeof HELPER?.unblock === 'function') HELPER.unblock();
      },
      error: function (xhr) {
        console.log('Error:', xhr);
        Swal.fire({ icon:'error', title: "Error", text: "System error!" });
        if (typeof HELPER?.unblock === 'function') HELPER.unblock();
      }
    });
  };

  onEdit = (id) => {
    $.ajax({
      url: "{{ route('role.showRole') }}",
      method: 'POST',
      data: { role_id: id },
      success: function (data) {
        $("#modelHeadingAdd").html('Ubah Data');
        $("#role_idAdd").val(data.data.id);
        $("#role_name").val(data.data.role_name);
        $("#modalAddRole").modal('show');
      },
      error: function () {
        Swal.fire({ icon:'error', title: "Error", text: "System error!" });
      }
    });
  };

  onSave = () => {
    $.ajax({
      data: $('#form-role-add').serialize(),
      url: "{{ route('role.store') }}",
      type: "POST",
      dataType: 'json',
      success: function () {
        $('#form-role-add').trigger("reset");
        $("#modalAddRole").modal('hide');
        $("#role_idAdd").val('');
        table.draw();
      },
      error: function (xhr) {
        console.log('Error:', xhr);
        Swal.fire({ icon:'error', title: "Error", text: "System error!" });
      }
    });
  };

  onDelete = (id) => {
    if (typeof HELPER?.confirmDelete === 'function') {
      HELPER.confirmDelete({
        url: "{{ route('role.destroy') }}",
        data: { id },
        callback: (response) => { if (response.success) table.draw(); },
      });
    } else {
      // fallback simple confirm
      if (!confirm('Yakin hapus role ini?')) return;
      $.post("{{ route('role.destroy') }}", { id }, function(){ table.draw(); });
    }
  };
</script>
