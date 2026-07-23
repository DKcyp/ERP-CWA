{{-- Modal: Set Hak Akses Menu (Tree) --}}
<div class="modal fade" id="modalRole" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg"> {{-- lebarkan biar tree nyaman --}}
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modelHeading">Form Role Menu</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="form-role" class="form-horizontal" action="javascript:onSaveRoleMenu()" method="POST">
          @csrf
          <input type="hidden" name="role_id" id="role_id">
          <div id="formContent" class="menu-tree"></div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" form="form-role" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

{{-- Modal: Tambah/Ubah Role --}}
<div class="modal fade" id="modalAddRole" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modelHeadingAdd">Tambah Data</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="form-role-add" class="form-horizontal" action="javascript:onSave()" method="POST">
          @csrf
          <input type="hidden" name="role_id" id="role_idAdd">
          <div class="mb-3">
            <label for="role_name" class="form-label">Role Name</label>
            <input type="text" class="form-control" name="role_name" id="role_name" required>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" form="form-role-add" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
