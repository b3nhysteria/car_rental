<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of rentals
 *
 * @author b3nhysteria
 */
class rentals extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("mod_clients");
        $this->load->model("mod_cars");
        $this->load->model("mod_rentals");
    }

    public function index() {
        $this->benchmark->mark('start_search');
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "POST") {
            if (count($_POST) > 0) {
                $obj = $this->getParameter();
                if ($this->checkingInput($obj)) {
                    $car = $this->mod_cars->getCar($obj["car-id"]);
                    $client = $this->mod_clients->getClient($obj["client-id"]);
                    if ($car && $client) {
                        if ($this->checkRange($obj["date-from"], $obj["date-to"])) {
                            if ($this->checkingDateRent($obj["date-from"], $obj["date-to"]) <= 3) {
                                if ($this->mod_rentals->checkingAva($obj)) {
                                    if ($this->mod_rentals->checkingAva($obj, 0)) {
                                        $result = $this->mod_rentals->saveRental($obj);
                                        echo $this->output($obj, $result);
                                    } else {
                                        echo $this->output($obj, "Client already booking/rent car");
                                    }
                                } else {
                                    echo $this->output($obj, "Car not available");
                                }
                            } else {
                                echo $this->output($obj, "Max rent 3 days.");
                            }
                        } else {
                            echo $this->output($obj, "Out Of Range Date rent.");
                        }
                    } else {
                        echo $this->output($obj, "Please check your car id and client id.");
                    }
                } else {
                    echo $this->output($obj, "All Field Required");
                }
            } else {
                echo $this->output("", "nothing to do.");
            }
        } else {
            if ($method == "GET") {
                $result = $this->mod_rentals->getRentals();
                echo $this->output("", $result);
            }
        }
    }

    public function edit() {
        $this->benchmark->mark('start_search');
        $rentalId = $this->uri->segment(2);
        $obj = $this->mod_rentals->getRental($rentalId);
        $method = $_SERVER['REQUEST_METHOD'];
        $newObj = $this->getParameter();
        if ($method == "PUT") {
            if ($obj) {
                if ($this->checkingInput($newObj)) {
                    $car = $this->mod_cars->getCar($newObj["car-id"]);
                    $client = $this->mod_clients->getClient($newObj["client-id"]);
                    if ($car && $client) {
                        if ($this->checkRange($newObj["date-from"], $newObj["date-to"])) {
                            if ($this->checkingDateRent($newObj["date-from"], $newObj["date-to"]) <= 3) {
                                if ($this->mod_rentals->checkingAva($newObj, 1, $rentalId)) {
                                    if ($this->mod_rentals->checkingAva($newObj, 0, $rentalId)) {
                                        $result = $this->mod_rentals->updateRental($newObj, $rentalId);
                                        echo $this->output($newObj, $result);
                                    } else {
                                        echo $this->output($newObj, "Client already booking/rent car");
                                    }
                                } else {
                                    echo $this->output($newObj, "Car not available");
                                }
                            } else {
                                echo $this->output($newObj, "Max rent 3 days.");
                            }
                        } else {
                            echo $this->output($newObj, "Out Of Range Date rent.");
                        }
                    } else {
                        echo $this->output($newObj, "Please check your car id and client id.");
                    }
                } else {
                    echo $this->output($newObj, "All Field Required");
                }
            } else {
                echo $this->output("", "Rental Not Found.");
            }
        } else {
            if ($method == "DELETE") {
                $this->mod_rentals->deleteRental($rentalId);
            } else {
                echo $this->output("", "nothing to do.");
            }
        }
    }

    public function checkingDateRent($start, $end) {
        return $this->mod_common->get_time_difference($start, $end)["days"];
    }

    public function checkRange($start, $end) {
        $start = date($start);
        $end = date($end);
        $cs = date('Y-m-d', strtotime("+1 days"));
        $ce = date('Y-m-d', strtotime("+7 days"));
        if ($start >= $cs && $start <= $ce) {
            if ($end >= $start && $end <= $ce) {
                return 1;
            }
        }
        return 0;
    }

}
