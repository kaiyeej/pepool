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
                'user_fname'    => $this->inputs['user_fname'],
                'user_mname'    => $this->inputs['user_mname'],
                'user_lname'    => $this->inputs['user_lname'],
                'username'      => $this->inputs['username'],
                'user_email'    => $this->inputs['user_email'],
                'user_contact_number'    => $this->inputs['user_contact_number'],
                
            );
            return $this->update($this->table, $form, "$this->pk = '$primary_id'");
        }
    }

    public function save_signature(){
        if(isset($this->inputs['user_id'])){
            $data = $this->clean($this->inputs['image_data']);
            $user_id = $this->clean($this->inputs['user_id']);
            $row = $this->rows($user_id);
            $e_sign = $row['e_signature'];

            $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));
            $file_name = $user_id . '-' . date('mdyhis', strtotime($this->getCurrentDate())) . ".png";
            $file = file_put_contents('../assets/' . $file_name, $data);
            if($file){
                $result = $this->update($this->table, ['e_signature' => $file_name], "user_id='$user_id'");
                if($result){
                    if($e_sign !== ""){
                        unlink("../assets/" . $e_sign);
                    }
                    return $file_name;
                }else{
                    return -1;
                }
            }
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

    public function update_password()
    {
        $primary_id = $this->inputs[$this->pk];
        $old_password = $this->inputs['old_password'];
        $is_exist = $this->select($this->table, $this->pk, "password = md5('$old_password') AND $this->pk = '$primary_id'");
        if ($is_exist->num_rows <= 0) {
            return 2;
        } else {
            $pass = $this->clean($this->inputs['new_password']);
            $form = array(
                'password' => md5($pass)
            );
            return $this->update($this->table, $form, "$this->pk = '$primary_id'");
        }
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

    public function show_recommeded()
    {
        $rows = array();
        $ids = $this->clean($this->inputs['ids']);
        $arr_recommeded = array();
        foreach ($ids as $value) {
            $user_id = $value['itemId'];
            if($user_id > 0){
                array_push($arr_recommeded, $user_id);
            }
        }

    
        $user_ids = implode(",", $arr_recommeded);

        $rows = array();
        $count = 1;
        $result = $this->select($this->table, '*', "user_id IN ($user_ids) ORDER BY user_fname ASC");
        while ($row = $result->fetch_assoc()) {
            $row['count'] = $count++;
            //$row['user_photo'] = $row['user_photo'] == "" ? "./worker.png" : $row['user_photo'];
            $row['user_fullname'] = $row['user_fname'] . " " . $row['user_mname'] . " " . $row['user_lname'];
            $rows[] = $row;
        }
        return $rows;
    }

    public function view()
    {
        $primary_id = $this->clean($this->inputs['id']);
        $result = $this->select($this->table, "*", "$this->pk = '$primary_id'");
        $row = $result->fetch_assoc();

        $fetch_jobs_posted = $this->select("tbl_job_posting", "COUNT(job_post_id) as count", "$this->pk = '$primary_id'");
        $job_posted_row = $fetch_jobs_posted->fetch_assoc();

        $fetch_approved = $this->select("tbl_transactions", "count(transaction_id) as total_approved", "$this->pk = '$primary_id' AND (status='O' or status='F')");
        $approved_row = $fetch_approved->fetch_assoc();

        $fetch_transactions = $this->select("tbl_transactions", "count(transaction_id) as total_applications", "$this->pk = '$primary_id'");
        $transactions_row = $fetch_transactions->fetch_assoc();

        $row['user_fullname'] = $row['user_fname'] . " " . $row['user_mname'] . " " . $row['user_lname'];
        $row['member_since'] = date("M d, Y", strtotime($row['date_added']));
        $row['jobs_posted'] = $job_posted_row['count'];
        $row['total_approved'] = $approved_row['total_approved'];
        $row['total_applications'] = $transactions_row['total_applications'];
        return $row;
    }

    public static function name($primary_id)
    {
        $self = new self;
        $result = $self->select($self->table, $self->name, "$self->pk  = '$primary_id'");
        $row = $result->fetch_assoc();
        return $row[$self->name];
    }

    
    public static function rows($primary_id)
    {
        $self = new self;
        $result = $self->select($self->table, "*", "$self->pk  = '$primary_id'");
        $row = $result->fetch_assoc();
        return $row;
    }

    public static function getUser($primary_id)
    {
        $self = new self;
        $result = $self->select($self->table, "*", "$self->pk  = '$primary_id'");
        $row = $result->fetch_assoc();
        return $row['user_fname'] . " " . $row['user_mname'] . " " . $row['user_lname'];
    }

    public function update_token(){
        $primary_id = $this->clean($this->inputs['user_id']);
        $token = $this->clean($this->inputs['token']);

        $form = array(
            'push_notification_token' => $token
        );

        return $this->update($this->table, $form, "user_id='$primary_id'");
    }

    public function login()
    {

        $username = $this->inputs['username'];
        $password = $this->inputs['password'];

        $result = $this->select($this->table, "*", "username = '$username' AND password = md5('$password') AND user_category='A'");
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

    public function login_mobile()
    {

        $username = $this->inputs['username'];
        $password = $this->inputs['password'];

        $result = $this->select($this->table, "*", "username = '$username' AND password = md5('$password') AND user_category='U'");
        $row = $result->fetch_assoc();

        if ($result->num_rows > 0) {
            $res = $row;
        } else {
            $res = 0;
        }

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
                //'user_mname' => $this->clean($this->inputs['user_mname']),
                'user_lname' => $this->clean($this->inputs['user_lname']),
                'username' => $this->clean($this->inputs['username']),
                'user_photo' => $this->clean($this->inputs['user_photo']),
                'user_category' => 'U'
                //'password' => md5($pass),
            );
            return $this->insert($this->table, $form, "Y");
        }
    }

    public function register_manual(){
        $user_email = $this->clean($this->inputs['user_email']);
        $is_exist = $this->select($this->table, $this->pk, "user_email = '$user_email'");
        if ($is_exist->num_rows > 0) {
            return -2;
        } else {
            //$pass = clean($this->inputs['password']);
            $form = array(
                'user_email' => $this->clean($this->inputs['user_email']),
                'user_fname' => $this->clean($this->inputs['user_fname']),
                'user_mname' => $this->clean($this->inputs['user_mname']),
                'user_lname' => $this->clean($this->inputs['user_lname']),
                'username' => $this->clean($this->inputs['user_email']),
                'user_address' => $this->clean($this->inputs['user_address']),
                'user_contact_number' => $this->clean($this->inputs['user_contact_number']),
                'password' => $this->clean(md5($this->inputs['password'])),
                'user_photo' => '',
                'user_category' => 'U'
                //'password' => md5($pass),
            );
            return $this->insert($this->table, $form, "Y");
        }
    }
}
