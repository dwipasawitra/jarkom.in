<?php
/*
 * Jarkomn.IN default template
 * Designer: Putu Wiramaswara Widya <initrunlevel0@gmail.com>
 * 
 */
?>
<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/columnal.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>" />
    
    <link rel="stylesheet" href="<?php echo base_url('assets/css/token-input-facebook.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/css/token-input.css'); ?>" />
    <script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.tokeninput.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.simplemodal.1.4.4.min.js'); ?>"> </script>
    <title>JARKOM.IN</title>
    <script>
        $(document).ready(function() {
            $("#error-left").hide();
            $("#form_login").submit(function() {
                $.post("<?php echo site_url('/akun/login'); ?>", $(this).serialize(), function(data) {
                    $("#error-left").hide();
                    if(data.hasil == true) {
                        window.location = "<?php echo site_url('/dasbor'); ?>"
                    }
                    else
                    {
                        $("#error-left").html(data.pesan);
                        $("#error-left").fadeIn(500);
                    }
                }, "json");
                return false;
            });
        });
    </script>
</head>
<body lang="id">
    <div id="container" class="container">
        <div id="main" class="row">
            <div id="left-part" class="col_3 alpha">
                <div id="left-part-content">
                    <center><img id="logo" src="<?php echo base_url('assets/img/logo.png'); ?>" /></center>
                    <p id="tagline">Solusi SMS broadcast/masal murah, cepat dan handal</p>
                    <div id="error-left">
                    </div>
                    <div id="login-form">
                    <?php if($this->sesi_model->apakah_login()) { ?>
                        Anda masuk sebagai <?php echo $this->sesi_model->ambil_nama_login(); ?>
                        <br/>
                        <a href="<?php echo site_url("akun/logout"); ?>">Keluar</a>
                    <?php } else { ?>
                        <form method="post" action="<?php echo site_url("akun/login") ?>" id="form_login">
                            <input type="text" name="nama_login" placeholder="ID Pengguna" />
                            <input type="password" name="password" placeholder="Password" />
                            <input type="submit" value="Masuk ke Sistem" />
                            
                        </form>
                    <?php } ?>
                    </div>
                    <center><strong>Menu Utama</strong></center>
                    <div id="sidebar">
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