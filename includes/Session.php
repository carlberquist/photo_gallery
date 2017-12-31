<?php
class Session
{

    private $logged_in = false;

    public function __construct()
    {
        session_start();
        $this->check_login();
    }

    private function check_login()
    {
        if (isset($_SESSION['user_uid'])) {
            $this->logged_in = true;
        } else {
            $this->logged_in = false;
        }
    }

    public function login(User $user)
    {
            $_SESSION['user_uid'] = $user->get_user_var('id');
            $this->logged_in = true;
    }
    public function logout()
    {
        unset($_SESSION['user_uid']);
        $this->logged_in = false;
    }

    public function get_logged_in()
    {
        return $this->logged_in;
    }
}
?>