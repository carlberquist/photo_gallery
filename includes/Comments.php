<?php
class Comments
{
    private $connection;
    public $table_name = "comments";
    private $photograph;
    private $user;
    public $comments;
    //table fields
    public $id;
    public $photograph_id;
    public $created;
    public $author;
    public $body;

    public function __construct(Int_Connection $connection, Files $photograph, User $user = null, $table_name = null, $user_table_name = null)
    {
        $this->connection = $connection;
        $this->photograph = $photograph;
        if (is_null($user)) {
            $this->user = new stdClass();
            $this->user->id = '';
            if (is_null($user_table_name)) {
                $this->user->table_name = 'users';
            } else {
                $this->user->table_name = $user_table_name;
            }

        } else {
            $this->user = $user;
        }
        if (!is_null($table_name)) {
            $this->table_name = $table_name;
        }
    }
    public function find_comments_photo_id()
    {
        $stmnt = "SELECT {$this->table_name}.id,  {$this->table_name}.created, {$this->table_name}.body, CONCAT({$this->user->table_name}.first_name,' ', {$this->user->table_name}.last_name) AS author FROM {$this->table_name} LEFT JOIN {$this->user->table_name} on {$this->table_name}.author = {$this->user->table_name}.id WHERE photograph_id = '{$this->photograph->id}'";
        $stmnt = $this->connection->get_query($stmnt, true);
        if (!is_array($stmnt)) {
            throw new Exception("Query returned not in array format", 1);
            return false;
        }
        foreach ($stmnt as $value) {
            if (is_null($value->author)) {
                $value->author = "anonymous";
            }
        }
        $this->comments = $stmnt;
    }
    public function insert_comment($body)
    {
        $stmnt = "INSERT INTO " . $this->table_name . "(id, photograph_id, created, author, body) VALUES (null,{$this->photograph->id},null,{$this->$user->id}, {$body})";
        $this->connection->get_query($stmnt);
        if ($stmnt->rowCount() == 1) {
            return true;
        } else {
            throw new Exception("Comment could not be inserted", 1);
            return false;
        }
    }
    public function delete_comment()
    {
        $stmnt = "DELETE FROM comments where id = {$this->id} LIMIT 1";
        $this->connection->get_query($stmnt);
        if ($stmnt->rowCount() == 1) {
            return true;
        } else {
            throw new Exception("Comment could not be deleted", 1);
            return false;
        }
    }
}
?>