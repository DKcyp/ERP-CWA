@extends('layouts.layout')
@section('title','Product Stock Quick View')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-3"><div class="card-body py-2">
        <div class="row g-2 align-items-center">
            <div class="col-md-6"><div class="input-group"><span class="input-group-text bg-white"><i class="bi bi-search"></i></span><input type="text" class="form-control form-control-lg" id="quick-search" placeholder="Ketik Product ID, Nama Produk, atau Nama Gudang..." autofocus></div></div>
            <div class="col-md-3"><span class="badge bg-light text-dark fs-6 p-2 w-100" id="result-count">Memuat...</span></div>
            <div class="col-md-3 d-flex justify-content-md-end"><button class="btn btn-outline-secondary btn-sm" onclick="loadData()"><i class="bi bi-arrow-clockwise me-1"></i>Refresh</button></div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body p-0">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 mb-0" id="quick-table">
            <thead class="table-dark"><tr><th class="text-center" style="width:40px">#</th><th>Product ID</th><th>Nama Produk</th><th>Gudang</th><th class="text-center">Available Qty</th><th class="text-center">UOM</th><th class="text-center">Last Updated</th></tr></thead>
            <tbody id="quick-body"></tbody>
        </table></div>
        <div class="text-center py-5 d-none" id="empty-state"><i class="bi bi-inbox text-muted" style="font-size:3rem;"></i><p class="text-muted mt-2 mb-0">Ketik kata kunci untuk mulai mencari...</p></div>
        <div class="text-center py-5 d-none" id="no-results"><i class="bi bi-search text-muted" style="font-size:3rem;"></i><p class="text-muted mt-2 mb-0">Data tidak ditemukan</p></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
let debounceTimer;
const $body=$('#quick-body'), $count=$('#result-count'), $empty=$('#empty-state'), $noRes=$('#no-results');

function loadData(q){
    $.get('{{route("product-stock-quick-view.data")}}',{search:q||''},function(r){
        $body.empty(); $empty.addClass('d-none'); $noRes.addClass('d-none');
        if(!q && r.data.length===0){$empty.removeClass('d-none');$count.text('0 produk');return;}
        if(r.data.length===0){$noRes.removeClass('d-none');$count.text('0 hasil ditemukan');return;}
        r.data.forEach(function(row,i){
            const avail=row.available;
            const cls=avail<=0?'text-danger fw-bold':(avail<100?'text-warning fw-bold':'text-success fw-bold');
            $body.append(`<tr><td class="text-center text-muted">${i+1}</td><td><code>${row.product_id}</code></td><td>${row.name}</td><td><i class="bi bi-geo-alt text-primary me-1"></i>${row.warehouse}</td><td class="text-center"><span class="${cls} fs-6">${avail.toLocaleString('id-ID')}</span></td><td class="text-center">${row.uom}</td><td class="text-center text-muted small">${row.last_updated}</td></tr>`);
        });
        $count.text(r.count+' produk ditemukan');
    });
}

$('#quick-search').on('input',function(){
    clearTimeout(debounceTimer);
    const q=$(this).val().trim();
    if(q.length===0){loadData('');return;}
    debounceTimer=setTimeout(function(){loadData(q)},300);
});

loadData('');
</script>
@endpush