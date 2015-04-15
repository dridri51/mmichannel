<?php

namespace Mmi\BackBundle\Controller;

use Mmi\BackBundle\Form\VideoType;
use Mmi\BackBundle\Form\PlaylistType;
use Mmi\BackBundle\Entity\Playlist;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Mmi\BackBundle\Entity\Video;
use Mmi\BackBundle\Entity\User;



class CreateVideoController extends Controller {

    public function indexAction(request $request)
    {
      /*  $user = new User();
        $user->setUsername('jean');
        $user->setEmail('adrien@gmail.com');
        $user->setPassword('adrien@gmail.com');
        $user->setNom('Bourbon');
        $user->setPrenom('Adrien');
        $user->setVerrou(0);*/



        $video = new Video();
        $form= $this->createForm(new VideoType, $video);
        $form->handleRequest($request);
        if($form->isValid())
        {
            // Récupérer les info des la vidéo (cas de youtube)

            function getTitle($url)
            {
                $result = false;

                $contents = getUrlContents($url);

                if (isset($contents) && is_string($contents))
                {
                    $title = null;
                    $metaTags = null;

                    preg_match('/<title>([^>]*)<\/title>/si', $contents, $match );

                    if (isset($match) && is_array($match) && count($match) > 0)
                    {
                        $title = strip_tags($match[1]);
                    } else {
                        $title=false;
                    }
                }

                return $title;
            }

            function getUrlContents($url, $maximumRedirections = null, $currentRedirection = 0)
            {
                $result = false;

                $contents = @file_get_contents($url);

                // Check if we need to go somewhere else

                if (isset($contents) && is_string($contents))
                {
                    preg_match_all('/<[\s]*meta[\s]*http-equiv="?REFRESH"?' . '[\s]*content="?[0-9]*;[\s]*URL[\s]*=[\s]*([^>"]*)"?' . '[\s]*[\/]?[\s]*>/si', $contents, $match);

                    if (isset($match) && is_array($match) && count($match) == 2 && count($match[1]) == 1)
                    {
                        if (!isset($maximumRedirections) || $currentRedirection < $maximumRedirections)
                        {
                            return getUrlContents($match[1][0], $maximumRedirections, ++$currentRedirection);
                        }

                        $result = false;
                    }
                    else
                    {
                        $result = $contents;
                    }
                }

                return $contents;
            }

            function get_donnees_video($url){
                $stud_detect = false;
                $embed_stubs = array(
                    array(
                        'providers' => 'YouTube',
                        'website' => 'http://www.youtube.com',
                        'url-match' => 'http(?:s|(?:[^"]*?))://(?:video\.google\.(?:com|com\.au|co\.uk|de|es|fr|it|nl|pl|ca|cn)/(?:[^"]*?))?(?:(?:www|au|br|ca|es|fr|de|hk|ie|in|il|it|jp|kr|mx|nl|nz|pl|ru|tw|uk)\.)?youtube\.com(?:[^"]*?)?(?:&|&amp;|/|\?|;|\%3F|\%2F)(?:video_id=|v(?:/|=|\%3D|\%2F))([0-9a-z-_]{11})',
                        'tab_images' => array(
                            'http://i.ytimg.com/vi/[MEDIA_ID]/0.jpg',
                            'http://i.ytimg.com/vi/[MEDIA_ID]/1.jpg',
                            'http://i.ytimg.com/vi/[MEDIA_ID]/2.jpg',
                            'http://i.ytimg.com/vi/[MEDIA_ID]/3.jpg'
                        ),
                        'iframe-player' => 'http://www.youtube.com/embed/[MEDIA_ID]',
                    ),
                    array(
                        'providers' => 'Dailymotion',
                        'website' => 'http(?:s|(?:[^"]*?))://www.dailymotion.com',
                        'url-match' => 'http://(?:www\.)?dailymotion\.(?:com|alice\.it)/(?:(?:[^"]*?)?video|swf)/([a-z0-9]{1,18})',
                        'tab_images' => array(
                            'http://www.dailymotion.com/thumbnail/650x400/video/[MEDIA_ID]'
                        ),
                        'iframe-player' => 'http://www.dailymotion.com/embed/video/[MEDIA_ID]',
                    ),
                    array(
                        'providers' => 'Vimeo',
                        'website' => 'http(?:s|(?:[^"]*?))://www.vimeo.com',
                        'url-match' => 'http://(?:www\.)?vimeo\.com/([0-9]{1,12})',
                        'image-src' => false,
                        'iframe-player' => 'http://player.vimeo.com/video/[MEDIA_ID]',
                    )
                );
                foreach ($embed_stubs as $stub) {
                    if ( preg_match('~'.$stub['url-match'].'~imu', $url, $match) ) {
                        $media_link = $match[0];
                        $media_id = $match[1];
                        $stud_detect = true;
                        break;
                    }
                }
                if($stud_detect && $media_id){
                    $tab_return['providers'] = $stub['providers'];
                    $tab_return['media_id'] = $media_id;
                    $tab_return['media_link'] = $media_link;
                    $tab_return['iframe-player'] = str_ireplace('[MEDIA_ID]',$media_id,$stub['iframe-player']);
                    foreach((array)$stub['tab_images'] AS $KEY => $value){
                        $tab_return['tab_images'][] = str_ireplace('[MEDIA_ID]',$media_id,$value);
                    }
                    switch($tab_return['providers']){
                        case 'YouTube' :
                            $xml = file_get_contents("http://gdata.youtube.com/feeds/api/videos/".$tab_return['media_id']);
                            preg_match('#<title(.*?)>(.*)<\/title>#is',$xml,$resultTitre);
                            $tab_return['titre'] = utf8_decode($resultTitre[count($resultTitre)-1]);
                            preg_match('#<content(.*?)>(.*)<\/content>#is',$xml,$resultDescription);
                            $tab_return['description'] = utf8_decode($resultDescription[count($resultDescription)-1]);
                            preg_match("#<yt:duration seconds='([0-9]+)'\/>#",$xml,$resultDuration);
                            $tab_return['duration'] = $resultDuration[count($resultDuration)-1];
                            break;
                        case 'Dailymotion' :
                            $content=file_get_contents('https://api.dailymotion.com/video/'.$tab_return['media_id'].'?fields=duration');
                            $data=json_decode($content);
                            $tab_return['titre'] = utf8_decode(str_replace("- Vidéo Dailymotion","",getTitle($tab_return['media_link'])));
                            $tags = get_meta_tags("http://www.dailymotion.com/video/".$tab_return['media_id']);
                            $tab_return['description'] = utf8_decode($tags["description"]);
                            $tab_return['duration']  = $data->{'duration'};

                            break;
                        case 'Vimeo' :
                            $xml = file_get_contents("http://vimeo.com/api/oembed.xml?url=".$tab_return['media_link']);
                            preg_match('#<title(.*?)>(.*)<\/title>#is',$xml,$resultTitre);
                            $tab_return['titre'] = utf8_decode($resultTitre[count($resultTitre)-1]);
                            preg_match('#<description(.*?)>(.*)<\/description>#is',$xml,$resultDescription);
                            $tab_return['description'] = utf8_decode($resultDescription[count($resultDescription)-1]);
                            preg_match('#<thumbnail_url(.*?)>(.*)<\/thumbnail_url>#is',$xml,$resultTumb);
                            $tab_return['tab_images'][] = $resultTumb[count($resultTumb)-1];
                            preg_match('#<duration(.*?)>([0-9]+)<\/duration>#is',$xml,$resultDuration);
                            $tab_return['duration'] = $resultDuration[count($resultDuration)-1];
                            break;
                    }
                    return $tab_return;
                }else {
                    return false;
                }

            }

            $id =  $this->getUser()->getId();
            $user= $this->getDoctrine()->getRepository('MmiBackBundle:User')->find($id);



            $recup=get_donnees_video($video->getVidLink());
            $time=time();
            $dateday=date('Y-m-d',$time);
            $video->setVidDate(new \DateTime("$dateday"));
            $video->setVidTitre(utf8_encode($recup['titre']));
            $video->setVidDuree($recup['duration']);
            $video->setVidDesc(utf8_encode($recup['description']));
            $video->setUser($user);

            if($form['vidImg']->getData()==null){
                $video->setVidImg("undefined");
            }
            $video->setVidid($recup['media_id']);

            $em= $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();
            return $this->redirect($this->generateUrl('mmi_create_video'));

        }


       return $this->render('MmiBackBundle:CreateVideo:createvideo.html.twig', array('form' => $form->createView()));
    }
}

