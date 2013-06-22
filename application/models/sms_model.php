<?php


/*
 * sms_model.php
 * Penulis: Putu Wiramaswara Widya <initrunlevel0@gmail.com>
 * Kontributor: <<Silahkan diisi>>
 * 
 * Model ini terhubung dengan tabel "sms_dipesan" (lihat PDM untuk lebih lanjut)
 */

class sms_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function daftarkan_sms_siap_kirim()
    {
        $result = array();
        
        // Hanya mendaftarkan SMS yang siap kirim (terkirim => 0)
        
        $query = $this->db->get_where("sms_pesanan", array("terkirim" => 0));
        foreach($query->result_array() as $row)
        {
            $result[] = $row;
        }
        
        return $result;
    }
    
    function tandai_sms_sebagai_dikirim($id_sms_pesanan)
    {
        $this->db->where("id_sms_pesanan", $id_sms_pesanan);
        
        // Tandai sebagai terkirim (terkirim => 1)
        $this->db->update("sms_pesanan", array ("terkirim" => 1));
    }
    
    function kirim_sms($id_kontak, $konten, DateTime $waktu_kirim = null)
    {
        if($waktu_kirim == null)
        {
            $waktu_kirim = new DateTime();
            
        }

        // INGAT: Tujuan pengiriman adalah id_kontak, bukan nomor tujuan yang sebenarnya
        // Jika nomor belum masuk kontak, maka harus ada mekanisme otomatis untuk memasukkan nomor tersebut
        // ke dalam kontak :D
        
        $this->db->insert("sms_pesanan", array("kontak" => $id_kontak, "konten" => $konten, "waktu_kirim" => $waktu_kirim->format("Y-m-d H:i:s"),  "terkirim" => 0));
    }
    
    
    
}
?>
