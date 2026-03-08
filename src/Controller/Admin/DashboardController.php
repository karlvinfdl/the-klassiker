<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\ContactMessage;
use App\Entity\Dish;
use App\Entity\GalleryPhoto;
use App\Entity\OpeningHours;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class DashboardController extends AbstractController
{
  #[Route('/', name: 'admin_dashboard')]
  public function index(EntityManagerInterface $em): Response
  {
    // Statistiques
    $stats = [
      'categories' => $em->getRepository(Category::class)->count([]),
      'dishes' => $em->getRepository(Dish::class)->count([]),
      'photos' => $em->getRepository(GalleryPhoto::class)->count([]),
      'messages' => $em->getRepository(ContactMessage::class)->count([]),
      'unreadMessages' => $em->getRepository(ContactMessage::class)->count(['isRead' => false]),
      'hours' => $em->getRepository(OpeningHours::class)->count([]),
      'users' => $em->getRepository(User::class)->count([]),
    ];

    // Derniers messages non lus
    $recentMessages = $em->getRepository(ContactMessage::class)->findBy(
      ['isRead' => false],
      ['createdAt' => 'DESC'],
      5
    );

    return $this->render('admin/dashboard.html.twig', [
      'stats' => $stats,
      'recentMessages' => $recentMessages,
    ]);
  }
}

