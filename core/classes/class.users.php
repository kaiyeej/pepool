<?php

class Users extends Connection
{
    private $table = 'tbl_users';
    private $pk = 'user_id';
    private $name = 'username';

    public function add()
    {
        $username = $this->clean($this->inputs['username']);
        $is_exist = $this->select($this->table, $this->pk, "username = '$username'");
        if ($is_exist->num_rows > 0) {
            return 2;
        } else {
            $pass = clean($this->inputs['password']);
            $form = array(
                'user_fname'    => clean($this->inputs['user_fname']),
                'user_mname'    => clean($this->inputs['user_mname']),
                'user_lname'    => clean($this->inputs['user_lname']),
                'username'      => clean($this->inputs['username']),
                'password'      => md5($pass),
                'date_added'    => $this->getCurrentDate()
            );
            return $this->insert($this->table, $form);
        }
    }

    public function edit()
    {
        $primary_id = $this->inputs[$this->pk];
        $user_fname = $this->clean($this->inputs['user_fname']);
        $username = $this->clean($this->inputs['username']);
        $is_exist = $this->select($this->table, $this->pk, "username = '$username' AND  $this->pk != '$primary_id'");
        if ($is_exist->num_rows > 0) {
            return 2;
        } else {
            $form = array(
                'user_fname' => $this->inputs['user_fname'],
                'user_mname' => $this->inputs['user_mname'],
                'user_lname' => $this->inputs['user_lname'],
                'username' => $this->inputs['username'],
            );
            return $this->update($this->table, $form, "$this->pk = '$primary_id'");
        }
    }

    public function block()
    {
        $ids = implode(",", $this->inputs['ids']);
        $form = array(
            'status' => '1'
        );

        return $this->update($this->table, $form, "$this->pk IN($ids)");
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
        $count = 1;
        $result = $this->select($this->table, '*', $param);
        while ($row = $result->fetch_assoc()) {
            $row['count'] = $count++;
            $row['user_fullname'] = $row['user_fname'] . " " . $row['user_mname'] . " " . $row['user_lname'];
            $rows[] = $row;
        }
        return $rows;
    }

    public function view()
    {
        $primary_id = $this->inputs['id'];
        $result = $this->select($this->table, "*", "$this->pk = '$primary_id'");
        $row = $result->fetch_assoc();
        $row['user_fullname'] = $row['user_fname'] . " " . $row['user_mname'] . " " . $row['user_lname'];
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
        return $row['user_fname'] . " " . $row['user_mname'] . " " . $row['user_lname'];
    }

    public function login()
    {

        $username = $this->inputs['username'];
        $password = $this->inputs['password'];

        $result = $this->select($this->table, "*", "username = '$username' AND password = md5('$password')");
        $row = $result->fetch_assoc();

        if ($row) {
            $_SESSION['status'] = "in";
            $_SESSION["pepool_user_id"] = $row['user_id'];

            $res = 1;
        } else {
            $res = 0;
        }

        // return $row[$this->name];

        return $res;
    }
    public function logout()
    {
        session_destroy();
        return 1;
    }

    public function register(){
        $user_email = $this->clean($this->inputs['user_email']);
        $is_exist = $this->select($this->table, $this->pk, "user_email = '$user_email'");
        if ($is_exist->num_rows > 0) {
            $user_row = $is_exist->fetch_array();
            return $user_row[0];
        } else {
            //$pass = clean($this->inputs['password']);
            $form = array(
                'user_email' => $this->clean($this->inputs['user_email']),
                'user_fname' => $this->clean($this->inputs['user_fname']),
                //'user_mname' => clean($this->inputs['user_mname']),
                'user_lname' => $this->clean($this->inputs['user_lname']),
                'username' => $this->clean($this->inputs['username']),
                'user_photo' => $this->clean($this->inputs['user_photo']),
                'user_category' => 'U'
                //'password' => md5($pass),
            );
            return $this->insert($this->table, $form, "Y");
        }
    }
}
