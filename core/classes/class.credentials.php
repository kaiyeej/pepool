<?php

class Credentials extends Connection {
    private $table = 'tbl_user_credentials';
    public $pk = 'credential_id';

    public function add(){
        if(isset($this->inputs['user_id'])){
            $user_id      = $this->clean($this->inputs['user_id']);
            $title  = $this->clean($this->inputs['title']);
            $date_received  = $this->clean($this->inputs['date_received']);
            $level  = $this->clean($this->inputs['level']);
            $image_data  = $this->clean($this->inputs['image_data']);

            $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image_data));
            $file_name = $user_id . '-' . date('mdyhis', strtotime($this->getCurrentDate())) . ".png";
            $file = file_put_contents('../assets/credentials/' . $file_name, $data);
            if($file){
                $form = array(
                    'user_id'               => $user_id,
                    'title'                 => $title,
                    'date_received'         => $date_received,
                    'level'                 => $level,
                    'file_name'             => $file_name,
                    'date_added'            => $this->getCurrentDate()
                );

                return $this->insertIfNotExist($this->table, $form, "title='$title' AND user_id='$user_id'");
            }else{
                return -1;
            }
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
        $result = $this->select($this->table, "*", "$this->pk  = '$primary_id'");
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
        $row = $this->rows($id);
        $sql = $this->delete($this->table, "$this->pk='$id'");
        if($sql){
            if($row['file_name'] !== ""){
                unlink("../assets/credentials/" . $row['file_name']);
            }
        }

        return $sql;
    }
}

?>