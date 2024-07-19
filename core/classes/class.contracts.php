<?php

class Contracts extends Connection {
    private $table = 'tbl_contracts';
    public $pk = 'contract_id';

    public function add(){
        if(isset($this->inputs['user_id'])){
            $user_id      = $this->clean($this->inputs['user_id']);
            $job_post_id  = $this->clean($this->inputs['job_post_id']);
            $worker_id  = $this->clean($this->inputs['worker_id']);
            $form = array(
                'job_post_id'      => $job_post_id,
                'client_user_id'      => $user_id,
                'client_did_sign'     => "Y",
                'worker_user_id'      => $worker_id,
                'worker_did_sign'     => "Y",
                'contract_key'        => $worker_id
                'date_added'          => $this->getCurrentDate()
            );
    
            return $this->insertIfNotExist($this->table, $form, "job_post_id='$job_post_id'");
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
        $result = $this->select($self->table, "*", "$this->pk  = '$primary_id'");
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
}

?>