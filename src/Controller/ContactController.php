<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Form\ContactType;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactController extends AbstractController
{
  #[Route('/contact', name: 'app_contact')]
  public function contact(
    Request $request,
    EntityManagerInterface $em,
    MailerInterface $mailer,
    EmailService $emailService,
    ValidatorInterface $validator
  ): Response {
    $message = new ContactMessage();
    $form = $this->createForm(ContactType::class, $message);
    $form->handleRequest($request);

    $success = false;
    $error = null;

    if ($form->isSubmitted() && $form->isValid()) {
      try {
        // Sauvegarder le message en base de données
        $em->persist($message);
        $em->flush();

        // Envoyer l'email de confirmation au client
        $emailService->sendContactConfirmation($message);

        // Envoyer la notification à l'admin
        $emailService->sendAdminNotification($message);

        $success = true;
        // Réinitialiser le formulaire
        $message = new ContactMessage();
        $form = $this->createForm(ContactType::class, $message);

      } catch (\Exception $e) {
        $error = 'Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer.';
      }
    }

    return $this->render('main/contact.html.twig', [
      'form' => $form->createView(),
      'success' => $success,
      'error' => $error,
    ]);
  }
}

