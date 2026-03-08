<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/mon-compte')]
class UserController extends AbstractController
{
  #[Route('/', name: 'app_user_dashboard')]
  public function dashboard(OrderRepository $orderRepository): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    
    // Get orders by customer email
    $orders = $orderRepository->createQueryBuilder('o')
      ->where('o.customerEmail = :email')
      ->setParameter('email', $user->getEmail())
      ->orderBy('o.createdAt', 'DESC')
      ->getQuery()
      ->getResult();

    // Calculate total spent
    $totalSpent = 0;
    foreach ($orders as $order) {
      $totalSpent += (float) $order->getTotalAmount();
    }

    return $this->render('user/dashboard.html.twig', [
      'user' => $user,
      'orders' => $orders,
      'totalSpent' => $totalSpent,
    ]);
  }

  #[Route('/commandes', name: 'app_user_orders')]
  public function orders(OrderRepository $orderRepository): Response
  {
    /** @var User $user */
    $user = $this->getUser();

    $orders = $orderRepository->createQueryBuilder('o')
      ->where('o.customerEmail = :email')
      ->setParameter('email', $user->getEmail())
      ->orderBy('o.createdAt', 'DESC')
      ->getQuery()
      ->getResult();

    return $this->render('user/orders.html.twig', [
      'user' => $user,
      'orders' => $orders,
    ]);
  }

  #[Route('/commandes/{id}', name: 'app_user_order_show')]
  public function orderShow(int $id, OrderRepository $orderRepository): Response
  {
    /** @var User $user */
    $user = $this->getUser();

    $order = $orderRepository->find($id);

    // Security check - only allow viewing own orders
    if (!$order || $order->getCustomerEmail() !== $user->getEmail()) {
      throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette commande.');
    }

    return $this->render('user/order_show.html.twig', [
      'order' => $order,
    ]);
  }

  #[Route('/profil', name: 'app_user_profile')]
  public function profile(): Response
  {
    /** @var User $user */
    $user = $this->getUser();

    return $this->render('user/profile.html.twig', [
      'user' => $user,
    ]);
  }
}

