@extends('layouts.layout')
@section('title', $title ?? 'Placeholder')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h3 class="mb-3">{{ $title ?? 'Placeholder Page' }}</h3>
        <p class="text-muted mb-0">Halaman ini belum dibuat. Silakan tambahkan tampilan sesuai modul QC yang dibutuhkan.</p>
    </div>
</div>
@endsection
