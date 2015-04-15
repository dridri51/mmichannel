<?php

namespace Mmi\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfileRegisController extends Controller
{
    public function indexAction()
    {
        return $this->render('MmiBackBundle:ProfileRegis:index.html.twig');
    }
}