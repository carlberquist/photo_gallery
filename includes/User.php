<?php
class User
{
    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;
    public $usr_first_last;

    public function insert_user(Int_Connection $connection, Int_Encryption $encryption, $username, $password, $first_name, $last_name)
    {
        $sql = "INSERT INTO users (id, username, password, first_name, last_name) VALUES ('', ?, ?, ?, ?)";
        try {
            $stmnt = $connection->get_connection()->prepare($sql);
            $stmnt->execute(array($username, $encryption->encode($password), $first_name, $last_name));
        } catch (PDOException $e) {
            print "PDO Query Error!: " . $e->getMessage() . '<br /> Error in query' . $sql;
        }
    }

    public function update_user(Int_Connection $connection, Int_Encryption $encryption, $username = '', $password = '', $first_name = '', $last_name = '')
    {
        $query = '';
        $stmntExecute = array();
        if (!empty($username)) {
            $query .= "username = ?,";
            $stmntExecute[] = $username;
        }
        if (!empty($password)) {
            $query .= "password = ?,";
            $stmntExecute[] = $encryption->encode($password);
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
        $connection->get_query("UPDATE users SET {$query} WHERE id = {$this->id}", $stmntExecute);
        $this->set_user_by_id($connection, $this->id);
    }
    /***
     * @param interface Connection = database connection
     * $param optional number $id
     * return PDO
     */
    public function set_user_by_id(Int_Connection $connection, $id)
    {
        $stmnt = $connection->get_query("SELECT * FROM users WHERE id = {$id}");
        $connection->bind_query($this, $stmnt, "usr_first_last");
        $stmnt->fetch(PDO::FETCH_BOUND);
        $this->set_user_first_last();
    }
    public function set_user_by_username(Int_Connection $connection, $username)
    {
        $stmnt = $connection->get_query("SELECT * FROM users WHERE username = ?", array(trim($username)));
        $connection->bind_query($this, $stmnt, "usr_first_last");
        $stmnt->fetch(PDO::FETCH_BOUND);
        $this->set_user_first_last();
    }
    private function set_user_first_last()
    {
        if (isset($this->first_name) && isset($this->last_name)) {
            $this->usr_first_last = $this->first_name . " " . $this->last_name;
        }
    }

    public function authenticate_user(Int_Encryption $encryption, Session $session, $password, Int_Connection $connection = null, $username = null)
    {
        if (isset($this->password)) {
            if ($encryption->decode($password, $this->password)) {
                return $session->login($this);
            } else {
                return false;
            }
        } else if (!is_null($connection) && !is_null($username)) {
            $stmnt = $connection->get_query("SELECT id, password FROM users WHERE username = ?", array(trim($username)));
            $connection->bind_query($this, $stmnt, array("username", "first_name", "last_name", "usr_first_last", ));
            $stmnt->fetch(PDO::FETCH_BOUND);
            $this->authenticate_user($encryption, $session, $password);
        } else {
            return false;
        }
    }
    public function get_all_users(Int_Connection $connection)
    {
        $sql = "SELECT * FROM users";
        $stmnt = $connection->get_connection()->prepare($sql);
        $stmnt->execute();
        while ($user = $stmnt->fetch(PDO::FETCH_ASSOC)) {
            //do something with all users
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
    //TODO check this
    public function set_user_var($var)
    {
        if (!$this->{$var} = $var) {
            throw new Exception("{$var} not set in " . get_class($this), 1);
        }
    }
    public function get_all_user_vars()
    {
        return get_object_vars($this);
    }
}
?>