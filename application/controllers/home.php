<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Home extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }
    
    function index()
    {
        // Tampilkan view
        $data["content"] = "home/index";
        $data["title"] = "Selamat Datang";
        
        $this->load->view("template", $data);
    }
}
?>
