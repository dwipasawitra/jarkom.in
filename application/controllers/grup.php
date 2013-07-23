<?php
class grup extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("kontak_model");
    }
    
   
    function index()
    {
        $data["content"] = "grup/index";
        $data["title"] = "Mengelola Grup";
        $data["data_grup"] = $this->kontak_model->lihat_semua_grup($this->sesi_model->ambil_nama_login());
        $id_grup = $this->input->get("id_grup");
        $data['id_grup'] = $id_grup;
        
        // Ambil data kontak dari pengguna
        if($id_grup == FALSE)
        {
            $data['id_grup'] = -1;
            $data['kontak'] = $this->kontak_model->lihat_semua_kontak($this->sesi_model->ambil_nama_login());
        }
        else
        {
            $data['kontak'] = $this->kontak_model->lihat_semua_kontak($this->sesi_model->ambil_nama_login(), $id_grup);
        }
        foreach($data['kontak'] as &$kontak)
        {
            $query_grup = $this->db->from("grup_kontak")->join("grup", "grup_kontak.grup = grup.id_grup", "inner")->where("grup_kontak.kontak", $kontak['id_kontak'])->get();
            $grup = array();
            if($query_grup->num_rows() > 0)
            {
                foreach($query_grup->result_array() as $grup_item)
                {
                    $grup[] = array("id_grup" => $grup_item['id_grup'], "nama_grup" => $grup_item['nama_grup']);
                }
            }
            
            $kontak['grup'] = $grup;
        }
        
        
        $this->load->view("template", $data);
        

    }
    //controler untuk nambah id facebook grup lewat form
    function sunting_facebook_grup()
    {
        $id_grup = $this->input->post("id_grup");
        $facebook_id_grup = $this->input->post("facebook_id_grup");
        $this->kontak_model->sunting_facebook_grup($id_grup, $facebook_id_grup);
    }
}  
?>
