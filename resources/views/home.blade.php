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
            @role('superadmin')
                <div class="card card-primary card-outline">
                    <div class="card-header d-flex p-0">
                        <h1 class="card-title p-3">Data Role Pengguna</h1>
                        <ul class="nav nav-pills ml-auto p-2">
                            <li class="nav-item"><a class="nav-link active btn-sm" href="#" data-toggle="modal"
                                    data-target="#modal-form">Tambah Data</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        {{ count($permissions) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-6">

                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ count($permissions) }}</h3>
                                <p>Permissions</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">

                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ count($roles) }}</h3>
                                <p>Roles</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">

                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ count($users) }}</h3>
                                <p>Users</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">

                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ count($menus) }}</h3>
                                <p>Menus</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            @endrole
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('') }}assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('') }}assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('') }}assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('') }}assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="{{ asset('') }}assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('') }}assets/js/notification.js"></script>
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url()->current() }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'guard_name',
                        name: 'guard_name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [{
                        width: '30px',
                        targets: 0
                    },
                    {
                        width: '120px',
                        targets: 3
                    }
                ]
            });

            $('#savedata').click(function(e) {
                e.preventDefault();
                if ($(this).val() === 'edit') {
                    var id = $('#dataID').val();
                    $(this).html('Mohon tunggu . . .!!');
                    $.ajax({
                        data: $('#formdata').serialize(),
                        url: `/roles/${id}`,
                        type: "PUT",
                        dataType: 'json',
                        success: function(data) {
                            $('#formdata').trigger("reset");
                            $('#modal-form').modal('hide');
                            table.draw();
                            $('#savedata').html('Simpan');
                            success(data.message);
                        },
                        error: function(data) {
                            console.log('Error:', data);
                            $('#save').html('Simpan');
                        }
                    });
                } else if ($(this).val() === 'create') {
                    $(this).html('Mohon tunggu . . .!!');
                    $.ajax({
                        data: $('#formdata').serialize(),
                        url: "{{ route('roles.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            $('#formdata').trigger("reset");
                            $('#modal-form').modal('hide');
                            table.draw();
                            $('#savedata').html('Simpan');
                            success(data.message);
                        },
                        error: function(data) {
                            console.log('Error:', data);
                            $('#save').html('Simpan');
                        }
                    });
                };
            });

            $('body').on('click', '.editdata', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ route('roles.index') }}" + '/' + id + '/edit',
                    type: "GET",
                    dataType: 'json',
                    success: function(data) {
                        $('#modal-form').modal('show');
                        $('#dataID').val(data.id);
                        $('#name').val(data.name);
                        $('#guard_name').val(data.guard_name);
                        $('#savedata').val('edit');
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#savedata').html('Simpan');
                    }
                });
            });

            $('body').on('click', '.deleteData', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: "Apakah anda yakin ?",
                    text: "Data yang dihapus tidak dapat dikembalikan",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, hapus !",
                    cancelButtonText: "Tidak !",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/roles/${id}`,
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(data) {
                                success(data.message);
                                table.draw();
                            },
                            error: function(data) {
                                error(data.message)
                                console.log('Error:', data);
                                $('#savedata').html('Simpan');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
