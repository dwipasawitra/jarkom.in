<?php
class kegiatan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if($this->sesi_model->apakah_login() == FALSE)
        {
            redirect("/"); 
            return;
        }
        $this->load->model("kegiatan_model");
        
    }
    
    function index()
    {
        $data["content"] = "kegiatan/index";
        $data["title"] = "Manajemen Kegiatan";
        $data["kegiatan"] = $this->kegiatan_model->lihat_semua_kegiatan($this->sesi_model->ambil_nama_login());
        $this->load->view("template", $data);
        
    }
    
    function tambah_kegiatan()
    {
        // Lakukan validasi masukan
        $this->form_validation->set_rules("id_kegiatan", "ID Kegiatan", "required");
        $this->form_validation->set_rules("nama_kegiatan", "Nama Kegiatan", "required");
        $this->form_validation->set_rules("waktu_pelaksanaan_kegiatan", "Waktu Pelaksanaan Kegiatan", "required");
        $this->form_validation->set_rules("grup", "Kelompok/Grup", "required");
        $this->form_validation->set_message("required", "%s belum diisi");
        $this->form_validation->set_error_delimiters("","<br/>");
        
        if($this->form_validation->run() == TRUE)
        {
            // Ambil data yang diperlukan
            $id_kegiatan = $this->input->post("id_kegiatan");
            $nama_kegiatan = $this->input->post("nama_kegiatan");
            $waktu_pelaksanaan_kegiatan = $this->input->post("waktu_pelaksanaan_kegiatan");
            $grup = $this->input->post("grup");
            
            $this->kegiatan_model->tambah_kegiatan($id_kegiatan, $nama_kegiatan, $grup, $waktu_pelaksanaan_kegiatan);
            $data['hasil'] = true;
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
            redirect(site_url("/kegiatan"));
        }
    }
    
    function kelola_pengingat()
    {
        $id_kegiatan = $this->input->get("id_kegiatan");
        
        $data["id_kegiatan"] = $id_kegiatan;
        $data["content"] = "kegiatan/pengingat_kegiatan";
        $data["title"] = "Kelola Pengingat Kegiatan";
        $data["kegiatan"] = $this->kegiatan_model->ambil_data_kegiatan($id_kegiatan);
        $data["pengingat_kegiatan"] = $this->kegiatan_model->lihat_pengingat_kegiatan($id_kegiatan);
        $this->load->view("template", $data);
    }
    
    function tambah_pengingat()
    {
        $id_kegiatan = $this->input->post("id_kegiatan");
        
        // Validasi
        $this->form_validation->set_rules("id_kegiatan", "ID Kegiatan", "required");
        $this->form_validation->set_rules("menit_sebelumnya", "Waktu peningat", "required");
        $this->form_validation->set_rules("pesan", "Pesan pengingat", "required");
        $this->form_validation->set_message("required", "%s belum diisi");
        $this->form_validation->set_error_delimiters("","<br/>");
        
        if($this->form_validation->run() == TRUE)
        {
            // Ambil data yang diperlukan
            $menit_sebelumnya = $this->input->post("menit_sebelumnya");
            $pesan = $this->input->post("pesan");
            
            $this->kegiatan_model->tambah_pengingat_kegiatan($id_kegiatan, $menit_sebelumnya, $pesan);
            $data['hasil'] = true;
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
            redirect(site_url("/kegiatan/pengingat_kegiatan?id_kegiatan=".$id_kegiatan));
        }
    }
    
    function konfirmasi_kegiatan()
    {
        $id_kegiatan = $this->input->get("id_kegiatan");
        
        $data["id_kegiatan"] = $id_kegiatan;
        $data["content"] = "kegiatan/konfirmasi_kegiatan";
        $data["title"] = "Melihat Konfirmasi Kegiatan";
        $data["kegiatan"] = $this->kegiatan_model->ambil_data_kegiatan($id_kegiatan);
        $data["konfirmasi_kegiatan"] = $this->kegiatan_model->lihat_konfirmasi_kegiatan($id_kegiatan);
        
        $this->load->view("template", $data);
    }
}
?>
