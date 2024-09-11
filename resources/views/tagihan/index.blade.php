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
                    <h1 class="card-title p-3">Data Tagihan Mahasiswa</h1>
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
                        <thead>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalForm">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="formdata">
                        @csrf
                        <input type="text" class="form-control" id="dataID" name="id" value="" hidden>
                        <input type="text" class="form-control" id="dataAksi" name="aksi" value="" hidden>
                        <div class="form-group">
                            <label>Nomor Pokok Mahasiswa</label>
                            <input type="text" class="form-control" id="nim" name="nim"
                                placeholder="Masukkan Nomor Induk Mahasiswa">
                        </div>
                        <div class="form-group">
                            <label>Nama Mahasiswa</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                placeholder="Masukkan Nama Mahasiswa">
                        </div>
                        <div class="info after-add-more-info">
                            <div class="form-group">
                                <label>Informasi Tagihan</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" placeholder="Label" disabled>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" placeholder="Value" disabled>
                                    </div>
                                    <div class="col-md-1 text-center">
                                        <button type="button" class="btn btn-success add-more-info">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="rincian after-add-more-rincian">
                            <div class="form-group">
                                <label>Rincian Tagihan</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" placeholder="Kode Rincian" disabled>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" placeholder="Nominal" disabled>
                                    </div>
                                    <div class="col-md-1 text-center">
                                        <button type="button" class="btn btn-success add-more-rincian">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="savedata" value="create">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalImport">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">IMPORT DATA</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="formimport" enctype="multipart/form-data">
                        @csrf
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFile" name="berkas">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="import">Import</button>
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
    <script type="text/javascript">
        $(function() {
            bsCustomFileInput.init();
        });
        $(document).ready(function() {
            $(".add-more-info").click(function() {
                var html = '<div class="form-group"><div class="info"><div class="row">' +
                    '<div class="col-md-4">' +
                    '<input type="text" class="form-control" placeholder="Label" value="" name="label_key[]">' +
                    '</div>' +
                    '<div class="col-md-7">' +
                    '<input type="text" class="form-control" placeholder="Value" value=""' +
                    'name="label_value[]">' +
                    '</div>' +
                    '<div class="col-md-1 text-center">' +
                    '<button type="button" class="btn btn-danger remove clear-info">x</button>' +
                    '</div></div></div></div>';;

                $(".after-add-more-info").after(html);
            });

            $("body").on("click", ".remove", function() {
                $(this).parents(".info").remove();
            });


            $(".add-more-rincian").click(function() {
                var html = '<div class="form-group"><div class="rincian"><div class="row">' +
                    '<div class="col-md-4">' +
                    '<input type="text" class="form-control" placeholder="Kode Rincian" value="" name="kode_rincian[]">' +
                    '</div>' +
                    '<div class="col-md-7">' +
                    '<input type="text" class="form-control" placeholder="Nominal" value="" ' +
                    'name="nominal[]">' +
                    '</div>' +
                    '<div class="col-md-1 text-center">' +
                    '<button type="button" class="btn btn-danger remove clear-rincian">x</button>' +
                    '</div></div></div></div>';

                $(".after-add-more-rincian").after(html);
            });

            $("body").on("click", ".remove", function() {
                $(this).parents(".rincian").remove();
            });
        });

        var url = "tagihan";
        var csrf = "{{ csrf_token() }}";

        var resetform = function() {
            $("#formdata").trigger('reset')
            $('#savedata').val('create')
            $('#dataID').val('')
            $('#dataAksi').val('')
            $('#nim').val('')
            $('#nama').val('')

            $(document).ready(function() {
                $(".clear-info").trigger("click");
                $(".clear-rincian").trigger("click");
            });
        }


        $('body').on('click', '.editdata', function() {
            resetform()
            var id = $(this).data('id');
            $.ajax({
                url: `/${url}/${id}/edit`,
                type: "GET",
                dataType: 'json',
                success: function(data) {
                    $('#savedata').val('edit');
                    $('#modalForm').modal('show')
                    $('#dataID').val(data.data.tagihan.id)
                    $('#nim').val(data.data.tagihan.id_pelanggan)
                    $('#nama').val(data.data.tagihan.nama)
                    $('#dataAksi').val(data.data.aksi.aksi)

                    var html_info = ''

                    var array_info = JSON.parse(data.data.tagihan.info);
                    array_info.forEach((element, index, array) => {
                        html_info +=
                            '<div class="form-group"><div class="info"><div class="row">' +
                            '<div class="col-md-4">' +
                            '<input type="text" class="form-control" placeholder="Label" value="' +
                            element.label_key + '" name="label_key[]">' +
                            '</div>' +
                            '<div class="col-md-7">' +
                            '<input type="text" class="form-control" placeholder="Value" value="' +
                            element.label_value + '"' +
                            'name="label_value[]">' +
                            '</div>' +
                            '<div class="col-md-1 text-center">' +
                            '<button type="button" class="btn btn-danger remove clear-info">x</button>' +
                            '</div></div></div></div>';
                    })

                    $(".after-add-more-info").after(html_info);

                    var html_rincian = ''
                    var array_rincian = JSON.parse(data.data.tagihan.rincian);
                    array_rincian.forEach((element, index, array) => {

                        html_rincian +=
                            '<div class="form-group"><div class="rincian"><div class="row">' +
                            '<div class="col-md-4">' +
                            '<input type="text" class="form-control" placeholder="Kode Rincian" value="' +
                            element.kode_rincian + '" name="kode_rincian[]">' +
                            '</div>' +
                            '<div class="col-md-7">' +
                            '<input type="text" class="form-control" placeholder="Nominal" value="' +
                            element.nominal + '" ' +
                            'name="nominal[]">' +
                            '</div>' +
                            '<div class="col-md-1 text-center">' +
                            '<button type="button" class="btn btn-danger remove clear-rincian">x</button>' +
                            '</div></div></div></div>';
                    })
                    $(".after-add-more-rincian").after(html_rincian);
                },
                error: function(data) {
                    if (data.status === 401) {
                        window.location.href = '401';
                    } else {
                        alert('Terjadi kesalahan: ' + data.statusText);
                    }
                }
            });
        });

        $('body').on('click', '#tambahdata', function() {
            resetform()
            $.ajax({
                url: `/${url}/create`,
                type: "GET",
                data: {
                    aksi: 'create'
                },
                success: function(data) {
                    $('#modalForm').modal('show')
                },
                error: function(data) {
                    if (data.status === 401) {
                        window.location.href = '401';
                    } else {
                        alert('Terjadi kesalahan: ' + data.statusText);
                    }
                }
            });
        });

        $('body').on('click', '#importdata', function() {
            resetform()
            $.ajax({
                url: `/${url}/create`,
                type: "GET",
                data: {
                    aksi: 'import'
                },
                success: function(data) {
                    $('#modalImport').modal('show')
                },
                error: function(data) {
                    if (data.status === 401) {
                        window.location.href = '401';
                    } else {
                        alert('Terjadi kesalahan: ' + data.statusText);
                    }
                }
            });
        });
        $('body').on('click', '#import', function() {
            let formData = new FormData($('#formimport')[0]);
            formData.append('aksi', 'preview');
            $.ajax({
                data: formData,
                url: `/${url}`,
                type: "POST",
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#modalImport').modal('hide')
                    $('.data-table').hide();
                    var previewData = response.data;

                    // Render data dalam bentuk tabel
                    var table =
                        '<table class="table table-bordered table-striped data-preview" style="width: 100%">';
                    table +=
                        '<thead><th>No.</th><th>NIM</th><th>Nama</th><th>Keterangan</th></thead></tbody>';
                    let jumlah_error = 0;
                    let rowClass = "";
                    previewData.forEach((element, index, array) => {
                        if (element.keterangan !== "Siap import") {
                            rowClass = "bg-danger text-white";
                            jumlah_error = (jumlah_error + 1);
                        } else {
                            rowClass = "bg-green text-white";
                        }
                        table += `<tr class="${rowClass}">`;
                        table += '<td>' + (index + 1) + '</td>';
                        table += '<td>' + element.nim + '</td>';
                        table += '<td>' + element.nama + '</td>';
                        table += '<td>' + element.keterangan + '</td>';
                        table += '</tr>';
                    });

                    table +=
                        '</tbody></table>';

                    $('.card-body').html(table);

                    if (jumlah_error > 0) {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Terdapat data yang tidak siap import, silahkan cek terlebih dahulu"
                        });
                    } else {
                        Swal.fire({
                            title: "Apakah anda yakin ?",
                            text: "Data siap untuk diimport",
                            icon: "question",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Ya, hapus !",
                            cancelButtonText: "Tidak !",
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                let formData = new FormData();
                                formData.append('aksi', 'import');
                                formData.append('input', JSON.stringify(response.data));
                                $.ajax({
                                    data: formData,
                                    url: `/${url}`,
                                    type: "POST",
                                    headers: {
                                        'X-CSRF-TOKEN': csrf
                                    },
                                    contentType: false,
                                    processData: false,
                                    success: function(response) {
                                        $('.data-preview').hide();
                                        $('.data-table').show();
                                        success(response.message);
                                        Swal.fire({
                                            title: "Berhasil . . .",
                                            text: "Data berhasil diimport",
                                            icon: "success",
                                            showCancelButton: false,
                                            confirmButtonColor: "#3085d6",
                                            confirmButtonText: "OK"
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                location.reload()
                                            }
                                        });
                                    },
                                    error: function(data) {
                                        if (data.status === 401) {
                                            window.location.href = '401';
                                        } else {
                                            alert('Terjadi kesalahan: ' + data
                                                .responseText);
                                        }
                                    }
                                });
                            }
                        });

                    }

                },
                error: function(data) {
                    if (data.status === 401) {
                        window.location.href = '401';
                    } else {
                        alert('Terjadi kesalahan: ' + data.statusText);
                    }
                }
            });
        });

        var table = $('.data-table').DataTable({
            processing: true,
            scrollX: true,
            ajax: `{{ url()->current() }}`,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'nomor_invoice',
                    name: 'nomor_invoice'
                },
                {
                    data: 'nomor_pembayaran',
                    name: 'nomor_pembayaran'
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'info',
                    name: 'info'
                },
                {
                    data: 'rincian',
                    name: 'rincian'
                },
                {
                    data: 'total_nominal',
                    name: 'total_nominal'
                },
                {
                    data: 'waktu_berlaku',
                    name: 'waktu_berlaku'
                },
                {
                    data: 'waktu_kadaluarsa',
                    name: 'waktu_kadaluarsa'
                },
                {
                    data: 'waktu_pembayaran',
                    name: 'waktu_pembayaran'
                },
                {
                    data: 'status_pembayaran',
                    name: 'status_pembayaran'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            columnDefs: [{
                    width: '300',
                    targets: 0
                },
                {
                    width: '800px',
                    targets: 8,
                    className: 'dt-center'
                },
                {
                    width: '800',
                    targets: 11,
                    className: 'dt-center'
                }
            ]
        });

        $('body').on('click', '.refreshData', function() {
            var id = $(this).data('id');
            $.ajax({
                url: `/${url}/${id}/edit`,
                type: "GET",
                dataType: 'json',
                success: function(data) {
                    Swal.fire({
                        title: "Perpanjangan Waktu Tagihan",
                        text: "Berapa Hari Akan Diperpanjang ?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, Perpanjang !",
                        cancelButtonText: "Tidak !",
                        reverseButtons: true,

                        input: "text",
                        inputAttributes: {
                            autocapitalize: "off"
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/${url}/${id}`,
                                type: "PUT",
                                data: {
                                    _token: `${csrf}`,
                                    id: `${id}`,
                                    hari: result.value,
                                    aksi: 'perpanjang'
                                },
                                success: function(data) {
                                    success(data.message);
                                },
                                error: function(data) {
                                    if (data.status === 401) {
                                        window.location.href = '401';
                                    } else {
                                        Swal.fire({
                                            icon: "error",
                                            title: "Oops...",
                                            text: data.responseJSON
                                                .error
                                        });
                                    }
                                }
                            });
                        }
                        table.ajax.reload(null, false);
                    });
                },
                error: function(data) {
                    if (data.status === 401) {
                        window.location.href = '401';
                    } else {
                        alert('Terjadi kesalahan: ' + data.responseJSON.error);
                    }
                }
            });
        });
        $('body').on('click', '.flagData', function() {
            var id = $(this).data('id');
            $.ajax({
                url: `/${url}/${id}/edit`,
                type: "GET",
                dataType: 'json',
                success: function(data) {
                    Swal.fire({
                        title: "Flagging Tagihan",
                        text: "Apakah anda yakin ?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, Flagging !",
                        cancelButtonText: "Tidak !",
                        reverseButtons: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/${url}/${id}`,
                                type: "PUT",
                                data: {
                                    _token: `${csrf}`,
                                    id: `${id}`,
                                    aksi: 'flagging'
                                },
                                success: function(data) {
                                    success(data.message);
                                },
                                error: function(data) {
                                    if (data.status === 401) {
                                        window.location.href = '401';
                                    } else {
                                        Swal.fire({
                                            icon: "error",
                                            title: "Oops...",
                                            text: data.responseJSON
                                                .error
                                        });
                                    }
                                }
                            });
                        }
                        table.ajax.reload(null, false);
                    });
                },
                error: function(data) {
                    if (data.status === 401) {
                        window.location.href = '401';
                    } else {
                        alert('Terjadi kesalahan: ' + data.responseJSON.error);
                    }
                }
            });
        });
    </script>
    <script src="{{ asset('') }}assets/js/crud.js"></script>
@endpush
