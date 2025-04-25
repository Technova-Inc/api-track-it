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
        $json = $this->request->getJSON();

        if ($json) {
            // Extraire les données
            $data = [
                'name'    => $json->name ?? 'Unknown',
                'os'      => $json->os ?? 'Unknown',
                'osname'      => $json->osname ?? 'Unknown',
                'architecture'      => $json->architecture ?? 'Unknown',
                'user'      => $json->user ?? 'Unknown',
                'ram'     => $json->ram ?? 'Unknown',
                'cpu'     => $json->cpu ?? 'Unknown',
                'serial'  => $json->serial ?? 'Unknown',
                'mac'     => $json->mac ?? 'Unknown',
                'ip'     => $json->ip ?? 'Unknown',
                'domaine'     => $json->domaine ?? 'Unknown',
                'windows_key'     => $json->windows_key ?? 'Unknown',
                'license_status'     => $json->license_status ?? 'Unknown',
                'uuid'     => $json->license_status ?? 'Unknown',


            ];
    }
    $pc = $this->Pc_model->get_Infos_cons_main($data['name']);
    
        if (empty($pc)) {
            $this->Pc_model->insert_pc($data);
        }
        else {
            $this->Pc_model->update_pc($data);
        }
    }
}