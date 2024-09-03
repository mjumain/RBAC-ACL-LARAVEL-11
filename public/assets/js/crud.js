$(function () {
    $('#savedata').click(function (e) {
        e.preventDefault();
        let formData = new FormData($('#formdata')[0]);
        if ($(this).val() === 'edit') {
            var id = $('#dataID').val();

            formData.append('_method', 'PUT');

            $(this).html('Mohon tunggu . . .!!');
            $.ajax({
                url: `/${url}/${id}`,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    console.log(data);

                    $('#modalForm').modal('hide');
                    $('#savedata').html('Simpan');
                    table.draw();
                    success(data.message);
                },
                error: function (xhr) {
                    if (xhr.status == 422) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessages = '';

                        const element = document.querySelector('.invalid-feedback');
                        if (element) {
                            element.innerHTML = '';
                        }

                        $('.is-invalid').removeClass('is-invalid');
                        $.each(errors, function (key, value) {
                            $('.error-' + key).append(value);
                            $('#' + key).addClass('is-invalid');
                            errorMessages += value.join('<br>') + '<br>';
                        });
                    }
                }
            });
        } else if ($(this).val() === 'create') {
            $(this).html('Mohon tunggu . . .!!');
            $.ajax({
                data: formData,
                url: `/${url}`,
                type: "POST",
                contentType: false,
                processData: false,
                success: function (response) {
                    console.log(response);

                    $('#savedata').html('Simpan');
                    $('#modalForm').modal('hide');
                    table.draw();
                    success(response.message);
                },
                error: function (xhr) {
                    if (xhr.status == 422) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessages = '';

                        const element = document.querySelector('.invalid-feedback');
                        if (element) {
                            element.innerHTML = '';
                        }

                        $('.is-invalid').removeClass('is-invalid');
                        $.each(errors, function (key, value) {
                            $('.error-' + key).append(value);
                            $('#' + key).addClass('is-invalid');
                            errorMessages += value.join('<br>') + '<br>';
                        });
                    }
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
                        table.draw();
                        success(data.message);
                    },
                    error: function (data) {
                        if (data.status === 401) {
                            window.location.href = '401';
                        } else {
                            alert('Terjadi kesalahan: ' + data.statusText);
                        }
                    }
                });
            }
        });
    });

});