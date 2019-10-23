<?php


namespace AppBundle\Controller\Admin;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrderController extends Controller
{
    /**
     * @Route("/admin/orders", name="admin_orders_list")
     * @Template()
     */
    public function indexAction()
    {
        $orders = $this
            ->get('doctrine')
            ->getRepository('AppBundle:CustomerOrder')
            ->findBy([], ['created' => 'DESC']);

        return ['orders' => $orders];
    }


    /**
     * @Route("/admin/order/{id}", name="admin_order_show")
     * @Template()
     *
     * @param $id
     * @return array
     */
    public function showAction($id)
    {
        $doctrine = $this->get('doctrine');

        $order = $doctrine
            ->getRepository('AppBundle:CustomerOrder')
            ->find($id);

        $items = $doctrine
            ->getRepository('AppBundle:OrderItem')
            ->findGroupedByOrder($order)
            ;


        if (!$order){
            throw $this->createNotFoundException('Order not Found!');
        }

        return ['order' => $order, 'items' => $items];

    }

}