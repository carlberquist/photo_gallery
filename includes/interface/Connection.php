<?php
interface Connection
{
    function __construct(credentials $db_cred, $opts);
    function get_connection();
}
?>