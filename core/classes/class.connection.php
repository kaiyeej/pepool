<?php
class Connection
{
    public $que;
    private $servername = HOST;
    private $username = USER;
    private $password = PASSWORD;
    private $dbname = DBNAME;
    private $result = array();
    private $mysqli = '';

    public function __construct()
    {
        $this->mysqli = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    }

    public function insert($table, $para = array(), $last_id = 'N')
    {
        $table_columns = implode(',', array_keys($para));
        $table_value = implode("','", $para);

        $sql = "INSERT INTO $table($table_columns) VALUES('$table_value')";

        $result = $this->mysqli->query($sql) or die($this->mysqli->error);
        $lastId = $this->mysqli->insert_id;
        $ret_ = ($last_id == 'Y') ? $lastId : 1;
        return $result ? $ret_ : 0;
    }

    public function insert_select($table, $table_select, $para, $where_clause = '')
    {
        $table_columns = array_keys($para);
        $table_value = implode(",", $para);
        $inject = ($where_clause == '') ? "" : "WHERE $where_clause";

        $sql = "INSERT INTO " . $table . " (`" . implode('`,`', $table_columns) . "`) SELECT $table_value FROM $table_select $inject";

        $result = $this->mysqli->query($sql) or die($this->mysqli->error);
        return $result ? 1 : 0;
    }

    public function insertIfNotExist($table, $form, $param = '', $last_id = 'N')
    {
        $inject = $param != '' ? $param : "$this->name = '" . $this->clean($this->inputs[$this->name]) . "'";
        $is_exist = $this->select($table, $this->pk, $inject);
        if ($is_exist->num_rows > 0) {
            return $last_id == 'Y' ? -2 : 2;
        } else {
            return $this->insert($table, $form, $last_id);
        }
    }

    public function update($table, $para = array(), $id)
    {
        $args = array();

        foreach ($para as $key => $value) {
            $args[] = "$key = '$value'";
        }

        $sql = "UPDATE  $table SET " . implode(',', $args);

        $sql .= " WHERE $id";

        $result = $this->mysqli->query($sql) or die($this->mysqli->error);
        return $result ? 1 : 0;
    }

    public function updateIfNotExist($table, $form)
    {
        $primary_id = $this->inputs[$this->pk];
        $name = $this->clean($this->inputs[$this->name]);
        $is_exist = $this->select($table, $this->pk, "$this->name = '$name' AND $this->pk != '$primary_id'");
        if ($is_exist->num_rows > 0) {
            return 2;
        } else {
            return $this->update($table, $form, "$this->pk = '$primary_id'");
        }
    }

    public function delete($table, $id)
    {
        $sql = "DELETE FROM $table";
        $sql .= " WHERE $id ";
        $sql;
        return $this->mysqli->query($sql) or die($this->mysqli->error);
    }

    public $sql;

    public function select($table, $rows = "*", $where = null)
    {
        $sql = "SELECT $rows FROM $table";
        $inject = $where != null ? " WHERE $where" : "";

        $sql .= $inject;

        return $this->mysqli->query($sql);
    }

    public function encrypt($password, $algo = PASSWORD_DEFAULT)
    {
        return password_hash($password, $algo);
    }

    public function clean($slug)
    {
        if (is_string($slug)) {
            return filter_var($slug, FILTER_SANITIZE_STRING);
        } else {
            return $slug;
        }
    }

    public function getCurrentDate()
    {
        ini_set('date.timezone', 'UTC');
        //error_reporting(E_ALL);
        date_default_timezone_set('UTC');
        $today = date('H:i:s');
        $system_date = date('Y-m-d H:i:s', strtotime($today) + 28800);
        return $system_date;
    }

    public function metadata($name, $type, $length = '', $allow_null = 'NOT NULL', $default = '', $extra = '', $comment = '')
    {
        return array(
            'name'          => $name,
            'type'          => $type,
            'length'        => $length,
            'allow_null'    => $allow_null,
            'default'       => $default,
            'extra'         => $extra,
            'comment'       => $comment,
        );
    }

    public function schemaCreator($tables)
    {
        $create = "";
        foreach ($tables as $table) {
            $name = $table['name'];
            $fields = $table['fields'];
            $is_exists = $this->table_exists($name);

            $field_list = array();
            foreach ($fields as $field) {

                $fld = array();
                $fld[] = "`$field[name]`";
                $fld[] = $field['type'] . ($field['length'] > 0 ? "($field[length])" : "");
                $fld[] = $field['allow_null'];
                $fld[] = $field['default'] != '' ? "DEFAULT $field[default]" : "";
                $fld[] = $field['extra'];
                $fld[] = $field['comment'] != '' ? "COMMENT $field[comment]" : "";

                if ($is_exists == 1) {
                    // $is_column_exists
                    if ($this->column_exists($name, $field['name']) != 1) {
                        array_push($field_list, (" ADD " . implode(" ", $fld)));
                    }
                } else {
                    $metadata = implode(" ", $fld);
                    array_push($field_list, $metadata);
                }
            }
            $is_exists == 1 ? "" : array_push($field_list, "PRIMARY KEY (`{$table['primary']}`)");
            if (count($field_list) > 0) {
                if ($is_exists == 1) {
                    $query = "ALTER TABLE `$name`";
                } else {
                    $query = "CREATE TABLE `$name` (";
                }
                $query .= implode(",", $field_list);
                $query .= $is_exists == 1 ? "" : ') ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;';
                $create .= $query;
                $this->mysqli->query($query);
            }
        }
        return $create;
    }

    function table_exists($table)
    {
        $result = $this->mysqli->query("SHOW TABLES LIKE '{$table}'");
        if ($result->num_rows == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
        $result->free();
    }

    function column_exists($table_name, $column_name)
    {
        $db_name = DBNAME;
        $result = $this->mysqli->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$db_name' AND TABLE_NAME = '$table_name' AND COLUMN_NAME = '$column_name'");
        if ($result->num_rows == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
        $result->free();
    }

    public $join = array();
    public $tablename = '';
    public $select2 = array();
    public $where = array();
    public $groupBy = '';

    public function table($table)
    {
        $this->tablename = $table;
        return $this;
    }

    public function selectRaw(...$select2)
    {
        foreach ($select2 as $query) {
            $this->select2[] = $query;
        }
        return $this;
    }

    public function join($table, $from, $identifier, $to)
    {
        $this->join[] = "INNER JOIN $table ON $from $identifier $to";
        return $this;
    }

    public function where($column, $equal, $to = '')
    {
        $where = ($to != '') ? "$equal '$to'" : "= '$equal'";
        $this->where[] = "$column $where";
        return $this;
    }

    public function groupBy($column)
    {
        $this->groupBy = "GROUP BY $column";
        return $this;
    }

    public function get()
    {
        $where = count($this->where) > 0 ? "WHERE " . implode(' AND ', $this->where) : '';
        $sql = "SELECT " . (count($this->select2) > 0 ? implode(",", $this->select2) : '*') . " FROM $this->tablename " . implode(' ', $this->join) . " $where $this->groupBy";

        return $this->mysqli->query($sql);
    }

    public function __destruct()
    {
        $this->mysqli->close();
    }
}
