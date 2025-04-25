<?php
class Notes_model extends CI_Model
{
    public function get_notes_by_pc($pc_name)
    {
        $this->db->select("NOTE");
        $this->db->from('hardware');
        $this->db->where('NAME', $pc_name);
        return $this->db->get()->result_array();
    }

    public function insert_note_for_pc($pc_name, $note)
    {
        $data = [
            'NOTE'    => $note,
        ];
        $this->db->where('NAME', $pc_name);
        $this->db->update('hardware', $data);
    }
}