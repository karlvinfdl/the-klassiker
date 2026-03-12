<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Dish;
use App\Entity\GalleryPhoto;
use App\Entity\MarqueeItem;
use App\Entity\OpeningHours;
use App\Entity\Supplement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
  #[Route('/', name: 'app_home')]
  public function index(EntityManagerInterface $em): Response
  {
    // Récupérer les catégories actives avec leurs plats
    $categories = $em->getRepository(Category::class)->findBy(
      ['isActive' => true],
      ['displayOrder' => 'ASC']
    );

    // Récupérer les plats en vedettes (featured)
    $featuredDishes = $em->getRepository(Dish::class)->findBy(
      ['isActive' => true, 'isFeatured' => true],
      ['displayOrder' => 'ASC']
    );

    // Récupérer les photos de galerie actives
    $galleryPhotos = $em->getRepository(GalleryPhoto::class)->findBy(
      ['isActive' => true],
      ['displayOrder' => 'ASC'],
      19 // Limiter à 19 photos
    );

    // Récupérer les horaires d'ouverture
    $openingHours = $em->getRepository(OpeningHours::class)->findBy(
      [],
      ['displayOrder' => 'ASC']
    );

    $marqueeItems = $em->getRepository(MarqueeItem::class)->findBy(
      ['isActive' => true],
      ['displayOrder' => 'ASC']
    );

    $supplements = $em->getRepository(Supplement::class)->findBy(
      ['isActive' => true],
      ['displayOrder' => 'ASC']
    );

    $drinks = $em->getRepository(Category::class)->findOneBy(['slug' => 'boissons']);
    $desserts = $em->getRepository(Category::class)->findOneBy(['slug' => 'desserts']);

    return $this->render('main/index.html.twig', [
      'categories' => $categories,
      'featuredDishes' => $featuredDishes,
      'galleryPhotos' => $galleryPhotos,
      'openingHours' => $openingHours,
      'marqueeItems' => $marqueeItems,
      'supplements' => $supplements,
      'drinksDishes' => $drinks ? $drinks->getDishes()->toArray() : [],
      'dessertsDishes' => $desserts ? $desserts->getDishes()->toArray() : [],
    ]);
  }

  #[Route('/recrutement', name: 'app_recrutement')]
  public function recrutement(): Response
  {
    return $this->render('main/recrutement.html.twig');
  }
}

