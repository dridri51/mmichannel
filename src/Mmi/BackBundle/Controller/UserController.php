<?php

namespace Mmi\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserUserBundle\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\Request;



class UserController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $alluser = $em ->createQuery(
            'SELECT p FROM MmiBackBundle:User p WHERE p.expiresAt IS NULL OR p.expiresAt > :date')
            ->setParameter('date',new \DateTime())

            ->getResult();
        //var_dump($alluser);
        //die();
        return $this->render('MmiBackBundle:AllUser:index.html.twig',array('user'=> $alluser));
    }

    public function oldallAction()
    {
        $em = $this->getDoctrine()->getManager();
        $alluser = $em ->createQuery(
            'SELECT p FROM MmiBackBundle:User p WHERE p.expiresAt < :date')
            ->setParameter('date',new \DateTime())

            ->getResult();
        //var_dump($alluser);
        //die();
        return $this->render('MmiBackBundle:AllUser:index.html.twig',array('user'=> $alluser));
    }

    public function modifUserAction($id,request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $alluser = $em ->getRepository('MmiBackBundle:User')->find($id);


        if (!$alluser) {
            throw $this->createNotFoundException(
                'Pas d\'utilisateurs correspondant à l\'id ' . $id
            );
        }

        $form= $this->createForm(new RegistrationFormType(),$alluser);

        if($alluser->getRoles()[0] == 'ROLE_ADMIN'){
            $datecontent = date('Y-m-d 23:59:59');
        }else{
            $datecontent = $alluser->getExpiresAt()->format('Y-m-d 23:59:59');
        }
        $form
            ->add('expiresAt','text',array(
                'data' => $datecontent,
                'disabled' => 'disabled'
            ))
            ->add('garder','choice',array(
            "mapped" => false,
                'label' => false,
            'choices' => array('keep' => 'Garder la date d\'expiration actuelle'),
            'expanded' => true,
            'multiple' => true))
            ->add('Modifier','submit')
        ;
        $form->remove('plainPassword');

        $form->handleRequest($request);
        if($form->isValid()){

                if($form->getData()->getRoles()[0]=='ROLE_CLIENT'){
                    if($form->offsetGet('garder')->getNormData()=='keep'){
                        $form->getData()->setExpiresAt($alluser->getExpiresAt());
                        var_dump($form->getData()->getExpiresAt());
                        die();
                    }elseif($form->offsetGet('jour')->getNormData()<0 || $form->offsetGet('jour')->getNormData()==null){
                        $date = new \DateTime();
                        $date->add(new \DateInterval('P'.$form->offsetGet('jour')->getNormData(0).'D'));
                        $form->getData()->setExpiresAt($date);

                    }else{
                        $date = new \DateTime();
                        $date->add(new \DateInterval('P'.$form->offsetGet('jour')->getNormData().'D'));
                        $form->getData()->setExpiresAt($date);

                    }
                }else{
                    $form->getData()->setExpiresAt(null);
                }


            $em->flush();

            return $this->redirect($this->generateUrl('admin_homepage'));
        }


        return $this->render('MmiBackBundle:ModifUser:index.html.twig',array('form'=> $form->createView()));
    }

    public function supprUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $recup = $em->getRepository('MmiBackBundle:User')->find($id);
    ;
        if (!$recup) {
            throw $this->createNotFoundException(
                'Pas d\'utilisateurs correspondant à l\'id ' . $id
            );
        }
        $recup2 = $em->getRepository('MmiBackBundle:Playlist')->findOneBy(array('user'=> $id));
        $recup3 = $em->getRepository('MmiBackBundle:Video')->findOneBy(array('user' => $id));

        $em->remove($recup);
        if($recup2) {
            $em->remove($recup2);
        }
        if($recup3){
        $em->remove($recup3);
        }
        $em->flush();
        return $this->redirect($this->generateUrl('admin_homepage'));
    }


}