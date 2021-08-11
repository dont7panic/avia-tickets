<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user')]
class UserController extends AbstractController
{
  #[Route('/', name: 'admin_user_index', methods: ['GET'])]
  public function index(UserRepository $userRepository): Response {
    return $this->render('admin/user/index.html.twig', [
      'users' => $userRepository->findAll(),
    ]);
  }

  #[Route('/{id}', name: 'admin_user_show', methods: ['GET'])]
  public function show(User $user): Response {
    return $this->render('admin/user/show.html.twig', [
      'user' => $user,
    ]);
  }

  #[Route('/{id}/edit', name: 'admin_user_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, User $user): Response {
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('admin/user/edit.html.twig', [
      'user' => $user,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'admin_user_delete', methods: ['POST'])]
  public function delete(Request $request, User $user): Response {
    if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->remove($user);
      $entityManager->flush();
    }

    return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
  }
}