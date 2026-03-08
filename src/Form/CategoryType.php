<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name', TextType::class, [
        'label' => 'Nom de la catégorie',
        'attr' => [
          'class' => 'form-control',
          'placeholder' => 'Ex: Burgers',
        ],
        'constraints' => [
          new Assert\NotBlank([
            'message' => 'Le nom est obligatoire.',
          ]),
          new Assert\Length([
            'min' => 2,
            'max' => 100,
          ]),
        ],
      ])
      ->add('slug', TextType::class, [
        'label' => 'Slug (URL)',
        'attr' => [
          'class' => 'form-control',
          'placeholder' => 'Ex: burgers',
        ],
        'required' => false,
        'help' => 'Laissez vide pour générer automatiquement',
      ])
      ->add('description', TextareaType::class, [
        'label' => 'Description',
        'attr' => [
          'class' => 'form-control',
          'rows' => 3,
          'placeholder' => 'Description optionnelle...',
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
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Category::class,
    ]);
  }
}

