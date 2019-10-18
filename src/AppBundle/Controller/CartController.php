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
     * @param Request $request
     *
     * @Route("/cart/{id}/add", name="add_to_cart", requirements={"id":"[0-9]+"})
     * @return RedirectResponse
     */
    public function addToCartAction(Request $request)
    {
        //get id product
        $id = (int) $request->get('id');

        //use CartService
        $cookie = $this->get('cart')->addProduct($id);

        //redirect
        $redirectUrl = $this->get('router')->generate('product_list');
        $response = new RedirectResponse($redirectUrl);
        $response->headers->setCookie($cookie);

        return $response;

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

        $cartItem = $this->get('cart')->getContents();
        $total = $this->get('cart')->getTotal($cartItem);

        return ['cartItem' => $cartItem, 'total' => $total];

    }
}