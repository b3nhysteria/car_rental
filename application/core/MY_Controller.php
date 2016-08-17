<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class MY_Controller extends CI_Controller {

    function MY_Controller() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function getStringRequest() {
        return file_get_contents("php://input");
    }

    public function getObjectJson($string) {
        return json_decode($string);
    }

    function proper_parse_str($str) {
        $arr = array();
        $pairs = explode('&', $str);
        foreach ($pairs as $i) {
            list($name, $value) = explode('=', $i, 2);
            if (isset($arr[$name])) {
                if (is_array($arr[$name])) {
                    $arr[$name][] = $this->replaceString($value);
                } else {
                    $arr[$name] = array($arr[$name], $this->replaceString($value));
                }
            } else {
                $arr[$name] = $this->replaceString($value);
            }
        }
        return $arr;
    }

    public function replaceString($value) {
        return str_replace("+", " ", $value);
    }

    public function getParameter() {
        $obj = $_POST;
        if (count($obj) == 0) {
            $obj = $this->getStringRequest();
            $objJson = $this->getObjectJson($obj);
            if (strlen($objJson) > 0) {
                return $obj;
            } else {
                return $this->proper_parse_str($obj);
            }
        } else {
            return $obj;
        }
    }

    public function checkingInput($obj) {
        foreach ($obj as $key => $value) {
            if (strlen($value) == 0) {
                return 0;
            }
        }
        return 1;
    }

    public function output($param, $result) {
        $this->benchmark->mark('end_search');
        return json_encode(array(
            'status' => 200,
            'message' => 'success',
            'elapsed_time' => $this->benchmark->elapsed_time('start_search', 'end_search') . ' s',
            'parameter' => json_encode($param),
            'result' => json_encode($result)
        ));
    }

    public function setter() {
        $this->benchmark->mark('start_search');
        $param = new stdClass();
        $param->email = $this->input->get_post("email", true);
        $param->code = $this->input->get_post("code", true);
        $json_req_string = file_get_contents('php://input');
        $json_req_object = json_decode($json_req_string);
        if (strlen($param->email) == 0) {
            $param->email = $json_req_object->email;
        }
        if (strlen($param->code) == 0) {
            $param->code = $json_req_object->code;
        }
        $return = new stdClass();
        $return->message = "Please Check Your Account";
        $return->stat = 0;
        $result = new stdClass();
        $result->param = $param;
        $result->return = $return;
        return $result;
    }

}
