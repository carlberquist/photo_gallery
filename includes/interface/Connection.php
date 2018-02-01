<?php
interface Connection{
    public function __construct(credentials $db_cred, $opts);
    public function get_connection();
}
?>