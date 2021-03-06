<?php

namespace Wesamly\KotobiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('WesamlyKotobiBundle:Default:index.html.twig');
    }

    public function aboutAction()
    {
        return $this->render('WesamlyKotobiBundle:Default:about.html.twig');
    }

    public function setlangAction($_locale)
    {
        return $this->redirect($this->generateUrl('wesamly_kotobi_homepage'));
    }
}
