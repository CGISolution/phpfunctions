<?php 

	//require_once dirname(__file__) . '/../vendor/less/lessc.inc.php';
		
class CI_less_pre_sys
{
	protected $path, $lessPath, $files, $env, $minv;
		
	function __construct ()
	{
		
		$path = dirname(dirname(dirname(dirname(dirname(__file__)))));
		
		$this->path = rtrim($path, '/') . DIRECTORY_SEPARATOR;


	}
	
	public function initialize ($args)
	{

		$this->lessPath = rtrim($args[0], '/') . DIRECTORY_SEPARATOR;;

		$this->files = $args[1];

		$this->env = strtoupper($args[2]);
			
		$this->minv = $args[3];
			
		if (!empty($this->files) && is_array($this->files))
		{

			foreach ($this->files as $lessFile => $outputFileName)
			{
				$lessFullPath = $this->path . $this->lessPath . $lessFile;
				$outputFullPath = $this->path . $this->lessPath . $outputFileName;

				if (file_exists($lessFullPath))
				{
					if (!file_exists($outputFullPath)) $this->_compileLessFile($lessFullPath, $outputFullPath);
					else
					{
						// if new min version, then it will rebuild
						// if in dev mode, will recompile the less each page load
						
						//if (self::_checkRebuild($this->path . $this->lessPath, $this->minv) || $this->env == 'DEVELOPMENT')
						if (self::_checkRebuild($this->path . $this->lessPath, $this->minv))
						{
							$this->_compileLessFile($lessFullPath, $outputFullPath);
						}
						
					}
				}
			}
		}
	}


	private function _compileLessFile ($file, $output)
	{
		if (file_exists($output)) @unlink($output); // removes old output file
		
		$cmd = "lessc --global-var=\"domain='{$_SERVER['HTTP_HOST']}'\" {$file} {$output}";
		
		system($cmd);
		
		if (!empty($this->minv)) self::_writeMinV($this->path . $this->lessPath, $this->minv);
	
		return true;
	}
	
	private static function _writeMinV ($path, $minv)
	{
		$file = $path . '.min_version';
	
		$t = touch($file);
		
		if ($t === false) throw new Exception("Unable to create ${file}");
		
		$w = file_put_contents($file, $minv);
		
		if ($w === false) throw new Exception("Unable to write to {$file}!");
		
		return true;
	}
	
	private static function _getLastMinV ($path)
	{
		$file = $path . '.min_version';
		
		return intval(file_get_contents($file));				
	}
	
	
	private static function _checkRebuild ($path, $currentV)
	{
		if (empty($currentV)) return false;
		
		$lastV = self::_getLastMinV($path);
		
		if ((int) $currentV > (int) $lastV) return true;
		
		return false;
	}
	
}