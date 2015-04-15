<?php

namespace Mmi\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mmi\BackBundle\Entity\Message;
use Symfony\Component\Validator\Constraints\DateTime;
use Mmi\BackBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Mmi\BackBundle\Form\MessageType;
use Mmi\BackBundle\Form\TweetsType;


class MessageController extends Controller
{
    public function indexAction(request $request)
    {
        $message = new Message();

        $form= $this->createForm(new MessageType, $message);
        $form->handleRequest($request);


        if($form->isValid()) {


            $time = time();
            $dateday = date('Y-m-d', $time);
            $message->setMsgDate(new \DateTime("$dateday"));

            $id = $this->getUser()->getId();
            $user = $this->getDoctrine()->getRepository('MmiBackBundle:User')->find($id);
            $message->addUser($user); //manytomanymagueule
            $message->setVoir(0);
            $em= $this->getDoctrine()->getManager();

            $em->persist($message);
            $em->flush();
            return $this->redirect($this->generateUrl('mmi_create_message'));

        }

        return $this->render('MmiBackBundle:CreateMessage:index.html.twig',array(
            'form' => $form->createView()
        ));

    }

    public function genetweetAction()
    {
        return $this->render('MmiBackBundle:Message:generertweet.html.twig');
    }


    public function alltweetAction(Request $request)
    {

        $tweets = $this->getDoctrine()->getRepository('MmiBackBundle:Message')->findByMsgType('tweet');



        return $this->render('MmiBackBundle:Message:alltweet.html.twig', array('tweet' => $tweets));


    }

    public function recupAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $tweets = $em->getRepository('MmiBackBundle:Message')->findByMsgType('tweet');


        foreach($tweets as $tw => $val)
        {
            if(in_array($tw,$_POST['affiche']))
            {
                $val->setVoir(1);
                $em->persist($val);

            }else{
                $val->setVoir(0);
                $em->persist($val);
            }

        }

        $em->flush();

        return $this->render('MmiBackBundle:Message:alltweet.html.twig', array('tweet' => $tweets));


    }

    public function allAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $msg = $em->getRepository('MmiBackBundle:Message')->findBy(array(),array('msgDate' => 'DESC'));

        return $this->render('MmiBackBundle:Message:allmsg.html.twig', array('msg' => $msg));

    }

    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $recup = $em->getRepository('MmiBackBundle:Message')->find($id);

        $form = $this->createForm(new MessageType(), $recup);
        $form->handleRequest($request);

        if($form->isValid())
        {
            $em->flush();
            return $this->redirect($this->generateUrl('client_all_message'));

        }
        return $this->render('MmiBackBundle:Message:edit.html.twig', array('form' => $form->createView()));

    }



}