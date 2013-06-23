<?php
// Pengendali akun

class akun extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("pengguna_model");

    }
    
    function login()
    {
        $nama_login = $_POST["nama_login"];
        $password = $_POST["password"];
        
        $this->form_validation->set_rules("nama_login", "Nama Login", "required");
        $this->form_validation->set_rules("password", "Password", "required");
        $this->form_validation->set_message("required", "%s belum diisi");
        $this->form_validation->set_error_delimiters("","<br/>");
        
        // Lakukan validasi form
        if($this->form_validation->run() == TRUE)
        {
            // Lakukan proses login
            if($this->sesi_model->lakukan_login($nama_login, $password))
            {
                $data['hasil'] = true;
            }
            else
            {
                $data['hasil'] = false;
                $data['pesan'] = "Nama login atau password salah";
            }
            
        
        }
        else
        {
            $data['hasil'] = false;
            $data['pesan'] = validation_errors();
        }
        
        if($this->input->is_ajax_request())
        {
            echo json_encode($data);
        }
        else
        {
            redirect("/");
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
        if($this->sesi_model->apakah_login() == FALSE)
        {
            redirect("/"); 
            return;
        }
        
        // Tampilkan view untuk kelola akun
        $data["content"] = "akun/kelola_akun";
        $data["title"] = "Kelola Akun";
        
        // Baca data pengguna
        $data["pengguna"] = $this->pengguna_model->baca_data_pengguna($this->sesi_model->ambil_nama_login());
        
        
        $this->load->view("template", $data);
    }
    
    function ubah_data_pengguna()
    {
        // Ambil data
        $nama_lengkap = $this->input->post("nama_lengkap");
        $no_handphone = $this->input->post("no_handphone");
        
        // Validasi
        $this->form_validation->set_rules("nama_lengkap", "Nama Lengkap", "required");
        $this->form_validation->set_rules("no_handphone", "No. Handphone", "required");
        $this->form_validation->set_message("required", "%s belum diisi");
        $this->form_validation->set_error_delimiters("","<br/>");
        
        if($this->form_validation->run() == TRUE)
        {
            $this->pengguna_model->ubah_data_pengguna($this->sesi_model->ambil_nama_login(), $nama_lengkap, $no_handphone);
            $data['hasil'] = true;
            $data['pesan'] = "Data pengguna berhasil diubah";
        }
        else
        {
            $data['hasil'] = false;
            $data['pesan'] = validation_errors();
        }
        
        if($this->input->is_ajax_request())
        {
            echo json_encode($data);
        }
        else
        {
            redirect("/akun/kelola_akun");
        }
    }
    
    function ubah_password()
    {
        // Ambil data
        $password_lama = $this->input->post("password_lama");
        $password_baru = $this->input->post("password_baru");
        $ulangi_password_baru = $this->input->post("ulangi_password_baru");
        
        // Validasi
        $this->form_validation->set_rules("password_lama", "Password lama", "required");
        $this->form_validation->set_rules("password_baru", "Password baru", "required");
        $this->form_validation->set_rules("ulangi_password_baru", "Ulangi password baru", "required|matches[password_baru]");
        
        $this->form_validation->set_message("required", "%s belum diisi");
        $this->form_validation->set_message("matches[password_baru", "Password harus sama");
        $this->form_validation->set_error_delimiters("","<br/>");
        
        if($this->form_validation->run() == TRUE)
        {
            // Cek password lama apakah benar
            if($this->pengguna_model->verifikasi_pengguna($this->sesi_model->ambil_nama_login(), md5($password_lama)) == TRUE)
            {
                $this->pengguna_model->ubah_data_pengguna($this->sesi_model->ambil_nama_login(), null, null, md5($password_baru));
                $data['hasil'] = true;
                $data['pesan'] = "Password berhasil diubah";
            }
            else
            {
                $data['hasil'] = false;
                $data['pesan'] = "Password lama salah";
            }
        }
        else
        {
            $data['hasil'] = false;
            $data['pesan'] = validation_errors();
        }
        
        if($this->input->is_ajax_request())
        {
            echo json_encode($data);
        }
        else
        {
            redirect("/akun/kelola_akun");
        }
    }
}
?>
