<?php
class sms extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if($this->sesi_model->apakah_login() == FALSE)
        {
            redirect("/"); 
            return;
        }
        $this->load->model("kontak_model");
        $this->load->model("pengguna_model");
        $this->load->model("sms_model");
    }
    
    // /sms/index: Menampilkan halaman penulisan SMS baru
    function index()
    {
        $data["content"] = "sms/index";
        $data["title"] = "Menulis SMS baru";
        $this->load->view("template", $data);
        
    }
    
    // Fungsi untuk mengkonversi nama kontak/grup kontak/nomor handphone ke dalam identitas kontak
    function _validasi_masukan_kontak($masukan_kontak)
    {
        // TODO: Belum diimplementasikan, asumsi masukan valid
        return true;
    }
    
    function _proses_masukan_kontak($masukan_kontak)
    {
        $hasil = array();
        
        // Masukan kontak harus divalidasi terlebih dahulu
        if($this->_validasi_masukan_kontak($masukan_kontak) == TRUE)
        {
            // Pecah masukan kontak berdasarkan tanda komanya
            $masukan_kontak = explode(",", $masukan_kontak);
            
            foreach($masukan_kontak as $kontak)
            {
                $hasil = array_merge($hasil, $this->kontak_model->ambil_id_kontak($this->sesi_model->ambil_nama_login(), $kontak));
            }
        }
        
        return $hasil;
    }
    
    function jumlah_kredit_dibutuhkan()
    {
        $masukan_kontak = $this->input->post("kontak");
        $panjang_sms = $this->input->post("panjang_sms");
        $hasil = $this->_proses_masukan_kontak($masukan_kontak);
        
        if($panjang_sms > 0)
        {
            $data['kredit_dibutuhkan'] =  count($hasil) * ceil(intval($panjang_sms)/160);
        }
        else
        {
            $data['kredit_dibutuhkan'] =  count($hasil);
        }
        
        $data['kredit_saat_ini'] = $this->pengguna_model->lihat_kredit_pengguna($this->sesi_model->ambil_nama_login());
        echo json_encode($data);
    }
    
    function proses_sms()
    {
        // Array untuk hasil data
        $data['hasil'] = false;
        $data['pesan'] = array();
        
        // Lakukan validasi masukan
        $this->form_validation->set_rules("kontak", "Kontak", "required");
        $this->form_validation->set_rules("konten", "Isi SMS", "required");
        $this->form_validation->set_message("required", "%s belum diisi");
        $this->form_validation->set_error_delimiters("","<br/>");
        
        if($this->form_validation->run() == TRUE)
        {
            $kontak = $this->input->post("kontak");
            $konten = $this->input->post("konten");
            
            // Lakukan pemrosesan terhadap pesan
            // Ambil id_kontak dari penerima
            $id_kontak_array = $this->_proses_masukan_kontak($kontak);
            
            // Untuk setiap id_kontak, kirimkan isi pesannya
            foreach($id_kontak_array as $id_kontak)
            {
                /*
                $data_sms_baru = array(
                    "konten" => $konten,
                    "kontak" => $id_kontak,
                    "terkirim" => false
                );
                
                $this->db->insert("sms_pesanan", $data_sms_baru);
                 * 
                 */
                $this->sms_model->kirim_sms($id_kontak, $konten);
            }
            
            $data['hasil'] = true;
        }
        else
        {
            $data['hasil'] = false;
            $data['pesan'] = validation_errors();
        }
        
        // Kembalikan dalam bentuk JSON atau halaman
        if($this->input->is_ajax_request())
        {
            echo json_encode($data);
        }
        else
        {
            $data["content"] = "sms/terkirim";
            $data["title"] = "SMS Telah Terkirim";
            $this->load->view("template", $data);
        }
        
    }
    
    function cari_kontak_json()
    {
        $hasil = array();
        $kriteria = $this->input->get("q");
        
        // Cari kontak
        $data_kontak = $this->kontak_model->lihat_kontak($this->sesi_model->ambil_nama_login(), $kriteria);
        foreach($data_kontak as $kontak)
        {
            $data_baru = array(
                "id" => $kontak["nama_kontak"],
                "name" => $kontak["nama_kontak"]." - ".$kontak["no_handphone"]
            );
            
            $hasil[] = $data_baru;
        }
        
        // Cari grup
        $data_grup = $this->kontak_model->lihat_grup($this->sesi_model->ambil_nama_login(), $kriteria);
        foreach($data_grup as $grup)
        {
            $data_baru = array(
                "id" => $grup["nama_grup"],
                "name" => $grup["nama_grup"]." [GRUP]"
            );
            
            $hasil[] = $data_baru;
        }
        
        // Buatkan item untuk nomor itu sendiri jika memang nomor
        $no_handphone_regex = "/^\d+/";
        if(preg_match($no_handphone_regex, $kriteria) == 1)
        {
            $data_baru = array(
                "id" => $kriteria,
                "name" => $kriteria . " [NOMOR BARU]"
            );
            
            $hasil[] = $data_baru;
        }
        // Tampilkan hasil
        echo json_encode($hasil);
    }
}
?>
