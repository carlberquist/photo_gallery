<?php
class Hmac implements Encryption
{
    private $key;
    private $enc_type;

    public function __contruct($enc_type = 'sha256', $key = 'C694AE29CFC1402BEF09225021ED311A')
    {
        $this->key = $key;
        $this->enc_type = $enc_type;
    }

    public function encode($data)
    {
        return hash_hmac($this->enc_type, $data, $this->key);
    }

    public function decode($data)
    {
        //$hashed_expected = hash_hmac($this->enc_type, $data, $this->key);
        return hash_equals(hash_hmac($this->enc_type, $data, $this->key), $data);
    }
}
?>