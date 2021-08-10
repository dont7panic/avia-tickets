<?php

namespace App\Form;

use App\Entity\Flight;
use App\Entity\Plane;
use App\Repository\FlightRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateInterval;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class FlightType extends AbstractType
{
  private $em;

  public function __construct(ManagerRegistry $registry) {
    $this->em = $registry;
  }

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder
      ->add('departsAt')
      ->add('airportFrom')
      ->add('airportTo')
      ->add('plane', EntityType::class, [
        'class' => Plane::class,
        'constraints' => [new Callback([$this, 'checkPlaneAccessibility'])]
      ]);
  }

  public function checkPlaneAccessibility($obj, ExecutionContextInterface $context) {
    $plane = $context->getRoot()->getData()->getPlane();
    $departsAt = $context->getRoot()->getData()->getDepartsAt();
    $arrivesAt = $departsAt->add(new DateInterval('PT4H'))->format('Y-m-d H:i:s');

    $fr = $this->em->getRepository(Flight::class);
    $res = $fr->findEngagedPlanes($plane, $departsAt->format('Y-m-d H:i:s'), $arrivesAt);

    if (count($res)) {
      $context
        ->buildViolation('The plane has already been engaged!')
        ->atPath('plane')
        ->addViolation();
    }
  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults([
      'data_class' => Flight::class,
    ]);
  }
}