<?php

class api_jarkomin extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("sms_model");
        $this->load->model("kontak_model");
        
        // Pengecekan keabsahan akses API
    }
    
    function lihat_sms_siap_kirim()
    {
        $hasil = array();
        
        // List semua SMS yang belum terkirim
        $sms_siap_kirim = $this->sms_model->daftarkan_sms_siap_kirim();
        
        // Cari nomor handphone untuk setiap id kontak
        foreach($sms_siap_kirim as $sms)
        {
            $no_handphone = $this->kontak_model->ambil_no_handphone($sms["kontak"]);
            $hasil[] = array("no_handphone" => $no_handphone, "konten" => $sms["konten"]);
        }
        
        // Kirimkan hasilnya ke klien
        header("Content-Type: application/json");
        echo json_encode($hasil);
    }
    
    function tandai_sms_sudah_terkirim()
    {
        $id_sms_pesanan = $this->input->post("id_sms_pesanan");
        
        // Tandai SMS ini sebagai sudah terkirim pada basis data
        $this->sms_model->tandai_sms_sebagai_dikirim($id_sms_pesanan);
        
    }
}
?>
