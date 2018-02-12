<?php
class MySQLDatabase implements Int_Connection
{
    private $connection;

    public function __construct(Int_Credentials $db_cred, $opts = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false))
    {
        try {
            $db_cred = $db_cred->get_credentials();
            $this->connection = new PDO('mysql:host=' . $db_cred['host'] . ';dbname=' . $db_cred['dbname'] . '', $db_cred['usrname'], $db_cred['psword'], $opts);
        } catch (PDOException $e) {
            throw new Exception("Connection Error!: " . $e->getMessage() . "/n", 1);
            die();
        }
    }

    public function get_query($sql, $prepare = false)
    {
        try {
            if (is_array($prepare)) {
                $query = $this->connection->prepare($sql);
                return $query->execute($prepare);
            } else {
                return $this->connection->query($sql);
            }
        } catch (PDOException $e) {
            throw new Exception("PDO Query Error!: " . $e->getMessage() . '<br /> Error in query' . $sql . "/n", 1);
        }
    }

    public function bind_query(&$object, $stmnt, $exclude = '')
    {
        if (is_null($object_vars = get_object_vars($object))) {
            throw new Exception("No variables found in class: " . get_class($object));
            return false;
        }
        foreach ($object_vars as $attribute => $value) {
            if (is_array($exclude) && !in_array($attribute, $exclude)) {
                $stmnt->bindColumn($attribute, $object->{$attribute});
                continue;
            }
            if ($attribute != $exclude) {
                $stmnt->bindColumn($attribute, $object->{$attribute});
            }
        }
    }

    public function get_connection()
    {
        return $this->connection;
    }
}
?>