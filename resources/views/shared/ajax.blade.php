<script>
function requestAjaxPost(opt, form, txt) {
    $.ajax({
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        url: opt.url,
        mimeType: 'multipart/form-data',
        method: opt.method,
        dataType: 'json',
        data: form,
        cache: false,
        processData: false,
        contentType: false
    }).done(function(msg) {
        $('button[name="close-modal"]').trigger('click');

        opt.element.text(txt.btnText);
        opt.element.attr("disabled", false);
        
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: txt.msgAlert,
            showConfirmButton: false,
            timer: 4000,
            background: '#059669',
        });

        if((opt.type == 'aJabatan'
            || opt.type == 'aKaryawan'
            || opt.type == 'aGaji'
            || opt.type == 'aLevel'
            || opt.type == 'aKomponen'
            || opt.type == 'kTtdt')
            && opt.table != '') opt.table.ajax.reload();

        $('.modal input').val('');
        $('.modal textarea').val('');
        $('.modal select').val('');
    }).fail(function(err) {
        opt.element.text(txt.btnText);
        opt.element.attr("disabled", false);

        if(err.status == 422) {
            if(err.responseJSON['data'] !== undefined) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: err.responseJSON.data,
                    showConfirmButton: false,
                    timer: 4000,
                    background: '#DC2626',
                });
            }else{
                var errorMessage = '';
                $.each(err.responseJSON['errors'], function (i, error) {
                    errorMessage += `<li>${error[0]}</li>`
                });

                $('.error-message').removeClass("d-none");
                $('.error-message ul').prepend(errorMessage);
            }
        }else{
            var errorMessage = '<li>Terjadi kesalahan pada server</li>';
            $('.error-message').removeClass("d-none");
            $('.error-message ul').prepend(errorMessage);
        }
    });
}

function requestAjaxDelete(opt, txt) {
    Swal.fire({
        title: 'Anda Yakin?',
        text: txt.msgAlert,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Iya, ' + txt.msgText,
        cancelButtonText: 'Tutup',
        showLoaderOnConfirm: true,
        preConfirm: function() {
            return $.ajax({
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                url: opt.url,
                method: opt.method,
                data: {},
            }).done(function(msg) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: txt.msgTitle,
                    showConfirmButton: false,
                    timer: 3000,
                    background: '#059669',
                });

                if((opt.type == 'aJabatan'
                    || opt.type == 'aKaryawan'
                    || opt.type == 'aGaji'
                    || opt.type == 'aLevel'
                    || opt.type == 'aKomponen'
                    || opt.type == 'kTtdt')
                    && opt.table != '') opt.table.ajax.reload();

            }).fail(function(err) {
                if(err.status == 422) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: err.responseJSON.data,
                        showConfirmButton: false,
                        timer: 4000,
                        background: '#DC2626',
                    });
                }else{
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Terjadi kesalahan pada server',
                        showConfirmButton: false,
                        timer: 4000,
                        background: '#DC2626',
                    });
                }
            });
        }
    });
}
</script>