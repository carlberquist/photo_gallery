<?php
interface Connection{
    public function __construct(credentials $db_cred, $opts = NULL);
    public function get_connection();
}
?>