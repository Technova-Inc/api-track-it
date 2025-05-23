<?php
class Consultation extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('Pc_model');
        $this->load->helper('url');
        $this->load->helper('form');
    }
    public function index()
    {
        $this->commun();
    }
    private function commun(){
        $url = parse_url($_SERVER['REQUEST_URI']);
        parse_str($url['query'], $params);
    
        // Vérifie si le paramètre 'pc' est bien présent dans l'URL
        if (!isset($params['pc']) || empty($params['pc'])) {
            show_error("Le paramètre 'pc' est requis dans l'URL.", 400);
            return;
        }

        $data = array();
        $nomPc = $params['pc'];
     
        // $data['software'] = $this->Pc_model->get_Infos_cons_software($nomPc);
        // $data['network'] = $this->Pc_model->get_Infos_cons_network($nomPc);
       
        $pc = $this->Pc_model->get_Infos_cons_main($nomPc);
        if (!empty($pc)) {
            $data['api']['pc'] = $pc;
        }

        // if (!empty($software)) {
        //     $data['software'] = $software;
        // }

        // if (!empty($network)) {
        //     $data['network'] = $network;
        // }

        // Charger la vue avec les données
        $this->load->view('api', $data);
    }
}