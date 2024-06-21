<?= $this->extend('layouts/template_admin'); ?>

<?= $this->section('content'); ?>

<!-- Button trigger modal -->
<button type="button" id="button-tambah" class="btn btn-primary my-3">
    <i class="fas fa-plus"></i> Tambah
</button>

<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="judulFormModal"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-popup">
                    <input type="hidden" id="id-data"> 
                    <div class="form-group">
                        <label for="pertanyaan">Pertanyaan:</label>
                        <textarea required class="form-control" placeholder="Pertanyaan" id="pertanyaan"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="form-simpan" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>


<table id="list-group" class="table table-hover bg-white" style="width:100%">
    <thead>
        <tr>
            <th>Pertanyaan</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Option</th>
        </tr>
    </thead>
</table>

<?= $this->endSection(); ?>

<?= $this->section('script'); ?>

<script>
    'use strict';
    
    // SETUP PAGE
    var urlApi = SITEURLWEB + "administrator/master-group"
    var tableTarget = $('#list-group');
    function dataPost(){
        return {
                name: $('#name').val(),
                code: $('#code').val(),
            }
    }
    function siapkanInputSimpan(){
        $('#id-data').val('')
        $('#name').val('')
        $('#code').val('')
    }
    function siapkanInputEdit(row){
        $('#id-data').val(row.id)
        $('#name').val(row.name)
        $('#code').val(row.code)
    }
    var formPopup = new Pristine(document.getElementById("form-popup")); // create the pristine instance
    // END SETUP PAGE

    var tableListData = tableTarget.DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: urlApi,
            error: function(xhr, error, thrown) {
                alert('Laman Bermasalah, Refresh Browser anda.');
                console.log(xhr);
            },
            timeout: 5 * 60 * 1000
        },
        order: [
            [0, 'asc']
        ],
        searchDelay: 1000,
        columns: [{
                data: "name"
            },
            {
                data: "code"
            },
            {
                data: "created_at"
            },
            {
                data: "updated_at",
            },
            {
                data: "id",
                searchable: false,
                sortable: false,
                render: function(data, type, row, meta) {
                    return '<button class="btn btn-warning mx-1 edit"><i class="fas fa-cog"></i></button>' +
                        '<button class="btn btn-danger mx-1 hapus"><i class="fas fa-trash"></i></button>'
                }
            },
        ],
    });

    $('#button-tambah').click(function() {
        $('#form-simpan').show()
        $('#form-update').hide()

        siapkanInputSimpan()

        $('#judulFormModal').text('Tambah Data')
        $('#formModal').modal({
            backdrop: 'static'
        })
    })

    $('#form-simpan').click(function() {
        var that = this
        // check if the form is valid
        if (formPopup.validate()) {
            var data = dataPost();
            $.ajax({
                url: urlApi,
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

                    tableListData.ajax.reload()
                    $('#formModal').modal('hide')
                }
            });
        }

    })

    $('body').on('click', '.edit', function() {
        $('#form-update').show()
        $('#form-simpan').hide()

        var row = tableListData.row( $(this).parents('tr') ).data();
        siapkanInputEdit(row)
        $('#judulFormModal').text('Edit Data')
        $('#formModal').modal({
            backdrop: 'static'
        })
    })
    $('#form-update').click(function() {
        var that = this
        // check if the form is valid
        if (formPopup.validate()) {
            var data = dataPost()
            $.ajax({
                url: urlApi + "/" + $('#id-data').val(),
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

                    tableListData.ajax.reload()
                    $('#formModal').modal('hide')
                }
            });
        }

    })

    $('body').on('click', '.hapus', function() {
        var that = this
        var row = tableListData.row( $(this).parents('tr') ).data();

        Swal.fire({
            title: 'Hapus Data?',
            text: "Data yang dihapus tidak akan dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: urlApi + "/" + row.id + "/delete",
                    type: "POST",
                    data: {},
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
                    success: function(res, status, xhr) {
                        Toastify({
                            text: res.message,
                            duration: -1,
                            style: {
                                background: "linear-gradient(to top, #00b09b, #96c93d)",
                            },
                            close: true,
                        }).showToast();
                        tableListData.ajax.reload()
                    }
                });
            }
        })
    })
</script>

<?= $this->endSection(); ?>