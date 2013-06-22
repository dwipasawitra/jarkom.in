<?php
class dasbor extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if($this->sesi_model->apakah_login() == FALSE)
        {
            redirect("/"); 
            return;
        }
    }
    
    function index()
    {
        $data["content"] = "dasbor/index";
        $data["title"] = "Dasbor dan Menu";
        $this->load->view("template", $data);
    }
}
?>
