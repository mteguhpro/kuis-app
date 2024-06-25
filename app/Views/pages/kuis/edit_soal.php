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

    <button type="button" id="open-form-opsi" class="btn btn-secondary" data-toggle="modal" data-target="#modalTambahJawaban">Tambah Opsi Jawaban</button>
    <button type="button" id="form-simpan" class="btn btn-primary">Save changes</button>

</div>


<div class="modal fade" id="modalTambahJawaban" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Jawaban</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-tambah-jawaban">
                    <input type="hidden" id="soal-id" name="soal_id" value="<?= $data->id ?>"/>    
                    <div class="form-group">
                        <label for="opsi-jawaban">Opsi Jawaban:</label>
                        <textarea required class="form-control" placeholder="Jawaban" id="opsi-jawaban"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="jawaban-benar">Jawaban Benar:</label>
                        <select name="jawaban-benar" id="jawaban-benar" class="form-control">
                            <option value="TIDAK">TIDAK</option>
                            <option value="YA">YA</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="simpan-opsi" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
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

    var formTambahJawaban = new Pristine(document.getElementById("form-tambah-jawaban"));
    $('#simpan-opsi').click(function(){
        var that = this
        if (!formTambahJawaban.validate()) {
            return false;
        }
        var data = {
            soal_id: $('#soal-id').val(),
            opsi_jawaban: $('#opsi-jawaban').val(),
            jawaban_benar: $('#jawaban-benar').val(),
        }
        $.ajax({
            url: SITEURLWEB + "admin/master-jawaban/",
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
                    duration: 7000,
                    style: {
                        background: "linear-gradient(to top, #00b09b, #96c93d)",
                    },
                    close: true,
                }).showToast();
            }
        });
    })
</script>

<?= $this->endSection(); ?>