<?php

namespace Mmi\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EdtController extends Controller{

    public function indexAction($time)
    {


        $json = file_get_contents("http://intranet.iut-troyes.univ-reims.fr/api/edtavenir/");

        $send= json_decode($json,true);

        if($time==1){
               $heure = "De 8h à 9h30";

        }elseif($time==4){
               $heure = "De 9h30 à 11h00";

        }elseif($time==7){
               $heure = "De 11h00 à 12h30";

        }elseif($time==13){
               $heure = "De 14h00 à 15h30";

        }elseif($time==16){
               $heure = "De 15h30 à 17h00";

        }elseif($time==19){
               $heure = "De 17h00 à 18h30";

        }elseif($time==22){
            $heure = "De 18h30 à 20h00";
        }

        // PROMO -> HORAIRE -> GROUPE TP -> INFOS
        return $this->render('MmiBackBundle:JSON:index.html.twig',array(
            'json' => $send,'time' => $time,'horaire' => $heure
        ));
    }
}
