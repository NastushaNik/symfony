<?php


namespace AppBundle\Controller\Customer;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @param Request $request
     * @return array
     *
     * @Route("/customer", name="homepagecustomer")
     * @Template()
     *
     */
    public function indexAction(Request $request)
    {
        return [];
    }
}