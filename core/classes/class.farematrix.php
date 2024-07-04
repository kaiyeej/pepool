<?php

class FareMatrix extends Connection
{
    private $table = 'tbl_fare_matrix';
    private $pk = 'fare_matrix_id';
    private $name = 'start_distance';

    public function add()
    {
        $start_distance = $this->clean($this->inputs['start_distance']);
        $end_distance = $this->clean($this->inputs['end_distance']);
        $is_exist = $this->select($this->table, $this->pk, "((start_distance <= '$start_distance' and end_distance >= '$end_distance') or (start_distance <= '$end_distance' and end_distance >= '$end_distance') or (start_distance >= '$start_distance' and end_distance <= '$end_distance'))");
        // $is_exist = $this->select($this->table, $this->pk, "start_distance = '$start_distance' and end_distance = '$end_distance'");

        if ($is_exist->num_rows > 0) {
            return 2;
        } else {
            $form = array(
                'start_distance' => $this->inputs['start_distance'],
                'end_distance' => $this->inputs['end_distance'],
                'fare_amount' => $this->inputs['fare_amount']
            );
            return $this->insert($this->table, $form);
        }
    }

    public function edit()
    {
        $primary_id = $this->inputs[$this->pk];
        $start_distance = $this->clean($this->inputs['start_distance']);
        $end_distance = $this->clean($this->inputs['end_distance']);
        $is_exist = $this->select($this->table, "fare_matrix_id", "start_distance = '$start_distance' and end_distance = '$end_distance' and fare_matrix_id!='$primary_id'");
        if ($is_exist->num_rows > 0) {
            return 2;
        } else {
            $form = array(
                'start_distance' => $this->inputs['start_distance'],
                'end_distance' => $this->inputs['end_distance'],
                'fare_amount' => $this->inputs['fare_amount']
            );
            return $this->update($this->table, $form, "$this->pk = '$primary_id'");
        }
    }

    public function remove()
    {
        $ids = implode(",", $this->inputs['ids']);
        return $this->delete($this->table, "$this->pk IN($ids)");
    }

    public function show()
    {
        $rows = array();
        $param = isset($this->inputs['param']) ? $this->inputs['param'] : null;
        $rows = array();
        $result = $this->select($this->table, '*', $param);
        while ($row = $result->fetch_assoc()) {
            $row['driver_id'] = $row['fare_matrix_id'];
            $row['distance'] = $row['start_distance']."-".$row['end_distance'];
            $rows[] = $row;
        }
        return $rows;
    }

    public function view()
    {
        $primary_id = $this->inputs['id'];
        $result = $this->select($this->table, "*", "$this->pk = '$primary_id'");
        $row = $result->fetch_assoc();
        $row['distance'] = $row['start_distance']."-".$row['end_distance'];
        return $row;
    }

    public static function name($primary_id)
    {
        $self = new self;
        $result = $self->select($self->table, $self->name, "$self->pk  = '$primary_id'");
        $row = $result->fetch_assoc();
        return $row[$self->name];
    }

    public static function getUser($primary_id)
    {
        $self = new self;
        $result = $self->select($self->table, "*", "$self->pk  = '$primary_id'");
        $row = $result->fetch_assoc();
        return $row['user_fname']." ".$row['user_mname']." ".$row['user_lname'];
    }
}
