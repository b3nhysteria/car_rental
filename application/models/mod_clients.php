<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mod_cars
 *
 * @author b3nhysteria
 */
class mod_clients extends GlobalObject {

    public function __construct() {
        parent::GlobalObject();
    }

    public function saveClient($obj) {
        $this->table_name = "clients";
        return $this->save($obj, "client_id");
    }

    public function getClient($client_id) {
        $temp = $this->bindingQuery("select * from clients where client_id = ?", array($client_id))->row();
        return $temp;
    }
    
    public function getClients() {
        $temp = $this->bindingQuery("select client_id as id ,name,gender from clients")->result();
        return $temp;
    }

    public function updateClient($obj,$client_id) {
        $this->table_name = "clients";
        $param = array();
        $param['client_id'] = $client_id;
        $this->updateRecord($param, $obj);
    }
    
    public function deleteClient($client_id){
        $this->bindingQuery("delete from clients where client_id = ?", array($client_id));
    }

    public function checkingPlate($obj, $state = 0) {
        $sql = "select car_id from cars where plate = ?";
        $param = array();
        $param[] = $obj;
        if ($state > 0) {
            $sql .= " and car_id != ?";
            $param[] = $state;
        }
        $temp = $this->bindingQuery($sql, $param)->result();
        return count($temp);
    }

}
