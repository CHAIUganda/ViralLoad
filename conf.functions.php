<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* STARTUP FUNCTIONS
*/

/**
* function to tell if a file string is a directory
* written coz the default php is_dir() was acting up!
* get a string, if it isn't the . or .., then check
* for the existance of a . and if this lies at the end
* of the file - if it does, and there are 3 or 4 characters
* after the ., this is a file and not a directory
*/
function isDir($filePath,$file) {
	//ensure this isn't a file
	if(!is_file($filePath.$file)) {
		if($file!="." && $file!="..") {
			$fileA=array();
			$fileA=preg_split("/\./",$file);
			if(count($fileA)) {
				//how many characters in this array?
				$num=0;
				$num=count($fileA);
				//get last bit of array
				$last=0;
				$last=$fileA[$num-1];
				//if extension != 3 or !=4, we have a directory
				if(strlen($last)!=3 || strlen($last)!=4) {
					return 1;
				}
			} else {
				return 1;
			}
		}
	}
}

/**
* function to manage the loading of modules
* @param: $path - path to the modules, on first run, this is not supplied
* so the default is taken $modules_path
* @param: $count - number of times the function has recalled itself
*/
function loadModules($path,$count) {
	//load globals
	global $modules_path;
	//path to the file
	$filePath=0;
	$filePath=($path?$path:$modules_path);
	//increment counter
	$count+=1;
	//loop only if
	if($handle=opendir($filePath)) {
		//loop through directory
		while(false!==($file=readdir($handle))) {
			if($file!="." && $file!="..") {
				//if count<2, proceed at will
				if($count<2) {
					if(isDir($filePath,$file)) {
						//loop through directory
						loadModules($filePath."$file/",$count);
					} else {
						//include module file
						include_once $filePath."$file";
					}
				} else {
					//only inlcude what's inside directory classes
					if(isDir($filePath,$file) && $file=="classes") {
						//loop through directory only if it's called classes
						loadModules($filePath."$file/",$count);
					} else if(!isDir($filePath,$file)){
						//include module file
						include_once $filePath."$file";
					}
				}
			}
		}
		closedir($handle);
	}
}

/**
* function to manage the loading of functions
*/
function loadFunctions() {
	//load globals
	global $functions_path;
	//path to the file
	$filePath=0;
	$filePath=$functions_path;
	//loop only if
	if($handle=opendir($filePath)) {
		//loop through directory
		while(false!==($file=readdir($handle))) {
			if($file!="." && $file!="..") {
				//include module file
				include_once $filePath."$file";
			}
		}
		closedir($handle);
	}
}
?>