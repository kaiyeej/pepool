<?php

class LoginUser extends Connection
{
    private $table = 'tbl_users';
    public $pk = 'user_id';
    public $name = 'username';

    public function login()
    {

        $username = $this->inputs['username'];
        $password = $this->inputs['password'];

        $result = $this->select($this->table, "*", "username = '$username' AND password = md5('$password') AND category='A' AND status='1'");
        $row = $result->fetch_assoc();

        if ($row) {
            $_SESSION['status'] = "in";
            $_SESSION["trisakay_user_fullname"] = $row['user_fname']." ".$row['user_mname']." ".$row['user_lname'];
            $_SESSION["trisakay_username"] = $row['username'];
            $_SESSION["trisakay_user_category"] = $row['category'];
            $_SESSION["trisakay_user_id"] = $row['user_id'];

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
}
