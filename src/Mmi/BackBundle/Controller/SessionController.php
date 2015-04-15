<?php

namespace Mmi\BackBundle\Controller;


use FOS\UserBundle\Model\UserInterface;
use Mmi\BackBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;


class SessionController extends Controller
{
    public function indexAction()
    {
    $user = $this->getUser();
    if (!is_object($user) || !$user instanceof UserInterface) {
    throw new AccessDeniedException('This user does not have access to this section.');
    }


    return $this->render('MmiBackBundle:Session:index.html.twig', array(
    'user' => $user
    ));
    }

    public function bonjourAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }


        return $this->render('MmiBackBundle:Session:bonjour.html.twig', array(
            'user' => $user
        ));
    }
}