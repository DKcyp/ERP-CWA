@extends('layouts.layout')

@section('title', 'Configuration')

@push('after-style')
    <style>
        .img-container {
            position: relative;
            width: 400px;
            height: 200px;
            /* overflow: hidden; */
        }

        .img-container img {
            width: 100%;
            height: 100%;
            border-radius: 16px;
            padding: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="page-heading">
        <h3>Configuration</h3>
    </div>
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <div class="img-container mb-5">
                    <i class="bg-white fas fa-pencil-alt p-2 rounded-circle"
                       style="position: absolute;right:2px;top:-2px;cursor: pointer;z-index:99;"
                       onclick="onInputLogo()"></i>
                    <input type="file" name="logo" id="logo" style="display: none;" accept="image/*">
                    <img src="" id="img-logo">
                </div>

                <form action="javascript:onSave()" method="post" id="form-configuration">
                    <div class="col-md-6">
                        <div class="form-group row align-items-center">
                            <div class="col-lg-4 col-4">
                                <label class="col-form-label required" for="app_name">Application Name</label>
                            </div>
                            <div class="col-lg-8 col-8">
                                <input type="text" id="app_name" class="form-control" name="app_name" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row align-items-center">
                            <div class="col-lg-4 col-4">
                                <label class="col-form-label" for="app_description">Description</label>
                            </div>
                            <div class="col-lg-8 col-8">
                                <textarea id="app_description" class="form-control" name="app_description"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- === Cut Off Stock (tanggal) === --}}
                    <div class="col-md-6">
                        <div class="form-group row align-items-center">
                            <div class="col-lg-4 col-4">
                                <label class="col-form-label" for="cutoff_stock">Cut Off Stock</label>
                            </div>
                            <div class="col-lg-8 col-8">
                                <input type="date" id="cutoff_stock" class="form-control" name="cutoff_stock">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" style="text-align: right;">
                        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
@endsection

@include('configuration.javascript')
