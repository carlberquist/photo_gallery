<?php
class Query
{
    /***
     * if $prepared == true return prepared statement,
     * else return executed prepared statment
     * if $bind == null perform normal query
     * query("UPDATE users SET column = ? WHERE id = ?", array($value1, $value2))
     * 
     * @param string $sql
     * @param optional array $bind 
     * $param optional bool $prepared true / false
     * return PDOStatement object
     */
    public function get_query(Connection $connection, $sql, $bind = NULL, $prepared = FALSE)
    {
        try {
            if ($bind && is_array($bind)) {
                $query = $connection->get_connection()->prepare($sql);
                $query->execute($bind);
                return $query;
            } else if ($prepared && $bind === NULL) {
                return $connection->get_connection()->prepare($sql);
            } else {
                return $connection->get_connection()->query($sql);
            }
        } catch (PDOException $e) {
            print "PDO Query Error!: " . $e->getMessage() . '<br /> Error in query' . $sql;
        }
    }
}
?>