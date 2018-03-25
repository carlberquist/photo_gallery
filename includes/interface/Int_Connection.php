<?php
interface Int_Connection
{
    function __construct(Int_Credentials $db_cred, $opts);
    function get_connection($opts);
    function set_connection($opts);
    function get_query($sql, $object);
}
?>