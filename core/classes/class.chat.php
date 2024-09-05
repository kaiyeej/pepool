<?php

class Chats extends Connection {
    private $table = 'tbl_chat_messages';
    public $pk = 'chat_message_id';

    public function add(){
        if(isset($this->inputs['user_id'])){
            $Notifications = new Notifications;
            $sender_id      = $this->clean($this->inputs['user_id']);
            $transaction_id  = $this->clean($this->inputs['transaction_id']);
            $content  = $this->clean($this->inputs['content']);
            
            $form = array(
                'sender_id'   => $sender_id,
                'transaction_id'        => $transaction_id,
                'content'               => $content,
                'date_added'            => $this->getCurrentDate()
            );

            $Notifications->send_notification_to_chat($transaction_id, $content);
            return $this->insert($this->table, $form);
        }
    }

    public function show()
    { 
        $id = $this->clean($this->inputs['id']);
        $rows = array();
        $count = 1;
        $result = $this->select($this->table, '*', "transaction_id='$id' ORDER BY date_added ASC");
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

}

?>