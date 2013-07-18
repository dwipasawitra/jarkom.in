<?php
/*
 * Jarkomn.IN default template
 * Designer: Putu Wiramaswara Widya <initrunlevel0@gmail.com>
 * 
 */
?>
<?php $this->load->model("pengguna_model"); ?>
<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/columnal.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>" />
    
    <link rel="stylesheet" href="<?php echo base_url('assets/css/token-input-facebook.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/css/token-input.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/css/jquery.simple-dtpicker.css'); ?>" />
    <script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.tokeninput.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.simplemodal.1.4.4.min.js'); ?>"> </script>
    <script src="<?php echo base_url('assets/js/jquery.simple-dtpicker.js'); ?>"> </script>
    <title>JARKOM.IN</title>
    <script>
        $(document).ready(function() {
            $("#error-left").hide();
            $("#form_login").submit(function() {
                $.post("<?php echo site_url('/akun/login'); ?>", $(this).serialize(), function(data) {
                    $("#error-left").hide();
                    if(data.hasil === true) {
                        window.location = "<?php echo site_url('/'); ?>"
                    }
                    else
                    {
                        $("#error-left").html(data.pesan);
                        $("#error-left").show(); 
                   }
                }, "json");
                return false;
            });
        });
    </script>
    <script>
        function muat_ulang_poin()
        {
            
            $.get("<?php echo site_url('akun/ambil_poin_json'); ?>", "", function(data) {
                $("#poin").html(data.poin);
                $("#poin").fadeOut(250);
                $("#poin").fadeIn(250);
            }, "json");
        }
    </script>
</head>
<body lang="id">
    <div id="container" class="container">
        <div id="main" class="row">
            <div id="left-part" class="col_3 alpha">
                <div id="left-part-content">
                    <center><img id="logo" src="<?php echo base_url('assets/img/logo.png'); ?>" /></center>
                    <p id="tagline">Solusi SMS broadcast/masal murah, cepat dan handal</p>
                    <div id="error-left" class="nodisplay">
                    </div>
                    <div id="login-form">
                    <?php if($this->sesi_model->apakah_login()) { ?>
                        Anda masuk sebagai <?php echo $this->sesi_model->ambil_nama_login(); ?>
                        <br/>
                        <a href="<?php echo site_url("akun/kelola_akun"); ?>">Kelola Akun</a> | 
                        <a href="<?php echo site_url("akun/logout"); ?>">Keluar</a>
                    <?php } else { ?>
                        <form method="post" action="<?php echo site_url("akun/login") ?>" id="form_login">
                            <input type="text" name="nama_login" placeholder="ID Pengguna" />
                            <input type="password" name="password" placeholder="Password" />
                            <input type="submit" value="Masuk ke Sistem" />
                            
                        </form>
                    <?php } ?>
                        
                    </div>
                    <div id="sidebar">
                        <?php if($this->sesi_model->apakah_login()) { ?>
                        <center><strong>Menu Utama</strong></center>
                        
                        <a href="<?php echo site_url('/sms'); ?>">
                        <div class="sidebar-item">
                            <img src="<?php echo base_url('assets/img/envelope.png'); ?>" class="mobile-hide">
                                <p>
                                    <strong>Kirim Pesan</strong>
                                    Lakukan pengiriman pesan pada kontak atau grup melalui SMS ataupun Twitter secara otomatis.
                                </p>
                        </div>
                        </a>
                        
                        <a href="<?php echo site_url('/kontak'); ?>">
                        <div class="sidebar-item">
                            <img src="<?php echo base_url('assets/img/people.png'); ?>" class="mobile-hide">
                                <p>
                                    <strong>Mengelola Kontak</strong>
                                    Kelola nomor handphone dan akun twitter kontak dan grup yang akan dikirimi pesan.
                                </p>
                        </div>
                        </a>
                        
                        <a href="<?php echo site_url('/kegiatan'); ?>">
                        <div class="sidebar-item">
                            <img src="<?php echo base_url('assets/img/run.png'); ?>" class="mobile-hide">
                                <p>
                                    <strong>Mengelola Kegiatan</strong>
                                    Kelola kegiatan berkelompok beserta pengingat dan konfirmasi kehadiran dari para pesertanya.
                                </p>
                        </div>
                        </a>
                        
                        <div class="sidebar-item">
                        <center><strong>Kredit Anda</strong><br/>
                            <h1><h2><span id="poin"><?php echo $this->pengguna_model->lihat_kredit_pengguna($this->sesi_model->ambil_nama_login()); ?></span> poin</h2> </h1></center>
                            <span>Kredit adalah jumlah kuota Anda dapat mengirim SMS sebanyak 160 karakter. Untuk menambah kredit, silahkan lakukan pembelian kredit melalui tautan ini</span>
                        </div>
                        
                        <div class="sidebar-item">
                            
                            <center><strong>Kirim pesan via SMS</strong></center>
                            <img src="<?php echo base_url('assets/img/envelope.png'); ?>" class="mobile-hide">
                            <p>
                            Anda juga dapat mengirim pesan melalui nomor handphone Anda ke sebuah kelompok. Caranya ketik <strong>(Nama_Kelompok) spasi (Pesan)</strong> (Untuk nama kelompok yang 
                            memiliki spasi, silahkan ganti spasi dengan tanda under score (_).
                            </p>
                        </div>
                        <?php } else { ?>
                        <?php } ?>
                        
                    </div>
                </div>		
            </div>
            <div id="right-part" class="col_9 omega">
                <div id="title">
                    <div id="title-content">
                        <h2><?php echo $title; ?></h2>
                    </div>

                </div>
                <div id="content">
                    <div id="content-content">
                    <!-- BEGIN of content -->
                    <?php $this->load->view($content); ?>
                    <!-- END of content -->
                    </div>
                </div>
            </div>
        </div>	
    </div>
    
</body>
</html>