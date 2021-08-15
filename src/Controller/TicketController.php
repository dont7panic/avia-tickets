<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\Airport;
use App\Entity\Flight;
use App\Repository\FlightRepository;
use DateInterval;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotEqualTo;

#[Route('/ticket')]
class TicketController extends AbstractController
{
  #[Route('/', name: 'ticket_index', methods: ['GET'])]
  public function index(FlightRepository $flightRepository, Request $request): Response {
    $form = $this->createFormBuilder(null, ['method' => Request::METHOD_GET])
      ->add('from', EntityType::class, [
        'class' => Airport::class,
//        'constraints' => [new NotEqualTo([$this, 'checkPlaneAccessibility'])]
      ])
      ->add('to', EntityType::class, [
        'class' => Airport::class,
//        'constraints' => [new NotEqualTo([$this, 'checkPlaneAccessibility'])]
      ])
      ->add('date', DateType::class, ['input' => 'datetime_immutable'])
      ->add('search', SubmitType::class, ['label' => 'Search'])
      ->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $flights = $flightRepository->findFlightsForTicket(
        $form->getData()['from'],
        $form->getData()['to'],
        $form->getData()['date']
      );

      return $this->render('ticket/index.html.twig', [
        'flights' => $flights,
        'form' => $form->createView(),
        'data' => [
          'from' => $form->getData()['from']->getName(),
          'to' => $form->getData()['to']->getName(),
          'date' => $form->getData()['date']->format('d.m.Y')
        ]
      ]);
    }
    return $this->render('ticket/index.html.twig', [
      'form' => $form->createView()
    ]);
  }

  #[Route('/new', name: 'ticket_new', methods: ['GET', 'POST'])]
  public function new(Request $request): Response {
    $ticket = new Ticket();

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($ticket);
    $entityManager->flush();

    return $this->redirectToRoute('ticket_index', [], Response::HTTP_SEE_OTHER);
  }

  #[Route('/{id}', name: 'ticket_show', methods: ['GET'])]
  public function show(Ticket $ticket): Response {
    return $this->render('ticket/show.html.twig', [
      'ticket' => $ticket,
    ]);
  }

  #[Route('/{id}/edit', name: 'ticket_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Ticket $ticket): Response {
    return $this->redirectToRoute('ticket_index', [], Response::HTTP_SEE_OTHER);
  }

  #[Route('/{id}', name: 'ticket_delete', methods: ['POST'])]
  public function delete(Request $request, Ticket $ticket): Response {
    if ($this->isCsrfTokenValid('delete' . $ticket->getId(), $request->request->get('_token'))) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->remove($ticket);
      $entityManager->flush();
    }

    return $this->redirectToRoute('ticket_index', [], Response::HTTP_SEE_OTHER);
  }
}