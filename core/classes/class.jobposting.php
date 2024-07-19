<?php

class JobPosting extends Connection
{
    private $table = 'tbl_job_posting';
    public $pk = 'job_post_id';
    public $name = 'job_title';


    public function add()
    {
        if(isset($this->inputs['user_id'])){
            $job_title = $this->clean($this->inputs['job_title']);
            $job_type_id = $this->clean($this->inputs['job_type_id']);
            $user_id = $this->clean($this->inputs['user_id']);
            $user_address_id = $this->clean($this->inputs['user_address_id']);
            $current_coordinates = $this->clean($this->inputs['job_post_coordinates']);
            
            if($user_address_id == 0){
                $job_coordinates = $current_coordinates;
            }else{
                $UserAddress = new UserAddress;
                $user_address_row = $UserAddress->rows($user_address_id);
                $job_coordinates = $user_address_row['coordinates'];
            }
            
            $form = array(
                $this->name             => $this->clean($this->inputs[$this->name]),
                'user_id'               => $this->clean($this->inputs['user_id']),
                'job_type_id'           => $this->clean($this->inputs['job_type_id']),
                'job_desc'              => $this->clean($this->inputs['job_desc']),
                'job_fee'               => $this->clean($this->inputs['job_fee']),
                'job_post_coordinates'  => $job_coordinates,
                'job_term'              => $this->clean($this->inputs['job_term']),
                'start_date'            => $this->clean($this->inputs['start_date']),
                'end_date'              => $this->clean($this->inputs['end_date']),
                'job_post_status'       => 'P',
                'date_added'            => $this->getCurrentDate()
            );

            $response = $this->insertIfNotExist($this->table, $form, "job_type_id='$job_type_id' AND job_post_status='P' AND user_id='$user_id'");

            if($response == 1){
                // send notif
                $Notifications = new Notifications;
                $Notifications->push_notification("c0sdHcE-TIuj7Gme6sv-v6:APA91bFVUvx9fxcwTGqBm1xM6Ixso_uKNx89fnfLPd2L7N7CqQKHpulzOP3cfjCU9uqsMEwGHkF3e3Nzn68StgA9lwSc7X7dD6CAcAgs4jH0gXbaII1S2ObudznJ51cCqnuCFWuqxq1S", $job_title, "New job available near you.");
                //push_notification("Test message", "some body of the message", "c0sdHcE-TIuj7Gme6sv-v6:APA91bFVUvx9fxcwTGqBm1xM6Ixso_uKNx89fnfLPd2L7N7CqQKHpulzOP3cfjCU9uqsMEwGHkF3e3Nzn68StgA9lwSc7X7dD6CAcAgs4jH0gXbaII1S2ObudznJ51cCqnuCFWuqxq1S");
            }

            return $response;
        }
    }
    

    public function edit()
    {
        $primary_id = $this->inputs[$this->pk];
        $form = array(
            $this->name             => $this->clean($this->inputs[$this->name]),
            'user_id'               => $this->clean($this->inputs['user_id']),
            'job_type_id'           => $this->clean($this->inputs['job_type_id']),
            'job_desc'              => $this->clean($this->inputs['job_desc']),
            'job_fee'               => $this->clean($this->inputs['job_fee']),
            'job_post_coordinates'  => $this->clean($this->inputs['job_post_coordinates']),
        );

        return $this->update($this->table, $form, "$this->pk = '$primary_id'");
    }

    public function show()
    { 
        $param = isset($this->inputs['param']) ? $this->inputs['param'] : null;
        $rows = array();
        $count = 1;
        $JobTypes = new JobTypes;
        $Users = new Users;
        $result = $this->select($this->table, '*', $param);
        while ($row = $result->fetch_assoc()) {
            $row['count'] = $count++;
            $row['job_type'] =  $JobTypes->name($row['job_type_id']);
            $row['user_fullname'] =  $Users->getUser($row['user_id']);
            $rows[] = $row;
        }
        return $rows;
    }

    public function show_nearby_jobs()
    {
        $lat = $this->clean($this->inputs['lat']) * 1;
        $lng = $this->clean($this->inputs['lng']) * 1;
        $map_radius = $this->clean($this->inputs['map_radius']) * 1;
        $rows = array();
        $count = 1;
        $result = $this->select("$this->table h LEFT JOIN tbl_job_types jt ON h.job_type_id=jt.job_type_id LEFT JOIN tbl_users u ON h.user_id=u.user_id", "h.*, jt.job_type, u.user_fname, u.user_mname, u.user_lname, u.user_email, u.user_photo, ACOS(SIN(('$lat' * (PI()/180))) * SIN(((SUBSTRING_INDEX(job_post_coordinates, ',', 1) * 1) * (PI()/180))) + COS(('$lat' * (PI()/180))) * COS(((SUBSTRING_INDEX(job_post_coordinates, ',', 1) * 1) * (PI()/180))) * COS(((((SUBSTRING_INDEX(job_post_coordinates, ',', -1) * 1) - '$lng') * PI()) / 180))) * 6371 as calculated_distance", "h.job_post_id > 0 HAVING calculated_distance <= '$map_radius' ORDER BY h.date_added DESC");
        while ($row = $result->fetch_assoc()) {
            $row['count'] = $count++;
            $row['employer_name'] =  $row['user_fname'] . " " . $row['user_lname'];
            $row['term_range'] =  date("M d, Y", strtotime($row['start_date'])) . " to " . date("M d, Y", strtotime($row['end_date']));
            $row['transaction_date'] =  date('M d, Y H:i A', strtotime($row['date_added']));
            if($row['job_post_status'] == "O"){
                $row['status'] = "Ongoing";
                $row['color_status'] = "warning";
            }else if($row['job_post_status'] == "F"){
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

    public function show_filtered()
    { 
        $param = isset($this->inputs['param']) ? $this->inputs['param'] : null;
        $rows = array();
        $count = 1;
        $result = $this->select("$this->table h LEFT JOIN tbl_job_types jt ON h.job_type_id=jt.job_type_id LEFT JOIN tbl_users u ON h.user_id=u.user_id LEFT JOIN tbl_transactions t ON t.job_post_id=h.job_post_id", 'h.*, jt.job_type, u.user_fname, u.user_mname, u.user_lname, u.user_email, u.user_photo, count(t.transaction_id) as number_of_applicants', $param);
        while ($row = $result->fetch_assoc()) {
            $row['count'] = $count++;
            $row['employer_name'] =  $row['user_fname'] . " " . $row['user_lname'];
            $row['job_fee'] =  number_format($row['job_fee'],2);
            $row['transaction_date'] =  date('M d, Y H:i A', strtotime($row['date_added']));
            if($row['job_post_status'] == "O"){
                $row['status'] = "Ongoing";
                $row['color_status'] = "warning";
            }else if($row['job_post_status'] == "F"){
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
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            return $row[$this->name];
        }else{
            return "<i>Not found</i>";
        }
    }
}
