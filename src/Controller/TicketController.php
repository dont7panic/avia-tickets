<?php

namespace App\Controller;

use App\Entity\MoneyTransaction;
use App\Entity\Notification;
use App\Entity\Ticket;
use App\Entity\Airport;
use App\Event\BoughtTicketCancelledEvent;
use App\Event\TicketBookedEvent;
use App\Event\TicketBoughtEvent;
use App\Repository\FlightRepository;
use App\Repository\TicketRepository;
use App\Subscriber\TicketSubscriber;
use Doctrine\DBAL\Exception;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\GreaterThan;

#[Route('/ticket')]
class TicketController extends AbstractController
{
  const TICKET_PRICE = 1000;

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

  /**
   * @throws Exception
   */
  #[Route('/{id}/buy', name: 'ticket_buy', methods: ['GET', 'POST'])]
  public function buy(FlightRepository $flightRepository, int $id, EventDispatcherInterface $dispatcher): Response {
    $em = $this->getDoctrine()->getManager();
    $flight = $flightRepository->findOneBy(['id' => $id]);
    $user = $this->getUser();
    $balance = $user->getBalance();

    if ($balance > self::TICKET_PRICE) {
      $em->getConnection()->beginTransaction();
      try {
        $ticket = new Ticket();
        $ticket->setUser($user);
        $ticket->setFlight($flight);
        $ticket->setStatus('paid');
        $em->persist($ticket);

        $mt = new MoneyTransaction();
        $mt->setUser($user);
        $mt->setMoney(self::TICKET_PRICE);
        $em->persist($mt);

        $user->setBalance($balance - $mt->getMoney());

        $notification = new Notification();
        $notification->setType('email');
        $notification->setUser($user);
        $notification->setTicket($ticket);
        $notification->setSubject('bought');
        $notification->setContent('You\'ve successfully bought the ticket to flight #' . $flight->getId() . '!');
        $notification->setStatus('NOT_SENT');
        $em->persist($notification);

        $em->flush();

        $dispatcher->addSubscriber(new TicketSubscriber());
        $dispatcher->dispatch(new TicketBoughtEvent($ticket), TicketBoughtEvent::NAME);

        $em->getConnection()->commit();

        $this->addFlash('success', 'You\'ve successfully bought the ticket to flight #' . $flight->getId() . '!');
      } catch (Exception $e) {
        $this->addFlash('error', 'Error: ' . $e->getCode() . '. ' . $e->getMessage());

        $em->getConnection()->rollBack();
        throw $e;
      }
    } else {
      $this->addFlash('error', 'You don\'t have enough money! Top up your balance first!');
      return $this->redirectToRoute('profile');
    }

    return $this->redirectToRoute('ticket_index', [], Response::HTTP_SEE_OTHER);
  }

  #[Route('/{id}/book', name: 'ticket_book', methods: ['GET', 'POST'])]
  public function book(FlightRepository $flightRepository, int $id, EventDispatcherInterface $dispatcher): Response {
    $em = $this->getDoctrine()->getManager();
    $flight = $flightRepository->findOneBy(['id' => $id]);
    $user = $this->getUser();

    $em->getConnection()->beginTransaction();
    try {
      $ticket = new Ticket();
      $ticket->setUser($user);
      $ticket->setFlight($flight);
      $ticket->setStatus('booked');
      $em->persist($ticket);

      $notification = new Notification();
      $notification->setType('email');
      $notification->setUser($user);
      $notification->setTicket($ticket);
      $notification->setSubject('booked');
      $notification->setContent('You\'ve successfully booked the ticket to flight #' . $flight->getId() . '!');
      $notification->setStatus('NOT_SENT');
      $em->persist($notification);

      $em->flush();

      $dispatcher->addSubscriber(new TicketSubscriber());
      $dispatcher->dispatch(new TicketBookedEvent($ticket), TicketBookedEvent::NAME);

      $em->getConnection()->commit();

      $this->addFlash('success', 'You\'ve successfully booked the ticket to flight #' . $flight->getId() . '!');
    } catch (Exception $e) {
      $this->addFlash('error', 'Error: ' . $e->getCode() . '. ' . $e->getMessage());

      $em->getConnection()->rollBack();
      throw $e;
    }

    return $this->redirectToRoute('ticket_index', [], Response::HTTP_SEE_OTHER);
  }

  #[Route('/{id}', name: 'ticket_show', methods: ['GET'])]
  public function show(Ticket $ticket): Response {
    return $this->render('ticket/show.html.twig', [
      'ticket' => $ticket,
    ]);
  }

  #[Route('/{id}/buy_existing', name: 'ticket_buy_existing', methods: ['GET', 'POST'])]
  public function buyExisting(Ticket $ticket, EventDispatcherInterface $dispatcher): Response {
    $em = $this->getDoctrine()->getManager();
    $user = $this->getUser();
    $balance = $user->getBalance();

    if ($balance > self::TICKET_PRICE) {
      $em->getConnection()->beginTransaction();
      try {
        $ticket->setStatus('paid');
        $em->persist($ticket);

        $mt = new MoneyTransaction();
        $mt->setUser($user);
        $mt->setMoney(self::TICKET_PRICE);
        $em->persist($mt);

        $user->setBalance($balance - $mt->getMoney());

        $notification = new Notification();
        $notification->setType('email');
        $notification->setUser($user);
        $notification->setTicket($ticket);
        $notification->setSubject('bought');
        $notification->setContent(
          'You\'ve successfully bought the ticket to flight #' . $ticket->getFlight()->getId() . '!'
        );
        $notification->setStatus('NOT_SENT');
        $em->persist($notification);

        $em->flush();

        $dispatcher->addSubscriber(new TicketSubscriber());
        $dispatcher->dispatch(new TicketBoughtEvent($ticket), TicketBoughtEvent::NAME);

        $em->getConnection()->commit();

        $this->addFlash(
          'success',
          'You\'ve successfully bought the ticket to flight #' . $ticket->getFlight()->getId() . '!');
      } catch (Exception $e) {
        $this->addFlash('error', 'Error: ' . $e->getCode() . '. ' . $e->getMessage());

        $em->getConnection()->rollBack();
        throw $e;
      }
    } else {
      $this->addFlash('error', 'You don\'t have enough money! Top up your balance first!');
      return $this->redirectToRoute('profile');
    }

    return $this->redirectToRoute('ticket_index', [], Response::HTTP_SEE_OTHER);
  }

  #[Route('/{id}/delete', name: 'ticket_delete', methods: ['GET', 'POST'])]
  public function delete(Request $request, Ticket $ticket, EventDispatcherInterface $dispatcher): Response {
    if ($this->isCsrfTokenValid('delete' . $ticket->getId(), $request->request->get('_token'))) {
      $em = $this->getDoctrine()->getManager();
      $user = $this->getUser();
      $balance = $user->getBalance();

      $em->getConnection()->beginTransaction();
      try {
        $mt = new MoneyTransaction();
        $mt->setUser($user);
        $mt->setMoney(-self::TICKET_PRICE / 2);
        $em->persist($mt);

        $user->setBalance($balance - $mt->getMoney());

        $notification = new Notification();
        $notification->setType('email');
        $notification->setUser($user);
        $notification->setSubject('gotten back');
        $notification->setContent('You\'ve successfully gotten back the ticket #' . $ticket->getId() . '!');
        $notification->setStatus('NOT_SENT');

        $em->persist($notification);
        $em->flush();

        $dispatcher->addSubscriber(new TicketSubscriber());
        $dispatcher->dispatch(new BoughtTicketCancelledEvent($ticket), BoughtTicketCancelledEvent::NAME);

        foreach ($ticket->getNotifications() as $notification) {
          $ticket->removeNotification($notification);
        }

        $em->remove($ticket);
        $em->flush();

        $em->getConnection()->commit();

        $this->addFlash('success', 'You\'ve successfully gotten back the ticket #' . $ticket->getId() . '!');
      } catch (Exception $e) {
        $this->addFlash('error', 'Error: ' . $e->getCode() . '. ' . $e->getMessage());

        $em->getConnection()->rollBack();
        throw $e;
      }
    }

    return $this->redirectToRoute('ticket_index', [], Response::HTTP_SEE_OTHER);
  }
}