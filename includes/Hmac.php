<?php
class Hmac implements Int_Encryption
{
    private $key = 'C694AE29CFC1402BEF09225021ED311A';
    private $enc_type = 'sha256';

    public function __construct($enc_type = '', $key = '')
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
        if (!hash_equals(hash_hmac($this->enc_type, $input, $this->key), $data)) {
            throw new Exception('hash does not match');
        }
    }
    public function get_hmac_var($var)
    {
        if (isset($this->{$var})) {
            return $this->{$var};
        } else {
            throw new Exception("{$var} not found in " . get_class($this), 1);
        }
    }
}
?>