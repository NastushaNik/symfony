<?php


namespace AppBundle\Controller;




use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{

    /**
     * @param Request $request
     * @return array
     *
     * @Route("/login", name="login")
     * @Template()
     */

    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // получить ошибку входа, если она есть
        $error = $authenticationUtils->getLastAuthenticationError();

        // последнее имя пользователя, введенное пользователем
        $lastUsername = $authenticationUtils->getLastUsername();

        return [
            'last_username' => $lastUsername,
            'error'         => $error,
        ];
    }

    /**
     *
     * @Route("/logout", name="logout")
     */

    public function logoutAction()
    {

    }

}