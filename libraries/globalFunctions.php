<?php


// error_log combined with print_r to easily output to error log

function elog ($data)
{	
	$class = (empty(__class__)) ? null : __class__ . '->';
	
	$file = str_ireplace($_SERVER['DOCUMENT_ROOT'], '', __file__);

	//return error_log($file . ' ' . $class . __function__ . ' (Line: ' . __line__ .')' . PHP_EOL . print_r($data, true));

	return error_log(print_r($data, true));
}

// print_r data into HTML comment for troubleshooting
function htmlPrint ($data)
{
	if (empty($data)) return false;

	return PHP_EOL . "<!-- " . PHP_EOL . print_r($data, true) . PHP_EOL . "-->" . PHP_EOL;
}

// for code igniter logs last query if connect to db
function ciQuery ()
{
	// hrmm
	if (class_exists('CI_DB')) elog(CI_DB);

	return true;
}

function println ($string)
{
	return print $string . PHP_EOL;
}

function tprintln ($string, $tabs = 1)
{
	return print str_repeat("\t", $tabs) . $string . PHP_EOL;
}