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


$bdd = mysqli_connect('localhost', 'adrien', 'Escalier1', 'mmichannel');
$result=mysqli_query($bdd, "SELECT * FROM Message WHERE msg_type = 'tweet'");
$tous=mysqli_fetch_assoc($result);



$tab2=array();

foreach($tous as $recup)
{
    $var=utf8_encode($recup['msg_text']);
    array_push($tab2,$var);
}


$tab3=array_diff($tab,$tab2);

foreach ($tab3 as $tw)
{
    $bdd = mysqli_connect('localhost', 'adrien', 'Escalier1', 'mmichannel');
    $result=mysqli_query($bdd, "INSERT INTO Message(msg_titre, msg_text, msg_date, msg_type,msg_voir) VALUES ('Tweet du Twitter',\"".utf8_decode($tw)."\",'".date('Y-m-d')."','tweet','0')");
    //echo "INSERT INTO Message(msg_titre, msg_text, msg_date, msg_type,msg_voir) VALUES ('Tweet du Twitter',\"".utf8_decode($tw)."\",'".date('Y-m-d')."','tweet','0')";
    $tous=mysqli_fetch_assoc($result);

}

echo utf8_decode("<p>Les tweets sont générés. Retourner aux messages en cliquant <a href='http://vps136201.ovh.net/projet/mmichannel/web/app_dev.php/client/message'>ici</a></p>");
?>