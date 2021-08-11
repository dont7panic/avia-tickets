<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
  #[Route('/register', name: 'app_register')]
  public function register(
    Request $request,
    UserPasswordEncoderInterface $passwordEncoder,
    UserAuthenticatorInterface $authenticator,
    LoginFormAuthenticator $formAuthenticator
  ): Response {
    $user = new User();
    $form = $this->createForm(RegistrationFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // encode the plain password
      $user->setPassword(
        $passwordEncoder->encodePassword(
          $user,
          $form->get('plainPassword')->getData()
        )
      );

      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($user);
      $entityManager->flush();
      // do anything else you need here, like send an email

      return $authenticator->authenticateUser($user, $formAuthenticator, $request);
    }

    return $this->render('registration/register.html.twig', [
      'registrationForm' => $form->createView(),
    ]);
  }
}