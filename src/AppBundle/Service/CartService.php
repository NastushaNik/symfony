<?php


namespace AppBundle\Service;



use AppBundle\Entity\OrderItem;
use AppBundle\Entity\Product;
use AppBundle\Model\Cart;
use AppBundle\Model\CartItem;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class CartService
{
    private $request;
    private $doctrine;

    public function __construct(RequestStack $requestStack, Registry $doctrine)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->doctrine = $doctrine;

    }

    private function setCookieResponse(Cookie $cookie)
    {
        //create response
        $response = new Response();
        //set cookie response
        $response->headers->setCookie($cookie);

        //send headers
        $response->sendHeaders();
    }

    public function addProduct(Product $product)
    {
        //get cookie
        $cartCookie = $this->request->cookies->get('cart');

        /**
         * If products have sale price
         */
        if (!$cartCookie){
            $cart = [];
        } else{
            $cart = unserialize($cartCookie);
        }

        $count = count($cart) + 1;
        $price = $product->getPrice();

        if ($count % 3 == 0){
            $price = $price * 0.5;
        }

        $cart[] = ['id' => $product->getId(), 'price' => $price];
        /**
         * If products have sale price
         */


        /**
         * If not sales product
         */
        //if cookie is empty
//        if (!$cartCookie){
//            $cart = [
//                $id => 1
//            ];
//        } else{
//            $cart = unserialize($cartCookie);
//            if (empty($cart[$id])){
//                $cart[$id] = 1;
//            } else{
//                $cart[$id]++;
//            }
//        }
        /**
         * If not sales product
         */

        $cartCookie = serialize($cart);

        //create cookie
        $cookie = new Cookie('cart', $cartCookie, time() + 60 * 60 * 24 * 7);

        $this->setCookieResponse($cookie);
    }

    public function getContents()
    {
        //get cart from cookies
        $cartCookie = $this->request->cookies->get('cart');
        $collection = [];

        if (!$cartCookie){
            return [];
        }
        $cart = unserialize($cartCookie);
        $products = [];

        foreach ($cart as $item){
            if (!isset($products[$item['id']])){
                $products[$item['id']] = $this->doctrine
                    ->getRepository('AppBundle:Product')
                    ->find($item['id']);
            }

            //get total price order
            $orderItem = new OrderItem();
            $orderItem->setOrder(null);
            $orderItem->setPrice($item['price']);
            $orderItem->setProduct($products[$item['id']]);
            $collection[] = $orderItem;
        }

        //render
        return new Cart($collection);
    }

    public function clear()
    {
        //create empty cookie
        $cookie = new Cookie('cart', serialize([]), time() + 60 * 60 * 24 * 7);

        $this->setCookieResponse($cookie);
    }


    public function isEmpty()
    {
        // cart from cookies
        return !$this->request->cookies->get('cart');
    }

}