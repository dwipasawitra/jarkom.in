<?php
/*
 * kontak_model.php
 * Penulis: Putu Wiramaswara Widya <initrunlevel0@gmail.com>
 * Kontributor: <<Silahkan diisi>>
 * 
 * Model ini terhubung dengan tabel "kontak" dan "grup" (lihat PDM untuk lebih lanjut)
 */

// PENDING: Harus ada perubahan PDM terlebih dahulu (23:48 17/06/2013) -- Wira
class kontak_model extends CI_Model
{
    // Kontak
    
    function tambah_kontak($nama_login, $nama_kontak, $no_handphone)
    {
        $data_baru = array(
            "nama_kontak" => $nama_kontak,
            "no_handphone" => $no_handphone,
            "pengguna" => $nama_login
        );
        
        $this->db->insert("kontak", $data_baru);
        
        // Ambil id kontak
        $query = $this->db->get_where("kontak", $data_baru);
        $data_kontak = $query->first_row("array");
        
        return $data_kontak["id_kontak"];
    }
    
    function sunting_kontak($id_kontak, $nama_kontak = null, $no_handphone = null)
    {
        
        if($nama_kontak != null)
        {
            $this->db->where("id_kontak", $id_kontak);
            $this->db->update("kontak", array("nama_kontak" => $nama_kontak));
        }
        
        if($no_handphone != null)
        {
            $this->db->where("id_kontak", $id_kontak);
            $this->db->update("kontak", array("no_handphone" => $no_handphone));
        }
    }
    
    function hapus_kontak($id_kontak)
    {
        // Cascade ke tabel grup_kontak;
        $this->db->where("kontak", $id_kontak);
        $this->db->delete("grup_kontak");
        
        // Cascade ke tabel sms_dipesan
        $this->db->where("kontak", $id_kontak);
        $this->db->delete("sms_pesanan");
        
        $this->db->where("id_kontak", $id_kontak);
        $this->db->delete("kontak");
        
        
        
    }
    
    function lihat_kontak($nama_login, $kriteria)
    {
        $hasil = array();
        $this->db->like("nama_kontak", $kriteria);
        $this->db->or_like("no_handphone", $kriteria);
        $query = $this->db->get_where("kontak", array("pengguna" => $nama_login));
        if($query->num_rows() > 0)
        {
            foreach($query->result_array() as $row)
            {
                $hasil[] = $row;
            }
        }
        return $hasil;
    }
    
    function lihat_semua_kontak($nama_login, $id_grup = null)
    {
        $hasil = array();
        $this->db->order_by("nama_kontak", "asc");
        
        if($id_grup == null)
        {
            
            $query = $this->db->get_where("kontak", array("kontak.pengguna" => $nama_login));
        }
        else
        {
            $this->db->select(array("id_kontak", "id_grup", "nama_kontak", "no_handphone", "twitter"));
            $this->db->join("grup_kontak", "grup_kontak.kontak = kontak.id_kontak", "inner");
            $this->db->join("grup", "grup_kontak.grup = grup.id_grup", "inner");
            $query = $this->db->get_where("kontak", array("kontak.pengguna" => $nama_login, "id_grup" => $id_grup));
        }
        if($query->num_rows() > 0)
        {
            foreach($query->result_array() as $row)
            {
                $hasil[] = $row;
            }
        }
        return $hasil;
    }
    
    function ambil_data_kontak($id_kontak)
    {
        $query = $this->db->get_where("kontak", array("id_kontak" => $id_kontak));
        if($query->num_rows() > 0)
        {
            return $query->first_row("array");
        }
        else
        {
            return null;
        }
    }
    
    // Grup
    function tambah_grup($nama_login, $nama_grup)
    {
        $data_baru = array(
            "nama_grup" => $nama_grup,
            "pengguna" => $nama_login
        );
        
        $this->db->insert("grup", $data_baru);
        
        // Ambil id grup dari grup yang baru ditambahkan
        $query = $this->db->get_where("grup", $data_baru);
        $data_grup = $query->first_row("array");
        
        return $data_grup["id_grup"];
    }
    
    function sunting_grup($id_grup, $nama_grup)
    {
        $this->db->where("id_grup", $id_grup);
        $this->db->update("grup", array("nama_grup" => $nama_grup));
    }
    
    function hapus_grup($id_grup)
    {
        $this->db->where("id_grup", $id_grup);
        $this->db->delete("grup");
    }
    
    function lihat_grup($nama_login, $kriteria)
    {
        $hasil = array();
        $this->db->like("nama_grup", $kriteria);
        $query = $this->db->get_where("grup", array("pengguna" => $nama_login));
        if($query->num_rows() > 0)
        {
            foreach($query->result_array() as $row)
            {
                $hasil[] = $row;
            }
        }
        return $hasil;
    }
    
    function lihat_semua_grup($nama_login)
    {
        $hasil = array();
        $query = $this->db->get_where("grup", array("pengguna" => $nama_login));
        if($query->num_rows() > 0)
        {
            foreach($query->result_array() as $row)
            {
                $hasil[] = $row;
            }
        }
        return $hasil;
    }
    
    
    // Grup + Kontak
    function tambah_kontak_pada_grup($id_kontak, $id_grup)
    {
        $data_baru = array(
            "grup" => $id_grup,
            "kontak" => $id_kontak
        );
        
        // Cek apakah sudah ada di dalam database, jika tidak, baru lakukan insert
        $query = $this->db->get_where("grup_kontak", $data_baru);
        if($query->num_rows == 0)
        {
            $this->db->insert("grup_kontak", $data_baru);
        }
    }
    
    function hapus_kontak_pada_grup($id_kontak, $id_grup = null)
    {
        if($id_grup != null)
        {
            $this->db->delete("grup_kontak", array("kontak" => $id_kontak, "grup" => $id_grup));
        }
        else
        {
            $this->db->delete("grup_kontak", array("kontak" => $id_kontak));
        }
    }
    
    // Data validator
    // Masukan bisa berupa: Nama kontak, nomor handphone, nama grup
    // Kembalikan dalam bentuk array dari id_kontak
    
    function ambil_id_kontak($nama_login, $masukan)
    {
        // Klasifikasn masukan
        // 0: Nama
        // 1: Nomor handphone
        
        $no_handphone_regex = "/^\d+/";
        $hasil = array();
        
        // Regex testing
        if(preg_match($no_handphone_regex, $masukan) == 1)
        { 
           // Dianggap sebagai nomor handphone, cari siapa pemilik nomor ini
            $query = $this->db->get_where("kontak", array("pengguna" => $nama_login, "no_handphone" => $masukan));
            if($query->num_rows() > 0)
            {
                $data_kontak = $query->first_row("array");
                $hasil[] = $data_kontak['id_kontak'];
                return $hasil;
            }
            else
            {
                // Buat kontak baru untuk ini
                $this->tambah_kontak($nama_login, $masukan, $masukan);
                $query = $this->db->get_where("kontak", array("pengguna" => $nama_login, "no_handphone" => $masukan));
                $data_kontak = $query->first_row("array");
                $hasil[] = $data_kontak['id_kontak'];
                return $hasil;
            }
        }
        else
        {
            // Dianggap sebagai nama, entah itu nama grup atau nama anggota
            // Pertama, cari di kontak
            
            $query = $this->db->get_where("kontak", array("pengguna" => $nama_login, "nama_kontak" => $masukan));
            if($query->num_rows() > 0)
            {
                $data_kontak = $query->first_row("array");
                $hasil[] = $data_kontak['id_kontak'];
                return $hasil;
            }
            
            // Kedua, cari di grup
            $query = $this->db->get_where("grup", array("pengguna" => $nama_login, "nama_grup" => $masukan));
            if($query->num_rows() > 0)
            {
                // Ambil id_grup
                $data_grup = $query->first_row("array");
                $id_grup = $data_grup['id_grup'];
                
                // Kueri dari tabel grup_kontak
                $query = $this->db->get_where("grup_kontak", array("grup" => $id_grup));
                
                if($query->num_rows() > 0)
                {
                    foreach($query->result_array() as $row)
                    {
                        $hasil[] = $row['kontak'];
                    }
                }
                
                
                return $hasil;
            }
        }
        
        return $hasil;
        
        
    }
    
    function ambil_no_handphone($id_kontak)
    {
        $query = $this->db->get_where("kontak", array("id_kontak" => $id_kontak));
        if($query->num_rows > 0)
        {
            $data_kontak = $query->first_row("array");
            return $data_kontak["no_handphone"];
        }
    }
    
    function ambil_id_grup($nama_grup)
    {
        $query = $this->db->get_where("grup", array("nama_grup" => $nama_grup));
        if($query->num_rows > 0)
        {
            $data_grup = $query->first_row("array");
            return $data_grup["id_grup"];
        }
        else
        {
            return null;
        }
    }
    
    function lihat_kontak_pada_grup($id_grup)
    {   
        $hasil = array();
        $query = $this->db->get_where("grup_kontak", array("grup" => $id_grup));
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $hasil[] = $row['kontak'];
            }
            return $hasil;
        }
        else
        {
            return null;
        }
        
    }
    
    
    
    
}
?>
