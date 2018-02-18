<?php
class MySQLDatabase implements Int_Connection
{
    private $connection;

    public function __construct(Int_Credentials $db_cred, $opts = null)
    {
        $this->get_connection($db_cred, $opts);
    }

    public function get_query($sql, $prepare = false)
    {
        try {
            if (is_array($prepare)) {
                $query = $this->get_connection()->prepare($sql);
                $query->execute($prepare);
                return $query;
            } else {
                return $this->get_connection()->query($sql);
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
        try {
            foreach ($object_vars as $attribute => $value) {
                if (is_array($exclude) && !in_array($attribute, $exclude)) {
                    $stmnt->bindColumn($attribute, $object->{$attribute});
                } else if (!is_array($exclude) && $attribute != $exclude) {
                    $stmnt->bindColumn($attribute, $object->{$attribute});
                }
            }
        } catch (PDOException $e) {
            throw new Exception("Binding Error!: " . $e->getMessage() . "/n", 1);
        }
    }

    public function get_connection(Int_Credentials $db_cred = null, $opts = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false))
    {
        try {
            if ($this->connection instanceof PDO && !is_null($this->connection->getAttribute(PDO::ATTR_CONNECTION_STATUS))) {
                return $this->connection;
            } else {
                $this->connection = new PDO('mysql:host=' . $db_cred->get_credentials('host') . ';dbname=' . $db_cred->get_credentials('dbname') . '', $db_cred->get_credentials('usrname'), $db_cred->get_credentials('psword'), $opts);
                return $this->connection;
            }
        } catch (PDOException $e) {
            throw new Exception("Connection Error!: " . $e->getMessage() . "/n", 1);
        }
    }
}
?>