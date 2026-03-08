<?php

namespace App\Form;

use App\Entity\Dish;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Positive;

class DishType extends AbstractType
{
  // Local images available for dishes
  private const LOCAL_DISH_IMAGES = [
    '1-.webp' => '1-.webp',
    '2-.webp' => '2-.webp',
    '3-.webp' => '3-.webp',
    '4-.webp' => '4-.webp',
    '5-.webp' => '5-.webp',
    '6-.webp' => '6-.webp',
    '7-.webp' => '7-.webp',
    '8-.webp' => '8-.webp',
    '9-.webp' => '9-.webp',
    '10-.webp' => '10-.webp',
    '11-.webp' => '11-.webp',
    '12-.webp' => '12-.webp',
  ];

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name', TextType::class, [
        'label' => 'Nom du plat',
        'attr' => [
          'class' => 'form-control',
          'placeholder' => 'Ex: Tasty & Crousty',
        ],
        'constraints' => [
          new Assert\NotBlank([
            'message' => 'Le nom est obligatoire.',
          ]),
          new Assert\Length([
            'min' => 2,
            'max' => 150,
          ]),
        ],
      ])
      ->add('description', TextareaType::class, [
        'label' => 'Description',
        'attr' => [
          'class' => 'form-control',
          'rows' => 3,
          'placeholder' => 'Description du plat...',
        ],
        'constraints' => [
          new Assert\NotBlank([
            'message' => 'La description est obligatoire.',
          ]),
          new Assert\Length([
            'min' => 10,
            'max' => 1000,
          ]),
        ],
      ])
      ->add('price', MoneyType::class, [
        'label' => 'Prix (€)',
        'attr' => [
          'class' => 'form-control',
          'placeholder' => '9.90',
        ],
        'currency' => 'EUR',
        'divisor' => 1,
        'constraints' => [
          new Assert\NotBlank([
            'message' => 'Le prix est obligatoire.',
          ]),
          new Positive([
            'message' => 'Le prix doit être positif.',
          ]),
        ],
      ])
      ->add('category', EntityType::class, [
        'label' => 'Catégorie',
        'class' => Category::class,
        'choice_label' => 'name',
        'attr' => [
          'class' => 'form-control',
        ],
        'constraints' => [
          new Assert\NotBlank([
            'message' => 'La catégorie est obligatoire.',
          ]),
        ],
      ])
      ->add('imageFile', FileType::class, [
        'label' => 'Télécharger une image',
        'attr' => [
          'class' => 'form-control',
        ],
        'required' => false,
        'mapped' => false,
        'help' => 'Formats acceptés: JPG, PNG, WebP. Taille max: 2Mo',
      ])
      ->add('localImage', ChoiceType::class, [
        'label' => 'Ou choisir une image existante',
        'attr' => [
          'class' => 'form-control',
        ],
        'required' => false,
        'mapped' => false,
        'choices' => self::LOCAL_DISH_IMAGES,
        'placeholder' => '-- Sélectionner une image --',
      ])
      ->add('displayOrder', IntegerType::class, [
        'label' => 'Ordre d\'affichage',
        'attr' => [
          'class' => 'form-control',
        ],
        'constraints' => [
          new Assert\PositiveOrZero(),
        ],
      ])
      ->add('isActive', CheckboxType::class, [
        'label' => 'Actif',
        'attr' => [
          'class' => 'form-check-input',
        ],
        'label_attr' => [
          'class' => 'form-check-label',
        ],
        'required' => false,
      ])
      ->add('isFeatured', CheckboxType::class, [
        'label' => 'En vedettes',
        'attr' => [
          'class' => 'form-check-input',
        ],
        'label_attr' => [
          'class' => 'form-check-label',
        ],
        'required' => false,
      ])
      ->add('submit', SubmitType::class, [
        'label' => 'Enregistrer',
        'attr' => [
          'class' => 'btn btn-primary mt-3',
        ],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Dish::class,
    ]);
  }
}

