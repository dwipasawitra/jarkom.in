<?php
// Pengendali akun

class akun extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }
    
    function login()
    {
        $nama_login = $_POST["nama_login"];
        $password = $_POST["password"];
        
        $this->form_validation->set_rules("nama_login", "Nama Login", "required");
        $this->form_validation->set_rules("password", "Password", "required");
        $this->form_validation->set_message("required", "%s belum diisi");
        
        // Lakukan validasi form
        if($this->form_validation->run() == TRUE)
        {
            // Lakukan proses login
            if($this->sesi_model->lakukan_login($nama_login, $password))
            {
                $data['hasil'] = "login-ok";
            }
            else
            {
                $data['hasil'] = "login-error";
                $data['galat'] = "Nama login atau password salah";
            }
            
        
        }
        else
        {
            $data['hasil'] = "login-error";
            $data['galat'] = validation_errors();
        }
        if($this->input->is_ajax_request())
        {
            echo json_encode($data);
        }
        else
        {
            // Belum diimplementasikan
        }
        
        
        
    }
    
    function logout()
    {
        // Langsung panggil fungsi logout pada model
        $this->sesi_model->lakukan_logout();
        
        // Alihkan ke halaman utama
        redirect("/");
    }
    
    function kelola_akun()
    {
        // Tampilkan view untuk kelola akun
        $data["content"] = "akun/kelola_akun";
        $data["title"] = "Kelola Akun";
        
        
        $this->load->view("template", $data);
    }
    
    function lakukan_kelola_akun()
    {
        // Ambil data
        $nama_lengkap = $_POST["nama_lengkap"];
        $surel = $_POST["surel"];
        $password = $_POST["password"];
        
        // Validasi
        $this->form_validation->set_rules("nama_lengkap", "Nama Lengkap", "required");
        $this->form_validation->set_rules("password", "Password", "required");
        $this->form_validation->set_rules("password_ulangi", "Ulangi Password", "required");
        $this->form_validation->set_rules("surel", "Surat Elektronik", "required");
        $this->form_validation->set_message("required", "%s belum diisi");
        
        if($this->form_validation->run() == TRUE)
        {
            // Lakukan proses pengubahan data
            
        
        }
        else
        {
            $data['hasil'] = "ubah-error";
            $data['galat'] = validation_errors();
        }
        
        echo json_encode($data);
    }
}
?>
