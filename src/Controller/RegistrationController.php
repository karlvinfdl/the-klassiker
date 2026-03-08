<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
  #[Route('/inscription', name: 'app_register')]
  public function register(
    Request $request,
    UserPasswordHasherInterface $passwordHasher,
    EntityManagerInterface $entityManager,
    MailerInterface $mailer
  ): Response {
    if ($this->getUser()) {
      return $this->redirectToRoute('app_home');
    }

    $user = new User();
    $form = $this->createForm(RegistrationFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // Get the plain password from the form data
      $plainPassword = $form->get('plainPassword')->get('first')->getData();

      // Encode the plain password
      $user->setPassword(
        $passwordHasher->hashPassword(
          $user,
          $plainPassword
        )
      );

      // Set default role for new users
      $user->setRoles(['ROLE_USER']);

      $entityManager->persist($user);
      $entityManager->flush();

      // Send confirmation email
      $email = (new TemplatedEmail())
        ->from('the.klassiker@gmail.com')
        ->to($user->getEmail())
        ->subject('Bienvenue chez The Klassiker !')
        ->htmlTemplate('email/registration_confirmation.html.twig')
        ->context([
          'user' => $user,
        ]);

      $mailer->send($email);

      $this->addFlash('success', 'Votre compte a été créé avec succès !');

      return $this->redirectToRoute('app_login');
    }

    return $this->render('registration/register.html.twig', [
      'registrationForm' => $form->createView(),
    ]);
  }
}

