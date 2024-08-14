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
        $result = $this->select("tbl_transactions t LEFT JOIN tbl_job_posting jp ON t.job_post_id=jp.job_post_id LEFT JOIN tbl_job_types jt ON jt.job_type_id=jp.job_type_id LEFT JOIN tbl_users u ON u.user_id=jp.user_id", "*, t.date_added as transaction_date, t.date_added as date_added","t.user_id='$user_id' ORDER BY t.date_added DESC");
        while ($row = $result->fetch_assoc()) {
            $row['transaction_date'] = date("M d, Y h:i A", strtotime($row['transaction_date']));
            $row['employer_name'] = $row['user_fname'] . " " . $row['user_mname'] . " " . $row['user_lname'];
            if($row['status'] == "O"){
                $row['status'] = "Ongoing";
                $row['color_status'] = "warning";
            }else if($row['status'] == "F"){
                $row['status'] = "Finished";
                $row['color_status'] = "primary";
            }else if($row['status'] == "D"){
                $row['status'] = "Denied";
                $row['color_status'] = "medium";
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
        $Notifications = new Notifications;
        $Chats = new Chats;
        $id = $this->clean($this->inputs['id']);
        $row = $this->rows($id);
        $form = array(
            'status' => 'O'
        );
        $result = $this->update($this->table, ['status' => 'D'], "job_post_id='$row[job_post_id]' AND $this->pk != '$id'");
        if($result){

            // update other transactions to denied
            $this->update($this->table, $form, "$this->pk='$id'");

            // insert contract
            

            // insert welcome message
            $Chats->inputs['sender_id'] = 0;
            $Chats->inputs['transaction_id'] = $id;
            $Chats->inputs['content'] = "Welcome to PePool! Your job application has been accepted. You may now start your transaction. This is an auto-generated message.";
            $Chats->add();

            // notify user
            $fetch_user = $this->select("tbl_users", "push_notification_token", "user_id='$row[user_id]'");
            $user_row = $fetch_user->fetch_assoc();
            $Notifications->push_notification($user_row['push_notification_token'], "Congratulations! Your job application was accepted.", "Open PePool to see details");

            // update user status
            $this->update("tbl_users", ['user_status' => 'O'], "user_id='$row[user_id]'");

            $form = array(
                'job_post_status' => 'O'
            );
            return $this->update("tbl_job_posting", $form, "job_post_id='$row[job_post_id]'");
        }
    }

    public function show_similar_transactions(){
        $id = $this->clean($this->inputs['id']);
        $user_id = $this->clean($this->inputs['user_id']);
        
        // set headers
        $header_arr = array();
        $list_of_worker_ids = array();
        array_push($header_arr, 'list_of_workers');
        $fetch_workers = $this->select("tbl_preferred_jobs", "user_id", "job_type_id='$id' GROUP BY user_id ORDER BY user_id ASC");
        while($worker_row = $fetch_workers->fetch_assoc()){
            array_push($header_arr, $worker_row['user_id']);
            array_push($list_of_worker_ids, $worker_row['user_id']);
        }

        if(sizeof($list_of_worker_ids) > 0){
            $rows = array();
            array_push($rows, $header_arr);

            $ids = implode(',',$list_of_worker_ids);

            $arr = array();

            $fetch = $this->select("$this->table t LEFT JOIN tbl_job_posting p ON t.job_post_id=p.job_post_id", "t.user_id as worker_id, p.user_id as client_id, AVG(t.transaction_rating) as transaction_rating", "p.job_type_id='$id' AND t.status='F' AND t.user_id IN ($ids) GROUP BY p.user_id");
            while($row = $fetch->fetch_assoc()){
                $ratings_for_workers = array();
                $ratings_for_workers = array_fill(0, sizeof($list_of_worker_ids) + 1, 0);
                $arr_index = array_search($row['worker_id'], $header_arr);
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

    public function rate_employer(){
        $Notifications = new Notifications;
        $id = $this->clean($this->inputs['transaction_id']);
        $rating = $this->clean($this->inputs['rating']);
        $feedback = $this->clean($this->inputs['feedback']);

        $fetch_transaction = $this->select($this->table, "job_post_id", "transaction_id='$id'");
        $transaction_row = $fetch_transaction->fetch_assoc();

        $fetch_job_post = $this->select("tbl_job_posting", "user_id, job_post_id", "job_post_id='$transaction_row[job_post_id]'");
        $job_post_row = $fetch_job_post->fetch_assoc();

        $form = array(
            'job_employer_rating' => $rating,
            'job_feedback' => $feedback
        );

        $res = $this->update("tbl_job_posting", $form, "job_post_id='$job_post_row[job_post_id]'");

        if($res){
            // update employer rate
            $fetch_emp = $this->select("tbl_job_posting", "AVG(job_employer_rating) as employer_rating", "user_id='$job_post_row[user_id]' AND job_post_status='F'");
            $emp_row = $fetch_emp->fetch_assoc();
            $this->update("tbl_users", ['employer_rating' => $emp_row['employer_rating']], "user_id='$job_post_row[user_id]'");

            // send notif to user for rating
            $fetch_user = $this->select("tbl_users", "push_notification_token", "user_id='$job_post_row[user_id]'");
            $user_row = $fetch_user->fetch_assoc();
            $Notifications->push_notification($user_row['push_notification_token'], "You have got a new rating of $rating from a worker", "Open PePool to see details");
        }

        return $res;
        
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
