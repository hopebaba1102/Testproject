<?php
class ApplicationModel extends CI_Model {
    public function getAllApplications() {
        $query = $this->db->get('table_applications');
        return $query->result();
    }

    public function addApplicationData($jsonData) {
        $this->db->set('application', $jsonData); // Replace 'column_name' with the actual column name in the 'table_applications' table
        $this->db->insert('table_applications');
        return $this->db->insert_id();
    }

    public function getApplicationData($applicationId) {
        $this->db->where('id', $applicationId);
        $query = $this->db->get('table_applications');
        return $query->row();
    }

    public function updateApplicationData($applicationId, $jsonData) {
        $data = array(
            'application' => $jsonData
        );
        $this->db->where('id', $applicationId);
        $this->db->update('table_applications', $data);
        return true;
    }

    public function deleteApplication($applicationId) {
        $this->db->where('id', $applicationId);
        $this->db->delete('table_applications');
        return true;
    }
    
    public function deleteAllApplications() {
        $this->db->empty_table('table_applications');
        return true;
    }
}