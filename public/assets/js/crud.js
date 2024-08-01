
$(function () {
    $('#savedata').click(function (e) {
        e.preventDefault();
        if ($(this).val() === 'edit') {
            var id = $('#dataID').val();
            $(this).html('Mohon tunggu . . .!!');
            $.ajax({
                data: $('#formdata').serialize(),
                url: `/${url}/${id}`,
                type: "PUT",
                dataType: 'json',
                success: function (data) {
                    if (data.success == true) {
                        $('#modalForm').modal('hide');
                        table.draw();
                        $('#savedata').html('Simpan');
                        success(data.message);
                    } else {
                        error(data.message);
                        // console.log('Error:', data.error);
                    }
                },
                error: function (data) {
                    // console.log('Error:', data.error);
                    $('#save').html('Simpan');
                }
            });
        } else if ($(this).val() === 'create') {
            $(this).html('Mohon tunggu . . .!!');
            $.ajax({
                data: $('#formdata').serialize(),
                url: `/${url}`,
                type: "POST",
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        $('#savedata').html('Simpan');
                        table.draw();
                        success(response.message);
                        $('#modalForm').modal('hide');
                    } else {
                        error(response.message);
                        console.log('Error:', response.error);
                    }
                },
                error: function (response) {
                    error(response.error)
                    $('#savedata').html('Simpan');
                    console.log('Error:', response.error);
                }
            });
        };
    });

    $('body').on('click', '.deleteData', function () {
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
                    url: `/${url}/${id}`,
                    type: "DELETE",
                    data: {
                        _token: `${csrf}`
                    },
                    success: function (data) {
                        if (data.success == true) {
                            success(data.message);
                            table.draw();
                        } else {
                            error(data.message);
                            // console.log('Error:', data.error);
                        }
                    },
                    error: function (data) {
                        error(data.message)
                        $('#savedata').html('Simpan');
                        // console.log('Error:', data.error);
                    }
                });
            }
        });
    });
});