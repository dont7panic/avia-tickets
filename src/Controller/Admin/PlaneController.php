<?php

namespace App\Controller\Admin;

use App\Entity\Plane;
use App\Form\PlaneType;
use App\Repository\PlaneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/plane')]
class PlaneController extends AbstractController
{
    #[Route('/', name: 'admin_plane_index', methods: ['GET'])]
    public function index(PlaneRepository $planeRepository): Response
    {
        return $this->render('admin/plane/index.html.twig', [
            'planes' => $planeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_plane_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $plane = new Plane();
        $form = $this->createForm(PlaneType::class, $plane);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($plane);
            $entityManager->flush();

            return $this->redirectToRoute('admin_plane_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/plane/new.html.twig', [
            'plane' => $plane,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_plane_show', methods: ['GET'])]
    public function show(Plane $plane): Response
    {
        return $this->render('admin/plane/show.html.twig', [
            'plane' => $plane,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_plane_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Plane $plane): Response
    {
        $form = $this->createForm(PlaneType::class, $plane);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_plane_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/plane/edit.html.twig', [
            'plane' => $plane,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_plane_delete', methods: ['POST'])]
    public function delete(Request $request, Plane $plane): Response
    {
        if ($this->isCsrfTokenValid('delete'.$plane->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($plane);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_plane_index', [], Response::HTTP_SEE_OTHER);
    }
}
