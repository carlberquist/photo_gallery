<?php
class User
{
    private $id;
    private $username;
    private $password;
    private $first_name;
    private $last_name;
    private $usr_first_last;

    private function bind_query($stmnt, $exclude)
    {
        $object_vars = get_object_vars($this);
        if (is_array($exclude)) {
            foreach ($object_vars as $attribute => $value) {
                foreach ($exclude as $exc) {
                    if ($attribute != $exc) {
                        $stmnt->bindColumn($attribute, $this->{$attribute});
                    }
                }
            }
        } else {
            foreach ($object_vars as $attribute => $value) {
                if ($attribute != $exclude) {
                    $stmnt->bindColumn($attribute, $this->{$attribute});
                }

            }
        }
    }

    public function insert_user(Connection $connection, Encryption $encryption, $username, $password, $first_name, $last_name)
    {
        $sql = "INSERT INTO users (ID, USERNAME, PASSWORD, FIRST_NAME, LAST_NAME) VALUES ('', ?, ?, ?, ?)";
        try {
            $stmnt = $connection->get_connection()->prepare($sql);
            $stmnt->execute(array($username, $encryption->encode($password), $first_name, $last_name));
        } catch (PDOException $e) {
            print "PDO Query Error!: " . $e->getMessage() . '<br /> Error in query' . $sql;
        }
    }

    public function update_user(Connection $connection, Encryption $encryption, $username, $password, $first_name, $last_name)
    {
        $sql = "UPDATE users SET USERNAME = ?, PASSWORD = ?, FIRST_NAME = ?, LAST_NAME = ?";
        try {
            $stmnt = $connection->get_connection()->prepare($sql);
            $stmnt->execute(array($username, $encryption->encode($password), $first_name, $last_name));
        } catch (PDOException $e) {
            print "PDO Query Error!: " . $e->getMessage() . '<br /> Error in query' . $sql;
        }
    }
    /***
     * @param interface Connection = database connection
     * $param optional number $id
     * return PDO
     */
    public function set_user_by_id(Connection $connection, $id)
    {
        $sql = "SELECT * FROM users WHERE id = {$id}";
        try {
            $stmnt = $connection->get_connection()->prepare($sql);
            $stmnt->execute();
            $this->bind_query($stmnt, 'usr_first_last');
            return $stmnt->fetch(PDO::FETCH_BOUND);
        } catch (PDOException $e) {
            print "PDO Query Error!: " . $e->getMessage() . '<br /> Error in query' . $sql;
        }
    }
    public function set_user_first_last()
    {
        if (isset($this->first_name) && isset($this->last_name)) {
            $this->usr_first_last = $this->first_name . " " . $this->last_name;
        }
    }
    public function set_user_authenticate(Connection $connection, Encryption $encryption, $username, $password)
    {
        $bind = array();
        $bind[] = trim($username);
        $bind[] = trim($password);
        $sql = "SELECT * FROM users WHERE username = ?";
        try {
            $stmnt = $connection->get_connection()->prepare($sql);
            $stmnt->execute($bind);
            $this->bind_query($stmnt, 'usr_first_last');
            return ($stmnt->fetch(PDO::FETCH_BOUND) && $encryption->decode($bind[1], $this->password)) ? true : false;
        } catch (PDOException $e) {
            print "PDO Query Error!: " . $e->getMessage() . '<br /> Error in query' . $sql;
        }
    }

    public function get_all_users(Connection $connection)
    {
        $sql = "SELECT * FROM users";
        try {
            $stmnt = $connection->get_connection()->prepare($sql);
            $stmnt->execute();
            while ($user = $stmnt->fetch(PDO::FETCH_ASSOC)) {
            //do something with all users
            }
        } catch (PDOException $e) {
            print "PDO Query Error!: " . $e->getMessage() . '<br /> Error in query' . $sql;
        }
    }
    public function get_user_var($var)
    {
        if (isset($this->{$var})) {
            return $this->{$var};
        }
    }
    public function get_all_user_vars()
    {
        return get_object_vars($this);
    }
}
?>