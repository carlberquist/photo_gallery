<?php
class MySQLDatabase implements Int_Connection
{
    private $connection;
    private $db_cred;

    public function __construct(Int_Credentials $db_cred, $opts = null)
    {
        $this->db_cred = $db_cred;
        $this->set_connection($opts);
    }

    public function get_connection($opts = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false))
    {
        try {
            if ($this->connection instanceof PDO && !is_null($this->connection->getAttribute(PDO::ATTR_CONNECTION_STATUS))) {
                return $this->connection;
            } else {
                $this->set_connection($opts);
                return $this->connection;
            }
        } catch (PDOException $e) {
            throw new Exception("Connection Error!: " . $e->getMessage() . "/n", 1);
        }
    }

    public function set_connection($opts = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false))
    {
        try {
            if ($this->connection instanceof PDO && !is_null($this->connection->getAttribute(PDO::ATTR_CONNECTION_STATUS))) {
            } else {
                $this->connection = new PDO('mysql:host=' . $this->db_cred->get_credentials('host') . ';dbname=' . $this->db_cred->get_credentials('dbname') . ';charset=' . $this->db_cred->get_credentials('charset') . '', $this->db_cred->get_credentials('usrname'), $this->db_cred->get_credentials('psword'), $opts);
            }
        } catch (PDOException $e) {
            throw new Exception("Connection Error!: " . $e->getMessage() . "/n", 1);
        }
    }

    public function get_query($sql, $object = null, $prepare = null)
    {
        if (is_array($object) && is_null($prepare)) {
            $prepare = $object;
            $object = null;
        }
        try {
            if (strpos($sql, "?") || strpos($sql, ":")) {
                if (is_null($object) && !is_null($prepare)) {
                    $query = $this->get_connection()->prepare($sql);
                    $query->execute($prepare);
                    return $query;
                } else if (is_object($object) && !is_null($prepare)) {
                    $query = $this->get_connection()->prepare($sql);
                    $query->setFetchMode(PDO::FETCH_INTO, $object);
                    $query->execute($prepare);
                    $query->fetch();
                } else if (is_string($object) && !is_null($prepare)) {
                    $query = $this->get_connection()->prepare($sql);
                    $query->execute($prepare);
                    return $query->fetchAll(PDO::FETCH_CLASS, $object);
                } else if (is_bool($object) && !is_null($prepare)) {
                    $query = $this->get_connection()->prepare($sql);
                    $query->execute($prepare);
                    return $query->fetchAll(PDO::FETCH_OBJ);
                } else {
                    throw new Exception("Object: " . $object . " or Prepare: " . $prepare . " is not valid in prepared statement", 1);
                    return false;
                }
            } else {
                if (is_object($object)) {
                    $query = $this->get_connection()->query($sql);
                    $query->setFetchMode(PDO::FETCH_INTO, $object);
                    $query->execute();
                    $query->fetch();
                } else if (is_string($object) && class_exists($object)) { // || interface_exists($object)
                    $query = $this->get_connection()->query($sql);
                    $query->execute();
                    return $query->fetchAll(PDO::FETCH_CLASS, $object);
                } else if (is_bool($object)) {
                    $query = $this->get_connection()->query($sql);
                    $query->execute();
                    return $query->fetchAll(PDO::FETCH_OBJ);
                } else {
                    return $this->get_connection()->query($sql);
                }
            }
        } catch (PDOException $e) {
            throw new Exception("PDO Query Error!: " . $e->getMessage() . '<br /> Error in query' . $sql . "/n", 1);
        }
    }
    public function __destruct()
    {
        $this->connection = null;
    }
}
?>