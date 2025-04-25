<?php
class Support_model extends CI_Model {
/** 
 * Classe d'accès aux données 
 * Hérite de la classe CI_Model
 */

    public function __construct()
    {
        $this->load->database();
    }
    /** 
     * Méthode pour la création d'un ticket de support
     * @params $data => Les données du ticket
     * @return bool => true si le ticket a été créé, false sinon
     */
    public function create_ticket($data){
        $this->db->insert('tickets', $data);
        return $this->db->affected_rows() > 0;
    }
}