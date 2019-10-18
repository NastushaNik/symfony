<?php


namespace AppBundle\Service;


use AppBundle\Entity\CustomerOrder;
use AppBundle\Entity\OrderItem;
use Doctrine\Bundle\DoctrineBundle\Registry;

class OrderService
{
    private $cartService;
    private $doctrine;

    /**
     * OrderService constructor.
     * @param CartService $cartService
     * @param Registry $doctrine
     */
    public function __construct(CartService $cartService, Registry $doctrine)
    {
        $this->cartService = $cartService;
        $this->doctrine = $doctrine;
    }

    public function placeOrder(CustomerOrder $order)
    {
        $em = $this->doctrine->getManager();

        $cart = $this->cartService->getCartArray();

        //get ids
        $productsIds = array_keys($cart);

        //get products by ids
        $products = $this->doctrine
            ->getRepository('AppBundle:Product')
            ->findBy(['id' => $productsIds])
        ;

        foreach ($products as $product){
            $amount = $cart[$product->getId()];
            for ($i = 1; $i <= $amount; $i++){
                $orderItem = (new OrderItem())
                    ->setOrder($order)
                    ->setProduct($product)
                    ->setPrice($product->getPrice())
                ;
                $em->persist($orderItem); //like git add
            }
        }

        $em->persist($order); //like git add
        $em->flush();        // like git commit
    }



}