@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/@form-validation/umd/styles/index.min.css', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss', 'resources/assets/vendor/libs/jstree/jstree.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js', 'resources/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js', 'resources/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js', 'resources/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js', 'resources/assets/vendor/libs/jstree/jstree.js'])
@endsection

@section('page-script')
    @vite(['modules/masterusers/resources/assets/js/menu.js'])
@endsection

@section('title', 'Master Users')

@section('content')
    @include('masterusers::modal.pages.editPermissionMenu')
    {{ Breadcrumbs::render('master-users-menu', $user->id) }}
    <div class="row">
        <div class="col-md-5 col-12">
            <div class="card mb-4">
                <h5 class="card-header">List Menu {{ $user->name }}</h5>
                <div class="card-body">
                    drag & drop untuk mengubah urutan atau letak menu sub/parent. <br>
                    klik kanan pada menu untuk menampilkan tombol aksi.
                    <div id="jstree-menu" class="overflow-auto mt-3"></div>
                </div>
            </div>
        </div>
        <div class="col-md-7 col-12">
            <div class="card mb-4">
                <h5 class="card-header">Form Tambah Menu</h5>
                <div class="card-body">
                    <div class="form-floating">
                        <form id="formInputMenuUser" class="formInputMenuUser" novalidate>
                            <input class="form-control visually-hidden" type="text" name="userId"
                                value="{{ $user->id }}">
                            <div class="col-md-12 mb-4">
                                <label for="menu" class="form-label">Menu</label>
                                <select name="menu" class="selectMenu form-select form-select-lg" data-allow-clear="true"
                                    required>
                                    <option value=""></option>
                                </select>
                                <div class="invalid-feedback"> Menu harus dipilih... </div>
                            </div>

                            <div class="col-md-12 mb-4">
                                <label for="permission" class="form-label">Permission</label>
                                <select name="permission[]" class="selectPermission form-select">
                                    <option value=""></option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary float-end">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
