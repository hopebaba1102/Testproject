<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MysqlAPI extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }
	public function index()
	{
        echo "welcome to the MysqlAPI";
	}
    public function execQuery(){
        function filterError($field){
            return $field['msg'] != "";
        }
        $errorArray = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $jsonPayload = $this->input->raw_input_stream;
            $payload = json_decode($jsonPayload);
            $sqlValue = $payload->sqlValue;
            $otherDb = $this->load->database('target_db', TRUE);
            // Delete all tables
            $otherDb->query("SET FOREIGN_KEY_CHECKS = 0"); // Disable foreign key checks
            $tables = $otherDb->list_tables(); // Get all tables
     
            foreach ($tables as $table) {
                $otherDb->query('DROP TABLE IF EXISTS ' . $table); 
            }
            $otherDb->query("SET FOREIGN_KEY_CHECKS = 1");

            $queryChunks = explode(";", $sqlValue);
            $executionResults = array();
            $trueCount = 0;
            $falseCount = 0;
            foreach ($queryChunks as $queryChunk) {
                $queryChunk = trim($queryChunk);

                if (!empty($queryChunk)) {
                    /*try {
                        $query = $otherDb->query($queryChunk);
                        $executionResults[] = array('query' => $queryChunk, 'result' => 'success');
                        $trueCount++;
                    } catch (Exception $e) {
                        $e->ignore_user_abort(true);
                        $executionResults[] = array('query' => $queryChunk, 'result' => 'failed');
                        $falseCount++;
                    }*/
                    $query = $otherDb->query($queryChunk);
            
                    if ($query) {
                        $executionResults[] = array('query' => $queryChunk, 'result' => 'success');
                        $trueCount++;
                    } else {
                        $executionResults[] = array('query' => $queryChunk, 'result' => 'failed');
                        $falseCount++;
                    }
                }
            }
            $returnValue = array('msg' => 'success', 'successCount' => $trueCount, 'failedCount' => $falseCount, 'executionResults' => $executionResults);
            echo json_encode($returnValue);
        }else if($_SERVER['REQUEST_METHOD'] == 'GET'){
            $returnValue = array('msg'=>'success', 'server'=>'running...');
            echo json_encode($returnValue);
        }
    
    }

    public function dumpToSQL(){
        $sqlValue = '';
        $otherDb = $this->load->database('target_db', TRUE);
        $tables = $otherDb->list_tables();
        $sqlValue = 'SET FOREIGN_KEY_CHECKS = 0;';
        foreach ($tables as $table) {
            $query = $otherDb->query('SHOW CREATE TABLE ' . $table);
            $row = $query->row_array();
            $createTableSQL = $row['Create Table'];
            
        //    $createTableSQL = preg_replace('/,\s*CONSTRAINT\s*.*?FOREIGN KEY\s*\(.*?\)\s*REFERENCES\s*.*?\s*\(.*?\)\s*\)/', '', $createTableSQL);            
        //    $createTableSQL = preg_replace('/,\s*PRIMARY KEY\s*\(.*?\)/', '', $createTableSQL);
        //    $createTableSQL = preg_replace('/,\s*KEY\s*.*?,/', '', $createTableSQL);
            $createTableSQL = preg_replace('/\sENGINE\s?=.*?DEFAULT CHARSET\s?=.*?COLLATE\s?=.*?(\s|$)/', ' ', $createTableSQL);
            
            $sqlValue .= str_replace("\n", " ", $createTableSQL);
            $sqlValue .= ";";
        }
        $sqlValue .= 'SET FOREIGN_KEY_CHECKS = 1;';

        $returnValue = array('msg'=>'success', 'dumpSQL'=>$sqlValue);
        echo json_encode($returnValue);
    }
}