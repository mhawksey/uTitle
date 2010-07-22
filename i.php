<?php
/**
 * @file
 * user has successfully authenticated with Twitter. Access tokens saved to session and DB.
 */

/* Load required lib files. */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');
$v = $_GET['v'];
if (!isset($v)){
	header('Location: ./index.php');
}


/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    //header('Location: ./clearsessions.php');
	$content = 'To leave comments <a href="./redirect.php?v='.$_GET['v'].'"><img src="./images/lighter.png" alt="Sign in with Twitter" border="0"/></a>';
} else {
	/* Get user access tokens out of the session. */
	$access_token = $_SESSION['access_token'];
	
	/* Create a TwitterOauth object with consumer/user tokens. */
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
	
	$user = $connection->get('account/verify_credentials');
	$userID = $user->id;
	$user = $user->screen_name;
}

//check if TwapperKeeper notebook exists
$notebookResult = resultsFromTK(TWAPPERKEEPER_API, $v, 'info');
if ($notebookResult->response->count == 0){
	// if not make one
	if (isset($user)){
    	createNotebook($v, $user, $userID);
	}
} else {
	// if one exists pull data
	$tkResult = resultsFromTK(TWAPPERKEEPER_API, $v, 'tweets');
	$tkResult = $tkResult->response->tweets_returned;
	$tkResult = objectToArray($tkResult);
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
	foreach ($tweets as $k => $val) {
		preg_match('/#'.$v.' (\d{2}:\d{2})$/',$tweets[$k]['text'],$m);
		$tweets[$k]['timestamp'] = strtotime("00:".$m[1]);
	}
	$startDate = strtotime("00:00:00");
	foreach ($tweets as $k => $val) {
		if(strlen(strstr($tweets[$k]['text'],"RT"))==0){
			$twtime = $tweets[$k]['timestamp'];
			$sub = $tweets[$k]['from_user'].": ".strip_tags($tweets[$k]['text']);
			if ($twtime!=""){
				$tt .= "<p style=\"s1\" title=\"Tweeted on ".gmdate('d M Y H:i:s', strtotime($tweets[$k]['created_at']))."\" begin=\"".gmdate('H:i:s', $twtime-$startDate)."\" id=\"p".$k."\" end=\"".gmdate('H:i:s', $tweets[$k+1]['timestamp']-$startDate)."\">";
				$tt .= $sub ."</p>\n";
			} else {
				$ct .= "<p style=\"s2\" title=\"Tweeted on ".gmdate('d M Y H:i:s', strtotime($tweets[$k]['created_at']))."\" ><span class=\"comm\">";
				$ct .= $sub ."</span></p>\n";
			}
		}
	}
	$out = file_get_contents('inc/ttHead.php').$tt.file_get_contents('inc/ttFoot.php');
	$file = fopen("xml/".$v.".xml","w");
	fwrite($file,$out);
	fclose($file);
	$file = fopen("xml/".$v.".txt","w");
	fwrite($file,$ct);
	fclose($file);
}
//$embedCode = file_get_contents('inc/embed.php');
$jwplayer = file_get_contents('inc/YouTubeBody.php');
$jwplayer = str_replace('%YOUTUBEURL%' , $v , $jwplayer );  
$jwplayer = str_replace('%XMLSUBTITLE%' , $ct.$tt , $jwplayer );
$jwplayer  = str_replace('%UTITLE_URL%' , UTITLE_URL , $jwplayer );
$jwplayer  = str_replace('%UTITLE_URL_ENCODE%' , urlencode(UTITLE_URL) , $jwplayer );
$s = isset($_GET['start']) ? $_GET['start'] : "0";
$jwplayer = str_replace('%START%' , $s , $jwplayer);

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

function createNotebook($name,$user,$userID){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://api.twapperkeeper.com/notebook/create/");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, true);
	$build_array = array(
		'apikey' => TWAPPERKEEPER_API,
		'name' => $name,
		'type' => 'hashtag',
		'description' => 'Notebook created by uTitle for YouTube timeline commenting',
		'created_by' => $user,
		'user_id' => $userID
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

 echo $jwplayer; ?>
<div id="entryArea">
  <?php if (isset($content)){ 
	echo $content;
} else {
?>
  <!-- The Name form field --> 
  <strong>Tweet preview:</strong> (Signed in as:
  <?php if ($user!="") echo $user.' | <a href="clearsessions.php">Logout</a>'; ?>
  )
  <div id="tw_preview"> </div>
  <div id="mark">Comment type:<a href="#" class="but_mk" id="but_com" accesskey="c">Timed</a> | Timed:<a href="#" class="but_mk" id="but_mk" accesskey="m">Auto-Mark</a></div>
  <div id="barbox">
    <div id="bar"></div>
  </div>
  <div id="count">--</div>
  <div id="time" style="display:none;"></div>
  <form name="myform" id="myform" action="" method="POST">
    <input name="tweet" type="hidden" id="tweet" />
    <textarea name="txt" id="txt"></textarea>
    <!-- The Submit button -->
    <input name="update_status" id="update_status" type="submit" class="but_tw_dis" value="Tweet" accesskey="t" disabled="disabled"/>
  </form>
  <?php } ?>
</div>

<!-- We will output the results from process.php here -->
<div id="results">Last tweet: </div>
<div style="width:996px; clear:both; font-size:70%; text-align:center;">
  <p>uTitle created by <a href="http://www.google.com/profiles/m.hawksey">m.hawksey</a> at <a href="http://www.rsc-ne-scotland.ac.uk/">JISC RSC Scotland North &amp; East</a> | This site is a third party application for <a href="http://twitter.com">Twitter</a> and <a href="http://www.youtube.com">YouTube<br />
    </a>Credits: Twitter integration using <a href='http://github.com/abraham/twitteroauth'>TwitterOAuth</a>, Twitter archive using <a href="http://twapperkeeper.com/">Twapper Keeper</a> and Video playback using <a href="http://www.longtailvideo.com/players/jw-flv-player/">JW Player</a></p>
</div>