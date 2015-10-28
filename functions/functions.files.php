<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* FILES/DIRECTORY SPECIFIC FUNCTIONS
*/
 
// ------------ lixlpixel recursive PHP functions -------------
// recursive_remove_directory( directory to delete, empty )
// expects path to directory and optional TRUE / FALSE to empty
// of course PHP has to have the rights to delete the directory
// you specify and all files and folders inside the directory
// ------------------------------------------------------------

// to use this function to totally remove a directory, write:
// recursive_remove_directory('path/to/directory/to/delete');
 
// to use this function to empty a directory, write:
// recursive_remove_directory('path/to/full_directory',TRUE);

function recursive_remove_directory($directory, $empty=FALSE)
{
    // if the path has a slash at the end we remove it here
    if(substr($directory,-1) == '/')
    {
        $directory = substr($directory,0,-1);
    }
 
     // if the path is not valid or is not a directory ...
    if(!file_exists($directory) || !is_dir($directory))
    {
        // ... we return false and exit the function
        echo "Incorrect path $directory...<br>";
		return FALSE;
 
    // ... if the path is not readable
    }elseif(!is_readable($directory))
    {
        // ... we return false and exit the function
        echo "Unreadable directory $directory...<br>";
        return FALSE;
 
    // ... else if the path is readable
    }else{
 
        // we open the directory
        $handle = opendir($directory);
 
        // and scan through the envelopes inside
       while (FALSE !== ($envelope = readdir($handle)))
        {
            // if the filepointer is not the current directory
            // or the parent directory
            if($envelope != '.' && $envelope != '..')
            {
                // we build the new path to delete
                $path = $directory.'/'.$envelope;
 
                // if the new path is a directory
               if(is_dir($path)) 
                {
                    // we call this function with the new path
                    recursive_remove_directory($path);
 
                // if the new path is a file
                }else{
                    // we remove the file
                    unlink($path);
                }
            }
        }
        // close the directory
        closedir($handle);
 
        // if the option to empty is not set to true
        if($empty == FALSE)
        {
            // try to delete the now empty directory
            if(!rmdir($directory))
            {
                // return false if not possible
		        echo "Unable to delete directory $directory ...<br>";
                return FALSE;
            }
        }
        // return success
        echo "Completed!<br>";
        return TRUE;
    }
}

//chmod("/dashboard/antssam1/public_html/projects/vl/onlinefiles/uploads",0755);
//recursive_remove_directory("/dashboard/antssam1/public_html/projects/vl/onlinefiles/uploads", TRUE);

/**
* get the corresponding MIME type for a file extension
* @param: $filename - same as extension
*/
function getMIME($filename) { 
	$mime = array(
		".htm" =>"application/xhtml+xml",
		".3dm" =>"x-world/x-3dmf",
		".3dmf" =>"x-world/x-3dmf",
		".ai" =>"application/postscript",
		".aif" =>"audio/x-aiff",
		".aifc" =>"audio/x-aiff",
		".aiff" =>"audio/x-aiff",
		".au" =>"audio/basic",
		".avi" =>"video/x-msvideo",
		".bcpio" =>"application/x-bcpio",
		".bin" =>"application/octet-stream",
		".cab" =>"application/x-shockwave-flash",
		".cdf" =>"application/x-netcdf",
		".chm" =>"application/mshelp",
		".cht" =>"audio/x-dspeeh",
		".class" =>"application/octet-stream",
		".cod" =>"image/cis-cod",
		".com" =>"application/octet-stream",
		".cpio" =>"application/x-cpio",
		".csh" =>"application/x-csh",
		".css" =>"text/css",
		".csv" =>"text/comma-separated-values",
		".dcr" =>"application/x-director",
		".dir" =>"application/x-director",
		".dll" =>"application/octet-stream",
		".doc" =>"application/msword",
		".dot" =>"application/msword",
		".dus" =>"audio/x-dspeeh",
		".dvi" =>"application/x-dvi",
		".dwf" =>"drawing/x-dwf",
		".dwg" =>"application/acad",
		".dxf" =>"application/dxf",
		".dxr" =>"application/x-director",
		".eps" =>"application/postscript",
		".es" =>"audio/echospeech",
		".etx" =>"text/x-setext",
		".evy" =>"application/x-envoy",
		".exe" =>"application/octet-stream",
		".fh4" =>"image/x-freehand",
		".fh5" =>"image/x-freehand",
		".fhc" =>"image/x-freehand",
		".fif" =>"image/fif",
		".gif" =>"image/gif",
		".gtar" =>"application/x-gtar",
		".gz" =>"application/gzip",
		".hdf" =>"application/x-hdf",
		".hlp" =>"application/mshelp",
		".hqx" =>"application/mac-binhex40",
		".htm" =>"text/html",
		".html" =>"text/html",
		".ief" =>"image/ief",
		".jpeg" =>"image/jpeg",
		".jpe" =>"image/jpeg",
		".jpg" =>"image/jpeg",
		".js" =>"text/javascript",
		".latex" =>"application/x-latex",
		".man" =>"application/x-troff-man",
		".mbd" =>"application/mbedlet",
		".mcf" =>"image/vasa",
		".me" =>"application/x-troff-me",
		".mid" =>"audio/x-midi",
		".midi" =>"audio/x-midi",
		".mif" =>"application/mif",
		".mov" =>"video/quicktime",
		".movie" =>"video/x-sgi-movie",
		".mp2" =>"audio/x-mpeg",
		".mpe" =>"video/mpeg",
		".mpeg" =>"video/mpeg",
		".mpg" =>"video/mpeg",
		".nc" =>"application/x-netcdf",
		".nsc" =>"application/x-nschat",
		".oda" =>"application/oda",
		".pbm" =>"image/x-portable-bitmap",
		".pdf" =>"application/pdf",
		".pgm" =>"image/x-portable-graymap",
		".php" =>"application/x-httpd-php",
		".phtml" =>"application/x-httpd-php",
		".png" =>"image/png",
		".pnm" =>"image/x-portable-anymap",
		".pot" =>"application/mspowerpoint",
		".ppm" =>"image/x-portable-pixmap",
		".pps" =>"application/mspowerpoint",
		".ppt" =>"application/mspowerpoint",
		".ppz" =>"application/mspowerpoint",
		".ps" =>"application/postscript",
		".ptlk" =>"application/listenup",
		".qd3" =>"x-world/x-3dmf",
		".qd3d" =>"x-world/x-3dmf",
		".qt" =>"video/quicktime",
		".ram" =>"audio/x-pn-realaudio",
		".ra" =>"audio/x-pn-realaudio",
		".ras" =>"image/cmu-raster",
		".rgb" =>"image/x-rgb",
		".roff" =>"application/x-troff",
		".rpm" =>"audio/x-pn-realaudio-plugin",
		".rtf" =>"application/rtf",
		".rtf" =>"text/rtf",
		".rtx" =>"text/richtext",
		".sca" =>"application/x-supercard",
		".sgm" =>"text/x-sgml",
		".sgml" =>"text/x-sgml",
		".sh" =>"application/x-sh",
		".shar" =>"application/x-shar",
		".shtml" =>"text/html",
		".sit" =>"application/x-stuffit",
		".smp" =>"application/studiom",
		".snd" =>"audio/basic",
		".spc" =>"text/x-speech",
		".spl" =>"application/futuresplash",
		".spr" =>"application/x-sprite",
		".sprite" =>"application/x-sprite",
		".src" =>"application/x-wais-source",
		".stream" =>"audio/x-qt-stream",
		".sv4cpio" =>"application/x-sv4cpio",
		".sv4crc" =>"application/x-sv4crc",
		".swf" =>"application/x-shockwave-flash",
		".t" =>"application/x-troff",
		".talk" =>"text/x-speech",
		".tar" =>"application/x-tar",
		".tbk" =>"application/toolbook",
		".tcl" =>"application/x-tcl",
		".tex" =>"application/x-tex",
		".texinfo" =>"application/x-texinfo",
		".texi" =>"application/x-texinfo",
		".tif" =>"image/tiff",
		".tiff
		" =>"image/tiff",
		".trtc" =>"application/rtc",
		".trtc" =>"application/x-troff",
		".tsi" =>"audio/tsplayer",
		".tsp" =>"application/dsptype",
		".tsv" =>"text/tab-separated-values",
		".txt" =>"text/plain",
		".ustar" =>"application/x-ustar",
		".viv" =>"video/vnd.vivo",
		".vivo" =>"video/vnd.vivo",
		".vmd" =>"application/vocaltec-media-desc",
		".vmf" =>"application/vocaltec-media-file",
		".vox" =>"audio/voxware",
		".vts" =>"workbook/formulaone",
		".vtts" =>"workbook/formulaone",
		".wav" =>"audio/x-wav",
		".wbmp" =>"image/vnd.wap.wbmp",
		".wml" =>"text/vnd.wap.wml",
		".wmlc" =>"application/vnd.wap.wmlc",
		".wmls" =>"text/vnd.wap.wmlscript",
		".wmlsc" =>"application/vnd.wap.wmlscriptc",
		".wrl" =>"model/vrml",
		".wrl" =>"x-world/x-vrml",
		".xbm" =>"image/x-xbitmap",
		".xhtml" =>"application/xhtml+xml",
		".xla" =>"application/msexcel",
		".xls" =>"application/msexcel",
		".xml" =>"text/xml",
		".xpm" =>"image/x-xpixmap",
		".xwd" =>"image/x-windowdump",
		".z" =>"application/x-compress",
		".zip" =>"application/zip" 
	); 
	return $mime[strrchr($filename,'.')]; 
}

/**
* get file extension
*/
function ext($documentName) {
	//retrieve the extension
	$extension=0;
	$string=array();
	$string=explode(".",$documentName);
	if(count($string)) {
		foreach($string as $s) {
			$extension=$s;
		}
		return strtolower($extension);
	}
}

/**
* establish if file is an image
*/
function isImage($extension) {
	switch(strtolower($extension)) {
		case jpeg:
		case jpg:
		case gif:
		case aif:
		case aiff:
		case bmp:
		case tif:
		case tiff:
			return 1;
		break;
	}
}

/**
* get file type from extension
*/
function getFileTypeFromExtension($extension) {
	switch($extension) {
		//word
		case doc:
			return "doc";
		break;
		//excel
		case xls:
			return "xls";
		break;
		//acrobat
		case pdf:
			return "pdf";
		break;
		//text
		case txt:
			return "txt";
		break;
		//jpeg,jpg,gif,aiff,aif,bmp,tif,tiff
		case jpeg:
		case jpg:
		case gif:
		case aif:
		case aiff:
		case bmp:
		case tif:
		case tiff:
			return "img";
		break;
		//mp3,mp4,avi,mpeg,mpg,wma
		case mp3:
		case mp4:
		case avi:
		case mpeg:
		case mpg:
		case wma:
			return "mp3";
		break;
		//php,htm,html,asp,jsp
		case htm:
		case html:
		case tml:
		case jsp:
		case asp:
		case jsp:
			return "htm";
		break;
		default:
			return "unknown";
		break;
	}
}

/**
* get file description from extension
*/
function getFileDescriptionFromExtension($extension) {
	switch($extension) {
		//word
		case doc:
			return "document";
		break;
		//excel
		case xls:
			return "excel file";
		break;
		//acrobat
		case pdf:
			return "acrobat file";
		break;
		//text
		case txt:
			return "text file";
		break;
		//jpeg,jpg,gif,aiff,aif,bmp,tif,tiff
		case jpeg:
		case jpg:
		case gif:
		case aif:
		case aiff:
		case bmp:
		case tif:
		case tiff:
			return "image file";
		break;
		//mp3,mp4,avi,mpeg,mpg,wma
		case mp3:
		case mp4:
		case avi:
		case mpeg:
		case mpg:
		case wma:
			return "multimedia file";
		break;
		//php,htm,html,asp,jsp
		case htm:
		case html:
		case tml:
		case jsp:
		case asp:
		case jsp:
			return "web file";
		break;
		default:
			return "unknown file type";
		break;
	}
}

/**
* get acceptable file type from extension
*/
function isFileTypeAcceptable($extension) {
	switch(strtolower($extension)) {
		case doc:
		case docx:
		case xls:
		case xlsx:
		case pdf:
		case txt:
		case jpeg:
		case jpg:
		case gif:
		case bmp:
		case png:
			return 1;
		break;
	}
}
?>