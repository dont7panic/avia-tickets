<?php

namespace App\Controller\Admin;

use App\Entity\Flight;
use App\Form\FlightType;
use App\Repository\FlightRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/flight')]
class FlightController extends AbstractController
{
    #[Route('/', name: 'admin_flight_index', methods: ['GET'])]
    public function index(FlightRepository $flightRepository): Response
    {
        return $this->render('admin/flight/index.html.twig', [
            'flights' => $flightRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_flight_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $flight = new Flight();
        $form = $this->createForm(FlightType::class, $flight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($flight);
            $entityManager->flush();

            return $this->redirectToRoute('admin_flight_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/flight/new.html.twig', [
            'flight' => $flight,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_flight_show', methods: ['GET'])]
    public function show(Flight $flight): Response
    {
        return $this->render('admin/flight/show.html.twig', [
            'flight' => $flight,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_flight_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Flight $flight): Response
    {
        $form = $this->createForm(FlightType::class, $flight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_flight_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/flight/edit.html.twig', [
            'flight' => $flight,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_flight_delete', methods: ['POST'])]
    public function delete(Request $request, Flight $flight): Response
    {
        if ($this->isCsrfTokenValid('delete'.$flight->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($flight);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_flight_index', [], Response::HTTP_SEE_OTHER);
    }
}
