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
                    <h1 class="card-title p-3">Profil Pengguna</h1>
                </div>
                <div class="card-body">
                </div>
            </div>
            <div class="card card-primary card-outline">
                <div class="card-header d-flex p-0">
                    <h1 class="card-title p-3">Ganti Password</h1>
                </div>
                <div class="card-body">
                    <form action="" id="formdatapassword">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Password saat ini</label>
                                    <input type="email" class="form-control" id="current_password" name="current_password"
                                        placeholder="Password saat ini">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Password baru</label>
                                    <input type="email" class="form-control" id="password" name="password"
                                        placeholder="Password baru">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <input type="submit" class="btn btn-primary" id="edit_password" value="Simpan">
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
    <script src="{{ asset('') }}assets/plugins/select2/js/select2.full.min.js"></script>
    <script src="{{ asset('') }}assets/js/notification.js"></script>
    <script>
        var csrf = "{{ csrf_token() }}";

        var resetform = function() {
            $("#formdatapassword").trigger('reset')
        }


        $('body').on('click', '#edit_password', function() {
            $(this).html('Mohon tunggu . . .!!');
            $.ajax({
                data: $('#formdatapassword').serialize(),
                url: '/change-password',
                type: "POST",
                dataType: 'json',
                success: function(response) {
                    if (response.success == true) {
                        success(response.message);
                    }
                }
            });
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
