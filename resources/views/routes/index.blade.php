@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ asset('') }}assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('') }}assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('') }}assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('') }}assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('') }}assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush
@section('content')
    <div class="content-header">
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header d-flex p-0">
                    <h1 class="card-title p-3">Data Role Pengguna</h1>
                    <ul class="nav nav-pills ml-auto p-2">
                        <li class="nav-item"><a class="nav-link active btn-sm" id="tambahdata" href="#"
                                data-toggle="modal" data-target="#modalForm">Tambah Data</a></li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered table-striped data-table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Route Name</th>
                                <th>Route Permission</th>
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
                    <h5 class="modal-title">Default Modal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="formdata">
                        @csrf
                        <input type="text" class="form-control" id="dataID" name="id" value="" hidden>
                        <div class="form-group">
                            <label>Route</label>
                            <select class="form-control select2" name="route" style="width: 100%;" id="dataroute">
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Permission</label>
                            <select class="form-control select2" name="permission_name" style="width: 100%;"
                                id="datapermission">
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" id="close" data-dismiss="modal">Close</button>
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
    <script src="{{ asset('') }}assets/plugins/select2/js/select2.full.min.js"></script>
    <script src="{{ asset('') }}assets/js/notification.js"></script>
    <script>
        var url = "routes";
        var csrf = "{{ csrf_token() }}";

        var resetform = function() {
            $("#formdata").trigger('reset')
            $('#savedata').val('create')
            $('#dataID').val('')
            $('#dataroute').empty();
            $('#datapermission').empty();
        }


        $('body').on('click', '.editdata', function() {
            var id = $(this).data('id');
            $.ajax({
                url: `/${url}/${id}/edit`,
                type: "GET",
                dataType: 'json',
                success: function(response) {
                    if (response.success == true) {
                        resetform()

                        // console.log(response.data.route);
                        $('#savedata').val('edit');
                        $('#modalForm').modal('show')
                        $('#dataID').val(response.data.route.id)

                        var html = ''
                        response.data.routes.forEach((element, index, array) => {
                            var checked = (element.action.as == response.data.route.route) ?
                                'selected' : '';

                            if (element.action.as) {
                                html = html +
                                    '<option ' + checked + ' value="' + element.action.as +
                                    '">' + element
                                    .action
                                    .as + '</option>'
                            }

                        });
                        $('#dataroute').append(html);


                        $('#datapermission').empty();
                        var html = ''
                        response.data.permissions.forEach((element, index, array) => {
                            var checked = (element.name == response.data.route
                                    .permission_name) ?
                                'selected' : '';
                            html = html +
                                '<option ' + checked + ' value="' + element.name + '">' +
                                element.name +
                                '</option>'
                        });
                        $('#datapermission').append(html);
                    } else {
                        resetform();
                        window.location.href = "401";
                    }
                }
            });
        });

        $('body').on('click', '#tambahdata', function() {
            $.ajax({
                url: `/${url}/create`,
                type: "GET",
                dataType: 'json',
                success: function(response) {
                    if (response.success == true) {
                        resetform();

                        var html = ''
                        response.data.routes.forEach((element, index, array) => {
                            if (element.action.as) {
                                html = html +
                                    '<option value="' + element.action.as + '">' + element
                                    .action
                                    .as + '</option>'

                            }
                        });
                        $('#dataroute').append(html);

                        var html = ''
                        response.data.permissions.forEach((element, index, array) => {
                            if (element.name) {
                                html = html +
                                    '<option value="' + element.name + '">' + element.name +
                                    '</option>'

                            }
                        });
                        $('#datapermission').append(html);
                    } else {
                        resetform();
                        window.location.href = "401";
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
                    name: 'DT_RowIndex'
                },
                {
                    data: 'route',
                    name: 'route'
                },
                {
                    data: 'permission_name',
                    name: 'permission_name'
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
                    width: '80px',
                    targets: 3,
                    className: 'dt-center'
                }
            ]
        });


        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        })
    </script>
    <script src="{{ asset('') }}assets/js/crud.js"></script>
@endpush
