<?php
/**
 * @file
 * user has successfully authenticated with Twitter. Access tokens saved to session and DB.
 */

/* Load required lib files. */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');

/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    header('Location: ./clearsessions.php');
}
/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

$user = $connection->get('account/verify_credentials');
$user = $user->screen_name;

$v = $_GET['v'];

//check if TwapperKeeper notebook exists
$notebookResult = resultsFromTK(TWAPPERKEEPER_API, $v, 'info');
if ($notebookResult->response->count == 0){
	// if not make one
    createNotebook($v, $);
} else {
	// if one exists pull data
	$tkResult = resultsFromTK(TWAPPERKEEPER_API, $v, 'tweets');
	$tkResult = $tkResult->response->tweets_returned;
	$tkResult = objectToArray($tkResult);
	//echo"helo";
	//print_r($tkResult);
	foreach($tkResult as $i){
	
	}
}

// topup results from Twitter Search API
$twResult = resultsFromTw($v);
$twResult = $twResult->results;
$twResult = objectToArray($twResult);
//print_r(merge($tkResult, $twResult));
//print_r($twResult);
if(isset($tkResult) && isset($twResult)){
	$attribs = array_merge($tkResult, $twResult);
} elseif (!isset($tkResult) && isset($twResult)) {
	$attribs = $twResult;
} elseif (isset($tkResult) && !isset($twResult)) {
	$attribs = $tkResult;
}
if (!empty($attribs)){
	$tweets = array(); 
	$exclude = array(""); 
	for ($i = 0; $i<=count($attribs)-1; $i++) { 
		if (!in_array(trim($attribs[$i]["id"]) ,$exclude)) { $tweets[] = $attribs[$i]; $exclude[] = trim($attribs[$i]["id"]); } 
	} 
	
	// $i is our sorted array 
	$tweets = subval_sort($tweets,"id");
	
	foreach ($tweets as $k => $v) {
		preg_match('/#'.$v.' (\d{2}:\d{2})$/',$tweets[$k]['text'],$m);
		$tweets[$k]['timestamp'] = strtotime($m[1]);
	}
	$startDate = $tweets[0]['timestamp'];
	foreach ($tweets as $k => $v) {
		$timestamp = $tweet[$k]['timestamp'];
		$sub = $tweets[$k]['from_user'].": ".strip_tags($tweets[$k]['text']);
		$tt = $tt. "<p style=\"s1\" begin=\"".gmdate('H:i:s', $timestamp-$startDate)."\" id=\"p".$k."\" end=\"".gmdate('H:i:s', $i[$k+1]['timestamp']-$startDate)."\">";
		$tt = $tt. $sub ."</p>\n";
	}
	$file = fopen("xml/".$v.".xml","w");
	fwrite($file,$out);
	fclose($file);
}
$jwplayer = file_get_contents('inc/YouTubeBody.php');
$jwplayer  = str_replace('%YOUTUBEURL%' , $v , $jwplayer );  
$jwplayer  = str_replace('%XMLSUBTITLE%' , $tt , $jwplayer );

function subval_sort($a,$subkey) {
	foreach($a as $k=>$v) {
		$b[$k] = strtolower($v[$subkey]);
	}
	asort($b);
	foreach($b as $key=>$val) {
		$c[] = $a[$key];
	}
	return $c;
}

function resultsFromTw($v){
	$build_array  = array(
		'q'=> '#'.$v,
		'rpp'=> '100',
		'result_type'=> 'recent'
		);
	$request_data = http_build_query($build_array);
	$c = curl_init('http://search.twitter.com/search.json/?'.$request_data);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($c);
	$status = curl_getinfo($c, CURLINFO_HTTP_CODE);
	curl_close($c);
	$result = json_decode($result);
	return $result;
}
function resultsFromTK($apikey, $name, $whatfor, $type="hashtag"){
	$build_array  = array(
		'apikey'=> $apikey,
		'name'=> $name,
		'type'=> $type
		);
	if ($whatfor == "tweets"){
		$build_array['start'] = "0000000000";
		$build_array['end'] = date("U");
	}
	$request_data = http_build_query($build_array);
	$c = curl_init('http://api.twapperkeeper.com/notebook/'.$whatfor.'/?'.$request_data);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($c);
	$status = curl_getinfo($c, CURLINFO_HTTP_CODE);
	curl_close($c);
	$result = json_decode($result);
	return $result;
}

function createNotebook($name,$user){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://api.twapperkeeper.com/notebook/create/");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, true);
	$build_array = array(
		'apikey' => TWAPPERKEEPER_API,
		'name' => $name,
		'type' => 'hashtag',
		'description' => 'Notebook created by uTitle for YouTube timeline commenting',
		'created_by' => $user
	);
	$request_data = http_build_query($build_array);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $request_data );
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}
function objectToArray($object){
	if(!is_object($object) && !is_array($object)){
		return $object;
	}
	if(is_object($object)){
		$object = get_object_vars( $object );
	}
	return array_map( 'objectToArray', $object );
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>uTitle - YouTube/Twitter timeline commenting tool</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.validate/1.7/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#myform").validate({
			submitHandler: function(form) {
				// do other stuff for a valid form
				$("#tweet").val($("#tw_preview").html());
				$.post('process.php', $("#myform").serialize(), function(data) {
					$('#results').html(data);
					if ($('#out').html() == "200"){
						$('#tw_preview').html("");
						$('#txt').val("");
						$(this).html("Mark");
						$(this).attr('class', 'but_mk');
						$(this).removeAttr('mk_time');
					}
				});
			}
		});
		$("#but_mk").toggle(
		function () {
$(this).html("Unmark");
$(this).attr('class', 'but_mk_dis');
$(this).attr('mk_time', $('#time').html())},
		function () { 
$(this).html("Mark");
$(this).attr('class', 'but_mk');
$(this).removeAttr('mk_time');}
);
		// character count http://www.9lessons.info/2010/04/live-character-count-meter-with-jquery.html
		$("#txt").keyup(function(){
			var v = "#<?php echo $v; ?>";
		    var maxChar = 140 - v.length - 6;
			var box=$(this).val();
			var main = box.length *100;
			var value= (main / maxChar);
			var count= maxChar - box.length;
			var countTxt = "Oops something is wrong";
			if ($("#but_mk").attr("mk_time")){
				countTxt = $("#but_mk").attr("mk_time");
			} else {
				countTxt = $('#time').html();
			}
			$('#tw_preview').html(box +" "+ v +" "+ countTxt);
			
			if (count <= 0 || count === maxChar){
				$('#bar').css('background-color', '#cc0000' );
				$('#count').css('color', '#cc0000' );
				$('#update_status').attr('disabled', 'disabled');
				$('#update_status').attr('class', 'but_tw_dis');
			} else {
				$('#bar').css('background-color', '#5fbbde' );
				$('#count').css('color', '#666666' );
				$('#update_status').removeAttr("disabled");
				$('#update_status').attr('class', 'but_tw');
			}
			if(box.length <= maxChar){
				$('#bar').animate({"width": value+'%',}, 1);
			} 
			$('#count').html(count);
			return false;
		});
	});
	 function playerReady(obj)
      {
        player = gid(obj.id);
        addListeners();
      };


      function addListeners()
      {
        playlist = player.getPlaylist();

        if(playlist.length > 0)
        {
          player.addModelListener('TIME',    'timeMonitor');
          player.addModelListener('LOADED',  'loadedMonitor');
        }
        else
        {
          setTimeout("addListeners();", 100);
        }
      };


      function timeMonitor(obj)
      {
        //currentDuration = obj.duration;
        currentTime     = obj.position;
        gid('time').innerHTML = getTimeCode(currentTime);
      };

      function gid(name)
      {
        return document.getElementById(name);
      };
	  //String formatter from: http://forums.devshed.com/javascript-development-115/convert-seconds-to-minutes-seconds-386816.html
String.prototype.pad = function(l, s){
return (l -= this.length) > 0 ? (s = new Array(Math.ceil(l / s.length) + 1).join(s)).substr(0, s.length) + this + s.substr(0, l - s.length) : this; };
 
	function getTimeCode(elapsed){
		var pureSeconds=elapsed % 60;
		var seconds=""+(elapsed % 60).toFixed().pad(2, "0");
		var pureMinutes=Math.floor((elapsed-pureSeconds) / 60) % 60;
		var minutes=(Math.floor((elapsed-pureSeconds) / 60) % 60).toFixed().pad(2, "0");
		pureSeconds+=60*pureMinutes;
		var timecode=minutes+":"+seconds;
		//var timecode="00:"+(Math.floor(elapsed / 60)).toFixed().pad(2, "0") + ":" + (elapsed % 60).toFixed().pad(2, "0")+".0";
		return timecode;
	}
	</script>
<link href="style.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
<h1>uTitle - YouTube/Twitter timeline commenting tool</h1>
<?php echo $jwplayer; ?>
<div id="content">
<div id="entryArea">
  
    <!-- The Name form field -->
	<strong>Tweet preview:</strong>
    <div id="tw_preview"> </div>
    <div id="mark"><a href="#" class="but_mk" id="but_mk">Mark</a></div>
	<div id="barbox">
      <div id="bar"></div>
    </div>
	<div id="count">--</div>
	
	
	<div id="time" style="display:none;"></div>
	<form name="myform" id="myform" action="" method="POST">
	  <input name="tweet" type="hidden" id="tweet" />
    <textarea name="txt" id="txt"></textarea><!-- The Submit button --><input name="update_status" id="update_status" type="submit" class="but_tw_dis" value="Tweet" disabled="disabled"/>
  </form>
</div>
<div id="info">
<img src="http://www.rsc-ne-scotland.ac.uk/images/RSCScotNE.png" alt="JISC RSC Scotland North &amp; East">
</div>
<!-- We will output the results from process.php here -->
<div id="results"></div>
</div>
</body>
</html>
