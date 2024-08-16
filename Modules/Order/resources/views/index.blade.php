@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.scss', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/spinkit/spinkit.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss', 'resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

@section('page-style')
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

@section('page-script')
    @vite(['modules/order/resources/assets/js/index.js', 'modules/order/resources/assets/js/modal/add.js'])
@endsection

@section('title', 'Order')

@section('content')
    {{ Breadcrumbs::render() }}
    @include('order::modal.add')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="card-title mb-0">List Order</h5>
            <div>
                <input type="text" id="monthPicker" class="form-control month-picker" placeholder="Select month"
                    autocomplete="off">
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="listOrder table">
                <thead class="border-top">
                    <tr>
                        <th>No</th>
                        <th>Bukti</th>
                        <th>Tanggal</th>
                        <th>Tanggal J.Tempo</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
