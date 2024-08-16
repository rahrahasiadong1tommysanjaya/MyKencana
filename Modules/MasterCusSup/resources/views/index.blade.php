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
    @vite(['resources/assets/js/currencyFormatter.js', 'modules/mastercussup/resources/assets/js/index.js', 'modules/mastercussup/resources/assets/js/modal/add.js', 'modules/mastercussup/resources/assets/js/modal/edit.js'])
@endsection

@section('title', 'Master Customers & Suppliers')

@section('content')
    {{ Breadcrumbs::render() }}
    @include('mastercussup::modal.add')
    @include('mastercussup::modal.edit')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">List Data Customers & Suppliers</h5>
            <div class="col-md-3 mb-4 mt-10">
                <div class="filter-select">
                    <select id="cusSupFilter" class="form-select">
                        <option value="cust">Customers</option>
                        <option value="supp">Suppliers</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="listCusSup table">
                <thead class="border-top">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Jenis</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
