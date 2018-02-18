<?php
class PasswordHash implements Int_Encryption
{
    public function encode($data)
    {
        return password_hash($data, PASSWORD_DEFAULT);
    }

    public function decode($input, $data)
    {
        if (!password_verify($input, $data)){
            throw new Exception('Password does not match');
            return false;
        }
        return true;
    }
}
?>