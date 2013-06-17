<?php

/*
 * sesi_model.php
 * Penulis: Putu Wiramaswara Widya <initrunlevel0@gmail.com>
 * Kontributor: <<Silahkan diisi>>
 */

class sesi_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function lakukan_login(string $nama_login, string $password)
    {
        $password_md5 = md5($password);
        
        // Lakukan proses login
        if($this->pengguna_model->verifikasi_pengguna($nama_login, $password_md5))
        {
            // Buat sesi baru
            $this->session->sess_create();
            $this->session->set_userdata("nama_login", $nama_login);
            
            return true;
        }
        else
        {
            return false;
        }
    }
    
    function apakah_login()
    {
        if($this->session->userdata("nama_login"))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    function ambil_nama_login()
    {
        return $this->session->userdata("nama_login");
    }
    
    function lakukan_logout()
    {
        return $this->session->sess_destroy();
    }
}
?>
