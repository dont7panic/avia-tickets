<?php

namespace App\Form;

use App\Entity\Flight;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FlightType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder
      ->add('departsAt')
//            ->add('arrivesAt')
//            ->add('createdAt')
//            ->add('updatedAt')
      ->add('airportFrom')
      ->add('airportTo')
      ->add('plane');
  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults([
      'data_class' => Flight::class,
    ]);
  }
}