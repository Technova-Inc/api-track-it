<?php
class Pull extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pc_model');
        $this->load->helper('url');
    }

    public function index()
    {
        // Initialiser $data avec des valeurs par défaut
        $data = [
            'name'           => 'Inconnu',
            'osversion'      => 'Inconnu',
            'osname'         => 'Inconnu',
            'architecture'   => 'Inconnu',
            'user'           => 'Inconnu',
            'ram'            => 'Inconnu',
            'cpu'            => 'Inconnu',
            'serial'         => 'Inconnu',
            'mac'            => 'Inconnu',
            'ip'             => 'Inconnu',
            'domaine'        => 'Inconnu',
            'windows_key'    => 'Inconnu',
            'license_status' => 'Inconnu',
            'uuid'           => 'Inconnu',
        ];

        // Récupération et validation du JSON
        $json = file_get_contents("php://input");
        if ($json) {
            $json = json_decode($json);

            // Vérifier si le JSON est valide
            if ($json === null && json_last_error() !== JSON_ERROR_NONE) {
                show_error("Invalid JSON format", 400);
                return;
            }

            // Extraire les données en évitant les erreurs
            $data = [
                'name'           => $json->name ?? 'Inconnu',
                'os_name'        => $json->os ?? 'Inconnu',
                'os_version'     => $json->os_version ?? 'Inconnu', // Correction ici
                'architecture'   => $json->architecture ?? 'Inconnu',
                'user'           => $json->user ?? 'Inconnu',
                'ram'            => $json->ram ?? 'Inconnu',
                'cpu'            => $json->cpu ?? 'Inconnu',
                'serial'         => $json->serial ?? 'Inconnu',
                'mac'            => $json->mac ?? 'Inconnu',
                'ip'             => $json->ip ?? 'Inconnu',
                'domaine'        => $json->domaine ?? 'Inconnu',
                'windows_key'    => $json->windows_key ?? 'Inconnu',
                'license_status' => $json->license_status ?? 'Inconnu',
                'uuid'           => $json->uuid ?? 'Inconnu',
            ];
        }

        $pc = $this->Pc_model->get_Infos_cons_main($data['name']);

        if (empty($pc)) {
            $this->Pc_model->insert_pc($data);
        } else {
            $this->Pc_model->update_pc($data['name'], $data);
        }

        // Réponse JSON
        $response = ['status' => 'success', 'message' => 'Data processed successfully'];
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}