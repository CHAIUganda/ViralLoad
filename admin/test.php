<?
include "conf.php";

	$home_url="http://www.vl.com/";
	$server_url="http://www.vl.com";
	$system_default_path="/dashboard/antsmsco/public_html/vl/";
	
	echo "home_url: $home_url, server_url: $server_url, system_default_path: $system_default_path<br>";
	
	$site_default_name=0;
	$site_default_name=$_SERVER['HTTP_HOST'];

	echo "site_default_name: $site_default_name<br>";

	$site_default_name=preg_replace("/admin//is","",$site_default_name);
	$site_default_name=preg_replace("/management/is","",$site_default_name);
	
	echo "site_default_name: $site_default_name<br>";

	$home_domain=0;
	$home_domain=preg_replace("/www./is","",$site_default_name);

	echo "home_domain: $home_domain<br>";

	$home_domain=preg_replace("/admin//is","",$home_domain);
	$home_domain=preg_replace("/management/is","",$home_domain);

	echo "home_domain: $home_domain<br>";

	$url=NULL;
	$url=@preg_replace("/$server_url/is","",$home_url);

	echo "url: $url<br>";

	/**
	* remove this component from the $_SERVER['REQUEST_URI']
	* leaving us with the exact url: ?p=page&a=action&c=country
	*/
	$return=0;
	$return=preg_replace("/$url/is","",$_SERVER['REQUEST_URI']);

	echo "REQUEST_URI: ".$_SERVER['REQUEST_URI']."<br>";
	echo "return: $return<br>";
	//final clean through
	$return=preg_replace("/$siteFolder/is","",$return);
	echo "return: $return<br>";
?>