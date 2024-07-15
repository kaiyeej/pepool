<?php

class Homepage extends Connection
{
    public function total_user()
    {
        $result = $this->select("tbl_users", "count(user_id)");
        $row = $result->fetch_array();
        return $row[0];
    }

    public function total_transaction()
    {
        $result = $this->select("tbl_transactions", "count(transaction_id)", "status = 'F'");
        $row = $result->fetch_array();
        return $row[0];
    }

    
    public function total_job_types()
    {
        $result = $this->select("tbl_job_types", "count(job_type_id)");
        $row = $result->fetch_array();
        return $row[0];
    }

    public function total_post()
    {
        $result = $this->select("tbl_job_posting", "count(job_post_id)");
        $row = $result->fetch_array();
        return $row[0];
    }
}
