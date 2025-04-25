<?php
class Dashboard extends CI_Controller
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
        $windows = $this->Pc_model->get_stat_win();
        $unix = $this->Pc_model->get_stat_unix();
        $android = $this->Pc_model->get_stat_android();
        $macos = $this->Pc_model->get_stat_macos();



        // Initialiser le tableau de données
        $data = array(
            'api' => array(
                'windows' => 0,
                'unix' => 0,
                'android' => 0,
                'macos' => 0

        )
    );

        // Vérifier si les résultats existent et les ajouter au tableau $data
        if (!empty($windows)) {
            $data['api']['windows'] = $windows;
        }

        if (!empty($unix)) {
            $data['api']['unix'] = $unix;
        }

        if (!empty($android)) {
            $data['api']['android'] = $android;
        }

        if (!empty($macos)) {
            $data['api']['macos'] = $macos;
        }
       

        // Charger la vue avec les données
        $this->load->view('api', $data);
    }
}
