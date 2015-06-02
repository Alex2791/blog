<?php

namespace First\UsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FirstUsBundle:Default:index.html.twig', array('name' => $name));
    }
}
