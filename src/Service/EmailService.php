<?php

namespace App\Service;

use App\Entity\ContactMessage;
use App\Entity\Order;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
  private ?string $adminEmail;
  private ?string $senderEmail;
  private ?string $senderName;

  public function __construct(
    private MailerInterface $mailer,
    ?string $senderEmail = 'the.klassiker@gmail.com',
    ?string $senderName = 'The Klassiker',
    ?string $adminEmail = 'the.klassiker@gmail.com'
  ) {
    $this->adminEmail = $adminEmail;
    $this->senderEmail = $senderEmail;
    $this->senderName = $senderName;
  }

  /**
   * Envoie un email de confirmation au client après soumission du formulaire
   */
  public function sendContactConfirmation(ContactMessage $message): void
  {
    $email = (new TemplatedEmail())
      ->from($this->senderEmail)
      ->to($message->getEmail())
      ->subject('Confirmation de votre message - The Klassiker')
      ->htmlTemplate('email/contact_confirmation.html.twig')
      ->context([
        'firstName' => $message->getFirstName(),
        'message' => $message,
      ]);

    $this->mailer->send($email);
  }

  /**
   * Envoie une notification à l'administrateur lorsqu'un nouveau message est reçu
   */
  public function sendAdminNotification(ContactMessage $message): void
  {
    if (!$this->adminEmail) {
      return;
    }

    $email = (new TemplatedEmail())
      ->from($this->senderEmail)
      ->to($this->adminEmail)
      ->subject('Nouveau message de contact - The Klassiker')
      ->htmlTemplate('email/admin_notification.html.twig')
      ->context([
        'message' => $message,
      ]);

    $this->mailer->send($email);
  }

  /**
   * Envoie un email de confirmation de commande au client
   */
  public function sendOrderConfirmation(Order $order): void
  {
    if (!$order->getCustomerEmail()) {
      return;
    }

    $email = (new TemplatedEmail())
      ->from($this->senderEmail)
      ->to($order->getCustomerEmail())
      ->subject('Confirmation de votre commande #' . $order->getId() . ' - The Klassiker')
      ->htmlTemplate('email/order_confirmation.html.twig')
      ->context([
        'order' => $order,
      ]);

    $this->mailer->send($email);
  }

  /**
   * Envoie une notification de nouvelle commande à l'administrateur
   */
  public function sendNewOrderNotification(Order $order): void
  {
    if (!$this->adminEmail) {
      return;
    }

    $typeLabel = $order->getType() === Order::TYPE_DELIVERY ? 'Livraison' : 'Click & Collect';

    $email = (new TemplatedEmail())
      ->from($this->senderEmail)
      ->to($this->adminEmail)
      ->subject('🛒 Nouvelle commande #' . $order->getId() . ' - ' . $typeLabel)
      ->htmlTemplate('email/new_order_admin.html.twig')
      ->context([
        'order' => $order,
      ]);

    $this->mailer->send($email);
  }
}

