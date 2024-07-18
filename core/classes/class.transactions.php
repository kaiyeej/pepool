<?php

class Transactions extends Connection
{
    private $table = 'tbl_transactions';
    public $pk = 'transaction_id';
    public $name = 'reference_number';

    public function add()
    {
        if(isset($this->inputs['job_post_id'])){
            $job_post_id  = $this->clean($this->inputs['job_post_id']);
            $user_id      = $this->clean($this->inputs['user_id']);
            $form = array(
                $this->name            => $this->generate(),
                'job_post_id'          => $job_post_id,
                'bidding_amount'       => $this->clean($this->inputs['bidding_amount']),
                'user_id'              => $user_id,
                'status'               => 'P',
                'transaction_rating'   => 0,
                'date_added'           => $this->getCurrentDate()
            );
    
            return $this->insertIfNotExist($this->table, $form, "job_post_id='$job_post_id' AND user_id='$user_id'");
        }
    }

    public function show_filtered(){
        $user_id = $this->clean($this->inputs['user_id']);
        $rows = array();
        $result = $this->select("tbl_transactions t LEFT JOIN tbl_job_posting jp ON t.job_post_id=jp.job_post_id LEFT JOIN tbl_job_types jt ON jt.job_type_id=jp.job_type_id LEFT JOIN tbl_users u ON u.user_id=jp.user_id", "*, t.date_added as transaction_date","t.user_id='$user_id'");
        while ($row = $result->fetch_assoc()) {
            $row['transaction_date'] = date("M d, Y h:i A", strtotime($row['transaction_date']));
            $row['employer_name'] = $row['user_fname'] . " " . $row['user_mname'] . " " . $row['user_lname'];
            if($row['status'] == "O"){
                $row['status'] = "Ongoing";
                $row['color_status'] = "warning";
            }else if($row['status'] == "F"){
                $row['status'] = "Finished";
                $row['color_status'] = "primary";
            }else{
                $row['status'] = "Pending";
                $row['color_status'] = "medium";
            }
            $rows[] = $row;
        }

        return $rows;
    }

    public function show_applicants(){
        if(isset($this->inputs['job_post_id'])){
            $job_post_id = $this->clean($this->inputs['job_post_id']);
            $rows = array();
            $result = $this->select("$this->table t LEFT JOIN tbl_users u ON t.user_id=u.user_id", "*", "job_post_id='$job_post_id'");
            while ($row = $result->fetch_assoc()) {
                $row['user_fullname'] = $row['user_fname'] . " " . $row['user_mname'] . " " . $row['user_lname'];
                $rows[] = $row;
            }

            return $rows;
        }
    }

    public function generate(){
        $date = $this->getCurrentDate();
        $user_id = $this->inputs['user_id'];
        $reference_number = date("mdyhis", strtotime($date)) . $user_id;
        return $reference_number;
    }

    public function accept(){
        $id = $this->clean($this->inputs['id']);
        $row = $this->rows($id);
        $form = array(
            'status' => 'O'
        );
        $result = $this->update($this->table, $form, "$this->pk='$id'");
        if($result){
            $form = array(
                'job_post_status' => 'O'
            );
            return $this->update("tbl_job_posting", $form, "job_post_id='$row[job_post_id]'");
        }
    }

    public function show_similar_transactions(){
        $id = $this->clean($this->inputs['id']);
        
        // set headers
        $header_arr = array();
        $list_of_worker_ids = array();
        array_push($header_arr, 'list_of_workers');
        $fetch_workers = $this->select("tbl_preferred_jobs p LEFT JOIN tbl_users u ON p.user_id=u.user_id", "p.user_id, u.user_email", "job_type_id='$id' GROUP BY p.user_id ORDER BY p.user_id ASC");
        while($worker_row = $fetch_workers->fetch_assoc()){
            array_push($header_arr, $worker_row['user_email']);
            array_push($list_of_worker_ids, $worker_row['user_id']);
        }

        if(sizeof($list_of_worker_ids) > 0){
            $rows = array();
            array_push($rows, $header_arr);

            $ids = implode(',',$list_of_worker_ids);

            $arr = array();

            $fetch = $this->select("$this->table t LEFT JOIN tbl_job_posting p ON t.job_post_id=p.job_post_id LEFT JOIN tbl_users u ON u.user_id=p.user_id", "t.user_id as worker_id, p.user_id as client_id, AVG(t.transaction_rating) as transaction_rating, u.user_email", "p.job_type_id='$id' AND t.user_id IN ($ids) GROUP BY p.user_id");
            while($row = $fetch->fetch_assoc()){
                $ratings_for_workers = array();
                $ratings_for_workers = array_fill(0, sizeof($list_of_worker_ids) + 1, 0);
                $arr_index = array_search($row['user_email'], $header_arr);
                $ratings_for_workers[0] = $row['client_id'];
                $ratings_for_workers[$arr_index] = $row['transaction_rating'] > 3 ? 1 : 0;
                // if($row['transaction_rating'] >= 4){
                //     $rating = 1;
                // }else if($row[''])
                array_push($rows, $ratings_for_workers);
                //$arr[] = $row;
            }

            return $rows;
        }
    }





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

    public function rows($primary_id)
    {
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
