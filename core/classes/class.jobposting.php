<?php

class JobPosting extends Connection
{
    private $table = 'tbl_job_posting';
    public $pk = 'job_post_id';
    public $name = 'job_title';


    public function add()
    {
        $form = array(
            $this->name             => $this->clean($this->inputs[$this->name]),
            'user_id'               => $this->clean($this->inputs['user_id']),
            'job_type_id'           => $this->clean($this->inputs['job_type_id']),
            'job_desc'              => $this->clean($this->inputs['job_desc']),
            'job_fee'               => $this->clean($this->inputs['job_fee']),
            'job_post_coordinates'  => $this->clean($this->inputs['job_post_coordinates']),
        );

        return $this->insert($this->table, $form);
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
