<?php

namespace App\Form;

use App\Entity\GalleryPhoto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class GalleryPhotoType extends AbstractType
{
  // Local images available for gallery
  private const LOCAL_GALLERY_IMAGES = [
    'galerie-11.webp' => 'galerie-11.webp',
    'galerie-12.webp' => 'galerie-12.webp',
    'galerie-13.webp' => 'galerie-13.webp',
    'galerie-14.webp' => 'galerie-14.webp',
    'galerie-15.webp' => 'galerie-15.webp',
    'galerie-16.webp' => 'galerie-16.webp',
    'galerie-17.webp' => 'galerie-17.webp',
    'galerie-18.webp' => 'galerie-18.webp',
    'galerie-19.webp' => 'galerie-19.webp',
    'galerie-20.webp' => 'galerie-20.webp',
    'galerie-21.webp' => 'galerie-21.webp',
    'galerie-22.webp' => 'galerie-22.webp',
    'galerie-23.webp' => 'galerie-23.webp',
    'galerie-24.webp' => 'galerie-24.webp',
    'galerie-25.webp' => 'galerie-25.webp',
    'galerie-26.webp' => 'galerie-26.webp',
    'galerie-27.webp' => 'galerie-27.webp',
    'galerie-28.webp' => 'galerie-28.webp',
    'galerie-29.webp' => 'galerie-29.webp',
    'hero1.webp' => 'hero1.webp',
    'livraison.webp' => 'livraison.webp',
  ];

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('imageFile', FileType::class, [
        'label' => 'Télécharger une image',
        'attr' => [
          'class' => 'form-control',
        ],
        'required' => false,
        'mapped' => false,
        'help' => 'Formats acceptés: JPG, PNG, WebP. Taille max: 5Mo',
      ])
      ->add('localImage', ChoiceType::class, [
        'label' => 'Ou choisir une image existante',
        'attr' => [
          'class' => 'form-control',
        ],
        'required' => false,
        'mapped' => false,
        'choices' => self::LOCAL_GALLERY_IMAGES,
        'placeholder' => '-- Sélectionner une image --',
        'data' => null, // Will be set in controller for existing photos
      ])
      ->add('altText', TextType::class, [
        'label' => 'Texte alternatif (SEO)',
        'attr' => [
          'class' => 'form-control',
          'placeholder' => 'Description de l\'image pour le SEO',
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
      'data_class' => GalleryPhoto::class,
    ]);
  }
}

