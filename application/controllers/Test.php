<?php
class Test extends CI_Controller {
    private $idPc;

    public function __construct(){
        parent::__construct();
        $this->load->model('Pc_model');
        $this->load->helper('url');
        $this->load->helper('form');
    }
    public function index()
    {
        $this->idClasse = $this->classes[0]['Code'];
        var_dump($this->Pc_model->get_Infos_visu("322-ACR-M4630G"));
    }
}