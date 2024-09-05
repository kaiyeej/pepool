<?php

class PreferredJobs extends Connection {
    private $table = 'tbl_preferred_jobs';
    public $pk = 'preferred_job_id';

    public function add(){
        if(isset($this->inputs['user_id'])){
            $job_type_id  = $this->clean($this->inputs['job_type_id']);
            $user_id      = $this->clean($this->inputs['user_id']);
            $form = array(
                'job_type_id'          => $job_type_id,
                'user_id'              => $user_id
            );
    
            return $this->insertIfNotExist($this->table, $form, "job_type_id='$job_type_id' AND user_id='$user_id'");
        }
    }

    public function add_mobile(){
        $user_id      = $this->clean($this->inputs['user_id']);
        $fetch = $this->select($this->table, "count(preferred_job_id) as count", "user_id='$user_id'");
        $row = $fetch->fetch_assoc();
        if($row['count'] < 5){
            return $this->add();
        }else{
            return -1;
        }
    }

    public function show()
    { 
        $param = isset($this->inputs['param']) ? $this->inputs['param'] : null;
        $rows = array();
        $count = 1;
        $result = $this->select("$this->table h LEFT JOIN tbl_job_types j ON h.job_type_id=j.job_type_id", '*', $param);
        while ($row = $result->fetch_assoc()) {
            $row['count'] = $count++;
            $rows[] = $row;
        }
        return $rows;
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

    public function remove_per_user()
    {
        $id = $this->clean($this->inputs['id']);
        $user_id = $this->clean($this->inputs['user_id']);
        return $this->delete($this->table, "job_type_id='$id' AND user_id='$user_id'");
    }
}

?>