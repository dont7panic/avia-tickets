<?php

namespace App\Form;

use App\Entity\Airport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AirportType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder
      ->add('name');
  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults([
      'data_class' => Airport::class,
    ]);
  }
}