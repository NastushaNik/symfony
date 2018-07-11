<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Feedback;
use AppBundle\Form\FeedbackType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;


class FeedbackController extends Controller
{
    /**
     * @Route("/contact-us", name="contact-us")
     * @Template()
     */
    public function contactAction(Request $request)
    {
        $feedback = new Feedback();
        $form = $this->createForm(FeedbackType::class, $feedback);
        $form->add('submit', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            //можно использовать getData, вместо $feedback = new Feedback();
            //$feedback = $form->getData();
            
            //entity manager сохранение объекта
            $em = $this->getDoctrine()->getManager();
            //получаем Ip адрес клиента
            $feedback->setIpAddress($request->getClientIp());
            //что пишем в БД
            $em->persist($feedback);
            //сама запись в БД
            $em->flush();
            //сообщение оботправке(ошибке)
            $this->addFlash('success', 'Saved!');
            $this->addFlash('fail', 'Not saved!');

            //редирект
            return $this->redirectToRoute('contact-us');
        }

        return ['feedback_form' => $form->createView()];
    }
}
