<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cars
 *
 * @author b3nhysteria
 */
class cars extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("mod_cars");
    }

    public function index() {
        $this->benchmark->mark('start_search');
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "POST") {
            if (count($_POST) > 0) {
                $obj = $this->getParameter();
                if ($this->checkingInput($obj)) {
                    if ($this->checkingYear($obj["year"])) {
                        if ($this->checkingPlate($obj["plate"])) {
                            $result = $this->mod_cars->saveCars($obj);
                            echo $this->output($obj, $result);
                        } else {
                            echo $this->output($obj, "Plate must be unique");
                        }
                    } else {
                        echo $this->output($obj, "Year can't be future");
                    }
                } else {
                    echo $this->output($obj, "All Field Required");
                }
            } else {
                echo $this->output("", "nothing to do.");
            }
        } else {
            if ($method == "GET") {
                $result = $this->mod_cars->getCars();
                echo $this->output("", $result);
            }
        }
    }

    public function edit() {
        $this->benchmark->mark('start_search');
        $carId = $this->uri->segment(2);
        $obj = $this->mod_cars->getCar($carId);
        $method = $_SERVER['REQUEST_METHOD'];
        $newObj = $this->getParameter();
        if ($method == "PUT") {
            if ($obj) {
                if ($this->checkingInput($newObj)) {
                    if ($this->checkingYear($newObj["year"])) {
                        if ($this->checkingPlate($newObj["plate"], $obj->car_id)) {
                            $this->mod_cars->updateCar($newObj, $carId);
                        } else {
                            echo $this->output($newObj, "Plate must be unique");
                        }
                    } else {
                        echo $this->output($newObj, "Year can't be future");
                    }
                } else {
                    echo $this->output($newObj, "All Field Required");
                }
            } else {
                echo $this->output("", "Car Not Found.");
            }
        } else {
            if ($method == "DELETE") {
                $this->mod_cars->deleteCar($carId);
            } else {
                echo $this->output("", "nothing to do.");
            }
        }
    }

    public function checkingYear($year) {
        return (date("Y") < $year) ? 0 : 1;
    }

    public function checkingPlate($plate, $state = 0) {
        $temp = $this->mod_cars->checkingPlate($plate, $state);
        return ($temp > 0) ? 0 : 1;
    }

}
