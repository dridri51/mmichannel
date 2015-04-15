<?php

namespace Mmi\BackBundle\Controller;

use Mmi\BackBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AnnonController extends Controller
{

    public function indexAction()
    {

        $em = $this->getDoctrine()->getRepository('MmiBackBundle:Message');
        $all = $em->findBy(array(), array('msgDate' => 'DESC'), 2,0);

        return $this->render('MmiBackBundle:LastAnnon:index.html.twig',array(
            'annon' => $all
        ));

    }

}