<?php
/**
 * Plugin Name:Theme Importer
 * Plugin URI: http://www.stevs.net/plugins/theme-importer/
 * Description: Install themes from remote locations into your themes folder.
 * Version: 1.0
 * Author: Stev
 * Author URI: http://www.stevs.net
 * License: PLv2 or later
 * License URI: http://www.stevs.net/plugins/licenses/
 *
 *  Copyright 2015  Stev  (email : support@stevs.net) 
 *
 **/




add_action('admin_menu', 'wpti_menu');
function wpti_menu() {
    add_theme_page('Theme Importer', 'Importer', 'manage_options', __FILE__, 'wpti_page');
}
function wpti_page() {
?>
<script>
function setUpExtract(zip){
if(zip.indexOf(".zip")>=0){
 document.f2.dl.value=zip;
 } 
}
msg="Welcome";
</script>
<style>
 h1, h2, h3 {font-family:verdana;}
 li {font-family:verdana;line-height:1.8;}
 blockquote {font-weight:normal;}
.txt { width: 600px; height:35px; font-family:verdana; padding:5px; font-size: 18px;}
.submitButton {width: 100px; height:35px; font-family:arial; font-size:14px; padding:5px; background-color:#2EA2CC;color:white;border:0px;  }
.fdata { font-size: 12px; }
</style>
<?php
function getRemoteFileSize1($url){
    $url_p = parse_url($url);
    $host = $url_p["host"];
    $path = $url_p["path"];
    $fp = @fsockopen($host, 80, $errno, $errstr, 20);
    if(!$fp) 
    {
        return false; 
    } else {
        fputs($fp, "HEAD ".$url." HTTP/1.1\r\n");
        fputs($fp, "HOST: dummy\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        while (!@feof($fp)) {
            $headers .= @fgets ($fp, 128);
        }
    }
    fclose ($fp);
    $return = false;
    $arr_headers = explode("\n", $headers);
    foreach($arr_headers as $header) {
        $s = "Content-Length: ";
        if(substr(strtolower ($header), 0, strlen($s)) == strtolower($s)) {
            $return = trim(substr($header, strlen($s)));
            break;
        }
    }
    echo $return;
}
$st1=21;
if($_POST){
$url  = $_POST['dl'];
$remoteFile = $url;
$ch = curl_init($remoteFile);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
$data = curl_exec($ch);
curl_close($ch);
if ($data === false) {
}
$contentLength = 'unknown';
$status = 'unknown';
if (preg_match('/^HTTP\/1\.[01] (\d\d\d)/', $data, $matches)) {
  $status = (int)$matches[1];
}
if (preg_match('/Content-Length: (\d+)/', $data, $matches)) {
  $contentLength = (int)$matches[1];
}
$remFLen = $contentLength;
}
?>

<h1><span style="font-family:arial;color:#000000">Theme Importer</span>
<button class="submitButton" onclick="top.location.href='http://www.stevs.net/plugins/theme-importer/';">Website</button>
<?php 
$tiVer='11'.$st1.'625'.'5';
$upload_dir = get_theme_root(); 
?>
</h1>
<table border=0 cellspacing=1 cellpadding=0 border=0 width=700><tr><td valign=top width=700>
<?php
$tbl=	'<table border=0 cellpadding=0 cellspacing=1><tr><td colspan=4>';
$fm=	'<form method=post name="f2" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'?page=theme-importer%2Ftheme-importer.php&id=3">'.
     	'<td valign=top>'.
    	'<input type=text  class=txt name="dl" value="">'.
    	'<input type="hidden" name="dldir" value="">'.
    	'</td><td align=right>'.
    	'<input type=submit  class="submitButton" value=" Import">'.
    	'</td></tr><tr><td>Need to import other files into Wordpress? <a href="http://www.stevs.net/plugins/fast-file-importer/" target="_blank">Click here</a></td></form></tr></table>';
$altOpt='<script>msg="This file'.
    	' is too big for the free version and'.
    	' requires the pro version.\n\n\tFREE - 10MB file limit\n\tPRO - Unlimited file limit\n\n'.
    	'Click OK for more info";q=confirm(msg); '.
'if(q==true){ top.location.href="http://www.stevs.net/plugins/theme-importer/";}'.
'else{ location.href="'.htmlspecialchars($_SERVER["PHP_SELF"]).'?page=theme-importer%2Ftheme-importer.php";}</script>';
function formatBytes($size, $precision = 2)
{
    $base = log($size) / log(1024);
    $suffixes = array('<font color=blue>B</font>', '<font color=blue>K</font>', '<font color=red>M</font>', '<font color=green>G</font>', '<font color=blue>T</font>');   
    return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
}
if($_GET){
$id = $_GET["id"];
if(!$id) {
echo $tbl.'<span class=txt>Paste Theme Zip URL Here</span></td></tr><tr>'.$fm;
}

if($id=="3") {
	$url = $_POST['dl'];
	$filename = basename($url);
	if (file_exists($upload_dir.'/'.$filename)) {
	$zip = new ZipArchive;
	$zipdir=$upload_dir.'/'.str_replace(".zip",$filename);
	if (file_exists($zipdir)) { 
		$res = $zip->open($upload_dir.'/'.$filename);
     		if ($res === TRUE) {
      	   	$zip->extractTo($zipdir);
      	   	$zip->close();
			}	
		}
	else if(mkdir($zipdir , 0777)) {
		$res = $zip->open($upload_dir.'/'.$filename);
     		if ($res === TRUE) {
      	   	$zip->extractTo($zipdir);
      	   	$zip->close();
			}	
    		}
	}
	else {
	if (@copy($url, $upload_dir.'/'.basename($url)))
	$zip = new ZipArchive;
	$zipdir=$upload_dir.'/'.str_replace(".zip","",$filename);
	if(mkdir($zipdir , 0777)) { 
		$res = $zip->open($upload_dir.'/'.$filename);
     		if ($res === TRUE) {
      	   	$zip->extractTo($zipdir);
      	   	$zip->close();
			}	
    		}
    	}
    	echo $tbl.'<span class=txt>'.basename($url).
    	' has been installed and is ready to activate</span></td></tr><tr>'.
    	$fm;
	unlink($upload_dir.'/'.basename($url));
	} 
   }
else 	
{
echo $tbl.'<span class=txt>Paste Theme Zip URL Here</span></td></tr><tr>'.$fm; 
}
?>

<?php
} 
?>