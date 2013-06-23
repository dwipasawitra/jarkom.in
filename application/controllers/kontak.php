<?php
class kontak extends CI_Controller
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
    }
    
    function list_grup_json()
    {
        $kriteria = $this->input->get("q");
        
        $hasil = array();
        
        // Cari grup
        $data_grup = $this->kontak_model->lihat_grup($this->sesi_model->ambil_nama_login(), $kriteria);
        foreach($data_grup as $grup)
        {
            $data_baru = array(
                "id" => $grup["nama_grup"],
                "name" => $grup["nama_grup"]
            );
            
            $hasil[] = $data_baru;
        }
        
        // Tambah hasil luaran berupa yaitu isi dari $q (untuk membuat kontak baru)
        $hasil[] = array(
                "id" => $kriteria,
                "name" => $kriteria
        );
        
        echo json_encode($hasil);
    }
    
    function index()
    {
        // Menampilkan semua daftar kontak dari pengguna
        $data['content'] = "contact/index";
        $data['title'] = "Mengelola Kontak";
        
        // Ambil data kontak dari pengguna
        $data['kontak'] = $this->kontak_model->lihat_semua_kontak($this->sesi_model->ambil_nama_login());
        
        // Cari tahu kontak ini masuk grup apa saja
        // http://stackoverflow.com/questions/3663881/update-object-value-within-foreach-loop
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
    
    function tambah_kontak()
    {
        // Validasi form
        $this->form_validation->set_rules("nama_kontak", "Nama kontak", "required");
        $this->form_validation->set_rules("no_handphone", "Nomor handphone", "required");
        $this->form_validation->set_message("required", "%s belum diisi");
        $this->form_validation->set_error_delimiters("","<br/>");
        
        if($this->form_validation->run() == TRUE)
        {
            $data['hasil'] = true;
            $nama_kontak = $this->input->post("nama_kontak");
            $no_handphone = $this->input->post("no_handphone");
            $data_grup = $this->input->post("data_grup");
            
            // Lakukan proses penambahan data kontak
            $id_kontak = $this->kontak_model->tambah_kontak($this->sesi_model->ambil_nama_login(), $nama_kontak, $no_handphone);
            
            // Proses untuk masing-masing grup
            if($data_grup != "")
            {
                $data_grup = explode($data_grup, ",");
                foreach($data_grup as $grup)
                {
                    // Cari apakah grup sudah ada atau belum
                    if($this->kontak_model->ambil_id_grup($grup) != null)
                    {
                        // Jika sudah ada, tambahkan pada grup kontak tersebut
                        $this->kontak_model->tambah_kontak_pada_grup($id_kontak, $this->kontak_model->ambil_id_grup($grup));
                    }
                    else
                    {
                        // Jika tidak ada, buatkan grup baru
                        $id_grup = $this->kontak_model->tambah_grup($this->sesi_model->ambil_nama_login(), $grup);
                        $this->kontak_model->tambah_kontak_pada_grup($id_kontak, $id_grup);
                    }


                }
            }
            
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
        
    }
    
    function ambil_data_kontak_json()
    {
        $id_kontak = $this->input->post("id_kontak");
        
        // Ambil data kontak
        $data = $this->kontak_model->ambil_data_kontak($id_kontak);
        
        echo json_encode($data);
    }
    
    function sunting_kontak()
    {
        // Validasi form
        $this->form_validation->set_rules("id_kontak", "id_kontak", "required");
        $this->form_validation->set_rules("nama_kontak", "Nama kontak", "required");
        $this->form_validation->set_rules("no_handphone", "Nomor handphone", "required");
        $this->form_validation->set_message("required", "%s belum diisi");
        $this->form_validation->set_error_delimiters("","<br/>");
        
        if($this->form_validation->run() == TRUE)
        {
            $data['hasil'] = true;
            $id_kontak = $this->input->post("id_kontak");
            $nama_kontak = $this->input->post("nama_kontak");
            $no_handphone = $this->input->post("no_handphone");
            
            // Lakukan proses penyuntingan data kontak
            $this->kontak_model->sunting_kontak($id_kontak, $nama_kontak, $no_handphone);
            
                        
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
    }
    
    function hapus_kontak()
    {
        $id_kontak = $this->input->post("id_kontak");
        $this->kontak_model->hapus_kontak($id_kontak);
    }
    
    function sunting_grup_kontak()
    {
        $data_grup = explode(",", $this->input->post("data_grup") );
        $id_kontak = $this->input->post("id_kontak");
        
        // Hapus semua grup yang melekat pada kontak ini
        $this->kontak_model->hapus_kontak_pada_grup($id_kontak);
        if(count($data_grup) > 0)
        {
            foreach($data_grup as $grup)
            {
                // Cari apakah grup sudah ada atau belum
                if($this->kontak_model->ambil_id_grup($grup) != null)
                {
                    // Jika sudah ada, tambahkan pada grup kontak tersebut
                    $this->kontak_model->tambah_kontak_pada_grup($id_kontak, $this->kontak_model->ambil_id_grup($grup));
                }
                else
                {
                    // Jika tidak ada, buatkan grup baru
                    $id_grup = $this->kontak_model->tambah_grup($this->sesi_model->ambil_nama_login(), $grup);
                    $this->kontak_model->tambah_kontak_pada_grup($id_kontak, $id_grup);
                }


            }
        }
    }
    
    function lihat_semua_grup()
    {
        $data = $this->kontak_model->lihat_semua_grup($this->sesi_model->ambil_nama_login());
        echo json_encode($data);
    }
}
?>
