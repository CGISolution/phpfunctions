<?php
/**
 * Author: William Gallios
 *
 *  The MIT License (MIT)
 *
 *  Copyright (c) 2014 William Gallios
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is
 *  furnished to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 *  SOFTWARE.
 *
 */


if (!defined('DYNAMIC')) define('DYNAMIC', false);
 
class PHPFunctions
{
	private $min_version, $min_debug;
	
	public $minscripts;
		
    function __construct ($args)
    {
    	//if (!function_exists('curl_version')) throw new Exception("Curl is required for this library");
    	//if (version_compare(PHP_VERSION, '5.3.0', '<')) throw new Exception("PHPFunctions requires PHP 5.3.x or greater");
    	
		$this->minscripts = array();

		$this->min_version = $args['min_version'];
		$this->min_debug = $args['min_debug'];
    }

	public function setMinVersion ($version, $debug = false)
	{
		$this->min_version = $version;
		$this->min_debug = $debug;
		
		return true;
	}
	


    /**
     * gets the extension of a given file, Example: some_image.test.JPG
     *
     * @param string $file - filename
     *
     * @return string. E.g.: jpg
     */
    public static function getFileExt ($file)
    {
    	if (empty($file)) throw new Exception('File name is empty!');
     	
        $ld = strrpos($file, '.');

        // gets file extension
        $ext = strtoupper(substr($file, $ld + 1, (strlen($file) - $ld)));

		return $ext;
	}
	
	 /**
     * encodes a JSON response, useful for ajax sending multiple bits of information
     *
     * @param mixed $status 
     * @param mixed $msg    
     * @param mixed $id     Optional, defaults to 0. 
     * @param array $additionalParams Option, defaults to Array
     *
     * @return TODO
     */
    public static function jsonReturn ($status, $msg, $exit = true, $id = 0, $additionalParams = array())
    {
        $return['status'] = $status;
        $return['msg'] = $msg;

        if (!empty($id)) $return['id'] = $id;

		// adds additional params to return array if there are any
		if (!empty($additionalParams))
		{
			foreach ($additionalParams as $k => $v)
			{
				$return[$k] = $v;
			}
		}

        $json_data = json_encode($return);

		if ($exit) die($json_data);
		
		return $json_data;
    }
    
	/**
	* creates a directory and sets permissions
	* @param String $path - Path to directory to check: Example: application/models/
	* @param Boolean $local - Optional, defaults true. only create directorys in current document root
	*/
	public static function createDir ($path, $local = true)
    {
    	if ($local) $path = $_SERVER['DOCUMENT_ROOT'] . $path;

        if (!is_dir($path))
        {
            $create = mkdir($path, 0777, true);

            if ($create === false) throw new exception("Unable to create directory:" . $path);
            
            @chmod($path, 0777);
        }
        else
        {
        	// directory has alread been created
            return true;
        }

        return true;
    }
    
    /**
     * Gets youtube Data
     *
     * @param mixed $id         
     * @param mixed $jsonDecode Optional, defaults to true. 
     *
     * @return JSON String
     */
    public static function getYoutudeVideoData ($id, $jsonDecode = true)
    {
        if (empty($id)) throw new Exception("YouTube Video ID is empty!");

        $url = "https://gdata.youtube.com/feeds/api/videos/{$id}?v=2&alt=json";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, false);

        $results = curl_exec($ch);

        if ($results === false) throw new Exception("Unable to Curl Address ({$url})! ". curl_error($ch));

        curl_close($ch);

        if ($jsonDecode === false) return $results;

        return json_decode($results);
    }
    
    /**
     * Extracts YouTube video ID from Youtube URL;
     * 
     * !! Still needs fine tuning
     *
     * @param mixed $url 
     *
     * @return TODO
     */
    public static function getYouTubeVideoID ($url)
    {
        $videoID = null;

        $pattern = '/youtu\.be\//i';

        $standardPattern = "/watch\?v\=/";

        $shareURL = preg_match($pattern, $url);

        $standardURLcheck = preg_match($standardPattern, $url);

        if ($shareURL > 0)
        {
            $pos = strpos($url, "youtu.be/");
            $videoID = substr($url, ($pos + 9));
        }
        else if ($standardURLcheck > 0)
        {

            $pos = strpos($url, "watch?v=");


            $videoID = substr($url, ($pos  + 8));


            $stop = strpos($videoID, "&");

            if ($stop !== false)
            {
                $videoID = substr($videoID, 0, $stop);
            }

            // echo "VID: {$videoID}<br>";

            // echo "YES Standard : {$url}";
        }
        else
        {
            return false;
            // echo "NO MATCH : {$url}";
        }

        return $videoID;
    }
    // returns array of States in US
    public static function getStates()
    {
        $states = array
        (
	        'AL'=>"Alabama",
	        'AK'=>"Alaska",
	        'AZ'=>"Arizona",
	        'AR'=>"Arkansas",
	        'CA'=>"California",
	        'CO'=>"Colorado",
	        'CT'=>"Connecticut",
	        'DE'=>"Delaware",
	        'DC'=>"District Of Columbia",
	        'FL'=>"Florida",
	        'GA'=>"Georgia",
	        'HI'=>"Hawaii",
	        'ID'=>"Idaho",
	        'IL'=>"Illinois",
	        'IN'=>"Indiana",
	        'IA'=>"Iowa",
	        'KS'=>"Kansas",
	        'KY'=>"Kentucky",
	        'LA'=>"Louisiana",
	        'ME'=>"Maine",
	        'MD'=>"Maryland",
	        'MA'=>"Massachusetts",
	        'MI'=>"Michigan",
	        'MN'=>"Minnesota",
	        'MS'=>"Mississippi",
	        'MO'=>"Missouri",
	        'MT'=>"Montana",
	        'NE'=>"Nebraska",
	        'NV'=>"Nevada",
	        'NH'=>"New Hampshire",
	        'NJ'=>"New Jersey",
	        'NM'=>"New Mexico",
	        'NY'=>"New York",
	        'NC'=>"North Carolina",
	        'ND'=>"North Dakota",
	        'OH'=>"Ohio",
	        'OK'=>"Oklahoma",
	        'OR'=>"Oregon",
	        'PA'=>"Pennsylvania",
	        'RI'=>"Rhode Island",
	        'SC'=>"South Carolina",
	        'SD'=>"South Dakota",
	        'TN'=>"Tennessee",
	        'TX'=>"Texas",
	        'UT'=>"Utah",
	        'VT'=>"Vermont",
	        'VA'=>"Virginia",
	        'WA'=>"Washington",
	        'WV'=>"West Virginia",
	        'WI'=>"Wisconsin",
	        'WY'=>"Wyoming"
        );

        return $states;
    }

	// Basic Timezone arrays to make the selection simpler for US
	public static function getTimezonesAbb ()
	{

		$aTimeZones = array
		(
		  'America/Puerto_Rico'=>'AST',
		  'America/New_York'=>'EDT',
		  'America/Chicago'=>'CDT',
		  'America/Boise'=>'MDT',
		  'America/Phoenix'=>'MST',
		  'America/Los_Angeles'=>'PDT',
		  'America/Juneau'=>'AKDT',
		  'Pacific/Honolulu'=>'HST',
		  'Pacific/Guam'=>'ChST',
		  'Pacific/Samoa'=>'SST',
		  'Pacific/Wake'=>'WAKT'
		);
		
		asort($aTimeZones);
		
		return $aTimeZones; 
	}

	// Non abbreviabed time zones
	public static function getTimezonesFull ()
	{

		$aTimeZones = array
		(
		  'America/Puerto_Rico'=>'Atlantic Standard Time',
		  'America/New_York'=>'Eastern Daylight Time',
		  'America/Chicago'=>'Central Daylight Time',
		  'America/Boise'=>'Mountain Daylight Time',
		  'America/Phoenix'=>'Mountain Standard Time',
		  'America/Los_Angeles'=>'Pacific Daylight Time',
		  'America/Juneau'=>'Alaska Daylight Time',
		  'Pacific/Honolulu'=>'Hawaii-Aleutian Standard Time',
		  'Pacific/Guam'=>'Chamorro Standard Time',
		  'Pacific/Samoa'=>'Samoa Standard Time',
		  'Pacific/Wake'=>'Wake Island Time'
		);
		
		asort($aTimeZones);
		
		return $aTimeZones; 
	}

	// will find timezone based upon on the time zones from above getTimezonesAbb or getTimezonesFull
	public function determineTimezoneAbb ($zone)
	{
		if (empty($zone)) throw new Exception("Timezone is empty!");
		
		$zones = self::getTimezonesAbb();
		
		$return = null;
		
		if (!empty($zones))
		{
			foreach ($zones as $z => $dis)
			{
				if ($z == $zone)
				{
					$return = $dis;
					break;
				}
			}
		}
		
		return $return;
	}

	public function minFiles ($files, $path, $type = 'text/javascript', $min = true, $method = 'f', $rel = 'stylesheet')
	{
		if (empty($files)) throw new Exception("No Files to minify");
		if (empty($path)) throw new Exception("Path is empty!");
		
		$scripts = array();

		if (is_array($files))
		{
			foreach ($files as $file)
			{
				$ext = $this->getFileExt($file);

				if ($ext == 'JS')
				{
					$scripts[] = $this->_buildJsScript($file, $path, $type, $min, $method);
				}
				else if ($ext == 'CSS')
				{
					$scripts[] = $this->_buildCssScript($file, $path, $min, $method, $rel);
				}
			}
		}
		else
		{
			foreach (explode(' ', $files) as $k => $file)
			{
				$ext = $this->getFileExt($file);
				
				if ($ext == 'JS')
				{
					$scripts[] = $this->_buildJsScript($file, $path, $type, $min, $method);
				}
				else if ($ext == 'CSS')
				{
					$scripts[] = $this->_buildCssScript($file, $path, $min, $method, $rel);			
				}
			}
		}

		
		return PHP_EOL . implode(PHP_EOL, $scripts) . PHP_EOL;
	}

    /**
     * TODO: short description.
     *
     * @param mixed $name 
     *
     * @return TODO
     */
    public function jsScript($name, $path = null, $type = 'text/javascript', $min = true, $method = 'f')
    {
    	if (empty($path)) $path = 'public' . DS . 'js' . DS;
    	
    	$scripts = array();
    
    	if (empty($name)) throw new Exception("Javascript filename is empty!");
    	
		if (is_array($name))
		{
			if (!empty($name))
			{
					foreach ($name as $k => $file)
					{
						$scripts[] = $this->_buildJsScript($file, $path, $type, $min, $method);						
					}
			}
		}
		else
		{

			foreach (explode(' ', $name) as $k => $file)
			{
				$scripts[] = $this->_buildJsScript($file, $path, $type, $min, $method);
			}	
		}

		return PHP_EOL . implode(PHP_EOL, $scripts) . PHP_EOL;
    }
    
    // builds actual HTML string for javascript
    protected function _buildJsScript ($name, $path = null, $type = 'text/javascript', $min = true, $method = 'f')
    {
    	if (empty($path)) $path = 'public' . DS . 'js' . DS;

		$httpCheck = stripos($name, 'http');

    	if ($httpCheck === false)
    	{

    		//if (!file_exists($path . $name)) return false;
		
			if ($min)
			{
				// check for CodeIgniter Instance
			$version = ($this->min_version) ? '&amp;' . $this->min_version : null;
			$debug = ($this->min_debug) ? '&amp;debug' : null;
				
				$src = "/min/?{$method}={$path}{$name}{$debug}{$version}";
			} 
			else $src = $path . $name;
		}
		else
		{
			$src = $name; 
		}

		if (!empty($type)) $type = "type='{$type}' ";
		
    	if (DYNAMIC) 
    	{
	    	$this->minscripts[] = $src;
	    	return '';
    	}
   	

    	return  "<script {$type}src='{$src}'></script>";
    }
    
    public function cssScript ($name, $path = null, $min = true, $method = 'f', $rel = 'stylesheet')
    {
    	if (empty($path)) $path = 'public' . DS . 'css' . DS;
    	
    	$scripts = array();
    
    	if (empty($name)) throw new Exception("CSS filename is empty!");
    	
		if (is_array($name))
		{
			if (!empty($name))
			{
					foreach ($name as $k => $file)
					{
						$scripts[] = $this->_buildCssScript($file, $path, $min, $method, $rel);						
					}
			}
		}
		else
		{
			foreach (explode(' ', $name) as $k => $file)
			{
				$scripts[] = $this->_buildCssScript($file, $path, $min, $method, $rel);
			}	
		}
		
		return PHP_EOL . implode(PHP_EOL, $scripts) . PHP_EOL;
    }
    
    // builds actual HTML string for Css scripts
    protected function _buildCssScript ($name, $path = null, $min = true, $method = 'f', $rel)
    {
    	if (empty($path)) $path = 'public' . DS . 'css' . DS;
    	
		// checks if file eixsts
		//if (!file_exists($path . $name)) return false;
   		
   		if ($min)
   		{
			$version = ($this->min_version) ? '&amp;' . $this->min_version : null;
			$debug = ($this->min_debug) ? '&amp;debug' : null;
			
			$src = "/min/?{$method}={$path}{$name}{$debug}{$version}";
   		}
		else $src = $path . $name;
		
    	if (DYNAMIC) 
    	{
	    	$this->minscripts[] = $src;
	    	return '';
    	}
    	
    	$type = " type='text/css'";
    	
    	if ($rel == 'stylesheet/less') $type = null;
		
	    return "<link rel='{$rel}'{$type} href='{$src}' />";	    
    }


	/*
	* general stack trace to error log
	*/
    public static function sendStackTrace($e)
    {
        $body = "Stack Trace Error:\n\n";
        $body .= "URL: {$_SERVER["SERVER_NAME"]}{$_SERVER["REQUEST_URI"]}\n";
        $body .= "Referer: {$_SERVER['HTTP_REFERER']}\n";
        $body .= "Message: " . $e->getMessage() . "\n\n";
        $body .= $e;

        error_log($body);
        
		return true;
	}
	
	/*
	* @param $path String: /var/www
	* @param $recursive Boolean - deafault true: should the function recursively scan directories
	* @param $files Boolean - default true: should results include files
	* @param $dirs Boolean - default true: should results include directories
	* @param $exclusiveExt Array - Default Null: Array of Extensions you only want returned
	* @param $ul - return HTML UL with LI's 

	* @return String/Array
	*/
	public function getDirContents ($path, $recursive = true, $files = true, $dirs = true, $includeHiddenFiles = true, $exclusiveExt = null, $cnt = 1)
	{
		if (empty($path)) throw new Exception("Path is empty!");
		
		$path = rtrim($path, '/') . '/';
		
		$currentFolder = basename($path) . EOL;
		
		
		$handle = opendir($path);
		
		if ($handle === false) throw new Exception("Unable to open directory: {$path}");

		
		$content = array();
		
		// ensures array of extensions is uppercase for the function				
		if (!empty($exclusiveExt) && is_array($exclusiveExt)) $exclusiveExt = array_map('strtoupper', $exclusiveExt);

			//foreach ($files as $el)
			while (false !== ($el = readdir($handle)))
			{

				if ($el == '.' || $el == '..') continue;
				
				$fullPath = $path  . $el;
				
				// if they aren ot pulling directories, skips it
				if (is_dir($fullPath) && $dirs == false) continue;
				
				if (is_file($fullPath) && $files == false) continue;
				
				if (!$includeHiddenFiles)
				{
					// checks if hidden file
					$hidden = (substr($el, 0, 1) == '.')  ? true : false;

					if ($hidden) continue;
				}

				// checks of excluded file
				if (!empty($exclusiveExt) && is_array($exclusiveExt) && !is_dir($fullPath))
				{
					$ext = PHPFunctions::getFileExt($el);
					
					if (!in_array($ext, $exclusiveExt)) continue;
					
				}


				if (is_dir($fullPath))
				{
					if ($recursive)
					{
						$subContent = $this->getDirContents($fullPath, $recursive, $files, $dirs, $includeHiddenFiles, $exclusiveExt, $cnt++);				
						
						if (!empty($subContent)) $content[$el] = $subContent;
					}

				}
				else $content[] = $el;
			} // end loop
	
		
		@closedir($handle); // closes directory
		
		return $content;
	}
	
	/**
	
		* @param $params array - $Array
			(
				'ul' => array('id' => 'files', 'class' => 'ul-class'),
				'li => array('id' => 'liID', 'class' => 'li-class')	
			)
	*
	*/
	public static function buildDirectoryUL ($dirArray, $path = null, $htmlParams = null, $cnt = 1)
	{
		if (empty($dirArray)) return false;
		
		$html .= "<ul";
			
		if ($cnt == 1)
		{
			if (!empty($htmlParams['ul']['id'])) $html .= " id='{$htmlParams['ul']['id']}'";
			if (!empty($htmlParams['ul']['class'])) $html .= " class='{$htmlParams['ul']['class']}'";					
		}
		
		$html .= ">" . PHP_EOL;
		
		foreach ($dirArray as $k => $v)
		{
			$html .= str_repeat("\t", $cnt);
			
			if (is_array($v)) $dataFile = $path;			
			else  $dataFile = $path . $v;		
				
			$html .= "<li data-file=\"{$dataFile}\"";


			if (!empty($htmlParams['li']['id'])) $html .= " id='{$htmlParams['li']['id']}'";
			if (!empty($htmlParams['li']['class'])) $html .= " class='{$htmlParams['li']['class']}'";
			
			$html .= ">";
			
			if (is_numeric($k))
			{
				$html .= "{$v}";	
			}
			else
			{
				$html .= $k;
				
				$cnt++;
				if (is_array($v) && !empty($v)) $html .= self::buildDirectoryUL($dirArray[$k], $path .$k . '/', $htmlParams, $cnt);

			}
			
			$html .= "</li>" . PHP_EOL;
		}
		$html .= PHP_EOL . str_repeat("\t", $cnt - 1) . "</ul>" . PHP_EOL;
		
		return $html;
	}
}
