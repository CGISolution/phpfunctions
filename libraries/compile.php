<?php
/**
* usage: php compile.php /path/of/source/ /path/destination/
*/

require_once 'cli.php';
require_once '../vendor/less/lessc.inc.php';
require_once '../config/compile.php'; // includes default config
require_once 'PHPFunctions.php';

class compile extends cli
{
	protected $source, $destination, $singleFile, $config, $less;
	
	function __construct ($argv, $config)
	{
		$this->config = $config;
		
		parent::__construct($argv);
		
		if (php_sapi_name() === 'cli')
		{
			$_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__);
		}
		
		$this->_clearDestinationFolder();
		$this->_setOptions();
	
		$this->less = new lessc;
	
	}
	
	private function _setOptions ()
	{

		$this->source = $this->config['compile']['source'];
		$this->destination = $this->config['compile']['destination'];
		$this->singleFile = $this->config['compile']['singleFile'];
		
		if (!empty($this->args) && is_array($this->args))
		{
			foreach ($this->args as $k => $v)
			{
				if ($k == 'source') $this->source = $v;
				if ($k == 'destination') $this->source = $v;
				if ($k == 'singleFile') $this->source = (bool) $v;
			}
		}
		
		return true;
	}
	
	public function build ()
	{
		
		$it = new RecursiveDirectoryIterator($this->source);

		$ext = array('less');

		foreach (new RecursiveIteratorIterator($it) as $file)
		{
			if (in_array(strtolower(array_pop(explode('.', $file))), $ext))
			{
			
				$fileName = basename($file);
				
				$localPath = str_replace($this->source, '', $file);
				
				if ($this->checkExclude($localPath)) continue;
				
				
				if (in_array($fileName, $this->config['compile']['files']))
				{
					$this->_compileLessFile($file);
				}
				
			}
		}
	}
	
	private function _compileLessFile ($file)
	{
		$destination = $this->source . $this->destination;
		
		$fileName = basename($file);
				
		$output = $destination . $fileName;
		
		$output = str_replace('.less', '.css', $output);
		
		echo "Compiling: {$file} > {$output} \n";
		
		try
		{
			$this->less->checkedCompile($file, $output);		
		}
		catch (Exception $e)
		{
			echo "Unable to compile {$file}";
			echo $e->getMessage() . PHP_EOL;			
		}
		

		
		return true;
	}
	
	private function _clearDestinationFolder ()
	{
		$path = $this->source . $this->destination;

		if (is_dir($path))
		{
			$files = scandir($path);
			
			$files = array_diff($files, array('.', '..'));
			
			if (!empty($files))
			{
				foreach ($files as $file)
				{
					$ext = PHPFunctions::getFileExt($file);	
					
					if (ext !== 'CSS') continue;
					
					unlink($path . $file);
				}
			}
		}
		
		return true;
	}
	
	private function checkExclude ($file)
	{
		$exclude = $this->config['compile']['exclude'];
		
		$excludeFile = false;
		
		if (!empty($exclude) && is_array($exclude))
		{
			foreach ($exclude as $k => $s)
			{

				if (strpos($file, $s))
				{
					$excludeFile = true;
					break;
				}
			}
		}
		
		return $excludeFile;
	}
}


try
{

	$c = new Compile($argv, $config);
	
	$c->build();

}
catch (Exception $e)
{
	error_log($e);
	print_r($e->getMessage());
}
