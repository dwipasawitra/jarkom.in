<?php
/*
 * pengguna_model.php
 * Penulis: Putu Wiramaswara Widya <initrunlevel0@gmail.com>
 * Kontributor: <<Silahkan diisi>>
 * 
 * Model ini terhubung dengan tabel "pengguna" (lihat PDM untuk lebih lanjut)
 */

class pengguna_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function tambahkan_pengguna(string $nama_login, string $nama_lengkap, string $surel, string $password_md5)
    {
        // Asumsi: Password telah dikonversi ke MD5 oleh pengendali
        $data = array(
            "nama_login" => $nama_login,
            "nama_lengkap" => $nama_lengkap,
            "surel" => $surel,
            "password" => $password_md5,
            "kredit" => 0
        );
        
        $this->db->insert("pengguna", $data);
                
    }
    
    function baca_data_pengguna(string $nama_login)
    {
        $query = $this->db->get_where("pengguna", array("nama_login" => $nama_login));
        return $query->first_row("array");
    }
    
    function ubah_data_pengguna(string $nama_login, string $nama_lengkap = null, string $surel = null, string $password_md5 = null)
    {
        if(!is_null($nama_lengkap)) 
        {
            $this->db->where("nama_login", $nama_login);
            $this->db->update("pengguna", array("nama_lengkap" => $nama_lengkap));
        }
        
        if(!is_null($surel)) 
        {
            $this->db->where("nama_login", $nama_login);
            $this->db->update("pengguna", array("surel" => $surel));
        }
        
        if(!is_null($password_md5)) 
        {
            $this->db->where("nama_login", $nama_login);
            $this->db->update("pengguna", array("password_md5" => $password_md5));
        }
        
    }
    
    function verifikasi_pengguna(string $nama_login, string $password_md5)
    {
        $query = $this->db->get_where("pengguna", array("nama_login" => $nama_login, "password" => $password_md5));
        if($query->num_rows() == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    function top_up_kredit_pengguna(string $nama_login, int $tambahan_topup)
    {
        // Ambil nilai kredit pengguna sebelumnya
        $query = $this->db->get_where("pengguna", array("nama_login" => $nama_login));
        $row = $query->first_row("array");
        $kredit = intval($row["kredit"]);
        
        // Tambahkan kredit
        $kredit += $tambahan_popup;
        
        $this->db->free_result();
        $this->db->update("pengguna", array("kredit" => $kredit));
        
    }
}
?>
