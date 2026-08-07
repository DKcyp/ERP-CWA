@extends('layouts.layout')
@section('title','Role Permission')

@section('content')
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label form-label-sm">Search</label>
                <input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="Cari role...">
            </div>
            <div class="col-md-8 text-end">
                <button class="btn btn-sm btn-primary" onclick="openForm()"><i class="bi bi-plus-lg me-1"></i>Tambah Role</button>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover table-sm mb-0" style="font-size:0.85rem;" id="roleTable">
            <thead class="table-dark">
                <tr>
                    <th width="30">#</th>
                    <th>Role Name</th>
                    <th>Role Code</th>
                    <th>Menu Access</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="formModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title"><i class="bi bi-shield-lock me-1"></i><span id="modalTitle">Tambah Role</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="mainForm">
                    <input type="hidden" id="formId">
                    <div class="mb-3">
                        <label class="form-label form-label-sm">Role Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="role_name" required placeholder="e.g. Admin, Manager, Staff">
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="saveForm()"><i class="bi bi-check-lg me-1"></i>Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="permissionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white py-2">
                <h6 class="modal-title"><i class="bi bi-key me-1"></i>Hak Akses Menu: <span id="permRoleName"></span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="permRoleId">
                <div class="mb-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleAll(true)">Pilih Semua</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleAll(false)">Hapus Semua</button>
                </div>
                <div id="menuTreeContainer" style="max-height:400px;overflow-y:auto;"></div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="savePermission()"><i class="bi bi-check-lg me-1"></i>Simpan Hak Akses</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-script')
<script>
let table;

$(function(){
    table = $('#roleTable').DataTable({
        processing:true, serverSide:true,
        ajax:{ url:'{{ route("role-permision.table") }}', data:function(d){
            d.filter_search = $('#filterSearch').val();
        }},
        columns:[
            {data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false},
            {data:'role_name',name:'role_name',render:function(d){return '<strong>'+d+'</strong>'}},
            {data:'role_code',name:'role_code',render:function(d){return '<span class="badge bg-secondary">'+d+'</span>'}},
            {data:'menu_count',name:'menu_count',render:function(d){return '<span class="badge bg-info">'+d+' menu</span>'}},
            {data:'action',name:'action',orderable:false,searchable:false},
        ],
        order:[[1,'asc']],
        language:{processing:'Memuat data...'},
    });
    $('#filterSearch').on('keyup', debounce(()=>table.ajax.reload(),300));
});

function openForm(){
    $('#modalTitle').text('Tambah Role');
    $('#mainForm')[0].reset();
    $('#formId').val('');
    new bootstrap.Modal('#formModal').show();
}

function editRole(id){
    $.post('{{ route("role-permision.show") }}',{role_id:id},function(r){
        if(r.data){
            $('#modalTitle').text('Edit Role');
            $('#formId').val(r.data.id);
            $('#role_name').val(r.data.role_name||'');
            new bootstrap.Modal('#formModal').show();
        }
    });
}

function saveForm(){
    const id = $('#formId').val();
    const payload = {role_name: $('#role_name').val()};
    if(!payload.role_name){alert('Role Name wajib diisi');return;}
    if(id) payload.role_id = id;

    $.ajax({url:'{{ route("role-permision.store") }}',method:'POST',data:payload,success:function(r){
        bootstrap.Modal.getInstance(document.getElementById('formModal')).hide();
        table.ajax.reload();
        showToast(r.message||'Data tersimpan','success');
    },error:function(xhr){
        const e=xhr.responseJSON||{};
        alert('Error: '+(e.message||xhr.responseText));
    }});
}

function deleteRole(id){
    if(!confirm('Hapus role ini? Semua hak akses akan dihapus.'))return;
    $.ajax({url:'{{ route("role-permision.destroy") }}',method:'POST',data:{id:id,_method:'DELETE'},success:function(r){
        table.ajax.reload();
        showToast(r.message||'Data dihapus','success');
    }});
}

let allMenus = [];
function detailRole(id){
    $.post('{{ route("role-permision.show") }}',{role_id:id},function(r){
        if(r.data){
            $('#permRoleId').val(r.data.id);
            $('#permRoleName').text(r.data.role_name);
            const assigned = (r.data.menus||[]).map(m=>m.id);

            $.get('{{ route("role-permision.getMenus") }}',function(mRes){
                allMenus = mRes.data||[];
                renderMenuTree(allMenus, assigned);
                new bootstrap.Modal('#permissionModal').show();
            });
        }
    });
}

function renderMenuTree(menus, assigned){
    const parents = menus.filter(m=>!m.main_menu);
    let html = '';
    parents.forEach(function(p){
        const children = menus.filter(m=>m.main_menu===p.id);
        const isAssigned = assigned.includes(p.id);
        html += '<div class="mb-1">';
        html += '<div class="form-check"><input class="form-check-input menu-parent" type="checkbox" value="'+p.id+'" data-parent="'+p.id+'" '+(isAssigned?'checked':'')+' onchange="toggleChildren('+p.id+',this.checked)"><label class="form-check-label fw-bold">'+p.name+'</label></div>';
        children.forEach(function(c){
            const isC Assigned = assigned.includes(c.id);
            html += '<div class="ms-4 form-check"><input class="form-check-input menu-child" type="checkbox" value="'+c.id+'" data-parent="'+c.main_menu+'" '+(isAssigned?'checked':'')+'><label class="form-check-label">'+c.name+'</label></div>';
        });
        html += '</div>';
    });
    $('#menuTreeContainer').html(html);
}

function toggleChildren(parentId, checked){
    $('.menu-child[data-parent="'+parentId+'"]').prop('checked', checked);
}

function toggleAll(checked){
    $('.menu-parent, .menu-child').prop('checked', checked);
}

function savePermission(){
    const ids = [];
    $('.menu-parent:checked, .menu-child:checked').each(function(){ ids.push($(this).val()); });
    $.ajax({url:'{{ route("role-permision.savePermission") }}',method:'POST',data:{role_id:$('#permRoleId').val(),menu_ids:ids},success:function(r){
        bootstrap.Modal.getInstance(document.getElementById('permissionModal')).hide();
        table.ajax.reload();
        showToast(r.message||'Hak akses tersimpan','success');
    }});
}
</script>
@endpush
