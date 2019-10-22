<?php


namespace AppBundle\Controller;


use AppBundle\Entity\CustomerOrder;
use AppBundle\Entity\OrderItem;
use AppBundle\Form\CustomerOrderType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends Controller
{
    /**
     * @Route("/place-order", name="place_order")
     * @Template()
     *
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function placeOrderAction(Request $request)
    {

        if ($this->get('cart')->isEmpty()) {
            return $this->redirectToRoute('homepage');
        }

        $order = new CustomerOrder();
        $form = $this->createForm(CustomerOrderType::class, $order);
        $form->add('submit',SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $orderService = $this->get('order');
            $orderService->placeOrder($order);


//            $redirectUrl = $this->get('router')->generate('homepage');
            $this->get('cart')->clear();

//            $response = new RedirectResponse($redirectUrl);
//            $response->headers->setCookie($emptyCookie);

            return $this->redirectToRoute('homepage');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @param Request $request
     * @param CustomerOrder $order
     * @return RedirectResponse
     */

//    private function placeOrder(Request $request, CustomerOrder $order)
//    {
//
//        return $this->redirectToRoute('homepage');
//    }
}