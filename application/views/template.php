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
    <script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js'); ?>"></script>
    <title>JARKOM.IN</title>
</head>
<body lang="id">
    <div id="container" class="container">
        <div id="main" class="row">
            <div id="left-part" class="col_3 alpha">
                <div id="left-part-content">
                    <center><img id="logo" src="<?php echo base_url('assets/img/logo.png'); ?>" /></center>
                    <p id="tagline">Solusi SMS broadcast/masal murah, cepat dan handal</p>
                    <div id="login-form">
                        <?php if($this->sesi_model->is_login()) { ?>
                        
                        <?php } else { ?>
                        <form method="post" action="<?php echo site_url("akun/login") ?>">
                            <input type="text" name="userid" placeholder="ID Pengguna" />
                            <input type="password" name="password" placeholder="Password" />
                            <input type="button" value="Masuk ke Sistem" />
                            <input type="button" value="Daftar ke Sistem" />
                        </form>
                        <?php } ?>
                    </div>
                    <center><strong>Menu Utama</strong></center>
                    <div id="sidebar">
                        <div class="sidebar-item">
                            <img src="<?php echo base_url('assets/img/luv.png'); ?>" class="mobile-hide">
                                <p>
                                    <strong>Tidak ada</strong>
                                    Menu ini belum diimplementasikan.
                                </p>
                        </div>
                        
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