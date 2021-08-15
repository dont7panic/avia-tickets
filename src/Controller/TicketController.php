<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\Airport;
use App\Repository\FlightRepository;
use App\Repository\TicketRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\GreaterThan;

#[Route('/ticket')]
class TicketController extends AbstractController
{
  #[Route('/search', name: 'ticket_search', methods: ['GET'])]
  public function search(FlightRepository $flightRepository, Request $request): Response {
    $form = $this->createFormBuilder(null, ['method' => Request::METHOD_GET])
      ->add('from', EntityType::class, [
        'class' => Airport::class
      ])
      ->add('to', EntityType::class, [
        'class' => Airport::class
      ])
      ->add('date', DateType::class, [
        'input' => 'datetime_immutable',
        'constraints' => [new GreaterThan('-1day')]
      ])
      ->add('search', SubmitType::class, ['label' => 'Search'])
      ->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $flights = $flightRepository->findFlightsForTicket(
        $form->getData()['from'],
        $form->getData()['to'],
        $form->getData()['date']
      );

      return $this->render('ticket/search.html.twig', [
        'flights' => $flights,
        'form' => $form->createView(),
        'data' => [
          'from' => $form->getData()['from']->getName(),
          'to' => $form->getData()['to']->getName(),
          'date' => $form->getData()['date']->format('d.m.Y')
        ]
      ]);
    }
    return $this->render('ticket/search.html.twig', [
      'form' => $form->createView()
    ]);
  }

  #[Route('/', name: 'ticket_index', methods: ['GET'])]
  public function index(TicketRepository $ticketRepository): Response {
    return $this->render('ticket/index.html.twig', [
      'tickets' => $ticketRepository->findMyTickets($this->getUser())
    ]);
  }

  #[Route('/{id}/buy', name: 'ticket_buy', methods: ['GET', 'POST'])]
  public function buy(Request $request, FlightRepository $flightRepository, int $id): Response {
    $ticket = new Ticket();
    $ticket->setUser($this->getUser());
    $ticket->setFlight($flightRepository->findOneBy(['id' => $id]));
    $ticket->setStatus('paid');

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($ticket);
    $entityManager->flush();

    return $this->redirectToRoute('ticket_index', [], Response::HTTP_SEE_OTHER);
  }

  #[Route('/{id}/book', name: 'ticket_book', methods: ['GET', 'POST'])]
  public function book(Request $request, FlightRepository $flightRepository, int $id): Response {
    $ticket = new Ticket();
    $ticket->setUser($this->getUser());
    $ticket->setFlight($flightRepository->findOneBy(['id' => $id]));
    $ticket->setStatus('booked');

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

  #[Route('/{id}/buy_existing', name: 'ticket_buy_existing', methods: ['GET', 'POST'])]
  public function buyExisting(Request $request, Ticket $ticket): Response {
    $ticket->setStatus('paid');

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($ticket);
    $entityManager->flush();

    return $this->redirectToRoute('ticket_index', [], Response::HTTP_SEE_OTHER);
  }

  #[Route('/{id}/delete', name: 'ticket_delete', methods: ['GET', 'POST'])]
  public function delete(Request $request, Ticket $ticket): Response {
    if ($this->isCsrfTokenValid('delete' . $ticket->getId(), $request->request->get('_token'))) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->remove($ticket);
      $entityManager->flush();
    }

    return $this->redirectToRoute('ticket_index', [], Response::HTTP_SEE_OTHER);
  }
}