<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php echo csrf_meta(); ?>
    <title>Dashboard</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome-free/css/all.min.css'); ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('assets/adminlte/css/adminlte.min.css'); ?>">

</head>

<body>
    <div class="container">

        <div class="row">
            <div class="col">
            </div>


            <div class="col-xs-12 col-md-6 col-lg-4 pt-5">
                
            
                <!-- CARD IDENTITAS -->
                <div class="card card-outline card-dark" id="card-identitas">
                    <div class="card-header text-center">
                        <span class="h1"><b>St</b>art</span>
                    </div>
                    <div class="card-body">

                        <form>
                            <div class="input-group mb-3">
                                <input type="text" id="nama" class="form-control" placeholder="Nama">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8">
                                    
                                </div>
                                <!-- /.col -->
                                <div class="col-4">
                                    <a href="#" id="start-game" class="btn btn-dark btn-block">Start Kuis</a>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>

                    </div>
                    <!-- /.card-body -->
                </div>

                <!-- SOAL CONTAINER -->
                <div id="soal-container">
                    <div class="card card-outline card-dark">
                        <div class="card-header text-center">
                            <span class="h1"><b>Kuiz</b></span>
                        </div>
                        <div class="card-body text-left" id="soal-contaier" style="white-space:pre">loading..</div>
                        <!-- /.card-body -->
                    </div>

                    <div id="area-opsi-jawaban" class="mt-5">

                    </div>
                </div>

                <!-- HASIL CONTAINER -->
                <div id="hasil-container">
                    <div class="card card-outline card-dark">
                        <div class="card-header text-center">
                            <span class="h1"><b>Hasil</b></span>
                        </div>
                        <div class="card-body text-left" id="soal-contaier">
                            <b>Nama: </b><span id="nama-pemain"></span>
                            <br/>
                            <b>Jumlah Soal: </b><span id="jumlah-soal"></span>
                            <br/>
                            <b>Score:</b><br/>
                            <h2 id="total-score" class="text-center"></h2>
                            <h3 id="status-lulus" class="text-center"></h3>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <a href="#" onclick="window.location.reload()" class="btn btn-dark btn-block">Reload</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
            </div>
        </div>
        

    </div>

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="<?= base_url('assets/jquery/jquery.min.js'); ?>"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url('assets/adminlte/js/adminlte.min.js'); ?>"></script>

    <script src="<?php echo base_url('assets/sweetalert2/sweetalert2.all.min.js');?>"></script>

    <script>
        'use strict';
        window.BASEURLWEB = "<?= base_url() ?>";
        window.SITEURLWEB = "<?= base_url() ?>";

        $('a.nav-link[href="'+window.location.href+'"]').addClass('active');

        $.ajaxSetup({
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'csrf_tok': $('meta[name=X-CSRF-TOKEN]').attr('content')
            }
        });

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>

    <script>
        'use strict';

        var namaPemain = null;
        var jawabanTerpilih = [];
        var listIdSoal = [];
        var listSoalTampil = [];

        $('#soal-container').hide()
        $('#hasil-container').hide()

        $.ajax({
            url: SITEURLWEB + "/play/list-id-soal",
            type: "GET",
            beforeSend: function(xhr) {
                $('#card-identitas').hide()
            },
            error: function(xhr, status, err) {
                var res = JSON.parse(xhr.responseText)
                alert('error')
            },
            timeout: 5 * 60 * 1000,
            success: function(res, status, xhr) {
                $('#card-identitas').show()
                listIdSoal = res.message
                console.log(listIdSoal)
            }
        });

        $('#start-game').click(function(){
            var nama = $('#nama').val()
            if(!nama){
                Swal.fire({
                    title: 'Nama masih kosong!',
                    text: "Mohon isi nama terlebih dahulu!",
                    icon: 'warning',
                    confirmButtonText: 'OK'
                })
                return null;
            }
            namaPemain = nama
            $('#nama-pemain').text(namaPemain)
            console.log(namaPemain)
            $('#card-identitas').hide()

            $('#soal-container').show()
            soalBaru()
        })


        function soalBaru(){
            $.ajax({
                url: SITEURLWEB + "/play/soal/",
                type: "POST",
                data: {
                    'list-soal-tampil' : listSoalTampil
                },
                beforeSend: function(xhr) {
                    $(".btn-jawaban").addClass('disabled')
                },
                complete: function(xhr, status) {
                    $(".btn-jawaban").removeClass('disabled')
                },
                error: function(xhr, status, err) {
                    var res = JSON.parse(xhr.responseText)
                    alert('error')
                },
                timeout: 5 * 60 * 1000,
                success: function(res, status, xhr) {
                    if(res.soal === null){
                        return tampilkanHasil()
                    }
                    listSoalTampil.push(res.soal.id)

                    $('#soal-contaier').text(res.soal.pertanyaan)

                    var area = $('#area-opsi-jawaban')

                    var html = ''
                    res.jawaban.forEach(function(data){
                        html += '<a href="#" data-jawaban-id="'+data.id+'" class="btn-jawaban btn btn-block btn-outline-secondary">'+data.keterangan+'</a>'
                            })
                    area.html(html)
                }
            });

        }

        function tampilkanHasil(){
            $('#hasil-container').show()
            $('#soal-container').hide()
            $('#jumlah-soal').text(listSoalTampil.length)
            if(jawabanTerpilih.length === 0){
                $('#total-score').text('---') 
            }

            $.ajax({
                url: SITEURLWEB + "/play/hasil/",
                type: "POST",
                data: {
                    'jawaban-terpilih' : jawabanTerpilih
                },
                beforeSend: function(xhr) {
                    $('#total-score').text('Menghitung nilai...')
                },
                error: function(xhr, status, err) {
                    var res = JSON.parse(xhr.responseText)
                    alert('error')
                },
                timeout: 5 * 60 * 1000,
                success: function(res, status, xhr) {
                    var nilai = parseInt(res.message) / listSoalTampil.length * 100
                    if(nilai < 75){
                        $('#status-lulus').text('Tidak Lulus')
                    }else{
                        $('#status-lulus').text('Lulus')
                    }
                    $('#total-score').text(Math.round(nilai) + '%')
                }
            });
        }

        $(document).off('click','.btn-jawaban').on('click','.btn-jawaban',function(){
            var that = this
            var id = $(that).data('jawaban-id')

            jawabanTerpilih.push(id)
            console.log({jawabanTerpilih : jawabanTerpilih})
            soalBaru()
        })
    </script>

</body>

</html>