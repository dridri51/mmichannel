<?php

namespace Mmi\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MmiBackBundle:Default:index.html.twig', array());
    }

    public function tweetAction()
    {
        $em= $this->getDoctrine()->getManager();
        $tweet = $em -> getRepository('MmiBackBundle:Message')->createQueryBuilder('m')
            ->where('m.msgType = :data')
            ->andWhere('m.voir = :data2')
            ->setParameters(array('data'=>'tweet',"data2"=>'1'))
            ->getQuery()->getArrayResult();

        $response = new Response(json_encode($tweet));
        $response->headers->set('Content-Type', 'application/json');
        return $response;



    }
}
