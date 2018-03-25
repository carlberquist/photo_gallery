<?php
interface Int_Encryption
{
    function encode($data);
    function decode($input, $data);
}