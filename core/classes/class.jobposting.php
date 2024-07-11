<?php

class JobPosting extends Connection
{
    private $table = 'tbl_job_posting';
    public $pk = 'job_post_id';
    public $name = 'job_title';


    public function add()
    {
        if(isset($this->inputs['user_id'])){
            $job_type_id = $this->clean($this->inputs['job_type_id']);
            $user_id = $this->clean($this->inputs['user_id']);
            $form = array(
                $this->name             => $this->clean($this->inputs[$this->name]),
                'user_id'               => $this->clean($this->inputs['user_id']),
                'job_type_id'           => $this->clean($this->inputs['job_type_id']),
                'job_desc'              => $this->clean($this->inputs['job_desc']),
                'job_fee'               => $this->clean($this->inputs['job_fee']),
                'job_post_coordinates'  => $this->clean($this->inputs['job_post_coordinates']),
                'job_post_status'       => 'P',
                'date_added'            => $this->getCurrentDate()
            );

            return $this->insertIfNotExist($this->table, $form, "job_type_id='$job_type_id' AND job_post_status='P' AND user_id='$user_id'");
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
            $row['job_fee'] =  number_format($row['job_fee'],2);
            $rows[] = $row;
        }
        return $rows;
    }

    public function show_filtered()
    { 
        $param = isset($this->inputs['param']) ? $this->inputs['param'] : null;
        $rows = array();
        $count = 1;
        $JobTypes = new JobTypes;
        $Users = new Users;
        $result = $this->select("$this->table h LEFT JOIN tbl_job_types jt ON h.job_type_id=jt.job_type_id LEFT JOIN tbl_users u ON h.user_id=u.user_id", 'h.*, jt.job_type, u.user_fname, u.user_mname, u.user_lname, u.user_email, u.user_photo', $param);
        while ($row = $result->fetch_assoc()) {
            $row['count'] = $count++;
            //$row['job_type'] =  $JobTypes->name($row['job_type_id']);
            //$row['user_fullname'] =  $Users->getUser($row['user_id']);
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
