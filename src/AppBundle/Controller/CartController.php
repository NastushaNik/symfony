<?php


namespace AppBundle\Controller;


use AppBundle\AppBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    /**
     * @param $id
     * @return RedirectResponse
     * @Route("/cart/{id}/add", name="add_to_cart", requirements={"id":"[0-9]+"})
     */
    public function addToCartAction($id)
    {
        $product = $this->get('doctrine')->getRepository('AppBundle:Product')->find($id);

        //use CartService
        $this->get('cart')->addProduct($product);

        return $this->redirectToRoute('product_list');

    }

    /**
     * @param Request $request
     *
     * @Route("/cart", name="cart_index")
     * @Template()
     * @return array|RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $cart = $this->get('cart')->getContents();

        return ['cart' => $cart];

    }
}