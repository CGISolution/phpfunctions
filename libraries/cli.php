<?php

class cli
{
	protected $argv, $args;
	
	function __construct ($argv)
	{
		$this->argv = $argv;
		$this->args = array();
		
		
		$this->parseArguments();
		
	}
	
	public function parseArguments ()
	{
		if (!empty($this->argv) && is_array($this->argv))
		{
			// checks flag
			foreach ($this->argv as $k => $arg)
			{
				$k = $v = null;
				
				if (substr($arg, 0, 1) !== '-')	 continue;
				
				$arg = substr($arg, 1); // removes - from begining of argument
				
				$equalLoc = strpos($arg, '=');
				
				if ($equalLoc === false)
				{
					$this->args[$arg] = null;
				}
				else
				{
					$k = substr($arg, 0, $equalLoc);
					$v = substr($arg, $equalLoc + 1);
					
					$this->args[$k] = $v;
				}
	
			}
		}
		
		return true;
	}
}