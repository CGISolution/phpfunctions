<?php


// error_log combined with print_r to easily output to error log

function elog($data)
{	
	return error_log(print_r($data, true));
}