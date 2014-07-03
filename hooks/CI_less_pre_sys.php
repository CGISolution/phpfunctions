<?php 

	//require_once dirname(__file__) . '/../vendor/less/lessc.inc.php';
		
class CI_less_pre_sys
{
	protected $path, $lessPath, $files;
		
	function __construct ()
	{
		
		$path = dirname(dirname(dirname(dirname(dirname(__file__)))));
		
		$this->path = rtrim($path, '/') . DIRECTORY_SEPARATOR;


	}
	
	public function initialize ($args)
	{
		$this->lessPath = rtrim($args[0], '/') . DIRECTORY_SEPARATOR;;

		$this->files = $args[1];
			
		if (!empty($this->files) && is_array($this->files))
		{

			foreach ($this->files as $lessFile => $outputFileName)
			{
				$lessFullPath = $this->path . $this->lessPath . $lessFile;
				$outputFullPath = $this->path . $this->lessPath . $outputFileName;

				if (file_exists($lessFullPath))
				{
					$this->_compileLessFile($lessFullPath, $outputFullPath);
				}
			}
		}
	}


	private function _compileLessFile ($file, $output)
	{		
		$cmd = "lessc {$file} {$output}";
		
		try
		{
			system($cmd);
		}
		catch (Exception $e)
		{
			echo "Unable to compile {$file}";
			echo $e->getMessage() . PHP_EOL;			
		}
		

		
		return true;
	}
	
}