<?php

namespace Mmi\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mmi\BackBundle\Entity\Message;
use Symfony\Component\Validator\Constraints\DateTime;
use Mmi\BackBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Mmi\BackBundle\Form\MessageType;

class CreateMessageController extends Controller
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
            $em= $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
            return $this->redirect($this->generateUrl('mmi_create_message'));

        }

        return $this->render('MmiBackBundle:CreateMessage:index.html.twig',array(
            'form' => $form->createView()
        ));

    }


}