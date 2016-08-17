<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of histories
 *
 * @author b3nhysteria
 */
class histories extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("mod_cars");
        $this->load->model("mod_clients");
        $this->load->model("mod_rentals");
    }

    public function car($id) {
        $this->benchmark->mark('start_search');
        $obj = $this->mod_cars->getCar($id);
        if ($obj) {
            $obj->histories = $this->mod_rentals->getHistoryCar($id, $this->getParam("month"));
            echo $this->output(array("car id", $id), $obj);
        } else {
            echo $this->output("", "Car Not Found.");
        }
    }

    public function client($id) {
        $this->benchmark->mark('start_search');
        $obj = $this->mod_clients->getClient($id);
        if ($obj) {
            $obj->histories = $this->mod_rentals->getHistoryClient($id);
            echo $this->output(array("client id", $id), $obj);
        } else {
            echo $this->output("", "Client Not Found.");
        }
    }

    public function rented() {
        $this->benchmark->mark('start_search');
        $obj=new stdClass();
        $date = $this->getParam("date");
        $obj->date = $date[0]."-".$date[1]."-".$date[2];
        $obj->histories = $this->mod_rentals->getRentedCar($obj->date);
        echo $this->output(array("date", $this->input->get("date")), $obj);
    }

    public function free() {
        $this->benchmark->mark('start_search');
        $obj=new stdClass();
        $date = $this->getParam("date");
        $obj->date = $date[0]."-".$date[1]."-".$date[2];
        $obj->free_cars = $this->mod_rentals->getFreeCar($obj->date);
        echo $this->output(array("date", $this->input->get("date")), $obj);
    }
    
    public function getParam($var) {
        $newObj = $this->input->get($var);
        $newObj = str_replace("{", "", $newObj);
        $newObj = str_replace("}", "", $newObj);
        $cs = explode("-", $newObj);
        return $cs;
    }

}
