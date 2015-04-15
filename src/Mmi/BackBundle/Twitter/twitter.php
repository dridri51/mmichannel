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


$bdd = new PDO("mysql:host=localhost;dbname=mmichannel","adrien","Escalier1");
$result=$bdd->query("SELECT * FROM message WHERE msg_type = 'tweet'");
$tous=$result->fetchAll();


$tab2=array();

foreach($tous as $recup)
{
    $var=utf8_encode($recup['msg_text']);
    array_push($tab2,$var);
}

//var_dump($tab);
//var_dump($tab2);

$tab3=array_diff($tab,$tab2);
//var_dump($tab3);
foreach ($tab3 as $tw)
{
    $bdd = new PDO("mysql:host=localhost;dbname=mmichannel","adrien","Escalier1");
    $bdd->query("INSERT INTO message(msg_titre, msg_text, msg_date, msg_type) VALUES ('Tweet du Twitter',\"".utf8_decode($tw)."\",'".date('Y-m-d')."','tweet')");
    echo "INSERT INTO message(msg_titre, msg_text, msg_date, msg_type) VALUES ('Tweet du site',".utf8_decode($tw).",".date('Y-m-d').",'tweet')";
}
if($this->getUser()->getRoles([0])=="ROLE_ADMIN"){
header('Location: http://vps136201.ovh.net/projet/mmichannel/web/app.php/admin/message');
}else{
    header('Location: http://vps136201.ovh.net/projet/mmichannel/web/app.php/client/message');

}
?>