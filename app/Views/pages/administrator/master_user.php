<?= $this->extend('layouts/template_admin'); ?>

<?= $this->section('content'); ?>

<!-- Button trigger modal -->
<button type="button" id="tambah-user" class="btn btn-primary my-3">
    <i class="fas fa-user-plus"></i> Tambah
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
                <form id="form-user">
                    <div class="form-group">
                        <label for="email">Email address:</label>
                        <input required type="email" class="form-control" placeholder="Enter email" id="email">
                    </div>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input required type="text" class="form-control" placeholder="Username" id="username">
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama:</label>
                        <input required type="text" class="form-control" placeholder="Nama" id="nama">
                    </div>
                    <div class="form-group">
                        <label for="password-add">Password:</label>
                        <input required type="password" class="form-control" placeholder="Password" id="password-add">
                    </div>
                    <div class="form-group">
                        <label for="nama">Hak Akses:</label>
                        <select required class="form-control select2" id="hak_akses_id" multiple="multiple">
                            <?php foreach ($groups as $group) : ?>
                                <option value="<?= $group['id'] ?>"><?= $group['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
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

<div class="modal fade" id="modal-ganti-password" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ganti Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-ganti-password">
                    <input type="hidden" id="ganti-password-id"> 
                    <div class="form-group">
                        <label for="ganti-password-password">Password:</label>
                        <input required type="password" class="form-control" placeholder="Password" id="ganti-password-password">
                    </div>
                    <div class="form-group">
                        <label for="ganti-password-re-password">Ulangi Password:</label>
                        <input required type="password" class="form-control" placeholder="Ulangi Password" id="ganti-password-re-password">
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="ganti-password-simpan" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>


<table id="list-user" class="table table-hover bg-white" style="width:100%">
    <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Created At</th>
            <th></th>
        </tr>
    </thead>
</table>

<?= $this->endSection(); ?>

<?= $this->section('script'); ?>

<script>
    'use strict';

    $('.select2').select2({
        width: '100%',
        theme: "classic",
    })

    var tableListUser = $('#list-user').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: SITEURLWEB + "administrator/master-user",
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
                data: "username"
            },
            {
                data: "email",
            },
            {
                data: "created_at"
            },
            {
                data: "id",
                searchable: false,
                sortable: false,
                render: function(data, type, row, meta) {
                    return '<a href="<?= site_url('administrator/master-user/') ?>'+row.id+'/edit" class="btn btn-warning mx-1"><i class="fas fa-cog"></i></a>' +
                        '<button class="btn btn-dark mx-1 gantiPassword"><i class="fas fa-key"></i></button>' +
                        '<button class="btn btn-danger mx-1 hapus"><i class="fas fa-trash"></i></button>'
                }
            },
        ],
    });

    $('#tambah-user').click(function() {
        $('#email').val('')
        $('#username').val('')
        $('#nama').val('')
        $('#hak_akses_id').val(null).trigger('change')
        $('#password-add').val('')

        $('#judulFormModal').text('Tambah User')
        $('#formModal').modal({
            backdrop: 'static'
        })
    })

    // create the pristine instance
    var formUser = new Pristine(document.getElementById("form-user"));

    $('#form-simpan').click(function() {
        var that = this
        // check if the form is valid
        if (formUser.validate()) {
            var data = {
                email: $('#email').val(),
                username: $('#username').val(),
                nama: $('#nama').val(),
                hak_akses_id: $('#hak_akses_id').val(),
                password: $('#password-add').val(),
            }
            $.ajax({
                url: SITEURLWEB + "administrator/master-user",
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

                    tableListUser.ajax.reload()
                    $('#formModal').modal('hide')
                }
            });
        }

    })

    $('body').on('click', '.hapus', function() {
        var that = this
        var row = tableListUser.row( $(this).parents('tr') ).data();

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
                    url: SITEURLWEB + "administrator/master-user/" + row.id +'/delete',
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
                        tableListUser.ajax.reload()
                    }
                });
            }
        })
    })

    $('body').on('click', '.gantiPassword', function() {
        $('#form-ganti-password').get(0).reset()

        var row = tableListUser.row( $(this).parents('tr') ).data();
        $('#ganti-password-id').val(row.id)

        $('#modal-ganti-password').modal({
            backdrop: 'static'
        })
    })

    $('#ganti-password-simpan').click(function(){
        var that = this
        
        $.ajax({
            url: SITEURLWEB + "administrator/master-user-ubah-password",
            type: "POST",
            data: {
                csrf_tok: $('meta[name=X-CSRF-TOKEN]').attr('content'),
                id : $('#ganti-password-id').val(),
                password: $('#ganti-password-password').val(),
                password_ulang: $('#ganti-password-re-password').val(),
            },
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
                $('#modal-ganti-password').modal('hide')
            }
        });

        
    })
    
</script>

<?= $this->endSection(); ?>