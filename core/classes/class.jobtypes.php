<?php

class JobTypes extends Connection
{
    private $table = 'tbl_job_types';
    public $pk = 'job_type_id';
    public $name = 'job_type';


    public function add()
    {
        $form = array(
            $this->name     => $this->clean($this->inputs[$this->name]),
        );

        return $this->insertIfNotExist($this->table, $form, "$this->name = '".$this->inputs[$this->name]."'");
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
