<?php
class Pc_model extends CI_Model {
/** 
 * Classe d'accès aux données 
 * Hérite de la classe CI_Model
 */

    public function __construct()
    {
        $this->load->database();
    }
    /**
     * Méthode permettant de récupérer les informations pour l'affichage des informations général d'un PC
     * @params $nomPc => Le nom du pc
     * @return Tableau avec les informations du pc
     */
    public function get_Infos_cons_main($nomPc){
        $this->db->select("*");
        $this->db->from('hardware');
        $this->db->where('NAME', $nomPc);
        return $this->db->get()->result_array();
    }

    public function get_Infos_soft_main($id){
        $this->db->select("*");
        $this->db->from('software');
        $this->db->where('HARDWARE_ID', $id);
        return $this->db->get()->result_array();
    }

    public function get_stat_win(){
        $this->db->select("*");
        $this->db->like('OSNAME', 'Windows'); 
        $this->db->from('hardware'); 
        return $this->db->count_all_results(); 
    }
    public function get_stat_unix(){
        $this->db->select("*");
        $this->db->like('OSNAME', 'unix');  
        $this->db->from('hardware'); 
        return $this->db->count_all_results(); 
    }

    public function get_stat_android(){
        $this->db->select("*");
        $this->db->like('OSNAME', 'android'); 
        $this->db->from('hardware'); 
        return $this->db->count_all_results(); 
    }

    public function get_stat_macos(){
        $this->db->select("*");
        $this->db->like('OSNAME', 'macos'); 
        $this->db->from('hardware'); 
        return $this->db->count_all_results(); 
    }

    public function get_lst_pc(){
        $this->db->select('NAME, LASTDATE, LASTCOME');
        $this->db->from('hardware'); 
        return $this->db->get()->result_array();
    }

    public function insert_pc($data){
        $insert_data = [
            'NAME'     => $data['name'],
            'OSNAME'       => $data['os_name'],
            'OSVERSION'   => $data['os_version'],
            'ARCHITECTURE'   => $data['architecture'],
            'USER'   => $data['user'],
            'MEMORY'      => $data['ram'],
            'CPU'      => $data['cpu'],
            'SERIAL'   => $data['serial'],
            'MAC'      => $data['mac'],
            'IPADDR'      => $data['ip'],
            'DOMAIN'      => $data['domaine'],
            'WINPRODKEY'      => $data['windows_key'],
            'licensestatus'      => $data['license_status'],
            'UUID'      => $data['uuid'],
           
        ];
        $this->db->insert('hardware', $insert_data);
    }

    public function update_pc($name, $data){
        $insert_data = [
            'NAME'     => $data['name'],
            'OSNAME'       => $data['os_name'],
            'OSVERSION'   => $data['os_version'],
            'ARCHITECTURE'   => $data['architecture'],
            'USER'   => $data['user'],
            'MEMORY'      => $data['ram'],
            'CPU'      => $data['cpu'],
            'SERIAL'   => $data['serial'],
            'MAC'      => $data['mac'],
            'IPADDR'      => $data['ip'],
            'DOMAIN'      => $data['domaine'],
            'WINPRODKEY'      => $data['windows_key'],
            'licensestatus'      => $data['license_status'],
            'UUID'      => $data['uuid'],

        ];
        $this->db->where('NAME', $name);
        $this->db->update('hardware', $insert_data);

    }

    public function insert_note($data){
        $insert_data = [
            'NAME'     => $data['name'],
            'OS'       => $data['os'],
            'OSNAME'   => $data['osname'],
            'ARCHITECTURE'   => $data['architecture'],
            'USER'   => $data['user'],
            'RAM'      => $data['ram'],
            'CPU'      => $data['cpu'],
            'SERIAL'   => $data['serial'],
            'MAC'      => $data['mac'],
            'IP'      => $data['ip'],
            'DOMAIN'      => $data['domaine'],
            'WINDOWSKEY'      => $data['windows_key'],
            'LICENSESTATUS'      => $data['license_status'],
            'UUID'      => $data['uuid'],
           
        ];
        $this->db->insert('hardware', $insert_data);
    }

    public function update_note($name, $data){
        $insert_data = [
            'NAME'     => $data['name'],
            'OS'       => $data['os'],
            'OSNAME'   => $data['osname'],
            'ARCHITECTURE'   => $data['architecture'],
            'USER'   => $data['user'],
            'RAM'      => $data['ram'],
            'CPU'      => $data['cpu'],
            'SERIAL'   => $data['serial'],
            'MAC'      => $data['mac'],
            'IP'      => $data['ip'],
            'DOMAIN'      => $data['domaine'],
            'WINDOWSKEY'      => $data['windows_key'],
            'LICENSESTATUS'      => $data['license_status'],
            'UUID'      => $data['uuid'],

        ];
        $this->db->where('NAME', $name);
        $this->db->update('hardware', $insert_data);

    }


    




    // public function get_Infos_cons_network($nomPc){
    //     $this->db->select("*");
    //     $this->db->from('networks');
    //     $this->db->join('hardware', 'hardware.ID = networks.HARDWARE_ID');
    //     $this->db->where('hardware.NAME', $nomPc);
    //     return $this->db->get()->result_array();
    // }
    // public function get_Infos_cons_software($nomPc){
    //     $this->db->select("software_name.NAME, VERSION, PUBLISHER, COMMENTS, LANGUAGE, INSTALLDATE ");
    //     $this->db->from('software');
    //     $this->db->join('hardware', 'hardware.ID = software.HARDWARE_ID');
    //     $this->db->join('software_name', 'software_name.ID = software.NAME_ID');
    //     $this->db->join('software_publisher', 'software_publisher.ID = software.PUBLISHER_ID');
    //     $this->db->join('software_version', 'software_version.ID = software.VERSION_ID');
    //     $this->db->where('hardware.NAME', $nomPc);
    //     return $this->db->get()->result_array();
    // }
}