<?php
interface Encryption
{
    public function __contruct($enc_type, $key);
    public function encode($data);
    public function decode($data);
}