<?php

namespace App\Controller\Admin;

use App\Entity\Airport;
use App\Form\AirportType;
use App\Repository\AirportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/airport')]
class AirportController extends AbstractController
{
    #[Route('/', name: 'admin_airport_index', methods: ['GET'])]
    public function index(AirportRepository $airportRepository): Response
    {
        return $this->render('admin/airport/index.html.twig', [
            'airports' => $airportRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_airport_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $airport = new Airport();
        $form = $this->createForm(AirportType::class, $airport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($airport);
            $entityManager->flush();

            return $this->redirectToRoute('admin_airport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/airport/new.html.twig', [
            'airport' => $airport,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_airport_show', methods: ['GET'])]
    public function show(Airport $airport): Response
    {
        return $this->render('admin/airport/show.html.twig', [
            'airport' => $airport,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_airport_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Airport $airport): Response
    {
        $form = $this->createForm(AirportType::class, $airport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_airport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/airport/edit.html.twig', [
            'airport' => $airport,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_airport_delete', methods: ['POST'])]
    public function delete(Request $request, Airport $airport): Response
    {
        if ($this->isCsrfTokenValid('delete'.$airport->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($airport);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_airport_index', [], Response::HTTP_SEE_OTHER);
    }
}
