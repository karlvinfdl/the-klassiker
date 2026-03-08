<?php

namespace App\Form;

use App\Entity\OpeningHours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class OpeningHoursType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $days = [
      'Lundi' => 1,
      'Mardi' => 2,
      'Mercredi' => 3,
      'Jeudi' => 4,
      'Vendredi' => 5,
      'Samedi' => 6,
      'Dimanche' => 0,
    ];

    $builder
      ->add('dayOfWeek', ChoiceType::class, [
        'label' => 'Jour de la semaine',
        'choices' => $days,
        'attr' => [
          'class' => 'form-control',
        ],
        'constraints' => [
          new Assert\NotBlank([
            'message' => 'Le jour est obligatoire.',
          ]),
        ],
      ])
      ->add('morningOpen', TimeType::class, [
        'label' => 'Matin - Ouverture',
        'attr' => [
          'class' => 'form-control',
        ],
        'required' => false,
        'input' => 'datetime_immutable',
        'widget' => 'single_text',
      ])
      ->add('morningClose', TimeType::class, [
        'label' => 'Matin - Fermeture',
        'attr' => [
          'class' => 'form-control',
        ],
        'required' => false,
        'input' => 'datetime_immutable',
        'widget' => 'single_text',
      ])
      ->add('afternoonOpen', TimeType::class, [
        'label' => 'Après-midi - Ouverture',
        'attr' => [
          'class' => 'form-control',
        ],
        'required' => false,
        'input' => 'datetime_immutable',
        'widget' => 'single_text',
      ])
      ->add('afternoonClose', TimeType::class, [
        'label' => 'Après-midi - Fermeture',
        'attr' => [
          'class' => 'form-control',
        ],
        'required' => false,
        'input' => 'datetime_immutable',
        'widget' => 'single_text',
      ])
      ->add('isClosed', CheckboxType::class, [
        'label' => 'Fermé',
        'attr' => [
          'class' => 'form-check-input',
        ],
        'label_attr' => [
          'class' => 'form-check-label',
        ],
        'required' => false,
      ])
      ->add('displayOrder', IntegerType::class, [
        'label' => 'Ordre d\'affichage',
        'attr' => [
          'class' => 'form-control',
        ],
        'constraints' => [
          new Assert\PositiveOrZero(),
        ],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => OpeningHours::class,
    ]);
  }
}

