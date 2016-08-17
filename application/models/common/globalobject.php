<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GlobalObject
 *
 * @author myMine
 */
class GlobalObject extends CI_Model {

    // for name table
    var $limit = 10;
    var $offset = 0;
    var $table_name = NULL;
    var $select = "*";
    var $header;

    function GlobalObject() {
        date_default_timezone_set('Asia/Jakarta');
        parent::__construct();
    }

    //encapsulate field

    public function set_table_name($name) {
        $this->table_name = $name;
    }

    public function set_limit($limit) {
        $this->limit = $limit;
    }

    public function get_limit() {
        return $this->limit;
    }

    public function set_offset($offset) {
        $this->offset = $offset * 10;
    }

    public function get_offset() {
        return $this->offset;
    }

    public function get_header() {
        $header = $this->header;
        $arr_header = '';
        $arr_header = explode(',', $header);
        $select = '';
        foreach ($arr_header as $value) {
            $select .= '<th>' . trim($value) . '</th>';
        }
        return $select;
    }

    public function set_header($header) {
        $this->header = $header;
    }

    public function set_select($select) {
        $this->select = $select;
    }

    //---------------------------------------------------------------------------------
    //operation for saving data detail
    public function save_detail($arrNewRecord, $id) {
        $this->load->database('default');
        $this->db->insert($this->table_name, $arrNewRecord);
        $this->db->select_max($id, 'id');
        $row = $this->db->get($this->table_name)->row();
        $this->db->close();
        return $row;
    }

    public function begin() {
        $this->db->trans_begin();
    }

    public function status() {
        $this->db->trans_status();
    }

    public function commit() {
        $this->db->trans_commit();
    }

    public function rollback() {
        $this->db->trans_rollback();
    }

    public function complete() {
        $this->db->trans_complete();
    }

    //operation for saving data detail
    public function save($arrNewRecord,$id) {
        $this->load->database('default');
        $this->db->insert($this->table_name, $arrNewRecord);
        $this->db->close();
        $this->db->select_max($id, 'id');
        return $this->db->get($this->table_name)->row();
    }

    // Get records according paging:
    public function get_records($where = '', $type_where = '', $order = '', $type_order = '', $groupby = '') {
        $this->load->database('default');
        $query = $this->db->select($this->select);
        if (isset($order) && $order != '' && $type_order == 'asc')
            $query = $this->db->order_by($order, 'asc');
        if (isset($order) && $order != '' && $type_order == 'desc')
            $query = $this->db->order_by($order, 'desc');
        if (isset($groupby) && $groupby != '')
            $query = $this->db->group_by($groupby);
        if (isset($where) && $where != '') {
            if (strtolower($type_where) == 'and')
                $query = $this->db->where($where);
            elseif (strtolower($type_where) == 'or')
                $query = $this->db->or_where($where);
            elseif (strtolower($type_where) == 'like')
                $query = $this->db->like($where);
            elseif (strtolower($type_where) == 'in')
                $query = $this->db->where_in($where);
            else
                $query = $this->db->where($where);
        }
        $query = $this->db->get($this->table_name, $this->limit, $this->offset);
        $this->db->close();
        return $query->result_array();
    }

    public function get_count_data($where = '', $type_where = '', $groupby = '') {
        $this->load->database('default');
        if (isset($groupby) && $groupby != '')
            $query = $this->db->group_by($groupby);
        if (isset($where) && $where != '') {
            if (strtolower($type_where) == 'and')
                $query = $this->db->where($where);
            elseif (strtolower($type_where) == 'or')
                $query = $this->db->or_where($where);
            elseif (strtolower($type_where) == 'like')
                $query = $this->db->like($where);
            elseif (strtolower($type_where) == 'in')
                $query = $this->db->where_in($where);
            else
                $query = $this->db->where($where);
        }
        $result = $this->db->from($this->table_name)->count_all_results();
        $this->db->close();
        return $result;
    }

    public function get_by_id($field, $id) {
        $this->load->database('default');
        $this->db->where($field, mysql_escape_string($id));
        $row = $this->db->get($this->table_name)->row();
        $this->db->close();
        return $row;
    }

    public function getWhere($data) {
        $this->load->database('default');
        $this->db->select($this->select);
        $this->db->where($data);
        $row = $this->db->get($this->table_name)->result();
        $this->db->close();
        return $row;
    }

    public function bindingQuery($sql, $param = "") {
        $this->load->database('default');
        if ($param != "")
            $result = $this->db->query($sql, $param);
        else
            $result = $this->db->query($sql);
        $this->db->close();
        return $result;
    }

    // Update record by id:
    public function updateRecord($param, $arrFields) {
        $this->load->database('default');
        $this->db->where($param);
        $this->db->update($this->table_name, $arrFields);
        $this->db->close();
    }

    // Delete record by id:
    public function delete($field, $id) {
        $this->load->database('default');
        $this->db->where($field, $id);
        $this->db->delete($this->table_name);
        $this->db->close();
    }

    // Get all, as an object:
    public function get_all($where = '', $order = NULL, $sort = '') {
        $this->load->database('default');
        $sql = 'SELECT ' . $this->select;
        $sql .= ' FROM ' . $this->table_name;
        if ($where != '')
            $sql .= ' where 1 = 1 ' . $where;
        if ($order != NULL)
            $sql .= ' order by ' . $order . ' ' . $sort;
        $result = $this->db->query($sql, array())->result();
        $this->db->close();
        return $result;
    }

}

?>
