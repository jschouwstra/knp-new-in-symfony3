<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\RegistrationForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }

    /**
     * @Route("/login", name="login")
     */
    public function sillyLoginAction()
    {
        $error = $this->get('security.authentication_utils')
            ->getLastAuthenticationError();

        return $this->render('default/login.html.twig', [
            'error' => $error
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
    }

    /**
     * @Route("/register", name="user_register")
     */
    public function registerAction(Request $request)
    {
        $form = $this->createForm(RegistrationForm::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            dump($form->getData());die;
        }

        return $this->render('default/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/users/{username}", name="user_view")
     */
    public function viewUserAction(User $user)
    {
        if (!$this->isGranted('USER_VIEW', $user)) {
            throw $this->createAccessDeniedException('NO!');
        }

        dump('Access granted!', $user);die;
    }
}
