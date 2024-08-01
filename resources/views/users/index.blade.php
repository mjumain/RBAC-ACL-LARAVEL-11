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
                    <h1 class="card-title p-3">Data Pengguna</h1>
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
                                <th>Name</th>
                                <th>Email</th>
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
                            <label>Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="User Name">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                        </div>
                        <div class="form-group" id="datarole">
                            <label>Role Akses</label>
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
    <script src="{{ asset('') }}assets/plugins/select2/js/select2.full.min.js"></script>
    <script src="{{ asset('') }}assets/js/notification.js"></script>
    <script>
        var url = "users";
        var csrf = "{{ csrf_token() }}";

        var resetform = function() {
            $("#formdata").trigger('reset')
            $('#savedata').val('create')
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
                        // console.log(response.data.role);
                        $('#savedata').val('edit')
                        $('#modalForm').modal('show')
                        $('#dataID').val(response.data.user.id)
                        $('#name').val(response.data.user.name)
                        $('#email').val(response.data.user.email)

                        $('#datarole').empty();
                        var html = ''
                        response.data.roles.forEach((element, index, array) => {
                            if (element.name) {
                                var checked = response.data.role.includes(element
                                        .name) ?
                                    'checked' : '';
                                html = html +
                                    '<div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">' +
                                    '<input type="checkbox" ' + checked +
                                    ' name="role[]" value="' + element
                                    .name +
                                    '" class="custom-control-input" id="' +
                                    element.name + '">' +
                                    '<label class="custom-control-label" for="' + element.name +
                                    '">' + element.name +
                                    '</label></div>'
                            }
                        });
                        $('#datarole').append(html);
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
                    // console.log(response.data);
                    resetform()
                    $('#datarole').empty();
                    var html = ''
                    response.data.roles.forEach((element, index, array) => {
                        if (element.name) {
                            html = html +
                                '<div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">' +
                                '<input type="checkbox" name="role[]" value="' + element.name +
                                '" class="custom-control-input" id="' +
                                element.name + '">' +
                                '<label class="custom-control-label" for="' + element.name +
                                '">' + element.name +
                                '</label></div>'
                        }
                    });
                    $('#datarole').append(html);
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
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            columnDefs: [{
                    width: '25px',
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
            $('.select2').select2()

            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        })
    </script>
    <script src="{{ asset('') }}assets/js/crud.js"></script>
@endpush
