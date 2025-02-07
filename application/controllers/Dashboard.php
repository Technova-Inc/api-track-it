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

        // Initialiser le tableau de données
        $data = array();

        // Vérifier si les résultats existent et les ajouter au tableau $data
        if (!empty($windows)) {
            $data['windows'] = $windows;
        }

        if (!empty($unix)) {
            $data['unix'] = $unix;
        }

        if (!empty($android)) {
            $data['android'] = $android;
        }

        // Si aucune donnée n'est trouvée, initialiser à 0
        if (empty($data['windows']) && empty($data['unix']) && empty($data['android'])) {
            $data['windows'] = 0;
            $data['unix'] = 0;
            $data['android'] = 0;
        }

        // Charger la vue avec les données
        $this->load->view('api', $data);
    }
}
