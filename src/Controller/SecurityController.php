<?php

namespace App\Controller;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
  /**
   * @Route("/login", name="app_login")
   */
  public function login(AuthenticationUtils $authenticationUtils): Response {
    if ($this->getUser()) {
      if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
        return $this->redirectToRoute('admin');
      }
      return $this->redirectToRoute('home');
    }

    // get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();
    // last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();

    return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
  }

  /**
   * @Route("/logout", name="app_logout")
   */
  public function logout() {
    throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
  }
}