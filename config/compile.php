<?php

if (file_exists('config.local.php'))
{
    include_once 'compile.local.php';
}


$config['compile'] = array
(
	'source' => dirname(dirname(dirname(dirname(dirname(__file__))))) . DIRECTORY_SEPARATOR,
	'destination' => 'public' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'less' . DIRECTORY_SEPARATOR,
	'singleFile' => false,
	'exclude' => array('/less/tests/', '/less/test/', '/less/benchmark', '/bootstrap/') // exlude array
);



$defaultFiles = array
(
	'alerts.less',
	'contextMenu.less',
	'dialog.less',
	'uploader.less'
);



/**
*
* Uncomment, or add the follow config line to compile.local.php
* this will define which less files get compiled
*/


if (is_array($config['compile']['files']))
{
	$config['compile']['files'] = array_merge($config['compile']['files'], $defaultFiles);	
}
else
{
	$config['compile']['files'] = $defaultFiles;
}
