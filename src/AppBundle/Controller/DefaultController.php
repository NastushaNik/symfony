<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    private $doctrine;

    /**
     * @Route("/", name="homepage")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        dump($this->get('test_service')->upload());
        $name = 'Bob';
        $a = 1;
        
        return [
            'name' => $name,
            'a' => $a
        ];
    }
}
