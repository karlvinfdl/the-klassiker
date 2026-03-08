<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('firstName', TextType::class, [
        'label' => 'Prénom',
        'attr' => [
          'placeholder' => 'Votre prénom',
          'class' => 'form-control'
        ],
        'constraints' => [
          new NotBlank([
            'message' => 'Veuillez entrer votre prénom',
          ]),
        ],
      ])
      ->add('email', EmailType::class, [
        'label' => 'Email',
        'attr' => [
          'placeholder' => 'votre@email.com',
          'class' => 'form-control'
        ],
        'constraints' => [
          new NotBlank([
            'message' => 'Veuillez entrer une adresse email',
          ]),
        ],
      ])
      ->add('plainPassword', RepeatedType::class, [
        'mapped' => false,
        'type' => PasswordType::class,
        'options' => [
          'attr' => [
            'class' => 'form-control',
            'autocomplete' => 'new-password',
          ],
        ],
        'first_options' => [
          'label' => 'Mot de passe',
          'attr' => [
            'placeholder' => 'Ex: Klassiker@2024',
          ],
        ],
        'second_options' => [
          'label' => 'Confirmer le mot de passe',
          'attr' => [
            'placeholder' => 'Ex: Klassiker@2024',
          ],
        ],
        'constraints' => [
          new NotBlank([
            'message' => 'Veuillez entrer un mot de passe',
          ]),
          new Length([
            'min' => 8,
            'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
            'max' => 4096,
          ]),
          new Regex([
            'pattern' => '/[A-Z]/',
            'message' => 'Le mot de passe doit contenir au moins une majuscule.',
          ]),
          new Regex([
            'pattern' => '/[0-9]/',
            'message' => 'Le mot de passe doit contenir au moins un chiffre.',
          ]),
          new Regex([
            'pattern' => '/[!@#$%^&*(),.?":{}|<>]/',
            'message' => 'Le mot de passe doit contenir au moins un caractère spécial.',
          ]),
        ],
        'invalid_message' => 'Les mots de passe ne correspondent pas.',
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => User::class,
    ]);
  }
}

