<?php

namespace App\Form;

use App\Entity\ContactMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('firstName', TextType::class, [
        'label' => 'Prénom',
        'attr' => [
          'class' => 'form-control',
          'placeholder' => 'Votre prénom',
        ],
        'constraints' => [
          new Assert\NotBlank([
            'message' => 'Le prénom est obligatoire.',
          ]),
          new Assert\Length([
            'min' => 2,
            'max' => 100,
            'minMessage' => 'Le prénom doit contenir au moins {{ limit }} caractères.',
            'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères.',
          ]),
          new Assert\Regex([
            'pattern' => '/^[a-zA-ZÀ-ÿ\s\-]+$/',
            'message' => 'Le prénom ne peut contenir que des lettres, espaces et tirets.',
          ]),
        ],
      ])
      ->add('lastName', TextType::class, [
        'label' => 'Nom',
        'attr' => [
          'class' => 'form-control',
          'placeholder' => 'Votre nom',
        ],
        'constraints' => [
          new Assert\NotBlank([
            'message' => 'Le nom est obligatoire.',
          ]),
          new Assert\Length([
            'min' => 2,
            'max' => 100,
            'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
            'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
          ]),
          new Assert\Regex([
            'pattern' => '/^[a-zA-ZÀ-ÿ\s\-]+$/',
            'message' => 'Le nom ne peut contenir que des lettres, espaces et tirets.',
          ]),
        ],
      ])
      ->add('email', EmailType::class, [
        'label' => 'Email',
        'attr' => [
          'class' => 'form-control',
          'placeholder' => 'votre@email.com',
        ],
        'constraints' => [
          new Assert\NotBlank([
            'message' => 'L\'email est obligatoire.',
          ]),
          new Assert\Email([
            'message' => 'L\'email "{{ value }}" n\'est pas valide.',
          ]),
        ],
      ])
      ->add('phone', TelType::class, [
        'label' => 'Téléphone (facultatif)',
        'attr' => [
          'class' => 'form-control',
          'placeholder' => '01 64 99 37 77',
        ],
        'required' => false,
        'constraints' => [
          new Assert\Regex([
            'pattern' => '/^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/',
            'message' => 'Le numéro de téléphone n\'est pas valide.',
          ]),
        ],
      ])
      ->add('subject', TextType::class, [
        'label' => 'Sujet',
        'attr' => [
          'class' => 'form-control',
          'placeholder' => 'Sujet de votre message',
        ],
        'constraints' => [
          new Assert\NotBlank([
            'message' => 'Le sujet est obligatoire.',
          ]),
          new Assert\Length([
            'min' => 3,
            'max' => 150,
            'minMessage' => 'Le sujet doit contenir au moins {{ limit }} caractères.',
            'maxMessage' => 'Le sujet ne peut pas dépasser {{ limit }} caractères.',
          ]),
        ],
      ])
      ->add('message', TextareaType::class, [
        'label' => 'Message',
        'attr' => [
          'class' => 'form-control',
          'placeholder' => 'Votre message...',
          'rows' => 5,
        ],
        'constraints' => [
          new Assert\NotBlank([
            'message' => 'Le message est obligatoire.',
          ]),
          new Assert\Length([
            'min' => 10,
            'max' => 5000,
            'minMessage' => 'Le message doit contenir au moins {{ limit }} caractères.',
            'maxMessage' => 'Le message ne peut pas dépasser {{ limit }} caractères.',
          ]),
        ],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => ContactMessage::class,
      'csrf_protection' => true,
      'csrf_field_name' => '_token',
      'csrf_token_id' => 'contact_form',
    ]);
  }
}

