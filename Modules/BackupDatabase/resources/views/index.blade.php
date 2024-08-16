@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/spinkit/spinkit.scss', 'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

@section('page-style')
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

@section('page-script')
    @vite(['modules/BackupDatabase/resources/assets/js/index.js'])
@endsection

@section('title', 'Backup Database')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Backup Database</h5>
        </div>
        <div class="card-body">
            <!-- Backup Button -->
            <div class="mb-4">
                <button id="backupBtn" class="btn btn-primary">Start Backup</button>
            </div>

            <!-- Backup Status -->
            <div id="backupStatus" class="alert alert-info d-none" role="alert">
                Backup in progress...
            </div>

            <div class="card-datatable table-responsive">
                <table id="backupTable" class="backupTable table">
                    <thead>
                        <tr>
                            <th>Backup Name</th>
                            <th>Date</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
