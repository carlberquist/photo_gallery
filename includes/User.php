<?php
class User implements Int_User
{
    public $table_name = 'users';
    private $connection;
    private $encryption;
    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;
    public $usr_first_last;

    public function __construct(Int_Connection $connection, Int_Encryption $encryption = null)
    {
        $this->connection = $connection;
        $this->encryption = $encryption;
    }

    private function insert_user($username, $password, $first_name, $last_name)
    {
        $sql = "INSERT INTO " . $this->table_name . " (id, username, password, first_name, last_name) VALUES ('', ?, ?, ?, ?)";
        $stmnt = $this->connection->get_connection()->get_query($sql, array($username, $this->encryption->encode($password), $first_name, $last_name));
        return $stmnt->affected_rows();
    }

    private function update_user($username = '', $password = '', $first_name = '', $last_name = '')
    {
        $query = '';
        $stmntExecute = array();
        if (!empty($username)) {
            $query .= "username = ?,";
            $stmntExecute[] = $username;
        }
        if (!empty($password)) {
            $query .= "password = ?,";
            $stmntExecute[] = $this->encryption->encode($password);
        }
        if (!empty($first_name)) {
            $query .= "first_name = ?,";
            $stmntExecute[] = $first_name;
        }
        if (!empty($last_name)) {
            $query .= "last_name = ?,";
            $stmntExecute[] = $last_name;
        }
        if (!empty($username) && !empty($password) && !empty($first_name) && !empty($last_name)) {
            throw new Exception('Please fill in at least one field');
            return;
        }
        $query = substr($query, 0, -1);
        $stmnt = $this->connection->get_query("UPDATE " . $this->table_name . " SET {$query} WHERE id = {$this->id} LIMIT 1", $stmntExecute);
        $this->set_user_by_id($this->connection, $this->id);
        return $stmnt->affected_rows();
    }
//unset user object after calling delete
    public function delete_user()
    {
        $stmnt = $this->connection->get_query("DELETE FROM " . $this->table_name . " WHERE id = {$this->id} LIMIT 1");
        return ($stmnt->affected_rows() == 1 ? true : false);
    }
    public function insert_update_user($username = '', $password = '', $first_name = '', $last_name = '')
    {
        isset($this->id) ? $this->update_user($this->connection, $this->encryption, $username, $password, $first_name, $last_name) : $this->insert_user($this->connection, $this->encryption, $username, $password, $first_name, $last_name);
    }
    /***
     * @param interface Connection = database connection
     * $param optional number $id
     * return PDO
     */
    public function set_user_by_id($id)
    {
        $stmnt = $this->connection->get_query("SELECT * FROM " . $this->table_name . " WHERE id = {$id} LIMIT 1", $this);
        $this->set_user_first_last();
    }
    public function set_user_by_username($username)
    {
        $stmnt = $this->connection->get_query("SELECT * FROM " . $this->table_name . " WHERE username = ? LIMIT 1", $this, array(trim($username)));
        $this->set_user_first_last();
    }
    private function set_user_first_last()
    {
        if (isset($this->first_name) && isset($this->last_name)) {
            $this->usr_first_last = $this->first_name . " " . $this->last_name;
        }
    }
    public function get_user_var($var)
    {
        if (isset($this->{$var})) {
            return $this->{$var};
        } else {
            throw new Exception("{$var} not found in " . get_class($this), 1);
        }
    }
}
?>