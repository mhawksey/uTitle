<?php
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');
	/*
	Here's where you want PHP to process the form data and do something with it, for example inserting the data into a database or sending the information to an email address and so on
	*/
	/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
$response = $connection->post('statuses/update', array('status' => stripslashes($_POST['tweet'])));
$r = $connection->http_code;
switch ($r) {
    case "200":
        print "Last tweet: ".stripslashes($_POST['tweet'])."<br>";
		print "<div id=\"out\">".$r."</div>";
        break;
    case "403":
        print "Twitter said: Your tweet has to be different<br>";
        break;
    case "400":
        print "Twitter said: You may have exceeded your update rate<br>";
        break;
	case "500":
        print "Twitter said: Something is broken<br>";
        break;
	case "502":
        print "Twitter said: Twitter is down or being upgraded<br>";
        break;
	case "503":
        print "Twitter said: The Twitter servers are up, but overloaded with requests. Try again later.<br>";
        break;
}
?>