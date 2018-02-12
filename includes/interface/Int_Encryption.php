<?php
interface Int_Encryption
{
    //function __contruct($enc_type, $key);
    function encode($data);
    function decode($input, $data);
}