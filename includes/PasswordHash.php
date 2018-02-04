<?php
class PasswordHash implements Encryption
{
    public function encode($data)
    {
        return password_hash($data, PASSWORD_DEFAULT);
    }

    public function decode($input, $data)
    {
        return password_verify($input, $data);
    }
}
?>