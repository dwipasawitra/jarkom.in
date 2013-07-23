<?php

class api_jarkomin extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("sms_model");
        $this->load->model("pengguna_model");
        $this->load->model("kontak_model");
        $this->load->model("kegiatan_model");
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
            $hasil[] = array("id_sms_pesanan" => $sms["id_sms_pesanan"], "no_handphone" => $no_handphone, "konten" => $sms["konten"]);
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
    
    function proses_sms_masuk()
    {
        // API ini akan memproses SMS masuk dari SMS gateway
        
        $no_handphone = str_replace("+62", "0", $this->input->post("no_handphone"));
        $konten = $this->input->post("konten");
        
        echo $konten;
        
        // Pada kasus penggunaan awal, SMS yang diterima adalah SMS dari nomor pengguna yang terdaftar di sistem
        // Pengguna bisa mengirimkan SMS ke grup melalui format nama_grup<spasi>pesan.
        $konten = explode(" ", $konten, 2); // Maksimum 2 elemen: Perintah<spasi>Pesannya
        $perintah = $konten[0];
        $pesan = $konten[1];
        
        // Cari tahu akun siapa yang bertanggung jawab atas pengirim pesan ini
        $nama_login = $this->pengguna_model->ambil_nama_login_dari_no_handphone($no_handphone);
        if($nama_login != null)
        {
            // Anggap $perintah merupakan nama suatu grup
            // Jika nama grup berisi spasi, maka user akan menginputnya dengan tanda under_score
            $grup_dicari = str_replace("_", " ", $perintah);
            if($this->kontak_model->ambil_id_grup($grup_dicari) != null)
            {
                // Grup ada, kirim pesan ke semua anggota grup
                $penerima_pesan = $this->kontak_model->ambil_id_kontak($nama_login, $grup_dicari);
                foreach($penerima_pesan as $penerima)
                {
                    $this->sms_model->kirim_sms($penerima, $pesan);
                }
                
            }
            else
            {
                // Jika bukan nama grup, kita anggap sebagai suatu command
                switch(strtoupper($perintah))
                {
                    case "KONFIRM":
                        // Proses konfirmasi kehadiran 
                        // Pecah $pesan menjadi dua
                        $pesan = explode(" ", $pesan, 2);
                        if(count($pesan) == 2)
                        {
                            $id_kegiatan = $pesan[0];
                            $konfirmasi = $pesan[1];
                        
                            // Cari nomor handphone ini masuk dalam id_kontak apa dalam kegiatan
                            $id_kontak = $this->kegiatan_model->ambil_id_kontak_dari_kegiatan($id_kegiatan, $no_handphone);
                            
                            // Ubah data konfirmasi
                            $this->kegiatan_model->ubah_data_konfirmasi($id_kegiatan, $id_kontak, $konfirmasi);
                        }
                        break;
                }
            }
        }
        
    }
    
    function lihat_pesan_grup_siap_kirim()
    {
        $hasil = array();
        
        // List semua SMS yang belum terkirim
        $pesan_grup_siap_kirim = $this->sms_model->daftarkan_pesan_grup_siap_kirim();
        
        // Cari nomor handphone untuk setiap id kontak
        foreach($pesan_grup_siap_kirim as $pesan_grup)
        {
            $id_grup_facebook = $this->kontak_model->ambil_id_grup_facebook($pesan_grup["grup"]);
            $hasil[] = array("id_pesan" => $pesan_grup["id_grup_pesan"], "grup_fb" => $id_grup_facebook, "konten" => $pesan_grup["pesan"]);
        }
        
        // Kirimkan hasilnya ke klien
        header("Content-Type: application/json");
        echo json_encode($hasil);
    
    }
    
    function tandai_pesan_grup_sudah_terkirim()
    {
        $id_grup_pesan = $this->input->post("id_grup_pesan");
        $this->sms_model->tandai_grup_pesan_sudah_terkirim($id_grup_pesan);
        
    }
}
?>
