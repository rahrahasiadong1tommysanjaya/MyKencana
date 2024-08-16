@php
    $customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Restore Database')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/dropzone/dropzone.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

@section('page-style')
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/dropzone/dropzone.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

@section('page-script')
    @vite(['resources/js/restore-database.js'])
@endsection

@section('content')
    <div class="container-xxl col-6">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-6">
                <!-- Restore database -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-6">
                            <a href="{{ url('/') }}" class="app-brand-link">
                                <span class="app-brand-logo demo">@include('_partials.macros', ['height' => 20, 'withbg' => 'fill: #fff;'])</span>
                                <span
                                    class="app-brand-text demo text-heading fw-bold">{{ config('variables.templateName') }}</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <p class="mb-6">* Silahkan upload file dalam bentuk ekstensi .sql untuk melakukan restore</p>
                        <form action="{{ route('restore-database') }}" class="dropzone needsclick" id="dropzone-basic"
                            enctype="multipart/form-data">

                            @csrf
                            <div class="dz-message needsclick">
                                Drop file atau klik untuk memilih file
                            </div>
                            <div class="fallback">
                                <input id="fileInput" name="file" type="file" />
                            </div>
                        </form>
                        <div class="mb-6 mt-5">
                            <button class=" btn btn-primary d-grid w-100" id="uploadButton" type="button">Upload</button>
                        </div>
                        {{-- <p class="text-center mt-10">
                            <a href="{{ route('auth-login') }}">
                                <span>Kembali ke halaman Login</span>
                            </a>
                        </p> --}}
                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>
@endsection
