<?php

namespace Mmi\BackBundle\Controller;

use Mmi\BackBundle\Form\PlaylistType;
use Mmi\BackBundle\Form\VideoType;
use Mmi\BackBundle\Form\VideoModifType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Mmi\BackBundle\Entity\Video;



class VideoController extends Controller {

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
                            if(isset($resultDescription[0])){
                            $tab_return['description'] = utf8_decode($resultDescription[count($resultDescription)-1]);
                            }else{
                                $tab_return['description']="Pas de description";
                            }
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

            $em = $this->getDoctrine()->getManager();
            if($this->getUser()->getRoles()[0]=='ROLE_ADMIN') {
                $lundi = date("Y-m-d", strtotime('last Monday'));
                $recup1 = $em->getRepository('MmiBackBundle:User')->findBySemaine(new \DateTime($lundi));
                $user= $this->getDoctrine()->getRepository('MmiBackBundle:User')->find($recup1[0]->getId());
            }else{
            $id =  $this->getUser()->getId();
            $user= $this->getDoctrine()->getRepository('MmiBackBundle:User')->find($id);

            }

            $recup=get_donnees_video($video->getVidLink());
            $time=time();
            $dateday=date('Y-m-d',$time);
            $video->setVidDate(new \DateTime("$dateday"));
            $video->setVidTitre(utf8_encode($recup['titre']));
            $video->setVidDuree($recup['duration']);
            $video->setVidDesc(utf8_encode($recup['description']));
            $video->setUser($user);

            if(preg_match("/\byoutube\b/i",$video->getVidLink())){
                $video->setType("youtube");
            }elseif(preg_match("/\bdailymotion\b/i",$video->getVidLink())){
                $video->setType("dailymotion");
            }elseif(preg_match("/\bvimeo\b/i",$video->getVidLink())){
                $video->setType("vimeo");
            }else{
                $video->setType("inconnu");
            }

            $pld= $video->getPlaylist()->getPlDuree();
            $pl=$video->getPlaylist();

            $tot = $pld + $video->getVidDuree();

            $editform = $this->createForm(new PlaylistType(), $pl);
            $editform->getData()->setPlDuree($tot);

            $video->setVidid($recup['media_id']);

            $em= $this->getDoctrine()->getManager();
            $em->persist($video,$editform);
            $em->flush();

            //UPDATE DE LA BDD POUR LE TEMPS TOTAL DE LA PLAYLIST


            return $this->redirect($this->generateUrl('mmi_create_video'));

        }


       return $this->render('MmiBackBundle:CreateVideo:createvideo.html.twig', array('form' => $form->createView()));
    }


    public function lastVideoAction()
    {
        $em = $this->getDoctrine()->getManager();
        if($this->getUser()->getRoles()[0]=='ROLE_ADMIN') {
            $lundi = date("Y-m-d", strtotime('last Monday'));
            $recup1 = $em->getRepository('MmiBackBundle:User')->findBySemaine(new \DateTime($lundi));
            $id= $recup1[0]->getId();
        }else{
        $id= $this->getUser()->getId();
        }
        $em = $this->getDoctrine()->getRepository('MmiBackBundle:Video');
        //$all = $em->findAll(array('order'=>'vidDate','limit' => 2));
        if($this->getUser()->getRoles()[0]=='ROLE_ADMIN'){
        $all = $em->findBy(array(), array('vidDate' => 'DESC', 'id' => 'DESC'), 2,0);
        }else{
        $all = $em->findBy(array('user'=>$id), array('vidDate' => 'DESC', 'id' => 'DESC'), 2,0);
        }
        /*$vimeothumb=[];
        for ($i=0; $i < count($all); $i++) {
            $test=[];
            if (strstr($all[$i]->getVidLink(), 'vimeo')) {
                $imgid = $all[$i]->getVidId();
                $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$imgid.php"));
                array_push($test, $hash[0]['thumbnail_medium'],$all[$i]->getVidId());
                array_push($vimeothumb,$test);
            }

            }*/


        return $this->render('MmiBackBundle:LastVideo:index.html.twig',array(
            'video' => $all
            /*, 'vimeo' => $vimeothumb*/
        ));

    }

    public function modifVideoAction($id, request $request){



        $em = $this->getDoctrine()->getManager();


        if($this->getUser()->getRoles()[0]=='ROLE_ADMIN') {
            $lundi = date("Y-m-d", strtotime('last Monday'));
            $recup1 = $em->getRepository('MmiBackBundle:User')->findBySemaine(new \DateTime($lundi));
            $user =$recup1[0]->getId();
        }else {
            $user = $this->getUser()->getId();
        }
        $recup2= $em->getRepository('MmiBackBundle:Video')->find($id);

        //Vérifier si la vidéo est dans une playlist qui est déjà dans un créneau

        $idpl = $recup2->getPlaylist();
        $playlist = $em->getRepository('MmiBackBundle:Playlist')->find($idpl);

        if($playlist->getCreneau() !== null && $this->getUser()->getRoles()[0]=='ROLE_CLIENT')
        {
            throw $this->createNotFoundException(
                'La vidéo que vous tentez de modifier est présente dans une playlist qui est attribué à un créneau. Veuillez retirer la playlist du créneau pour pouvoir modifier la vidéo '
            );
        }





        $pld2= $recup2->getPlaylist()->getPlDuree();
        $pl2=$recup2->getPlaylist();
        $tot2 = $pld2 - $recup2->getVidDuree();
        $editform2 =$this->createForm(new PlaylistType(), $pl2);
        $editform2->getData()->setPlDuree($tot2);

        $recup= $em->getRepository('MmiBackBundle:Video')->find($id);



        if (!$recup) {
            throw $this->createNotFoundException(
                'Pas d\'image ou de video correspondant à l\'id ' . $id
            );
        }

        if($user !== $recup->getUser()->getId()){
            throw $this->createNotFoundException(
                'Vous n\'êtes pas autorisé à modifier cette video comportant l\'ID n° '. $id
            );
        }
        $form= $this->createForm(new VideoModifType(),$recup);
        $form            ->add('vidDuree','text',array(
            'label' => 'Durée',
            'disabled' => true,
        ));
        $form->handleRequest($request);
        if($form->isValid()) {




                $pld= $recup->getPlaylist()->getPlDuree();
                $pl=$recup->getPlaylist();


                // ON ATTRIBUT LA DATE A LA NOUVELLE PLAYLIST


                $tot = $pld + $recup->getVidDuree();

                $editform = $this->createForm(new PlaylistType(), $pl);
                $editform->getData()->setPlDuree($tot);

                $em= $this->getDoctrine()->getManager();
                $em->persist($recup,$editform,$editform2);







            $em->flush();
            if($this->getUser()->getRoles()[0]=='ROLE_ADMIN') {
                return $this->redirect($this->generateUrl('admin_video'));

            }else{
                return $this->redirect($this->generateUrl('mmi_video'));
            }
        }

        return $this->render('MmiBackBundle:ModifVideo:index.html.twig', array('form' => $form->createView(),'recup' => $recup));


    }

    public function deleteVideoAction($id)
    {
        $em= $this->getDoctrine()->getManager();

        if($this->getUser()->getRoles()[0]=='ROLE_ADMIN') {
        $lundi = date("Y-m-d", strtotime('last Monday'));
        $recup1 = $em->getRepository('MmiBackBundle:User')->findBySemaine(new \DateTime($lundi));
        $user =$recup1[0]->getId();
        }else{
        $user= $this->getUser()->getId();
        }
        $recup= $em->getRepository('MmiBackBundle:Video')->find($id);


        if (!$recup) {
            throw $this->createNotFoundException(
                'Pas d\'image ou de video correspondant à l\'id ' . $id
            );
        }
        if($user !== $recup->getUser()->getId()){
            throw $this->createNotFoundException(
                'Vous n\'êtes pas autorisé à modifier cette video comportant l\'ID n° '. $id
            );
        }
        $id2= $recup->getPlaylist()->getId();

        $recup2 = $em->getRepository('MmiBackBundle:Playlist')->find($id2);

        $time=$recup2->getplDuree();
        $time= $time - $recup->getVidDuree();

        $recup2->setplDuree($time);

        $em->remove($recup);
        $em->persist($recup2);
            $em->flush();
            if($this->getUser()->getRoles()=='ROLE_ADMIN'){
                return $this->redirect($this->generateUrl('admin_video'));
            }elseif($this->getUser()->getRoles()=='ROLE_CLIENT'){
                return $this->redirect($this->generateUrl('client_video'));
            }
        return $this->redirect($this->generateUrl('client_video'));



    }

    public function  loadAction()
    {
        $playlist = $_POST['playlist'];
        $em = $this->getDoctrine()->getManager();

        if($playlist == "all")
        {
            if($this->getUser()->getRoles()[0]=='ROLE_ADMIN') {
                $lundi = date("Y-m-d", strtotime('last Monday'));
                $recup = $em->getRepository('MmiBackBundle:User')->findBySemaine(new \DateTime($lundi));
                $recup2= $em->getRepository('MmiBackBundle:User')->find($recup[0]->getId());
                $recup= $em->getRepository('MmiBackBundle:Video')->findByUser($recup2);
            }else{
            $id=$this->getUser()->getId();
            $recup2= $em->getRepository('MmiBackBundle:User')->find($id);
            $recup= $em->getRepository('MmiBackBundle:Video')->findByUser($recup2);
            }

        }else{
            $recup= $em->getRepository('MmiBackBundle:Video')->createQueryBuilder('v')
            ->leftjoin('v.playlist','p')
            ->where('p.plNom=:data')
            ->setParameter(':data', $playlist )
            ->getQuery()->getResult();
        }
        return $this->render('MmiBackBundle:Video:load.html.twig',array('video' => $recup));

    }
}

