<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/


$hook['pre_system'] = array(
                    'class'    => 'CI_less_pre_sys',
                    'function' => 'initialize',
                    'filename' => 'CI_less_pre_sys',
                    'filepath' => 'third_party/phpfunctions/hooks',
                    'params'   => array()
                    );

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */