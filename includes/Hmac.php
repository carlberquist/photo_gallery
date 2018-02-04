<?php
class Hmac implements Encryption
{
    private $key = 'C694AE29CFC1402BEF09225021ED311A';
    private $enc_type = 'sha256'; 

    public function __contruct($enc_type = '', $key = '')
    {
        if (!empty($enc_type)) {
            $this->key = $key;
        }
        if (!empty($key)) {
            $this->enc_type = $enc_type;
        }
    }
/***
 * generates 64 char string
 */
    public function encode($data)
    {
        return hash_hmac($this->enc_type, $data, $this->key);
    }

    public function decode($input, $data)
    {
        return hash_equals(hash_hmac($this->enc_type, $input, $this->key), $data);
    }
}
?>