<?php

namespace App\Form;

use App\Entity\Supplement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class SupplementType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name', TextType::class, [
        'label' => 'Nom du supplément',
        'attr' => ['class' => 'form-control', 'placeholder' => 'Ex: Extra fromage, Sauce piquante...'],
        'constraints' => [new Assert\NotBlank()],
      ])
      ->add('price', MoneyType::class, [
        'label' => 'Prix (€)',
        'currency' => 'EUR',
        'attr' => ['class' => 'form-control'],
        'constraints' => [new Assert\PositiveOrZero()],
      ])
      ->add('isActive', CheckboxType::class, [
        'label' => 'Actif',
        'attr' => ['class' => 'form-check-input'],
        'label_attr' => ['class' => 'form-check-label'],
        'required' => false,
      ])
      ->add('displayOrder', IntegerType::class, [
        'label' => "Ordre d'affichage",
        'attr' => ['class' => 'form-control'],
        'constraints' => [new Assert\PositiveOrZero()],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults(['data_class' => Supplement::class]);
  }
}
