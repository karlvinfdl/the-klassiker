<?php

namespace App\Controller\Admin;

use App\Entity\ContactMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/message')]
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

  #[Route('/{id}/mark-read', name: 'admin_message_mark_read')]
  public function markRead(ContactMessage $message, EntityManagerInterface $em): Response
  {
    $message->setIsRead(true);
    $em->flush();

    $this->addFlash('success', 'Message marqué comme lu.');

    return $this->redirectToRoute('admin_message_show', ['id' => $message->getId()]);
  }

  #[Route('/{id}/mark-unread', name: 'admin_message_mark_unread')]
  public function markUnread(ContactMessage $message, EntityManagerInterface $em): Response
  {
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

