<?php
class ListPC extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pc_model');
        $this->load->helper('form');
    }
    
    public function index()
    {
        $this->commun();
    }
    
    private function commun()
    {
        // Récupérer les données
        $lstpc = $this->Pc_model->get_lst_pc();


        // Initialiser le tableau de données
        $data = array();

        // Vérifier si les résultats existent et les ajouter au tableau $data
        

        if (!empty($lstpc)) {
            $data['api']['lstpc'] = $lstpc;
        }


        // Charger la vue avec les données
        $this->load->view('api', $data);
    }
}
