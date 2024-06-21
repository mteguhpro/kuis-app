<?= $this->extend('layouts/template_admin'); ?>

<?= $this->section('content'); ?>

<div class="container-float">
    <form id="form-data">
        <input required type="hidden" id="id_data" value="<?= $data->id ?>">
        <div class="form-group">
            <label for="pertanyaan">Pertanyaan:</label>
            <textarea required class="form-control" placeholder="Pertanyaan" id="pertanyaan"><?= $data->pertanyaan ?></textarea>
        </div>
    </form>

    <button type="button" id="form-simpan" class="btn btn-primary">Save changes</button>

</div>

<?= $this->endSection(); ?>

<?= $this->section('script'); ?>

<script>
    'use strict';

    $('.select2').select2({
        width: '100%',
        theme: "classic",
    })

    var formData = new Pristine(document.getElementById("form-data"));
    $('#form-simpan').click(function() {
        var that = this
        // check if the form is valid
        if (formData.validate()) {
            var data = {
                pertanyaan: $('#pertanyaan').val(),
            }
            $.ajax({
                url: SITEURLWEB + "admin/master-soal/" + $('#id_data').val(),
                type: "POST",
                beforeSend: function(xhr) {
                    $(that).addClass('disabled')
                },
                complete: function(xhr, status) {
                    $(that).removeClass('disabled')
                },
                error: function(xhr, status, err) {
                    var res = JSON.parse(xhr.responseText)
                    Toastify({
                        text: res.message ? res.message : xhr.responseText,
                        style: {
                            background: "linear-gradient(to top, red, pink)",
                        },
                    }).showToast();
                },
                timeout: 5 * 60 * 1000,
                data: data,
                success: function(res, status, xhr) {
                    Toastify({
                        text: res.message,
                        duration: -1,
                        style: {
                            background: "linear-gradient(to top, #00b09b, #96c93d)",
                        },
                        close: true,
                    }).showToast();
                }
            });
        }

    })
</script>

<?= $this->endSection(); ?>