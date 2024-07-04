<?php

class Homepage extends Connection
{
    public function unit_graph()
    {
        $rows = array();
        $result = $this->select('tbl_units','count(unit_id) as total, unit_status', "unit_status!='' GROUP BY unit_status");
        $count = 0;
        while($row = $result->fetch_assoc()){
            if($row['unit_status'] == "G"){
                $list['label'] = "Good Condition";
            }else if($row['unit_status'] == "R"){
                $list['label'] = "For Repair";
            }else{
                $list['label'] = "Damaged";
            }
            $list['total'] =  $row['total']*1;
            $rows[] = $list;
        }

        return $rows;
    }


    public function total_user(){
        $result = $this->select("tbl_users", "count(user_id)", "category='U'");
        $row = $result->fetch_array();
        return $row[0];
    }

    public function total_driver(){
        $result = $this->select("tbl_users", "count(user_id)", "category='D'");
        $row = $result->fetch_array();
        return $row[0];
    }

    public function total_transaction(){
        $result = $this->select("tbl_transactions", "sum(amount)","status = 'F'");
        $row = $result->fetch_array();
        return number_format($row[0],2);
    }
}

?>
