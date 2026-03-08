<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/users')]
class UserController extends AbstractController
{
  #[Route('/', name: 'admin_user_index')]
  public function index(EntityManagerInterface $entityManager): Response
  {
    $users = $entityManager->getRepository(User::class)->findAll();

    return $this->render('admin/user/index.html.twig', [
      'users' => $users,
    ]);
  }

  #[Route('/{id}/toggle-role', name: 'admin_user_toggle_role')]
  public function toggleRole(User $user, EntityManagerInterface $entityManager): Response
  {
    $roles = $user->getRoles();

    if (in_array('ROLE_ADMIN', $roles)) {
      // Remove admin role
      $roles = array_filter($roles, fn($role) => $role !== 'ROLE_ADMIN');
      $user->setRoles(array_values($roles));
      $this->addFlash('success', 'Le rôle ADMIN a été retiré.');
    } else {
      // Add admin role
      $roles[] = 'ROLE_ADMIN';
      $user->setRoles(array_values($roles));
      $this->addFlash('success', 'Le rôle ADMIN a été ajouté.');
    }

    $entityManager->flush();

    return $this->redirectToRoute('admin_user_index');
  }

  #[Route('/{id}/delete', name: 'admin_user_delete')]
  public function delete(User $user, EntityManagerInterface $entityManager): Response
  {
    // Prevent deleting yourself
    if ($user === $this->getUser()) {
      $this->addFlash('error', 'Vous ne pouvez pas supprimer votre propre compte.');
      return $this->redirectToRoute('admin_user_index');
    }

    $entityManager->remove($user);
    $entityManager->flush();

    $this->addFlash('success', 'Utilisateur supprimé avec succès.');

    return $this->redirectToRoute('admin_user_index');
  }
}

