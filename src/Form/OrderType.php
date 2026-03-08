<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class OrderType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('type', ChoiceType::class, [
        'label' => 'Mode de commande',
        'choices' => [
          'Click & Collect (à emporter)' => Order::TYPE_PICKUP,
          'Livraison à domicile' => Order::TYPE_DELIVERY,
        ],
        'expanded' => true,
        'multiple' => false,
        'constraints' => [
          new Assert\NotBlank([
            'message' => 'Veuillez sélectionner un mode de commande.',
          ]),
        ],
      ])
      ->add('customerName', TextType::class, [
        'label' => 'Nom complet',
        'attr' => [
          'class' => 'form-control',
          'placeholder' => 'Votre nom et prénom',
        ],
        'constraints' => [
          new Assert\NotBlank([
            'message' => 'Le nom est obligatoire.',
          ]),
          new Assert\Length([
            'min' => 2,
            'max' => 150,
            'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
            'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
          ]),
        ],
      ])
      ->add('customerPhone', TelType::class, [
        'label' => 'Téléphone',
        'attr' => [
          'class' => 'form-control',
          'placeholder' => '01 64 99 37 77',
        ],
        'constraints' => [
          new Assert\NotBlank([
            'message' => 'Le téléphone est obligatoire.',
          ]),
          new Assert\Regex([
            'pattern' => '/^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/',
            'message' => 'Le numéro de téléphone n\'est pas valide.',
          ]),
        ],
      ])
      ->add('customerEmail', EmailType::class, [
        'label' => 'Email (pour la confirmation)',
        'attr' => [
          'class' => 'form-control',
          'placeholder' => 'votre@email.com',
        ],
        'required' => false,
        'constraints' => [
          new Assert\Email([
            'message' => 'L\'email "{{ value }}" n\'est pas valide.',
          ]),
        ],
      ])
      ->add('deliveryAddress', TextareaType::class, [
        'label' => 'Adresse de livraison',
        'attr' => [
          'class' => 'form-control',
          'placeholder' => 'Numéro, rue, ville, code postal',
          'rows' => 3,
        ],
        'required' => false,
        'constraints' => [
          new Assert\Length([
            'max' => 500,
            'maxMessage' => 'L\'adresse ne peut pas dépasser {{ limit }} caractères.',
          ]),
        ],
      ])
      ->add('comments', TextareaType::class, [
        'label' => 'Notes spéciales',
        'attr' => [
          'class' => 'form-control',
          'placeholder' => 'Allergies, instructions spéciales...',
          'rows' => 3,
        ],
        'required' => false,
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Order::class,
      'csrf_protection' => true,
      'csrf_field_name' => '_token',
      'csrf_token_id' => 'order_form',
    ]);
  }
}

