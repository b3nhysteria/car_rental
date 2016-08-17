<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mod_common
 *
 * @author myMine
 */
class mod_common extends GlobalObject {

    public function permalink_page_generator($str) {
        $permalink = strtolower($str);
        $tanda = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "-", "+", "=", "{", "}", "[", "]", "\\", "|", ":", ";", "\"", "'", ",", "<", ">", ".", "?", "/");
        foreach ($tanda as $t) {
            $permalink = str_replace($t, "", $permalink);
        }
        $permalink = str_replace(" ", "_", $permalink);
        $strsql = "select page_permalink from menu where page_permalink like ? order by menu_id desc limit 1";
        $rs = $this->mod_common->bindingQuery($strsql, array($permalink . "%"))->row();
        if ($rs) {
            $count = explode("-", $rs->page_permalink);
            $last = count($count) - 1;
            if (is_numeric($count[$last])) {
                $total = $count[$last] + 1;
                $permalink = $permalink . "_" . $total;
            } else {
                $total = $count[$last] + 2;
                $permalink = $permalink . "_" . $total;
            }
        }
        return $permalink;
    }

    public function permalink_generator($str) {
        return str_replace(" ", "_", strtolower($str));
    }

    public function permalink_job_generator($str) {
        $permalink = strtolower($str);
        $tanda = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "-", "+", "=", "{", "}", "[", "]", "\\", "|", ":", ";", "\"", "'", ",", "<", ">", ".", "?", "/");
        foreach ($tanda as $t) {
            $permalink = str_replace($t, "", $permalink);
        }
        $permalink = str_replace(" ", "_", $permalink);
        $strsql = "select job_permalink from job where job_permalink like ? order by job_id desc limit 1";
        $rs = $this->mod_common->bindingQuery($strsql, array($permalink . "%"))->row();
        if ($rs) {
            $count = explode("-", $rs->job_permalink);
            $last = count($count) - 1;
            if (is_numeric($count[$last])) {
                $total = $count[$last] + 1;
                $permalink = $permalink . "_" . $total;
            } else {
                $total = $count[$last] + 2;
                $permalink = $permalink . "_" . $total;
            }
        }
        return $permalink;
    }

    public function permalink_career_generator($str) {
        $permalink = strtolower($str);
        $tanda = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "-", "+", "=", "{", "}", "[", "]", "\\", "|", ":", ";", "\"", "'", ",", "<", ">", ".", "?", "/");
        foreach ($tanda as $t) {
            $permalink = str_replace($t, "", $permalink);
        }
        $permalink = str_replace(" ", "_", $permalink);
        $strsql = "select ca_adv_permalink from carier_advice where ca_adv_permalink like ? order by ca_adv desc limit 1";
        $rs = $this->mod_common->bindingQuery($strsql, array($permalink . "%"))->row();
        if ($rs) {
            $count = explode("-", $rs->ca_adv_permalink);
            $last = count($count) - 1;
            if (is_numeric($count[$last])) {
                $total = $count[$last] + 1;
                $permalink = $permalink . "_" . $total;
            } else {
                $total = $count[$last] + 2;
                $permalink = $permalink . "_" . $total;
            }
        }
        return $permalink;
    }

    public function permalink_ind_generator($str) {
        $permalink = strtolower($str);
        $tanda = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "-", "+", "=", "{", "}", "[", "]", "\\", "|", ":", ";", "\"", "'", ",", "<", ">", ".", "?", "/");
        foreach ($tanda as $t) {
            $permalink = str_replace($t, "", $permalink);
        }
        $permalink = str_replace(" ", "_", $permalink);
        $strsql = "select comp_permalink from company where comp_permalink like ? order by comp_id desc limit 1";
        $rs = $this->mod_common->bindingQuery($strsql, array($permalink . "%"))->row();
        if ($rs) {
            $count = explode("-", $rs->comp_permalink);
            $last = count($count) - 1;
            if (is_numeric($count[$last])) {
                $total = $count[$last] + 1;
                $permalink = $permalink . "_" . $total;
            } else {
                $total = $count[$last] + 2;
                $permalink = $permalink . "_" . $total;
            }
        }
        return $permalink;
    }

    public function __construct() {
        parent::GlobalObject();
//        $this->load->library("browser");
//        $this->load->helper('cookie');
//        $cookies = $this->input->cookie('mb_mail', TRUE);
//        if ($cookies) {
//            $this->session->set_userdata('login', $cookies->value);
//        }
    }

    public function checking_permalink($param) {
        $sql = "SELECT a.page_content,a.page_title,a.menu_title,a.page_cre_dt,b.`page_permalink` as p_permalink,b.`menu_title` as p_title FROM menu a INNER JOIN menu b ON a.`men_menu_id` = b.`menu_id` WHERE a.page_permalink = ?";
        return $this->bindingQuery($sql, array(strtolower($param)))->row();
    }

    public function checking_scpermalink($param) {
        $sql = "SELECT a.ca_adv_title,a.ca_adv_content,a.ca_adv_desc from carier_advice a WHERE a.ca_adv_permalink = ?";
        return $this->bindingQuery($sql, array(strtolower($param)))->row();
    }

    public function checking_ipermalink($param) {
        $sql = "SELECT a.industries_name,a.industries_content from industries a WHERE a.industries_permalink = ?";
        return $this->bindingQuery($sql, array(strtolower($param)))->row();
    }

    public function checking_cpermalink($param) {
        $sql = "SELECT a.comp_name,a.comp_content from company a WHERE a.comp_permalink = ?";
        return $this->bindingQuery($sql, array(strtolower($param)))->row();
    }

    public function checking_jpermalink($param) {
        $sql = "SELECT
                `job_id`,
                (SELECT b.category_name FROM category b WHERE b.category_id = j.`category_id`) AS category,
                (SELECT comp_name FROM company c WHERE c.comp_id = j.`comp_id`) AS company,
                (select region_name from region r where r.region_id = j.region_id ) as region_name,
                `job_type`,
                `job_cre_dt`,
                `job_name`,
                `job_carier`,
                `job_exp_dt`,
                `job_salary`,
                `job_intro`,
                `job_req`,
                `job_res`,
                `job_permalink`,
                `job_cre_by`
              FROM `job` j where job_permalink = ?";
        return $this->bindingQuery($sql, array(strtolower($param)))->row();
    }

    public function toAscii($str, $replace = array(), $delimiter = '-') {
        setlocale(LC_ALL, 'en_US.UTF8');
        if (!empty($replace)) {
            $str = str_replace((array) $replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }

    public function export_invalid_mail($sql, $param, $filename) {
        $this->load->dbutil();
        $delimiter = ",";
        $newline = "\r\n";
        $result = $this->db->query($sql);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        $this->force_download($filename, $data);
    }

    function force_download($filename = '', $data = '') {
        if ($filename == '' OR $data == '') {
            return FALSE;
        }

        if (FALSE === strpos($filename, '.')) {
            return FALSE;
        }

        $x = explode('.', $filename);
        $extension = end($x);

        @include(APPPATH . 'config/mimes' . EXT);

        if (!isset($mimes[$extension])) {
            $mime = 'application/octet-stream';
        } else {
            $mime = (is_array($mimes[$extension])) ? $mimes[$extension][0] : $mimes[$extension];
        }

        if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
            header('Content-Type: "' . $mime . '"');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header("Content-Transfer-Encoding: binary");
            header('Pragma: public');
            header("Content-Length: " . strlen($data));
        } else {
            header('Content-Type: "' . $mime . '"');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header("Content-Transfer-Encoding: binary");
            header('Expires: 0');
            header('Pragma: no-cache');
            header("Content-Length: " . strlen($data));
        }

        exit($data);
    }

    function isInteger($input) {
        return preg_match('@^[0-9]+$@', $input) === 1;
    }

    public function get_promo() {
        $sql = 'select promo_banner_pic,promo_permalink,promo_id from ms_promo where now() between promo_start_offer and promo_end_offer order by promo_id desc limit 2';
        return $this->bindingQuery($sql, array())->result();
    }

    public function removeListSeassion() {
        $this->session->set_userdata('step', 0);
        $session = array('item_cart' => '', 'invoice_header' => '');
        $this->session->unset_userdata('item_cart');
        $this->session->unset_userdata('invoice_header');
        $this->session->unset_userdata($session);
    }

    public function get_list_child_cat($permalink) {
        $sql = 'select 
                category_id,
                category_name,
                category_permalink
                ,
                (select count(1) from ms_item_dt dt 
                where exists(
                select 1 from item_category ic where ic.item_id = dt.item_id and ic.category_id = master.category_id
                )) as tot_item 
                from ms_category master 
                where exists(select 1 from ms_category mcd where mcd.category_permalink = ? and mcd.category_id = master.ms__category_id)';
        $row = $this->bindingQuery($sql, array($permalink))->result();
        if ($row) {
            return $row;
        }
        return NULL;
    }

    public function getter_cookie() {
        $his = $this->session->userdata(base64_encode('his'));
        $cookie = $this->input->cookie(base64_encode('his'));
        if ($his) {
            if ($cookie)
                $cookie = json_decode($cookie);
            if (is_array($cookie)) {
                $data = array();
                $his_data = json_decode($his);
                $isIn = TRUE;
                for ($i = 0; $i < count($cookie); $i++) {
                    if ($cookie[$i]->item_dt_id == $his_data->item_dt_id) {
                        $isIn = FALSE;
                    }
                }
                if ($isIn)
                    array_push($data, $his_data);
                foreach ($cookie as $value) {
                    array_push($data, $value);
                }
                return $data;
            } else {
                $data = array();
                $temp = json_decode($his);
                if (is_array($temp)) {
                    foreach ($temp as $value) {
                        $data[] = $value;
                    }
                } else {
                    $data[] = $temp;
                }
                if ($cookie)
                    $data[] = $cookie;
                return $data;
            }
        } else
            return json_decode($cookie);
    }

    public function setter_cookie($data) {
        $cookie = $this->input->cookie(base64_encode('his'), TRUE);
        $data_array = array();
        $data_array[] = $data;
        $cookie_data = array(
            'name' => base64_encode('his'),
            'expire' => (86400 * 7)
        );
        if ($cookie) {
            $values = json_decode($cookie);
            if ($values) {
                if (is_array($values)) {
                    for ($i = 0; $i < count($values); $i++) {
                        if ($values[$i]->item_dt_id == $data->item_dt_id) {
                            unset($values[$i]);
                        }
                    }
                }
            }
            if (is_array($values)) {
                $temp = array();
                foreach ($values as $value) {
                    $data_array[] = $value;
                    if (count($data_array) == 4)
                        break;
                }
            } else {
                if ($data->item_dt_id != $values->item_dt_id)
                    $data_array[] = $values;
            }
        }
        $cookie_data['value'] = json_encode($data_array);
        $this->input->set_cookie($cookie_data);
        $this->session->set_userdata(base64_encode('his'), json_encode($data));
    }

    public function index() {
        
    }

    public function get_keyword() {
        $refer = parse_url($_SERVER['HTTP_REFERER']);
        $host = $refer['host'];
        $refer = @$refer['query'];

        if (strstr($host, 'google')) {
//do google stuff  
            $match = preg_match('/&q=([a-zA-Z0-9+-]+)/', $refer, $output);
            $querystring = $output[0];
            $querystring = str_replace('&q=', '', $querystring);
            $keywords = explode('+', $querystring);
            return $keywords;
        } elseif (strstr($host, 'yahoo')) {
//do yahoo stuff  
            $match = preg_match('/p=([a-zA-Z0-9+-]+)/', $refer, $output);
            $querystring = $output[0];
            $querystring = str_replace('p=', '', $querystring);
            $keywords = explode('+', $querystring);
            return $keywords;
        } elseif (strstr($host, 'msn')) {
//do msn stuff  
            $match = preg_match('/q=([a-zA-Z0-9+-]+)/', $refer, $output);
            $querystring = $output[0];
            $querystring = str_replace('q=', '', $querystring);
            $keywords = explode('+', $querystring);
            return $keywords;
        } else {
//else, who cares  
            return false;
        }
    }

    function try_blacklist($phrase, $who, $what) {
        $sql = "select count(1) as count_data from black_list where user = ? and type = ? and words =?";
        $count = $this->bindingQuery($sql, array($who, $what, $phrase))->result();
        return $count[0]->count_data;
    }

    function check_email($email) {
        if (!preg_match("/^[a-z0-9_.-]+@[a-z0-9_-]+\.[a-z0-9.]+/", $email))
            return 1;
        return 0;
    }

    function array_to_text($data) {
        $log = '';
        foreach ($data as $name => $value) {
            $log .= $name . ' : ' . htmlspecialchars(strip_tags($value)) . '<br/>';
        }
        return $log;
    }

    public function checking_admin($admin) {
        $problem = '';
        $this->set_table_name('adm_master');
        $this->set_select('adm_id, adm_name, role_id, adm_email');
        $param = array('adm_username' => $admin->username,
            'adm_password' => $admin->password);

        $result = $this->getWhere($param);
        $this->mod_common->write($this->db->last_query(), 0, 'checking admin', 'm');
        $personLoggin = NULL;
        $data = NULL;
        $param = NULL;
        if ($result != NULL) {
            $this->set_table_name('adm_master');
            $personLoggin->id = $result[0]->adm_id;
            $personLoggin->name = $result[0]->adm_name;
            $personLoggin->email = $result[0]->adm_email;
            $personLoggin->role = $result[0]->role_id;
            $param = array(
                'adm_id' => $personLoggin->id
            );
            $data = array(
                'adm_lastaccess' => $this->mod_common->getTimeDB()->now_time
            );
            $this->updateRecord($param, $data);
            $this->act_log($personLoggin->id, 'Login');
        } else {
            $problem = 'Username/password salah.';
        }
        return array('problem' => $problem, 'person' => $personLoggin);
    }

    function cheking_usr_pass($txt) {
        if (!preg_match("/^[@;.a-z0-9]+([\\s]{1}[@;.a-z0-9]|[@;.a-z0-9])+$/i", $txt))
            return 1;
        return 0;
    }

    function checking_phone($iphone) {
        if (!preg_match(" /^([\+][0-9]{1,3}[\ \.\-])?([\(]{1}[0-9]{2,6}[\)])?([0-9\ \.\-\/]{3,20})((x|ext|extension)[\ ]?[0-9]{1,4})?$/", $iphone))
            return 1;
        return 0;
    }

    function get_network() {
        $proxy = 0;
        if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        elseif (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
            $proxy = 1;
        } elseif (getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        elseif ($_SERVER[REMOTE_ADDR])
            $ip = $_SERVER[REMOTE_ADDR];
        else
            $ip = 'UNKNOWN';
        $result->ip = $ip;
        $result->proxy = $proxy;
        return $result;
    }

    public function getTimeDB() {
        $this->load->database('default');
        return $this->db->query("SELECT NOW() as now_time ")->row()->now_time;
    }

    public function generate_code($type, $table, $field_code) {
        $lentype = strlen($type);
        $monthReq = date('m');
        $yearReq = date('Y');
        $strsql = "select max(" . $field_code . ") as maxid from " . $table . " where month(trans_date) = " . intval($monthReq) . " and year(trans_date) = " . intval($yearReq) . "";
        $rs = $this->bindingQuery($strsql, array())->row();
        $reqID = false;
        $yearReq = substr($yearReq, 2, 2);

        if ($rs) {
            // start ID
            $monthDB = substr($rs->maxid, $lentype + 2, 2);
            $yearDB = substr($rs->maxid, $lentype, 2);
            $noDB = intval(substr($rs->maxid, $lentype + 4, 5));
            $noNow = substr("0000" . ($noDB + 1), -5);
            if ($monthReq == $monthDB and $yearReq == $yearDB) {
                $reqID = true;
            }
        }


        if ($reqID) {
            $transcode = $type . $yearReq . $monthReq . $noNow;
        } else {
            $transcode = $type . $yearReq . $monthReq . "00001";
        }

        return $transcode;
    }

    function write($table, $user_id, $message, $type) {
        $logging = NULL;
        $logging->log_date = $this->getTimeDB()->now_time;
        $logging->log_table = $table;
        $logging->log_user = $user_id;
        $logging->log_message = $message;
        $logging->log_type = $type;
        $this->set_table_name('logging_information');
        $this->save($logging);
    }

    function act_log($adm_id, $desc, $old_value = '', $new_value = '') {
        $adm_act->adm_id = $adm_id;
        $adm_act->adm_act_log_desc = $desc;
        $adm_act->adm_act_log_page = current_url();
        $adm_act->adm_act_log_new_value = $new_value;
        $adm_act->adm_act_log_old_value = $old_value;
        $adm_act->adm_act_log_time = $this->getTimeDB()->now_time;
        $adm_act->adm_act_log_ip = $this->browser->getRealIpAddr();
        $adm_act->adm_act_log_useragent = $this->browser->getBrowser() . ' ' . $this->browser->getVersion();
        $this->set_table_name('adm_activity_log');
        $this->save($adm_act);
    }

    public function valus_yahoo() {
        $value = YahooFinanceConverter::yahoo_convert(1, 'USD', 'IDR');
        return (($value != 0) ? $value : 0);
    }

    function get_time_difference($start, $end) {
        $uts['start'] = strtotime($start);
        $uts['end'] = strtotime($end);
        if ($uts['start'] !== -1 && $uts['end'] !== -1) {
            if ($uts['end'] >= $uts['start']) {
                $diff = $uts['end'] - $uts['start'];
                $days = intval((floor($diff / 86400)));
                if ($days)
                    $diff = $diff % 86400;
                $hours = intval((floor($diff / 3600)));
                if ($hours)
                    $diff = $diff % 3600;
                $minutes = intval((floor($diff / 60)));
                if ($minutes)
                    $diff = $diff % 60;
                return( array('days' => $days, 'hours' => $hours, 'minutes' => $minutes, 'seconds' => $diff) );
            }
            else {
                echo "Ending date/time is earlier than the start date/time";
                exit;
            }
        } else {
            echo "Invalid date/time data detected";
            exit;
        }
        return 0;
    }

    public function checking_referer($reg_url, $referer) {
        $list_url = explode('/', $referer);
        $url = ($list_url[1] != "") ? $list_url[1] : $list_url[2];
        $register_url = explode('/', $reg_url);
        $reg_url = "";
        if (strtolower($url) == "localhost" || strtolower($url) == "127.0.0.1")
            return TRUE;
        foreach ($register_url as $value) {
            if ($value != "http:" && $value != "https:" && $value != "" && strlen($value) > 1) {
                $reg_url = $value;
                break;
            }
        }
        return ($reg_url == $url) ? FALSE : TRUE;
    }

    public function getCurrentUrl() {
        $url = (@$_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
        $url .= (@$_SERVER['SERVER_PORT'] != '80') ? $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'] : $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        return $url;
    }

    public function get_month_name($month) {
        switch ($month) {
            case 1;
                return "Januari";
            case 2;
                return "Februari";
            case 3;
                return "Maret";
            case 4;
                return "April";
            case 5;
                return "Mei";
            case 6;
                return "Juni";
            case 7;
                return "Juli";
            case 8;
                return "Agustus";
            case 9;
                return "September";
            case 10;
                return "Oktober";
            case 11;
                return "Nopember";
            case 12;
                return "Desember";
        }
    }

    public function get_list_day_month() {
        $query = "SELECT extract(month from NOW()) as month_number, extract(year from NOW()) as year, DATE_PART('days', DATE_TRUNC('month', NOW()) + '1 MONTH'::INTERVAL - DATE_TRUNC('month', NOW())) as total_day";
        $result = $this->db->query($query)->row();
        return $result;
    }

    public function create_cookies($username, $password, $who) {
        $a = "a~" . $this->encrypt_code($username) . "&b~" . $this->encrypt_code($password) . "&c~" . $this->encrypt_code($who);
        $p = $this->encrypt_code($a);
        $this->load->helper('cookie');
        $cookie = array(
            'name' => 'lm_cookie',
            'value' => $p,
            'expire' => time() - 86400,
            'domain' => '.' . $_SERVER['SERVER_NAME'],
            'path' => '/',
            'prefix' => '',
            'secure' => FALSE
        );
        set_cookie($cookie);
    }

    public function get_cookies() {
        $cookie_data = $this->input->cookie('lm_cookie');
        if ($cookie_data) {
            if (strlen($cookie_data) > 0) {
                $parameter = $this->escape($cookie_data);
                $parameters = $this->decrypt_code($parameter);
                $parameters = explode("&", $parameters);
                if (count($parameters) == 3) {
                    $value_cookies;
                    foreach ($parameters as $value) {
                        $values = explode("~", $value);
                        if ($values[0] == "a") {
                            $value_cookies->username = $this->decrypt_code($values[1]);
                            $value_cookies->username = $this->escape($value_cookies->username);
                            $a = $values[1];
                        } elseif ($values[0] == "b") {
                            $value_cookies->password = $this->decrypt_code($values[1]);
                            $value_cookies->password = $this->escape($value_cookies->password);
                            $b = $values[1];
                        } elseif ($values[0] == "c") {
                            $value_cookies->who = $this->decrypt_code($values[1]);
                            $value_cookies->who = $this->escape($value_cookies->who);
                            $b = $values[1];
                        }
                    }
                    return $value_cookies;
                }
            }
        }
        return false;
    }

    public function write_log_trans($message, $trans_id, $data, $status) {
        $log = '';
        foreach ($data as $key => $value) {
            if ($key == 'currency_id') {
                $sql = 'select name from ms_currency where currency_id = ?';
                $result = $this->bindingQuery($sql, array($value))->row();
                $log .= 'Currency : <span>' . $result->name . '</span>';
            } else
                $log .= $this->dashesToCamelCase($key, TRUE) . ' : <strong>' . $value . '</strong>';
        }
        $message = $message . ' <br/> Detail Data : <br/> ' . $log;
        $data = NULL;
        $data->trans_id = $trans_id;
        $data->keterangan = $message;
        $data->status = $status;
        $data->cretime = $this->getTimeDB();
        $data->creby = $this->session->userdata('stakeholder')->id;
        $this->save($data);
    }

    public function write_log_user($message, $customer_id, $data, $status) {
        $log = '';
        foreach ($data as $key => $value) {
            if ($key == 'currency_id') {
                $sql = 'select name from ms_currency where currency_id = ?';
                $result = $this->bindingQuery($sql, array($value))->row();
                $log .= 'Currency : <span>' . $result->name . '</span>';
            } else
                $log .= $this->dashesToCamelCase($key, TRUE) . ' : <strong>' . $value . '</strong>';
        }
        $message = $message . ' <br/> Detail Data : <br/> ' . $log;
        $data = NULL;
        $data->customer_id = $customer_id;
        $data->keterangan = $message;
        $data->status = $status;
        $data->cretime = $this->getTimeDB();
        $data->creby = $this->session->userdata('stakeholder')->id;
        $this->save($data);
    }

    public function dashesToCamelCase($string, $capitalizeFirstCharacter = false) {

        $str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));

        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }

}

class YahooFinanceConverter {

    CONST YAHOO_URL = 'http://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s=%s%s=X';

    public static function yahoo_convert($a, $from, $to) {
        $fo = @fopen(sprintf(self::YAHOO_URL, $from, $to), 'r');
        if ($fo) {
            $response = fgets($fo, 4096);
            fclose($fo);
            $array = explode(',', $response);
            if (strval($array[1]) > 0) {
                return strval($a) * strval($array[1]);
            }
        }
        return false;
    }

}
