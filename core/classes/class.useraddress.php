<?php

class UserAddress extends Connection {
    private $table = 'tbl_user_address';
    public $pk = 'user_address_id';

    public function add(){
        if(isset($this->inputs['user_id'])){
            $user_id      = $this->clean($this->inputs['user_id']);
            $coordinates  = $this->clean($this->inputs['address_coordinates']);
            $address_name  = $this->clean($this->inputs['address_name']);
            $form = array(
                'address_coordinates'   => $coordinates,
                'address_name'          => $address_name,
                'user_id'               => $user_id,
                'date_added'            => $this->getCurrentDate()
            );
    
            return $this->insertIfNotExist($this->table, $form, "address_coordinates='$coordinates' AND user_id='$user_id'");
        }
    }

    public function show()
    { 
        $param = isset($this->inputs['param']) ? $this->inputs['param'] : null;
        $rows = array();
        $count = 1;
        $result = $this->select($this->table, '*', $param);
        while ($row = $result->fetch_assoc()) {
            $row['count'] = $count++;
            $rows[] = $row;
        }
        return $rows;
    }

    public function view()
    {
        $primary_id = $this->inputs['id'];
        $result = $this->select($this->table, "*", "$this->pk = '$primary_id'");
        return $result->fetch_assoc();
    }

    public function rows($primary_id)
    {
        $result = $this->select($this->table, "*", "$this->pk  = '$primary_id'");
        $row = $result->fetch_assoc();
        return $row;
    }

    public function remove()
    {
        $ids = implode(",", $this->inputs['ids']);
        return $this->delete($this->table, "$this->pk IN($ids)");
    }

    public function remove_unique()
    {
        $id = $this->clean($this->inputs['id']);
        return $this->delete($this->table, "$this->pk='$id'");
    }

    public function show_nearby_workers()
    {
        $user_id = $this->clean($this->inputs['user_id']);
        $lat = $this->clean($this->inputs['lat']) * 1;
        $lng = $this->clean($this->inputs['lng']) * 1;
        $map_radius = $this->clean($this->inputs['map_radius']) * 1;
        $rows = array();
        $result = $this->select("$this->table ua LEFT JOIN tbl_users u ON ua.user_id=u.user_id", "u.user_id, u.user_fname, u.user_mname, u.user_lname, u.user_photo, u.user_rating, u.user_status, ua.address_coordinates, ACOS(SIN(('$lat' * (PI()/180))) * SIN(((SUBSTRING_INDEX(address_coordinates, ',', 1) * 1) * (PI()/180))) + COS(('$lat' * (PI()/180))) * COS(((SUBSTRING_INDEX(address_coordinates, ',', 1) * 1) * (PI()/180))) * COS(((((SUBSTRING_INDEX(address_coordinates, ',', -1) * 1) - '$lng') * PI()) / 180))) * 6371 as calculated_distance", "ua.user_id != '$user_id' GROUP BY u.user_id HAVING calculated_distance <= '$map_radius'");
        while ($row = $result->fetch_assoc()) {
            $row['user_fullname'] = $row['user_fname'] . " " . $row['user_lname'];
            $row['user_status'] = $row['user_status'] == "A" ? "Available" : "Occupied";
            $rows[] = $row;
        }
        return $rows;
    }
}

?>