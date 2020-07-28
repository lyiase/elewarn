<?php
require_once("init.php");
require_once("elewarn.php");
require_once("lib/twitter/twitteroauth.php");

// OAuthオブジェクト生成
post($area);sleep(3);
foreach($warnMsgs as $message){
	post($message);
	sleep(3);
}

function post($message){
	$oTwitter = new TwitterOAuth(OAUTH_CONSUMER_KEY,OAUTH_CONSUMER_SECRET,OAUTH_ACCESS_TOKEN,OAUTH_ACCESS_TOKEN_SECRET);
	$r_url = "https://api.twitter.com/1.1/statuses/update.json";
	$r_method = "POST";
	$oTwitter->OAuthRequest($r_url,$r_method,array("status" => $message));
}
?>