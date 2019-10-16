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
        //get cookie
        $cartCookie = $request->cookies->get('cart');

        //get id product
        $id = (int) $request->get('id');

        //if cookie is empty
        if (!$cartCookie){
            $cart = [
                $id => 1
            ];
        } else{
            $cart = unserialize($cartCookie);
            if (empty($cart[$id])){
                $cart[$id] = 1;
            } else{
                $cart[$id]++;
            }
        }

        $cartCookie = serialize($cart);

        //save to cookie
        $cookie = new Cookie('cart', $cartCookie, time() + 60 * 60 * 24 * 7);

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
     * @return array
     */
    public function indexAction(Request $request)
    {
        //get cart from cookies
        $cartCookie = $request->cookies->get('cart');
        if (!$cartCookie){
            return ['products' => []];
        }
        $cart = unserialize($cartCookie);

        //get ids
        $productsIds = array_keys($cart);

        //get products by ids
        $products = $this
            ->get('doctrine')
            ->getRepository('AppBundle:Product')
            ->findBy(['id' => $productsIds]);

        //render
        return ['products' => $products, 'cart' => $cart];
    }
}