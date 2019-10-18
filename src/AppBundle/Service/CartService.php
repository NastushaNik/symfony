<?php


namespace AppBundle\Service;



use AppBundle\Model\CartItem;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private $request;
    private $doctrine;

    public function __construct(RequestStack $requestStack, Registry $doctrine)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->doctrine = $doctrine;

    }

    public function addProduct($id)
    {
        //get cookie
        $cartCookie = $this->request->cookies->get('cart');

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

        //return cookie
        return new Cookie('cart', $cartCookie, time() + 60 * 60 * 24 * 7);
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

        //get ids
        $productsIds = array_keys($cart);

        //get products by ids
        $products = $this->doctrine
            ->getRepository('AppBundle:Product')
            ->findBy(['id' => $productsIds])
        ;

        //get total price order
        foreach ($products as $product){
            $cartItem = new CartItem();
            $cartItem->product = $product;
            $cartItem->amount = $cart[$product->getId()];
            $collection[] = $cartItem;
        }

        //render
        return $collection;
    }

    public function getTotal($cartItems)
    {
        $total = 0;

        foreach ($cartItems as $item){
            $total += $item->amount * $item->product->getPrice();
        }

        return $total;
    }

    public function getCartArray()
    {
        //get cart
        $cartCookie = $this->request->cookies->get('cart');
        if (!$cartCookie){
            return null;
        }

        return unserialize($cartCookie);
    }

    public function isEmpty()
    {
        // cart from cookies
        return !$this->request->cookies->get('cart');
    }

}