<?php

/**
* Encryption library extension for codeigniter
* Ensures mcrypt is always installed
*/

class MY_Encrypt extends CI_Encrypt
{
    public function __construct()
    {
    	echo "M";
        if (!function_exists('mcrypt_encrypt'))
        {
            throw new Exception("Encryption requires mcrypt PHP extension!");
        }

        parent::__construct();
    }
}