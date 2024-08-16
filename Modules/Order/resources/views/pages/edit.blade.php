@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.scss', 'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.css', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

@section('page-style')
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

@section('page-script')
    @vite(['modules/order/resources/assets/js/pages/edit.js', 'modules/order/resources/assets/js/pages/listDetail.js', 'modules/order/resources/assets/js/modal/addDetail.js'])
@endsection

@section('title', 'Edit Order')

@section('content')

    {{ Breadcrumbs::render('order-edit', $result->id) }}
    @include('order::modal.addDetail')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="card-title mb-0">Edit Order {{ $result->buk }}</h5>
            <button type="submit" class="btn-update btn btn-primary"><i class='ti ti-device-floppy me-1'></i>
                Update</button>
        </div>
        <div class="card-body">
            <form id="formEdit" class="formEdit" autocomplete="off">
                <div class="row mb-3 mb-4">
                    <div class="col-sm-4">
                        <label for="ket" class="form-label fs-6">Tanggal
                            <input type="text" class="form-control visually-hidden" id="buk" name="buk"
                                value="{{ $result->buk }}">
                            <input type="text" class="form-control visually-hidden" id="id" name="id"
                                value="{{ $result->id }}">
                        </label>
                        <input type="text" id="tgl" name="tgl" class="tgl form-control date-picker"
                            value="{{ date('d-m-Y', strtotime($result->tgl)) }}" placeholder="Pilih tanggal"
                            autocomplete="off" required>
                        <div class="invalid-feedback"> Tanggal harus diisi... </div>
                    </div>
                    <div class="col-sm-4">
                        <label for="ar_id" class="form-label fs-6">Customer</label>
                        <select id="ar_id" name="ar_id" class="selectCustomers form-select form-select-md"
                            data-allow-clear="true" required>
                            <option value="{{ $result->ar_id }}" selected>{{ $result->nar }}</option>
                        </select>
                        <div class="invalid-feedback"> Customer harus dipilih... </div>
                    </div>
                    <div class="col-sm-4">
                        <label for="ket" class="form-label fs-6">Tanggal Jatuh Tempo
                        </label>
                        <input type="text" id="tjt" name="tjt" class="tjt form-control date-picker"
                            value="{{ date('d-m-Y', strtotime($result->tjt)) }}" placeholder="Pilih tanggal"
                            autocomplete="off" required>
                        <div class="invalid-feedback"> Tanggal Jatuh Tempo harus diisi... </div>
                    </div>
                </div>
                <div class="col-md-12 mb-4">
                    <label for="ctt" class="form-label fs-6">Catatan</label>
                    <textarea id="ctt" name="ctt" class="form-control" placeholder="Masukkan catatan" autocomplete="off">{{ $result->ctt }}</textarea>
                </div>
            </form>
            <br>
            <div class="card-datatable table-responsive">
                <table class="listDetail table">
                    <thead class="border-top">
                        <tr>
                            <th>No</th>
                            <th>Barang</th>
                            <th>Tanggal Kirim</th>
                            <th>Quantity</th>
                            <th>Harga</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
