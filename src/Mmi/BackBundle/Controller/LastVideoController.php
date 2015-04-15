<?php

namespace Mmi\BackBundle\Controller;

use Mmi\BackBundle\Entity\Video;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LastVideoController extends Controller
{

    public function indexAction()
    {
        $id= $this->getUser()->getId();
        $em = $this->getDoctrine()->getRepository('MmiBackBundle:Video');
        //$all = $em->findAll(array('order'=>'vidDate','limit' => 2));
        $all = $em->findBy(array('user'=>$id), array('vidDate' => 'DESC', 'id' => 'DESC'), 2,0);
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

}