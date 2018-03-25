<?php
interface Int_User
{
    //function insert_user($username, $password, $first_name, $last_name);
    //function update_user($username = '', $password = '', $first_name = '', $last_name = '');
    function delete_user();
    function insert_update_user($username = '', $password = '', $first_name = '', $last_name = '');
    function set_user_by_id($id);
    function set_user_by_username($username);
    //function set_user_first_last();
    function get_user_var($var);
}
?>