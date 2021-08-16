<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;

class ProfileController extends AbstractController
{
  #[Route('/profile', name: 'profile', methods: ['GET', 'POST'])]
  public function index(Request $request): Response {
    $form = $this->createFormBuilder(null, ['method' => Request::METHOD_POST])
      ->add('first-name', TextType::class, [
        'label' => 'First name',
        'attr' => ['value' => $this->getUser()->getFirstName()],
        'constraints' => [new Length(['min' => 3])]
      ])
      ->add('last-name', TextType::class, [
        'label' => 'Last name',
        'attr' => ['value' => $this->getUser()->getLastName()],
        'constraints' => [new Length(['min' => 3])]
      ])
      ->add('save', SubmitType::class, ['label' => 'Save'])
      ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $user = $this->getUser();
      $user->setFirstName($form->getData()['first-name']);
      $user->setLastName($form->getData()['last-name']);

      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('profile', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('profile/index.html.twig', [
      'form' => $form->createView()
    ]);
  }

  #[Route('/profile/edit/top-up', name: 'profile_top_up', methods: ['POST'])]
  public function topUp() {
    return 'hello';
    return $this->getUser();
  }
}