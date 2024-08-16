@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/spinkit/spinkit.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss', 'resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

@section('page-style')
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

@section('page-script')
    @vite(['resources/assets/js/currencyFormatter.js', 'modules/masterjenisbarang/resources/assets/js/index.js'])
@endsection

@section('title', 'Master Jenis Barang')

@section('content')
    {{ Breadcrumbs::render() }}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">List Data Jenis Barang</h5>
        </div>
        <div class="card-datatable table-responsive">
            <table class="listJenisBarang table">
                <thead class="border-top">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Aksi</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="text" class="form-control" id="acc" name="acc" placeholder="masukkan kode"
                                autocomplete="off" /></td>
                        <td><input type="text" class="form-control" id="ket" name="ket"
                                placeholder="masukkan nama" autocomplete="off" /></td>
                        <td>
                            <button type="button" class="btn-tambah btn btn-primary me-1 mb-1">
                                <i class="fa fa-save opacity-50 me-1 float-right"></i> Tambah
                            </button>
                        </td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
