<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* IMAGE/FILE SPECIFIC FUNCTIONS
*/

/**
* get file extension
*/
function ext($documentName) {
	//retrieve the last 3 characters from the file's name
	$extension=0;
	$position=0;
	$position=strlen($documentName)-3;
	$extension = substr($documentName, $position, 3);
	//address the jpeg issue
	if(strtolower($extension)=="peg") {
		return "jpeg";
	} else {
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
* create the image from the db or uploaded file
*/
function createImage($categoryID,$filename,$extension,$fileType,$fileSize,$fileID) {
	//create a folder within images
	global $system_default_path,$home_url,$default_randomlower,$default_randomupper;
	global $image_dir0,$image_dir1,$image_dir2,$image_dir3,$image_dir4;
	global $gallery;
	
	$dir=0;
	$dir=rand($default_randomlower,$default_randomupper);

	//use any of the five folders under images
	$folderarray=array();
	$folderarray[0]=$image_dir0;
	$folderarray[1]=$image_dir1;
	$folderarray[2]=$image_dir2;
	$folderarray[3]=$image_dir3;
	$folderarray[4]=$image_dir4;

	//pick the variable
	$var=0;
	$var=rand(0,4);

	//setup the path
	$path=0;
	$path=$system_default_path."images/$folderarray[$var]/";
	//make a $path dir
	if(!is_dir($path)) {
		mkdir($path,0755);
		chmod($path,0755);
	}

	//append to the path
	$path.="_$dir/";
	//make a $path dir
	if(!is_dir($path)) {
		mkdir($path,0755);
		chmod($path,0755);
	}
	
	//remove spaces
	$filename=removeSpaces($filename,".");
	//avoid conflicts
	$filename=removeFileConflict($path,$filename);
	
	//file creation
	$image=0;
				
	switch($extension) {
		case GIF:
		case gif:
			if(function_exists("imagegif")) {
				$image=ImageCreateFromGIF($_FILES['userfile']['tmp_name']);
				ImageGIF($image,$path.$filename);
				chmod($path.$filename,0755);
			}
		break;
		case JPEG:
		case jpeg:
		case JPG:
		case jpg:
			if(function_exists("imagejpeg")) {
				$image=ImageCreateFromJPEG($_FILES['userfile']['tmp_name']);
				ImageJPEG($image,$path.$filename);
				chmod($path.$filename,0755);
			}
		break;
		case PNG:
		case png:
			if(function_exists("imagepng")) {
				$image=ImageCreateFromPNG($_FILES['userfile']['tmp_name']);
				ImagePNG($image,$path.$filename);
				chmod($path.$filename,0755);
			}
		break;
	}
	if($gallery) {
		//create an icon 49 x 49
		createIcon("small",$path,$path.$filename,$filename,49,49,$extension,$home_url."images/$folderarray[$var]/_$dir/");
		//resize image to 386 x 256
		resizeImage($path,$path.$filename,$filename,386,256,$extension);
		//db storage
		$fileData=0;
		$fileData=addslashes(file_get_contents($path.$filename));
		//filesize
		$fileSize=0;
		$fileSize=filesize($path.$filename);
		//update the db record
		mysqlquery("update vl_filenames set 
					file='$fileData', 
					size='$fileSize' 
					where id='$fileID'");
	}

	return $path.$filename." :: ".$home_url."images/$folderarray[$var]/_$dir/$filename";
}

/**
* remove file conflicts
*/
function removeFileConflict($path,$filename) {
	global $default_randomlower,$default_randomupper;
	if(!is_file($path.$filename)) {
		return $filename;
	} else {
		$filename="_".rand($default_randomlower,$default_randomupper).$filename;
		removeFileConflict($path,$filename);
	}
}

/**
* create icon
*/

function createIcon($convention,$path,$file,$filename,$width,$height,$format,$urlpath) {
	/*
	use this example for reference
	path: C:/xampp/htdocs/vl/onlinefiles/uploads/_usericons/84/,
	file: C:/xampp/htdocs/vl/onlinefiles/uploads/_usericons/84/bmwx62.jpg,
	filename: bmwx62.jpg,
	width: 640,
	height: 480,
	dir: ,
	format: jpg
	*/
	global $categoryID,$fileOriginalName,$fileType,$datetime,$user,$extension;
	
	//icon name
	$iconname=0;
	$iconname=preg_replace("/.$format/is","",$filename);
	$iconname.=".$convention.$format";
	//thumbnail settings
	$tmb_src=0;
	$tmb_src=$file;
					
	//thumbnail creation
	$image=0;
	$imagewidth=0;
	$imageheight=0;
				
	switch($format) {
		case GIF:
		case gif:
			$image=ImageCreateFromGIF($tmb_src);
		break;
		case JPEG:
		case JPG:
		case jpeg:
		case jpg:
			$image=ImageCreateFromJPEG($tmb_src);
		break;
		case PNG:
			$image=ImageCreateFromPNG($tmb_src);
		break;
	}
		
	//parsed dimensions
	$tmb_width=0;
	$tmb_height=0;

	//default dimensions
	$imagewidth=ImageSx($image);
	$imageheight=ImageSy($image);
					
	$newimage=0;
	//do the neccessary resizing on condition that ...
	if($imagewidth>$width || $imageheight>$height) {
		if ($imagewidth > $imageheight) {
			$tmb_width=$width;
			$tmb_height=$imageheight*($width/$imagewidth);
		} else if($imageheight > $imagewidth) {
			$tmb_height=$height;
			$tmb_width=$imagewidth*($height/$imageheight);
		} else {
			$tmb_width=$width;
			$tmb_height=$height;
		}
	} else {
		$tmb_width=$imagewidth;
		$tmb_height=$imageheight;
	}
		
	$newimage=imagecreatetruecolor($tmb_width,$tmb_height);
	imageCopyResized($newimage,$image,0,0,0,0,$tmb_width,$tmb_height,$imagewidth,$imageheight);
	switch($format) {
		case GIF:
		case gif:
			if(function_exists("imagegif")) {
				//Header("Content-type: image/gif");
				ImageGIF($newimage,$path.$iconname);
				chmod($path.$iconname,0755);
			} else if(function_exists("imagejpeg")) {
				//Header("Content-type: image/jpeg");
				ImageJPEG($newimage,$path.$iconname);
				chmod($path.$iconname,0755);
			}
		break;
		case JPEG:
		case JPG:
		case jpeg:
		case jpg:
			if(function_exists("imagejpeg")) {
				//Header("Content-type: image/jpeg");
				ImageJPEG($newimage,$path.$iconname);
				chmod($path.$iconname,0755);
			}
		break;
		case PNG:
		case png:
			if(function_exists("imagepng")) {
				//Header("Content-type: image/png");
				ImagePNG($newimage,$path.$iconname);
				chmod($path.$iconname,0755);
			}
		break;
	}
	
	//db storage
	$fileData=0;
	$fileData=addslashes(file_get_contents($path.$iconname));
	//filesize
	$fileSize=0;
	$fileSize=filesize($path.$iconname);
	
	//icon has been created, now store a reference
	mysqlquery("insert into vl_filenames 
		(categoryID,name,filename,type,mimeType,size,file,filepath,fileurl,admin,gallery,created,createdby) 
		values 
		('$categoryID','$iconname','$iconname','$extension','$fileType','$fileSize','$fileData','".$path.$iconname."','".$urlpath.$iconname."','1','1','$datetime','$user')");
}

/**
* resize image
*/

function resizeImage($path,$file,$filename,$width,$height,$format) {
	/*
	use this example for reference
	path: C:/xampp/htdocs/vl/onlinefiles/uploads/_usericons/84/,
	file: C:/xampp/htdocs/vl/onlinefiles/uploads/_usericons/84/bmwx62.jpg,
	filename: bmwx62.jpg,
	width: 640,
	height: 480,
	dir: ,
	format: jpg
	*/
	
	//thumbnail settings
	$tmb_src=0;
	$tmb_src=$file;
					
	//thumbnail creation
	$image=0;
	$imagewidth=0;
	$imageheight=0;
				
	switch($format) {
		case GIF:
		case gif:
			$image=ImageCreateFromGIF($tmb_src);
		break;
		case JPEG:
		case JPG:
		case jpeg:
		case jpg:
			$image=ImageCreateFromJPEG($tmb_src);
		break;
		case PNG:
			$image=ImageCreateFromPNG($tmb_src);
		break;
	}
		
	//parsed dimensions
	$tmb_width=0;
	$tmb_height=0;

	//default dimensions
	$imagewidth=ImageSx($image);
	$imageheight=ImageSy($image);
					
	$newimage=0;
	//do the neccessary resizing on condition that ...
	if($imagewidth>$width || $imageheight>$height) {
		if ($imagewidth > $imageheight) {
			$tmb_width=$width;
			$tmb_height=$imageheight*($width/$imagewidth);
		} else if($imageheight > $imagewidth) {
			$tmb_height=$height;
			$tmb_width=$imagewidth*($height/$imageheight);
		} else {
			$tmb_width=$width;
			$tmb_height=$height;
		}
	} else {
		$tmb_width=$imagewidth;
		$tmb_height=$imageheight;
	}
		
	$newimage=imagecreatetruecolor($tmb_width,$tmb_height);
	imageCopyResized($newimage,$image,0,0,0,0,$tmb_width,$tmb_height,$imagewidth,$imageheight);
	switch($format) {
		case GIF:
		case gif:
			if(function_exists("imagegif")) {
				//Header("Content-type: image/gif");
				ImageGIF($newimage,$path.$filename);
				chmod($path.$filename,0755);
			} else if(function_exists("imagejpeg")) {
				//Header("Content-type: image/jpeg");
				ImageJPEG($newimage,$path.$filename);
				chmod($path.$filename,0755);
			}
		break;
		case JPEG:
		case JPG:
		case jpeg:
		case jpg:
			if(function_exists("imagejpeg")) {
				//Header("Content-type: image/jpeg");
				ImageJPEG($newimage,$path.$filename);
				chmod($path.$filename,0755);
			}
		break;
		case PNG:
		case png:
			if(function_exists("imagepng")) {
				//Header("Content-type: image/png");
				ImagePNG($newimage,$path.$filename);
				chmod($path.$filename,0755);
			}
		break;
	}
}

/**
* get user's icon
*/

function getUserIcon($email) {
	$query=0;
	$query=mysqlquery("select * from vl_filenames where createdby='$email' and admin=0 and gallery=0 order by created desc");
	if(mysqlnumrows($query)) {
		$q=array();
		while($q=mysqlfetcharray($query)) {
			//get the type
			$type=0;
			$type=$q["type"];
			//get the filename
			$name=0;
			$name=$q["name"];
			//remove extension from name
			$newname=0;
			$newname=preg_replace("/.$type/is","",$name);
			//length of this file
			$length=0;
			$length=strlen($newname);
			//are the last 6 characters == .small
			if(substr($newname,($length-6),6)==".small") {
				return $q["fileurl"];
				break;
			}
		}
	} else {
		return "images/icons/personal.s.gif";
	}
}

/**
* get user's image
*/

function getUserImg($iconID) {
	$query=0;
	$query=mysqlquery("select * from vl_filenames where id='$iconID' and admin=0");
	if(mysqlnumrows($query)) {
		//get the fileurl, remove .small from it, and return
		return preg_replace("/.small/is","",mysqlresult($query,0,'fileurl'));
	} else {
		return "images/icons/personal.s.gif";
	}
}

/**
* remove user images
*/

function removeUserImages($email) {
	$query=0;
	$query=mysqlquery("select * from vl_filenames where createdby='$email'");
	if(mysqlnumrows($query)) {
		$q=array();
		while($q=mysqlfetcharray($query)) {
			if(file_exists($q["filepath"])) {
				unlink($q["filepath"]);
			}
		}
	}
	logDataRemoval("delete from vl_filenames where createdby='$email'");
	mysqlquery("delete from vl_filenames where createdby='$email'");
}

/**
* load image
*/
function loadImage($url) {
	//get the corresponding file
	$query=0;
	$query=mysqlquery("select * from vl_filenames where fileurl='$url'");
	if(mysqlnumrows($query)) {
		//file missing
		if(!is_file(mysqlresult($query,0,'filepath'))) {
			//return db ref
			return "sys.viewfile.php?wR=".mysqlresult($query,0,'id');
		} else {
			$rand=0;
			$rand=rand(1,5);
			switch($rand) {
				case 5:
					return "sys.viewfile.php?wR=".mysqlresult($query,0,'id');
				break;
				case 4:
				case 3:
				case 2:
				case 1:
				default:
					return $url;
				break;
			}
		}
	} else {
		return $url;
	}
}
?>