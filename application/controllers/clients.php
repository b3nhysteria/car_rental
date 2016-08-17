<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of clients
 *
 * @author b3nhysteria
 */
class clients extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("mod_clients");
    }

    public function index() {
        $this->benchmark->mark('start_search');
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "POST") {
            if (count($_POST) > 0) {
                $obj = $this->getParameter();
                if ($this->checkingInput($obj)) {
                    if ($this->checkingGender($obj["gender"])) {
                        $result = $this->mod_clients->saveClient($obj);
                        echo $this->output($obj, $result);
                    } else {
                        echo $this->output($obj, "Only male or female");
                    }
                } else {
                    echo $this->output($obj, "All Field Required");
                }
            } else {
                echo $this->output("", "nothing to do.");
            }
        } else {
            if ($method == "GET") {
                $result = $this->mod_clients->getClients();
                echo $this->output("", $result);
            }
        }
    }

    public function edit() {
        $this->benchmark->mark('start_search');
        $clientId = $this->uri->segment(2);
        $obj = $this->mod_clients->getClient($clientId);
        $method = $_SERVER['REQUEST_METHOD'];
        $newObj = $this->getParameter();
        if ($method == "PUT") {
            if ($obj) {
                if ($this->checkingInput($newObj)) {
                    if ($this->checkingGender($newObj["gender"])) {
                        $this->mod_clients->updateClient($newObj, $clientId);
                    } else {
                        echo $this->output($newObj, "Year can't be future");
                    }
                } else {
                    echo $this->output($newObj, "All Field Required");
                }
            } else {
                echo $this->output("", "Client Not Found.");
            }
        } else {
            if ($method == "DELETE") {
                $this->mod_clients->deleteClient($clientId);
            } else {
                echo $this->output("", "nothing to do.");
            }
        }
    }

    public function checkingGender($sex) {
        return ($sex == "male" || $sex == "female") ? 1 : 0;
    }

}
