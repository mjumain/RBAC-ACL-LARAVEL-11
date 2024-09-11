@extends('layouts.app')
@push('css')
<link rel="stylesheet" href="{{ asset('') }}assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('') }}assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('') }}assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endpush
@section('content')
<div class="content-header">
</div>
<div class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header d-flex p-0">
                <h1 class="card-title p-3">Import Tagihan Mahasiswa</h1>
                <ul class="nav nav-pills ml-auto p-2">
                    <li class="nav-item"><a class="nav-link active btn-sm" id="tambahdata" href="#">Tambah
                            Data</a></li>
                </ul>
                <ul class="nav nav-pills ml-auto p-2">
                    <li class="nav-item"><a class="nav-link active btn-sm" id="importdata" href="#">Import
                            Data</a></li>
                </ul>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped data-table" style="width: 100%">
                    <!-- <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Invoice</th>
                            <th>Nomor Pembayaran</th>
                            <th>Nama Mahasiswa</th>
                            <th>Informasi Tagihan</th>
                            <th>Rincian Tagihan</th>
                            <th>Total Tagihan</th>
                            <th>Tanggal Berlaku</th>
                            <th>Tanggal Kadaluarsa</th>
                            <th>Tanggal Pembayaran</th>
                            <th>Status Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody> -->
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ asset('') }}assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('') }}assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('') }}assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('') }}assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="{{ asset('') }}assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('') }}assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="{{ asset('') }}assets/js/notification.js"></script>
@endpush