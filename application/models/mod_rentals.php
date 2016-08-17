<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mod_rentals
 *
 * @author b3nhysteria
 */
class mod_rentals extends GlobalObject {

    public function __construct() {
        parent::GlobalObject();
    }

    public function saveRental($obj) {
        $this->table_name = "rentals";
        return $this->save($obj, "rental_id");
    }

    public function getRental($rental_id) {
        $temp = $this->bindingQuery("select * from rentals where rental_id = ?", array($rental_id))->row();
        return $temp;
    }

    public function updateRental($obj, $rental_id) {
        $this->table_name = "rentals";
        $param = array();
        $param['rental_id'] = $rental_id;
        $this->updateRecord($param, $obj);
    }

    public function getRentals() {
        $temp = $this->bindingQuery("select 
                    c.name,
                    ca.brand,
                    ca.type,
                    ca.plate,
                    r.`date-from`,
                    r.`date-to`
                    from
                    rentals r
                    inner join 
                    clients c on r.`client-id` = c.client_id
                    INNER JOIN
                    cars ca on r.`car-id` = ca.car_id")->result();
        return $temp;
    }

    public function deleteRental($rental_id) {
        $this->bindingQuery("delete from rentals where rental_id = ?", array($rental_id));
    }

    public function checkingAva($obj, $car = 1, $id = 0) {
        $sql = "select * from
                rentals 
                where ";
        $param = array();
        if ($car == 1) {
            $sql .= "`car-id` = ?";
            $param[] = $obj["car-id"];
        } else {
            $sql .= "`client-id` = ?";
            $param[] = $obj["client-id"];
        }
        $sql .= "and ? between `date-from` and `date-to` 
                or ? between `date-from` and `date-to`";
        $param[] = $obj["date-from"];
        $param[] = $obj['date-to'];
        if ($id != 0) {
            $sql .= " and rental_id != ?";
            $param[] = $id;
        }
        $result = $this->bindingQuery($sql, $param)->result();
        return (count($result) > 0) ? 0 : 1;
    }
    
    public function getHistoryCar($car_id,$param){
        $sql = "select `name` as `rent-by`,`date-from`,`date-to` from
                rentals r
                inner join
                clients c on r.`client-id` = c.client_id
                where 
                `car-id` = ?
                and ? between month(`date-from`) and month(`date-to` )
                and ? between year(`date-from`) and year(`date-to`)";
        return $this->bindingQuery($sql,array($car_id,$param[0]*1,$param[1]*1))->result();
    }
    
    public function getHistoryClient($client_id){
        $sql = "select brand,type,plate,`date-from`,`date-to` from
                rentals r
                inner join
                cars c on r.`car-id` = c.car_id
                where 
                `client-id` = ?";
        return $this->bindingQuery($sql,array($client_id))->result();
    }
    
    public function getRentedCar($date){
        $sql = "select brand,type,plate
                from
                rentals r
                inner join
                cars c on r.`car-id` = c.car_id
                where 
                ? between `date-from` and `date-to` ";
        return $this->bindingQuery($sql,array(date("Y-m-d",strtotime($date))))->result();
    }
    
     public function getFreeCar($date){
        $sql = "select 
                brand,type,plate
                from
                cars cr
                where
                not exists(
                select 1
                from
                rentals r
                where 
                ? between `date-from` and `date-to` 
                and r.`car-id` = cr.car_id
                )";
        return $this->bindingQuery($sql,array(date("Y-m-d",strtotime($date))))->result();
    }
    
}
