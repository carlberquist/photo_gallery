<?php
interface Int_Connection
{
    function __construct(Int_Credentials $db_cred, $opts);
    function get_connection();
    function get_query($sql, $prepare);
    public function bind_query(&$object, $stmnt, $exclude);
}
?>