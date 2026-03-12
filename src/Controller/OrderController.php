<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Dish;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Form\OrderType;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
  private const CART_SESSION_KEY = 'order_cart';

  public function __construct(
    private RequestStack $requestStack,
    private EntityManagerInterface $em
  ) {
  }

  /**
   * Page principale de commande - Menu avec ajout rapide
   */
  #[Route('/commande', name: 'app_order')]
  public function index(): Response
  {
    // Récupérer les catégories actives avec leurs plats
    $categories = $this->em->getRepository(Category::class)->findBy(
      ['isActive' => true],
      ['displayOrder' => 'ASC']
    );

    // Récupérer le panier depuis la session
    $cart = $this->getCart();
    $cartCount = $this->getCartCount();

    return $this->render('order/index.html.twig', [
      'categories' => $categories,
      'cart' => $cart,
      'cartCount' => $cartCount,
    ]);
  }

  /**
   * Page détail d'un produit (dépréciée - maintenant en modal)
   * Gardée pour compatibilité si JS désactivé
   */
  #[Route('/produit/{id}', name: 'app_product_show', requirements: ['id' => '\d+'])]
  public function show(int $id): Response
  {
    $dish = $this->em->getRepository(Dish::class)->find($id);

    if (!$dish) {
      throw $this->createNotFoundException('Produit non trouvé');
    }

    // Rediriger vers la page de commande avec le produit en paramètre
    return $this->redirectToRoute('app_order', ['_fragment' => 'menu']);
  }

  /**
   * Ajouter un article au panier
   */
  #[Route('/commande/ajouter', name: 'app_order_add', methods: ['POST'])]
  public function addToCart(Request $request): Response
  {
    $dishId = $request->request->get('dish_id');
    $quantity = (int) $request->request->get('quantity', 1);
    $specialInstructions = $request->request->get('special_instructions', '');

    if (!$dishId || $quantity < 1) {
      $this->addFlash('error', 'Article invalide');
      return $this->redirectToRoute('app_order');
    }

    $dish = $this->em->getRepository(Dish::class)->find($dishId);
    if (!$dish) {
      $this->addFlash('error', 'Article non trouvé');
      return $this->redirectToRoute('app_order');
    }

    // Ajouter au panier en session
    $cart = $this->getCart();
    $found = false;

    foreach ($cart as &$item) {
      if ($item['dish_id'] === $dishId && $item['special_instructions'] === $specialInstructions) {
        $item['quantity'] += $quantity;
        $found = true;
        break;
      }
    }

    if (!$found) {
      $cart[] = [
        'dish_id' => $dishId,
        'dish_name' => $dish->getName(),
        'unit_price' => $dish->getPrice(),
        'quantity' => $quantity,
        'special_instructions' => $specialInstructions,
      ];
    }

    $this->saveCart($cart);

    $this->addFlash('success', $dish->getName() . ' ajouté au panier !');

    // Renvoyer vers la page précédente ou commander
    $referer = $request->headers->get('referer', $this->generateUrl('app_order'));
    return $this->redirect($referer);
  }

  /**
   * Mettre à jour la quantité d'un article
   */
  #[Route('/commande/update', name: 'app_order_update', methods: ['POST'])]
  public function updateCart(Request $request): Response
  {
    $index = (int) $request->request->get('index');
    $quantity = (int) $request->request->get('quantity');

    $cart = $this->getCart();

    if (isset($cart[$index])) {
      if ($quantity <= 0) {
        unset($cart[$index]);
        $cart = array_values($cart); // Réindexer
      } else {
        $cart[$index]['quantity'] = $quantity;
      }
      $this->saveCart($cart);
    }

    return $this->redirectToRoute('app_order_cart');
  }

  /**
   * Supprimer un article du panier
   */
  #[Route('/commande/remove/{index}', name: 'app_order_remove')]
  public function removeFromCart(int $index): Response
  {
    $cart = $this->getCart();

    if (isset($cart[$index])) {
      unset($cart[$index]);
      $cart = array_values($cart); // Réindexer
      $this->saveCart($cart);
      $this->addFlash('success', 'Article supprimé du panier');
    }

    return $this->redirectToRoute('app_order_cart');
  }

  /**
   * Afficher le panier
   */
  #[Route('/commande/panier', name: 'app_order_cart')]
  public function cart(): Response
  {
    $cart = $this->getCart();
    $cartTotal = $this->getCartTotal();

    return $this->render('order/panier.html.twig', [
      'cart' => $cart,
      'cartTotal' => $cartTotal,
    ]);
  }

  /**
   * Page de checkout - Formulaire de commande
   */
  #[Route('/commande/checkout', name: 'app_order_checkout')]
  public function checkout(Request $request): Response
  {
    $cart = $this->getCart();
    if (empty($cart)) {
      $this->addFlash('error', 'Votre panier est vide');
      return $this->redirectToRoute('app_order');
    }

    $cartTotal = $this->getCartTotal();

    $order = new Order();
    $form = $this->createForm(OrderType::class, $order);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // Ajouter les articles au order
      foreach ($cart as $item) {
        $orderItem = new OrderItem();
        $orderItem->setDishName($item['dish_name']);
        $orderItem->setUnitPrice($item['unit_price']);
        $orderItem->setQuantity($item['quantity']);
        $orderItem->setSpecialInstructions($item['special_instructions'] ?? null);

        // Associer le plat si existant
        if (isset($item['dish_id'])) {
          $dish = $this->em->getRepository(Dish::class)->find($item['dish_id']);
          if ($dish) {
            $orderItem->setDish($dish);
          }
        }

        $order->addItem($orderItem);
      }

      // Calculer le total
      $order->setTotalAmount((string) $cartTotal);
      $order->setStatus(Order::STATUS_PENDING);

      // Sauvegarder en base
      $this->em->persist($order);
      $this->em->flush();

      // Vider le panier
      $this->saveCart([]);

      // Envoyer les emails (sans bloquer si erreur)
      try {
        $emailService = $this->container->get(EmailService::class);
        $emailService->sendOrderConfirmation($order);
        $emailService->sendNewOrderNotification($order);
      } catch (\Exception $e) {
        // Log error but don't block
      }

      return $this->redirectToRoute('app_order_confirmation', ['id' => $order->getId()]);
    }

    return $this->render('order/checkout.html.twig', [
      'form' => $form->createView(),
      'cart' => $cart,
      'cartTotal' => $cartTotal,
    ]);
  }

  /**
   * Page de confirmation de commande
   */
  #[Route('/commande/confirmation/{id}', name: 'app_order_confirmation')]
  public function confirmation(int $id): Response
  {
    $order = $this->em->getRepository(Order::class)->find($id);

    if (!$order) {
      $this->addFlash('error', 'Commande non trouvée');
      return $this->redirectToRoute('app_order');
    }

    return $this->render('order/confirmation.html.twig', [
      'order' => $order,
    ]);
  }

  // ================= GESTION DU PANIER EN SESSION =================

  private function getCart(): array
  {
    return $this->requestStack->getSession()->get(self::CART_SESSION_KEY, []);
  }

  private function saveCart(array $cart): void
  {
    $this->requestStack->getSession()->set(self::CART_SESSION_KEY, $cart);
  }

  private function getCartCount(): int
  {
    $cart = $this->getCart();
    $count = 0;
    foreach ($cart as $item) {
      $count += $item['quantity'];
    }
    return $count;
  }

  private function getCartTotal(): float
  {
    $cart = $this->getCart();
    $total = 0;
    foreach ($cart as $item) {
      $total += $item['quantity'] * (float) $item['unit_price'];
    }
    return $total;
  }
}

