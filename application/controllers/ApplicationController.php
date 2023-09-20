<?php
class ApplicationController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('ApplicationModel');
    }

    public function getAllApplications() {
        $jsonPayload = $this->input->raw_input_stream;
        $payload = json_decode($jsonPayload);
        $rows = $this->ApplicationModel->getAllApplications();
        // $row = $this->ApplicationModel->getApplicationData(20);
        
        if ($rows) {
            $returnValue = array('msg'=>'success', 'allApplicationsData'=>$rows );
        } else {
            $returnValue = array('msg'=>'success', 'allApplicationsData'=>[] );
        }
        echo json_encode($returnValue);
    }

    public function getApplication() {
        $jsonPayload = $this->input->raw_input_stream;
        $payload = json_decode($jsonPayload);
        $row = $this->ApplicationModel->getApplicationData($payload->id);
        // $row = $this->ApplicationModel->getApplicationData(20);
        
        if ($row) {
            $returnValue = array('msg'=>'success', 'applicationData'=>$row);
        } else {
            $returnValue = array('msg'=>'failed');
        }
        echo json_encode($returnValue);
    }

    public function addApplication() {
        $jsonPayload = $this->input->raw_input_stream;
        // $payload = json_decode($jsonPayload);
        $insertId = $this->ApplicationModel->addApplicationData($jsonPayload);
        
        if ($insertId) {
            $returnValue = array('msg'=>'success', 'applicationID'=>''.$insertId);
        } else {
            $returnValue = array('msg'=>'failed');
        }
        echo json_encode($returnValue);
    }

    public function saveApplication() {
        $jsonPayload = $this->input->raw_input_stream;
        $payload = json_decode($jsonPayload);
        $applicationJSON = array(
            'applicationJSON' => $payload->applicationJSON
        );
        $result = $this->ApplicationModel->updateApplicationData($payload->id, json_encode($applicationJSON));
        
        if ($result) {
            $returnValue = array('msg'=>'success');
        } else {
            $returnValue = array('msg'=>'failed');
        }
        echo json_encode($returnValue);
    }

    public function deleteApplication() {
        $jsonPayload = $this->input->raw_input_stream;
        $payload = json_decode($jsonPayload);
        $result = $this->ApplicationModel->deleteApplication($payload->id);
        if ($result) {
            $returnValue = array('msg'=>'success');
        } else {
            $returnValue = array('msg'=>'failed');
        }
        echo json_encode($returnValue);
    }

    public function deleteAllApplications() {
        $result = $this->ApplicationModel->deleteAllApplications();
        if ($result) {
            $returnValue = array('msg'=>'success');
        } else {
            $returnValue = array('msg'=>'failed');
        }
        echo json_encode($returnValue);
    }
}