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
            'name'           => 'Unknown',
            'osversion'      => 'Unknown',
            'osname'         => 'Unknown',
            'architecture'   => 'Unknown',
            'user'           => 'Unknown',
            'ram'            => 'Unknown',
            'cpu'            => 'Unknown',
            'serial'         => 'Unknown',
            'mac'            => 'Unknown',
            'ip'             => 'Unknown',
            'domaine'        => 'Unknown',
            'windows_key'    => 'Unknown',
            'license_status' => 'Unknown',
            'uuid'           => 'Unknown',
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
                'name'           => $json->name ?? 'Unknown',
                'os_name'        => $json->os ?? 'Unknown',
                'os_version'     => $json->os_version ?? 'Unknown', // Correction ici
                'architecture'   => $json->architecture ?? 'Unknown',
                'user'           => $json->user ?? 'Unknown',
                'ram'            => $json->ram ?? 'Unknown',
                'cpu'            => $json->cpu ?? 'Unknown',
                'serial'         => $json->serial ?? 'Unknown',
                'mac'            => $json->mac ?? 'Unknown',
                'ip'             => $json->ip ?? 'Unknown',
                'domaine'        => $json->domaine ?? 'Unknown',
                'windows_key'    => $json->windows_key ?? 'Unknown',
                'license_status' => $json->license_status ?? 'Unknown',
                'uuid'           => $json->uuid ?? 'Unknown',
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