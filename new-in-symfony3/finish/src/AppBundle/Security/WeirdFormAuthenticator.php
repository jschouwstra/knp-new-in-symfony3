<?php

namespace AppBundle\Security;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class WeirdFormAuthenticator extends AbstractGuardAuthenticator
{
    private $em;
    private $router;
    private $robot;

    public function __construct(EntityManager $em, RouterInterface $router, EvilSecurityRobot $robot)
    {
        $this->em = $em;
        $this->router = $router;
        $this->robot = $robot;
    }

    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() != '/login' || !$request->isMethod('POST')) {
            return;
        }

        return [
            'username' => $request->request->get('_username'),
            'password' => $request->request->get('_password'),
            'answer'   => $request->request->get('the_answer'),
            'terms'    => $request->request->get('terms'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['username'];

        // this looks like a weird username
        if (substr($username, 0, 1) == '@') {
            throw new CustomUserMessageAuthenticationException(
                'Starting a username with @ is weird, don\'t you think?'
            );
        }

        return $this->em->getRepository('AppBundle:User')
            ->findOneBy(['username' => $username]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if (!$this->robot->doesRobotAllowAccess()) {
            throw new CustomUserMessageAuthenticationException(
                'RANDOM SECURITY ROBOT SAYS NO!'
            );
        }

        if ($credentials['password'] != 'symfony3') {
            return;
        }

        if ($credentials['answer'] != 42) {
            throw new CustomUserMessageAuthenticationException(
                'Don\'t you read any books?'
            );
        }

        if (!$credentials['terms']) {
            throw new CustomUserMessageAuthenticationException(
                'Agree to our terms!!!'
            );
        }

        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

        $url = $this->router->generate('login');

        return new RedirectResponse($url);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $url = $this->router->generate('homepage');

        return new RedirectResponse($url);
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $url = $this->router->generate('login');

        return new RedirectResponse($url);
    }

    public function supportsRememberMe()
    {
        return true;
    }
}
