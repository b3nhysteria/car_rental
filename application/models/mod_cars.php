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
class mod_cars extends GlobalObject {

    public function __construct() {
        parent::GlobalObject();
    }

    public function saveCars($obj) {
        $this->table_name = "cars";
        return $this->save($obj, "car_id");
    }

    public function getCar($car_id) {
        $temp = $this->bindingQuery("select * from cars where car_id = ?", array($car_id))->row();
        return $temp;
    }

    public function updateCar($obj, $car_id) {
        $this->table_name = "cars";
        $param = array();
        $param['car_id'] = $car_id;
        $this->updateRecord($param, $obj);
    }

    public function getCars() {
        $temp = $this->bindingQuery("select brand,type,year,color,plate from cars")->result();
        return $temp;
    }

    public function deleteCar($car_id) {
        $this->bindingQuery("delete from cars where car_id = ?", array($car_id));
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
