<?php

namespace App\Controller\Admin;

use App\Entity\ContactMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/message')]
#[IsGranted('ROLE_ADMIN')]
class MessageController extends AbstractController
{
  #[Route('/', name: 'admin_message_index')]
  public function index(EntityManagerInterface $em): Response
  {
    $messages = $em->getRepository(ContactMessage::class)->findBy(
      [],
      ['createdAt' => 'DESC']
    );

    return $this->render('admin/message/index.html.twig', [
      'messages' => $messages,
    ]);
  }

  #[Route('/{id}', name: 'admin_message_show')]
  public function show(ContactMessage $message): Response
  {
    return $this->render('admin/message/show.html.twig', [
      'message' => $message,
    ]);
  }

  #[Route('/{id}/mark-read', name: 'admin_message_mark_read', methods: ['POST'])]
  public function markRead(Request $request, ContactMessage $message, EntityManagerInterface $em): Response
  {
    if (!$this->isCsrfTokenValid('mark_read' . $message->getId(), $request->request->get('_token'))) {
      $this->addFlash('error', 'Token de sécurité invalide.');
      return $this->redirectToRoute('admin_message_show', ['id' => $message->getId()]);
    }

    $message->setIsRead(true);
    $em->flush();

    $this->addFlash('success', 'Message marqué comme lu.');

    return $this->redirectToRoute('admin_message_show', ['id' => $message->getId()]);
  }

  #[Route('/{id}/mark-unread', name: 'admin_message_mark_unread', methods: ['POST'])]
  public function markUnread(Request $request, ContactMessage $message, EntityManagerInterface $em): Response
  {
    if (!$this->isCsrfTokenValid('mark_unread' . $message->getId(), $request->request->get('_token'))) {
      $this->addFlash('error', 'Token de sécurité invalide.');
      return $this->redirectToRoute('admin_message_show', ['id' => $message->getId()]);
    }

    $message->setIsRead(false);
    $em->flush();

    $this->addFlash('success', 'Message marqué comme non lu.');

    return $this->redirectToRoute('admin_message_show', ['id' => $message->getId()]);
  }

  #[Route('/{id}/delete', name: 'admin_message_delete')]
  public function delete(Request $request, ContactMessage $message, EntityManagerInterface $em): Response
  {
    $csrfToken = $request->request->get('_token');
    if ($this->isCsrfTokenValid('delete' . $message->getId(), $csrfToken)) {
      $em->remove($message);
      $em->flush();
      $this->addFlash('success', 'Message supprimé avec succès.');
    }

    return $this->redirectToRoute('admin_message_index');
  }
}

