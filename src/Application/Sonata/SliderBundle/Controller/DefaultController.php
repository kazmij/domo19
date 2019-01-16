<?php

namespace Application\Sonata\SliderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ApplicationSonataSliderBundle:Default:index.html.twig');
    }
}
