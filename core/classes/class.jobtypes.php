<?php

class JobTypes extends Connection
{
    private $table = 'tbl_job_types';
    public $pk = 'job_type_id';
    public $name = 'job_type';


    public function add()
    {
        if(isset($this->inputs[$this->name])){
            $form = array(
                $this->name     => $this->clean(STRTOUPPER($this->inputs[$this->name])),
                'user_id'       => $this->clean($this->inputs['user_id'])
            );
    
            return $this->insertIfNotExist($this->table, $form, "$this->name = '".$this->inputs[$this->name]."'", "Y");
        }
    }

    public function edit()
    {
        $primary_id = $this->inputs[$this->pk];
        $is_exist = $this->select($this->table, $this->pk, "$this->name = '".$this->inputs[$this->name]."' AND $this->pk != '$primary_id'");
        if ($is_exist->num_rows > 0) {
            return 2;
        } else {
            $form = array(
                $this->name     => $this->clean($this->inputs[$this->name]),
            );

            return $this->updateIfNotExist($this->table, $form, "$this->pk = '$primary_id'");
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

    public function show_per_user()
    {
        $user_id = $this->clean($this->inputs['user_id']);

        $preferred_jobs_arr = array();
        $fetch_preferred_jobs = $this->select("tbl_preferred_jobs", "job_type_id"," user_id='$user_id'");
        while($preferred_jobs_row = $fetch_preferred_jobs->fetch_assoc()){
            $preferred_jobs_arr[] = $preferred_jobs_row['job_type_id'];
        }

        $rows = array();
        $count = 1;
        $result = $this->select($this->table, '*', "job_type_id > 0 ORDER BY job_type ASC");
        while ($row = $result->fetch_assoc()) {
            $is_in_array = in_array($row['job_type_id'], $preferred_jobs_arr);
            $row['in_array'] = $is_in_array;
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
