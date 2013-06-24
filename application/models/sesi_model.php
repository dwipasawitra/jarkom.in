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
        $this->load->model("pengguna_model");
    }
    
    function apakah_batas_percobaan_login()
    {
        if($this->session->userdata("percobaan_login"))
        {
            if($this->session->userdata("percobaan_login") > 20)
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }
    
    function tambah_percobaan_login()
    {
        if($this->session->userdata("percobaan_login"))
        {
            $percobaan_login = $this->session->userdata("percobaan_login") + 1;
            $this->session->set_userdata("percobaan_login", $percobaan_login);
        }
        else
        {
            $this->session->set_userdata("percobaan_login", 1);
        }
    }
    
    function lihat_jumlah_percobaan_login()
    {
        if($this->session->userdata("percobaan_login"))
        {
            return $this->session->userdata("percobaan_login");
        }
        else
        {
            return 0;
        }
    }
    
    function lakukan_login($nama_login, $password)
    {
        $password_md5 = md5($password);
        
        // Lakukan proses login
        if($this->pengguna_model->verifikasi_pengguna($nama_login, $password_md5) && !$this->apakah_batas_percobaan_login())
        {
            // Buat sesi barudi 
            $this->session->sess_create();
            $this->session->set_userdata("nama_login", $nama_login);
            $this->session->set_userdata("secret_key", "lalalalalalalalalala");
            
            return true;
        }
        else
        {
            $this->tambah_percobaan_login();
            return false;
        }
    }
    
    function apakah_login()
    {
        if($this->session->userdata("nama_login") && $this->session->userdata("secret_key"))
        {
            if($this->session->userdata("secret_key") == "lalalalalalalalalala")
            {
                return true;
                
            }
        }
        return false;
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
