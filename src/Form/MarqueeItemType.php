<?php

namespace App\Form;

use App\Entity\MarqueeItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class MarqueeItemType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('text', TextType::class, [
        'label' => 'Texte à afficher',
        'attr' => ['class' => 'form-control', 'placeholder' => 'Ex: 🔥 Promo -20% ce week-end !'],
        'constraints' => [new Assert\NotBlank(['message' => 'Le texte est obligatoire.'])],
      ])
      ->add('isActive', CheckboxType::class, [
        'label' => 'Actif',
        'attr' => ['class' => 'form-check-input'],
        'label_attr' => ['class' => 'form-check-label'],
        'required' => false,
      ])
      ->add('displayOrder', IntegerType::class, [
        'label' => 'Ordre d\'affichage',
        'attr' => ['class' => 'form-control'],
        'constraints' => [new Assert\PositiveOrZero()],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults(['data_class' => MarqueeItem::class]);
  }
}
