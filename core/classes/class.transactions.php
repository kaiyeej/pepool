<?php

class Transactions extends Connection
{
    private $table = 'tbl_transactions';
    public $pk = 'transaction_id';
    public $name = 'ref_number';

    public function edit()
    {
        $primary_id = $this->inputs[$this->pk];
        $is_exist = $this->select($this->table, $this->pk, "ref_number = '$this->name' AND $this->pk != '$primary_id'");
        if ($is_exist->num_rows > 0) {
            return 2;
        } else {
            $form = array(
                $this->name     => $this->clean($this->inputs[$this->name]),
                //'user_id'       => $this->inputs['user_id'],
                'driver_id'     => $this->inputs['driver_id'],
                'remarks'       => $this->inputs['remarks'],
            );
            return $this->update($this->table, $form, "$this->pk = '$primary_id'");
        }
    }

    public function cancel()
    {
        $ids = implode(",", $this->inputs['ids']);
        $form = array(
            'status' => 'C'
        );

        return $this->update($this->table, $form, "$this->pk IN($ids)");
    }

    public function show()
    {
        $rows = array();
        $Users = new Users();
        $rows = array();
        $start_date = $this->inputs['start_date'];
        $end_date = $this->inputs['end_date'];
        $type = $this->inputs['type'];

        if($type == "T"){
            $status = $this->inputs['status_t'];
    
            if($status < 0){
                $param = "date_added BETWEEN '$start_date' AND DATE_ADD('$end_date', INTERVAL 1 DAY)";
            }else{
                $param = "status = '$status' AND date_added BETWEEN '$start_date' AND DATE_ADD('$end_date', INTERVAL 1 DAY)";
            }
            
        }else{
            
            $user_id = $this->inputs['user_id'];
            if($user_id < 0){
                $param = "date_added BETWEEN '$start_date' AND DATE_ADD('$end_date', INTERVAL 1 DAY)";
            }else{
                if($type == "D"){
                    $param = "driver_id = '$user_id' AND date_added BETWEEN '$start_date' AND DATE_ADD('$end_date', INTERVAL 1 DAY)";
                }else if($type == "U"){
                    $param = "user_id = '$user_id' AND date_added BETWEEN '$start_date' AND DATE_ADD('$end_date', INTERVAL 1 DAY)";
                }
            }
        }


        $result = $this->select($this->table, '*', $param);
        while ($row = $result->fetch_assoc()) {
            $review = $this->ratings($row['transaction_id']);

            $row['driver'] = $Users->getUser($row['driver_id']);
            $row['user'] = $Users->getUser($row['user_id']);
            $row['rating'] = $review[0] > 0 ?  $review[0]."/5" : "---";
            $row['remarks'] = $review[1];
            $rows[] = $row;
        }
        return $rows;
    }

    public function show2()
    {
        $rows = array();
        $Users = new Users();
        $rows = array();
        $result = $this->select($this->table, '*');
        while ($row = $result->fetch_assoc()) {
            $review = $this->ratings($row['transaction_id']);

            $row['driver'] = $Users->getUser($row['driver_id']);
            $row['user'] = $Users->getUser($row['user_id']);
            $row['rating'] = $review[0] > 0 ?  $review[0]."/5" : "---";
            $row['remarks'] = $review[1];
            $rows[] = $row;
        }
        return $rows;
    }

    public function ratings($id){
        $result = $this->select("tbl_ratings", "rating,remarks", "$this->pk = '$id'");
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            return [$row['rating'],$row['remarks']];
        }else{
            return [0,""];
        }
        
    }

    public function view()
    {
        $primary_id = $this->inputs['id'];
        $result = $this->select($this->table, "*", "$this->pk = '$primary_id'");
        return $result->fetch_assoc();
    }

    public function remove()
    {
        $ids = implode(",", $this->inputs['ids']);
        return $this->delete($this->table, "$this->pk IN($ids)");
    }

    public function name($primary_id)
    {
        $result = $this->select($this->table, $this->name, "$this->pk = '$primary_id'");
        $row = $result->fetch_assoc();
        return $row[$this->name];
    }
}
