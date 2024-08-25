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
                    <h1 class="card-title p-3">Data Permission Pengguna</h1>
                    <ul class="nav nav-pills ml-auto p-2">
                        <li class="nav-item"><a class="nav-link active btn-sm" href="#" id="tambahdata"
                                data-toggle="modal" data-target="#modalForm">Tambah Data</a></li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered table-striped data-table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Permission Name</th>
                                <th>Guard Name</th>
                                <th>Action</th>
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
        <div class="modal-dialog">
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
                        <div class="form-group">
                            <label>Permission Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Permission Name">
                            <div class="invalid-feedback error-name">
                            </div>
                        </div>
                        <div class="form-group" id="permission">
                            <div class="row">
                                <div class="col-md-6">
                                    <div
                                        class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" name="permission[]" value="index"
                                            class="custom-control-input" id="customSwitch1">
                                        <label class="custom-control-label" for="customSwitch1">Index</label>
                                    </div>
                                    <div
                                        class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" name="permission[]" value="create"
                                            class="custom-control-input" id="customSwitch2">
                                        <label class="custom-control-label" for="customSwitch2">Create</label>
                                    </div>
                                    <div
                                        class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" name="permission[]" value="store"
                                            class="custom-control-input" id="customSwitch3">
                                        <label class="custom-control-label" for="customSwitch3">Store</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div
                                        class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" name="permission[]" value="edit"
                                            class="custom-control-input" id="customSwitch4">
                                        <label class="custom-control-label" for="customSwitch4">Edit</label>
                                    </div>
                                    <div
                                        class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" name="permission[]" value="update"
                                            class="custom-control-input" id="customSwitch5">
                                        <label class="custom-control-label" for="customSwitch5">Update</label>
                                    </div>
                                    <div
                                        class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" name="permission[]" value="delete"
                                            class="custom-control-input" id="customSwitch6">
                                        <label class="custom-control-label" for="customSwitch6">Delete</label>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="invalid-feedback error-permission">
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="savedata" value="create">Simpan</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
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
        var url = "permissions";
        var csrf = "{{ csrf_token() }}";

        var resetform = function() {
            $("#formdata").trigger('reset')
            $('#savedata').val('create')
            $('#dataID').val('')
            $('#name').val('');
            $('#permission').show();
        }

        $('body').on('click', '#tambahdata', function() {
            $.ajax({
                url: `/${url}/create`,
                type: "GET",
                dataType: 'json',
                success: function(response) {
                    resetform();
                },
                error: function(response) {
                    if (response.status === 401) {
                        window.location.href = '401';
                    } else {
                        alert('Terjadi kesalahan: ' + response.statusText);
                    }
                }
            });
        });

        $('body').on('click', '.editdata', function() {
            var id = $(this).data('id');
            $.ajax({
                url: `/${url}/${id}/edit`,
                type: "GET",
                dataType: 'json',
                success: function(data) {
                    resetform()
                    $('#savedata').val('edit');
                    $('#modalForm').modal('show')
                    $('#dataID').val(data.data.id)
                    $('#name').val(data.data.name)
                    $('#permission').hide();
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
            serverSide: true,
            ajax: "{{ url()->current() }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
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
                    width: '100px',
                    targets: 3,
                    className: 'dt-center'
                }
            ]
        });
    </script>
    <script src="{{ asset('') }}assets/js/crud.js"></script>
@endpush
