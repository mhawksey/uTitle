<?php 
require_once('config.php');
$id = $_GET['v'];

$xml = file_get_contents("xml/".$id.".txt").file_get_contents("xml/".$id.".xml");
$xmlHead = file_get_contents('inc/ttHead.php');
$xmlFoot = file_get_contents('inc/ttFoot.php');
$xml = str_replace($xmlHead, "", $xml);
$xml = str_replace($xmlFoot, "", $xml);
$jwplayer = file_get_contents('inc/YouTube.php');
$jwplayer  = str_replace('%YOUTUBEURL%' , $id , $jwplayer );  
$jwplayer  = str_replace('%XMLSUBTITLE%' , $xml , $jwplayer );
$jwplayer  = str_replace('%UTITLE_URL%' , UTITLE_URL , $jwplayer );
$jwplayer  = str_replace('%UTITLE_URL_ENCODE%' , urlencode(UTITLE_URL) , $jwplayer );
header("Content-type:text/html; charset=utf-8");
echo $jwplayer;
?>
