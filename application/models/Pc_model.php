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

    public function get_stat_win(){
        $this->db->like('OSNAME', 'Windows'); // Filtrage avec LIKE
        $this->db->from('hardware'); // Table à interroger
        return $this->db->count_all_results(); // Retourner le nombre de résultats
    }
    public function get_stat_unix(){
        $this->db->like('OSNAME', 'unix'); // Filtrage avec LIKE
        $this->db->from('hardware'); // Table à interroger
        return $this->db->count_all_results(); // Retourner le nombre de résultats
    }

    public function get_stat_android(){
        $this->db->like('OSNAME', 'android'); // Filtrage avec LIKE
        $this->db->from('hardware'); // Table à interroger
        return $this->db->count_all_results(); // Retourner le nombre de résultats
    }


    public function get_Infos_cons_network($nomPc){
        $this->db->select("*");
        $this->db->from('networks');
        $this->db->join('hardware', 'hardware.ID = networks.HARDWARE_ID');
        $this->db->where('hardware.NAME', $nomPc);
        return $this->db->get()->result_array();
    }
    public function get_Infos_cons_software($nomPc){
        $this->db->select("software_name.NAME, VERSION, PUBLISHER, COMMENTS, LANGUAGE, INSTALLDATE ");
        $this->db->from('software');
        $this->db->join('hardware', 'hardware.ID = software.HARDWARE_ID');
        $this->db->join('software_name', 'software_name.ID = software.NAME_ID');
        $this->db->join('software_publisher', 'software_publisher.ID = software.PUBLISHER_ID');
        $this->db->join('software_version', 'software_version.ID = software.VERSION_ID');
        $this->db->where('hardware.NAME', $nomPc);
        return $this->db->get()->result_array();
    }
}