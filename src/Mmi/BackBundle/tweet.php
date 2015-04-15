<?php



session_start();
require_once("twitteroauth/twitteroauth/twitteroauth.php"); //Path to twitteroauth library

$twitteruser = "Adri511";
$notweets = 100;
$consumerkey = "zmcek7OeAGzTeomKu1OLx29Yq";
$consumersecret = "iRnDZQzSD4wtZ6lAN3xiOdkrtNGXtaxzFv37LcK0YQmn731pNS";
$accesstoken = "363814592-u9k01app4GhKP7DUSaXTl3irAikmTYvn2CPXN9PD";
$accesstokensecret = "aUfBBD4wy44DxGFAP3Kysk7qR8PUz1RBJ93NgDtshwvAy";

function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
$connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
return $connection;
}

$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);

$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$twitteruser."&count=".$notweets);

$tab = array();
foreach($tweets as $tweet)
{
    if(preg_match("/RT/i",$tweet->{'text'}) || preg_match("/@/i",$tweet->{'text'})){

    }else{
        array_push($tab,$tweet->{'text'});
    }
}


$bdd=mysqli_connect("localhost","root","","mmichannel");
$result=mysqli_query($bdd,"SELECT * FROM message WHERE msg_type = 'tweet'");

$tab1 = $tweet->{'text'};
$tab2 = $result;