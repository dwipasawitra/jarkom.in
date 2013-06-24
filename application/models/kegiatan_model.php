<?php
class kegiatan_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("kontak_model");
        $this->load->model("sms_model");
    }
    
    function tambah_kegiatan($id_kegiatan, $nama_kegiatan, $id_grup, $waktu_mulai_kegiatan)
    {
        $data_baru = array(
            "id_kegiatan" => $id_kegiatan,
            "nama_kegiatan" => $nama_kegiatan,
            "pengguna" => $this->sesi_model->ambil_nama_login(),
            "grup" => $id_grup,
            "waktu_mulai_kegiatan" => $waktu_mulai_kegiatan
        );
        
        $this->db->insert("kegiatan", $data_baru);
        $this->bangun_data_konfirmasi_kegiatan($id_kegiatan, $id_grup);
        
        return $id_kegiatan;
        
    }
    
    function sunting_kegiatan($id_kegiatan, $nama_kegiatan = null, $id_grup = null, $waktu_mulai_kegiatan = null)
    {
        $this->db->where("id_kegiatan", $id_kegiatan);
        if($nama_kegiatan != null)
        {
            $this->db->update("kegiatan", array("nama_kegiatan" => $nama_kegiatan));
        }
        
        if($waktu_mulai_kegiatan != null)
        {
            $this->db->update("kegiatan", array("waktu_mulai_kegiatan" => $waktu_mulai_kegiatan));
        }
        
        if($id_grup != null)
        {
            $this->db->delete("konfirmasi_kegiatan", array("kegiatan" => $id_kegiatan));
            $this->db->update("kegiatan", array("grup" => $id_grup));
            $this->bangun_data_konfirmasi_kegiatan($id_kegiatan, $id_grup);
        }
    }
    
    function lihat_semua_kegiatan($nama_login)
    {
        $this->db->join("grup", "kegiatan.grup=grup.id_grup", "inner");
        $query = $this->db->get_where("kegiatan", array("kegiatan.pengguna" => $nama_login));
        
        return $query->result_array();
        
       
    }
    
    function ambil_data_kegiatan($id_kegiatan)
    {
        $this->db->join("grup", "kegiatan.grup=grup.id_grup", "inner");
        $query = $this->db->get_where("kegiatan", array("id_kegiatan" => $id_kegiatan));
        if($query->num_rows() > 0)
        {
            return $query->first_row("array");
        }
        else
        {
            return null;
        }
    }
    
    function bangun_data_konfirmasi_kegiatan($id_kegiatan, $id_grup)
    {
        // Hapus data sebelumnya
        $this->db->delete("konfirmasi_kegiatan", array("kegiatan" => $id_kegiatan));
        
        // Data konfirmasi per anggota grup per kegiatan
        // Ambil siapa saja yang masuk grup ini
        $anggota_grup = $this->kontak_model->lihat_kontak_pada_grup($id_grup);
        foreach($anggota_grup as $anggota)
        {
            $this->db->insert("konfirmasi_kegiatan", array("kegiatan" => $id_kegiatan, "kontak" => $anggota, "konfirmasi" => 0));
        }
    }
    
    function lihat_konfirmasi_kegiatan($id_kegiatan)
    {
        // Yang dilihat: Id kegiatan, nama kontak, nomor handphone, konfirmasinya
        $this->db->select(array('kegiatan', 'nama_kontak', 'no_handphone', 'konfirmasi'));
        $this->db->from('konfirmasi_kegiatan');
        $this->db->join('kontak', 'konfirmasi_kegiatan.kontak = kontak.id_kontak', 'inner');
        $this->db->where('konfirmasi_kegiatan.kegiatan', $id_kegiatan);
        $query = $this->db->get();
        
        return $query->result_array();
        
        
    }
    
    function tambah_pengingat_kegiatan($id_kegiatan, $menit_sebelumnya, $pesan)
    {
        // Tambahkan datanya pada tabel pengingat_kegiatan
        
        $data_pengingat_kegiatan = array(
            "kegiatan" => $id_kegiatan,
            "menit_sebelumnya" => $menit_sebelumnya,
            "pesan" => $pesan
        );
        
        $this->db->insert("pengingat_kegiatan", $data_pengingat_kegiatan);
        
        // Tambahkan datanya pada tabel sms_dipesan
        // Ambil waktu mulai kegiatan ini
        $data_kegiatan = $this->ambil_data_kegiatan($id_kegiatan);
        $waktu_kegiatan = new DateTime($data_kegiatan['waktu_mulai_kegiatan']);
        
        // Set waktu pengiriman SMS yang sesuai
        $waktu_pengingat_kegiatan = $waktu_kegiatan->sub(new DateInterval("PT".$menit_sebelumnya."M"));
        
        // Kirimkan SMS 
        $anggota_grup = $this->kontak_model->lihat_kontak_pada_grup($data_kegiatan['grup']);
        foreach($anggota_grup as $anggota)
        {
           $this->sms_model->kirim_sms($anggota, $pesan, $waktu_pengingat_kegiatan);
        }
        
        
    }
    
    function lihat_pengingat_kegiatan($id_kegiatan)
    {
        
        $query = $this->db->get_where("pengingat_kegiatan", array("kegiatan" => $id_kegiatan));
        return $query->result_array();
    }
    
    function ambil_id_kontak_dari_kegiatan($id_kegiatan, $no_handphone)
    {
        $this->db->select("kegiatan, id_kontak");
        $this->db->from("konfirmasi_kegiatan");
        $this->db->join("kontak", "konfirmasi_kegiatan.kontak = kontak.id_kontak", "inner");
        $this->db->where("kegiatan", $id_kegiatan);
        $this->db->where("no_handphone", $no_handphone);
        $query = $this->db->get();
        
        if($query->num_rows() > 0)
        {
            $data = $query->first_row("array");
            return $data['id_kontak'];
        }
        else
        {
            return null;
        }
    }
    
    function ubah_data_konfirmasi($id_kegiatan, $id_kontak, $konfirmasi)
    {
        $this->db->where(array("kegiatan" => $id_kegiatan, "kontak" => $id_kontak));
        if($konfirmasi == "Y")
        {
            $this->db->update("konfirmasi_kegiatan", array("konfirmasi" => 1));
        }
        else if ($konfirmasi == "T")
        {
            $this->db->update("konfirmasi_kegiatan", array("konfirmasi" => 0));
        }
    }
}
?>
