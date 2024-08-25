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
                    <h1 class="card-title p-3">Data Menu Pengguna</h1>
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
                                <th>Menu Name</th>
                                <th>Menu Icon</th>
                                <th>Menu Route</th>
                                <th>Parent ID</th>
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
                            <label>Menu Name</label>
                            <input type="email" class="form-control" id="name" name="name"
                                placeholder="Menu Name">
                        </div>
                        <div class="form-group">
                            <label>Menu Icon <code><a href="https://fontawesome.com/v5/search?q=home&o=r&m=free"
                                        target="_blank" rel="noopener noreferrer">Daftar icon</a></code></label>
                            <input type="email" class="form-control" id="icon" name="icon"
                                placeholder="Menu Icon">
                        </div>
                        <div class="form-group">
                            <label>Route</label>
                            <select class="form-control select2" name="route" style="width: 100%;" id="dataroute">
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Parent ID</label>
                            <select class="form-control select2" name="parent_id" style="width: 100%;" id="dataparent">
                            </select>
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
        var url = "menus";
        var csrf = "{{ csrf_token() }}";

        var resetform = function() {
            $("#formdata").trigger('reset')
            $('#savedata').val('create')
            $('#dataID').val('')
            $('#name').val('');
            $('#icon').val('');
            $('#dataroute').empty();
            $('#dataparent').empty();

        }

        $('body').on('click', '.editdata', function() {
            var id = $(this).data('id');
            $.ajax({
                url: `/${url}/${id}/edit`,
                type: "GET",
                dataType: 'json',
                success: function(response) {
                    resetform()
                    // console.log(response.data);
                    $('#savedata').val('edit');
                    $('#modalForm').modal('show')
                    $('#dataID').val(response.data.menu.id)
                    $('#name').val(response.data.menu.name)
                    $('#icon').val(response.data.menu.icon)

                    var html = '<option value="#">#</option>'
                    response.data.routes.forEach((element, index, array) => {
                        if (element.action.as) {
                            var checked = (element.action.as == response.data.menu.route) ?
                                'selected' : '';
                            html = html +
                                '<option ' + checked + ' value="' + element.action.as +
                                '">' + element
                                .action.as +
                                '</option>'

                        }
                    });
                    $('#dataroute').append(html);

                    var html = '<option value="0">PARENT MENU</option>'
                    response.data.menus.forEach((element, index, array) => {
                        if (element.name) {
                            var checked = (element.id == response.data.menu.parent_id) ?
                                'selected' : '';
                            html = html +
                                '<option ' + checked + ' value="' + element.id + '">' +
                                element.name +
                                '</option>'

                        }
                    });
                    $('#dataparent').append(html);
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
            $.ajax({
                url: `/${url}/create`,
                type: "GET",
                dataType: 'json',
                success: function(response) {
                    resetform();

                    var html = '<option value="#">#</option>'
                    console.log(response.data.routes);
                    response.data.routes.forEach((element, index, array) => {
                        if (element.action.as) {
                            html = html +
                                '<option value="' + element.action.as + '">' + element
                                .action
                                .as +
                                '</option>'

                        }
                    });
                    $('#dataroute').append(html);

                    var html = '<option value="0">PARENT MENU</option>'
                    response.data.menus.forEach((element, index, array) => {
                        if (element.name) {
                            html = html +
                                '<option value="' + element.id + '">' + element.name +
                                '</option>'

                        }
                    });
                    $('#dataparent').append(html);
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
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'icon',
                    name: 'icon'
                },
                {
                    data: 'route',
                    name: 'route'
                },
                {
                    data: 'parent_id',
                    name: 'parent_id'
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
                    targets: 5,
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
