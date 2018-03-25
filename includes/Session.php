<?php
class Session
{
    private $logged_in = false;
    private $user;
    private $encryption;

    public function __construct(Int_User $user, Int_Encryption $encryption)
    {
        $this->user = $user;
        $this->encryption = $encryption;
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function check_login()
    {
        if (isset($_SESSION['user_uid'])) {
            $this->logged_in = true;
        } else {
            $this->logged_in = false;
        }
        return $this->logged_in;
    }

    public function login($password)
    {
        if ($this->encryption->decode($password, $this->user->get_user_var('password'))) {
            $_SESSION['user_uid'] = $this->user->id;
            $this->logged_in = true;
        } else {
            $this->logged_in = false;
        }
        return $this->logged_in;
    }
    public function logout()
    {
        if (isset($_SESSION['user_uid'])) {
            unset($_SESSION['user_uid']);
        }
        $this->logged_in = false;
    }

    public function get_logged_in($var)
    {
        if (isset($this->{$var})) {
            return $this->{$var};
        } else {
            throw new Exception("{$var} not found in " . get_class($this), 1);
        }
    }
}
?>