<?php

namespace App\Controller\Admin;

use App\Entity\Dish;
use App\Form\DishType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/dish')]
#[IsGranted('ROLE_ADMIN')]
class DishController extends AbstractController
{
  #[Route('/', name: 'admin_dish_index')]
  public function index(EntityManagerInterface $em): Response
  {
    $dishes = $em->getRepository(Dish::class)->findBy(
      [],
      ['displayOrder' => 'ASC']
    );

    return $this->render('admin/dish/index.html.twig', [
      'dishes' => $dishes,
    ]);
  }

  #[Route('/new', name: 'admin_dish_new')]
  public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
  {
    $dish = new Dish();
    $form = $this->createForm(DishType::class, $dish);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // Gérer l'upload de l'image ou la sélection d'une image locale
      $imageFile = $form->get('imageFile')->getData();
      $localImage = $form->get('localImage')->getData();

      if ($localImage) {
        // Utiliser l'image locale (elle est déjà dans build/images/)
        $dish->setImage($localImage);
      } elseif ($imageFile) {
        // Upload d'une nouvelle image
        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

        try {
          $imageFile->move(
            $this->getParameter('uploads_directory'),
            $newFilename
          );
          $dish->setImage($newFilename);
        } catch (FileException $e) {
          $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
        }
      }

      $em->persist($dish);
      $em->flush();

      $this->addFlash('success', 'Plat créé avec succès.');

      return $this->redirectToRoute('admin_dish_index');
    }

    // Récupérer les catégories pour le formulaire
    $categories = $em->getRepository(\App\Entity\Category::class)->findBy(
      ['isActive' => true],
      ['displayOrder' => 'ASC']
    );

    return $this->render('admin/dish/new.html.twig', [
      'form' => $form->createView(),
      'categories' => $categories,
    ]);
  }

  #[Route('/{id}/edit', name: 'admin_dish_edit')]
  public function edit(Request $request, Dish $dish, EntityManagerInterface $em, SluggerInterface $slugger): Response
  {
    $originalImage = $dish->getImage();

    $form = $this->createForm(DishType::class, $dish);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // Gérer l'upload de la nouvelle image ou la sélection d'une image locale
      $imageFile = $form->get('imageFile')->getData();
      $localImage = $form->get('localImage')->getData();

      if ($localImage) {
        // Utiliser l'image locale (elle est déjà dans build/images/)
        $dish->setImage($localImage);
      } elseif ($imageFile) {
        // Upload d'une nouvelle image
        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

        try {
          $imageFile->move(
            $this->getParameter('uploads_directory'),
            $newFilename
          );
          $dish->setImage($newFilename);

          // Supprimer l'ancienne image si elle était dans uploads
          if ($originalImage) {
            $this->safeUnlink($this->getParameter('uploads_directory'), $originalImage);
          }
        } catch (FileException $e) {
          $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
        }
      } else {
        // Garder l'image originale
        $dish->setImage($originalImage);
      }

      $em->flush();

      $this->addFlash('success', 'Plat modifié avec succès.');

      return $this->redirectToRoute('admin_dish_index');
    }

    $categories = $em->getRepository(\App\Entity\Category::class)->findBy(
      ['isActive' => true],
      ['displayOrder' => 'ASC']
    );

    return $this->render('admin/dish/edit.html.twig', [
      'form' => $form->createView(),
      'dish' => $dish,
      'categories' => $categories,
    ]);
  }

  #[Route('/{id}/toggle', name: 'admin_dish_toggle')]
  public function toggle(Dish $dish, EntityManagerInterface $em): Response
  {
    $dish->setIsActive(!$dish->isIsActive());
    $em->flush();

    $status = $dish->isIsActive() ? 'activé' : 'désactivé';
    $this->addFlash('success', "Plat $status avec succès.");

    return $this->redirectToRoute('admin_dish_index');
  }

  #[Route('/{id}/feature', name: 'admin_dish_feature')]
  public function feature(Dish $dish, EntityManagerInterface $em): Response
  {
    $dish->setIsFeatured(!$dish->isIsFeatured());
    $em->flush();

    $status = $dish->isIsFeatured() ? 'mis en avant' : 'retiré des favoris';
    $this->addFlash('success', "Plat $status avec succès.");

    return $this->redirectToRoute('admin_dish_index');
  }

  #[Route('/{id}/delete', name: 'admin_dish_delete')]
  public function delete(Request $request, Dish $dish, EntityManagerInterface $em): Response
  {
    $csrfToken = $request->request->get('_token');
    if ($this->isCsrfTokenValid('delete' . $dish->getId(), $csrfToken)) {
      if ($dish->getImage()) {
        $this->safeUnlink($this->getParameter('uploads_directory'), $dish->getImage());
      }

      $em->remove($dish);
      $em->flush();
      $this->addFlash('success', 'Plat supprimé avec succès.');
    }

    return $this->redirectToRoute('admin_dish_index');
  }

  private function safeUnlink(string $directory, string $filename): void
  {
    $realDir = realpath($directory);
    if ($realDir === false) {
      return;
    }
    $fullPath = $realDir . DIRECTORY_SEPARATOR . basename($filename);
    $realPath = realpath($fullPath);
    if ($realPath !== false && str_starts_with($realPath, $realDir . DIRECTORY_SEPARATOR) && is_file($realPath)) {
      unlink($realPath);
    }
  }
}

