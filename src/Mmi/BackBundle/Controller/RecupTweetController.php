<?php

namespace Mmi\BackBundle\Controller;


use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Abraham\TwitterOAuth\TwitterOAuth;



class RecupTweetController extends Controller
{
    public function indexAction()
    {
        require "vendor/autoload.php";

        $connection = new TwitterOAuth('zmcek7OeAGzTeomKu1OLx29Yq','iRnDZQzSD4wtZ6lAN3xiOdkrtNGXtaxzFv37LcK0YQmn731pNS','363814592-u9k01app4GhKP7DUSaXTl3irAikmTYvn2CPXN9PD
','aUfBBD4wy44DxGFAP3Kysk7qR8PUz1RBJ93NgDtshwvAy');

        $tweet = $connection= get('statuses/user_timeline');
        var_dump($tweet);

    }
}