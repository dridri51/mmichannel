<?php

namespace Mmi\BackBundle\Controller;

use Mmi\BackBundle\Entity\Message;
use Mmi\BackBundle\Entity\Playlist;
use Mmi\BackBundle\Entity\Creneau;
use Mmi\BackBundle\Form\PlaylistType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class ProgController extends Controller
{

    public function indexAction(Request $request)
    {
        $id = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $recupid = $em->getRepository('MmiBackBundle:User')->find($id);
        $recup = $em->getRepository('MmiBackBundle:Playlist')->findBy(array('user' => $recupid));

        return $this->render('MmiBackBundle:Prog:index.html.twig',array('playlists' => $recup));

    }

    public function updateAction(Request $request)
    {
        $json = $_POST['json'];
        $json = str_replace("[","",$json);
        $json = str_replace("]","",$json);
        $tab = explode(',',$json);
        $i=1;

        $em = $this->getDoctrine()->getManager();
        $id = $this->getUser()->getId();
        $recup = $em->getRepository('MmiBackBundle:User')->find($id);


        $recup1 = $em->getRepository('MmiBackBundle:Playlist')->findBy(array('date' => $recup->getSemaine()));
        foreach ($tab as $tab2)
        {

            if($tab2)
            {

                $key = array_search($tab2,$recup1);
                $form = $this->createForm(new PlaylistType(), $recup1[$key]);
                $form->handleRequest($request);
                $recup2 = $em->getRepository('MmiBackBundle:Creneau')->find($i);
                echo $form->getData()->setCreneau($recup2->getId());
            die();
                $em->persist($form);

            }
            $i++;
        }
        $em->flush();


        return $this->redirect($this->generateUrl('client_all_message'));

    }

}