<?php

namespace Mmi\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RegisterController extends Controller
{
    public function indexAction()
    {
        $jour = date('D');
        $heure = date("H:i:s");
        $heure2 = date('H:i:s', strtotime("-90 minute", strtotime($heure)));
        $lundi = date("Y-m-d",strtotime('last Monday'));


        $em = $this->getDoctrine()->getManager();
        $recup = $em->getRepository('MmiBackBundle:Video')->createQueryBuilder('v')
        ->leftjoin('v.playlist','p')
        ->leftjoin('p.creneau','c')
        ->where('c.crJour=:data')
        ->andWhere('c.crHeure > :data3')
        ->andWhere('p.date=:data2')
        ->orderBy('v.id','ASC')
        ->setParameters(array(':data'=> $jour,':data2' => $lundi,'data3' => $heure2 ))
        ->getQuery()->getResult();

        $recup2 = $em->getRepository('MmiBackBundle:Playlist')->createQueryBuilder('p')
        ->leftjoin('p.creneau','c')
        ->where('p.date=:data2')
        ->orderBy('c.crHeure','ASC')
        ->setParameter(':data2' ,$lundi)
        ->getQuery()->getResult();


        $i=0;
        $tab=array();
        foreach($recup2 as $recup3)
        {
            if ($recup2[$i]->getCreneau()!==null ){
            array_push($tab,$recup2[$i]->getCreneau()->getId());
            $i++;
            }else{
                array_push($tab,null);
                $i++;
            }


        }

        return $this->render('MmiBackBundle::register.html.twig',array('direct' => $recup, 'prog' => $recup2,'creneau' => $tab));
    }

    public function videoAction()
    {
        $em = $this->getDoctrine()->getManager();

        $id = $this->getUser()->getId();
        $recup = $em->getRepository('MmiBackBundle:User')->find($id);
        $recup2 = $em -> getRepository('MmiBackBundle:Video')->findByUser($recup->getId());
        $playlist = $em->getRepository('MmiBackBundle:Playlist')->findByUser($recup->getId());

        return $this->render('MmiBackBundle::video.html.twig',array('video' => $recup2,'playlists' => $playlist));
    }

    public function playlistAction()
    {
        return $this->render('MmiBackBundle::playlist.html.twig');
    }

    public function progAction()
    {

        $lundi = date("Y-m-d",strtotime('last Monday'));


        $em = $this->getDoctrine()->getManager();

        $recup2 = $em->getRepository('MmiBackBundle:Playlist')->createQueryBuilder('p')
            ->leftjoin('p.creneau','c')
            ->where('p.date=:data2')
            ->orderBy('c.crHeure','ASC')
            ->setParameter(':data2' ,$lundi)
            ->getQuery()->getResult();


        $i=0;
        $tab=array();
        foreach($recup2 as $recup3)
        {
            if ($recup2[$i]->getCreneau()!==null ){
                array_push($tab,$recup2[$i]->getCreneau()->getId());
                $i++;
            }else{
                array_push($tab,null);
                $i++;
            }


        }


        return $this->render('MmiBackBundle::prog.html.twig',array('prog' => $recup2,'creneau' => $tab));
    }

    public function messAction()
    {
        return $this->render('MmiBackBundle::mess.html.twig');
    }

    public function utilAction()
    {
        return $this->render('MmiBackBundle::util.html.twig');
    }

    public function busAction()
    {
        return $this->render('MmiBackBundle::bus.html.twig');
    }

    public function directAction()
    {
        return $this->render('MmiBackBundle::direct.html.twig');
    }

}