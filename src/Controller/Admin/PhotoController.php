<?php

namespace App\Controller\Admin;

use App\Entity\GalleryPhoto;
use App\Form\GalleryPhotoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/photo')]
class PhotoController extends AbstractController
{
  #[Route('/', name: 'admin_photo_index')]
  public function index(EntityManagerInterface $em): Response
  {
    $photos = $em->getRepository(GalleryPhoto::class)->findBy(
      [],
      ['displayOrder' => 'ASC']
    );

    return $this->render('admin/photo/index.html.twig', [
      'photos' => $photos,
    ]);
  }

  #[Route('/new', name: 'admin_photo_new')]
  public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
  {
    $photo = new GalleryPhoto();
    $form = $this->createForm(GalleryPhotoType::class, $photo);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // Gérer l'upload de l'image ou la sélection d'une image locale
      $imageFile = $form->get('imageFile')->getData();
      $localImage = $form->get('localImage')->getData();

      if ($localImage) {
        // Utiliser l'image locale (elle est déjà dans build/images/)
        $photo->setFilename($localImage);
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
          $photo->setFilename($newFilename);
        } catch (FileException $e) {
          $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
        }
      }

      $em->persist($photo);
      $em->flush();

      $this->addFlash('success', 'Photo ajoutée avec succès.');

      return $this->redirectToRoute('admin_photo_index');
    }

    return $this->render('admin/photo/new.html.twig', [
      'form' => $form->createView(),
    ]);
  }

  #[Route('/{id}/edit', name: 'admin_photo_edit')]
  public function edit(Request $request, GalleryPhoto $photo, EntityManagerInterface $em, SluggerInterface $slugger): Response
  {
    $originalFilename = $photo->getFilename();

    $form = $this->createForm(GalleryPhotoType::class, $photo);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // Gérer l'upload de la nouvelle image ou la sélection d'une image locale
      $imageFile = $form->get('imageFile')->getData();
      $localImage = $form->get('localImage')->getData();

      if ($localImage) {
        // Utiliser l'image locale (elle est déjà dans build/images/)
        $photo->setFilename($localImage);

        // Supprimer l'ancienne image si elle était dans uploads
        if ($originalFilename && file_exists($this->getParameter('uploads_directory') . '/' . $originalFilename)) {
          unlink($this->getParameter('uploads_directory') . '/' . $originalFilename);
        }
      } elseif ($imageFile) {
        // Upload d'une nouvelle image
        $filename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($filename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

        try {
          $imageFile->move(
            $this->getParameter('uploads_directory'),
            $newFilename
          );
          $photo->setFilename($newFilename);

          // Supprimer l'ancienne image
          if ($originalFilename) {
            $oldPath = $this->getParameter('uploads_directory') . '/' . $originalFilename;
            if (file_exists($oldPath)) {
              unlink($oldPath);
            }
          }
        } catch (FileException $e) {
          $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
        }
      } else {
        $photo->setFilename($originalFilename);
      }

      $em->flush();

      $this->addFlash('success', 'Photo modifiée avec succès.');

      return $this->redirectToRoute('admin_photo_index');
    }

    return $this->render('admin/photo/edit.html.twig', [
      'form' => $form->createView(),
      'photo' => $photo,
    ]);
  }

  #[Route('/{id}/toggle', name: 'admin_photo_toggle')]
  public function toggle(GalleryPhoto $photo, EntityManagerInterface $em): Response
  {
    $photo->setIsActive(!$photo->isIsActive());
    $em->flush();

    $status = $photo->isIsActive() ? 'activée' : 'désactivée';
    $this->addFlash('success', "Photo $status avec succès.");

    return $this->redirectToRoute('admin_photo_index');
  }

  #[Route('/{id}/delete', name: 'admin_photo_delete')]
  public function delete(Request $request, GalleryPhoto $photo, EntityManagerInterface $em): Response
  {
    $csrfToken = $request->request->get('_token');
    if ($this->isCsrfTokenValid('delete' . $photo->getId(), $csrfToken)) {
      // Supprimer l'image
      if ($photo->getFilename()) {
        $imagePath = $this->getParameter('uploads_directory') . '/' . $photo->getFilename();
        if (file_exists($imagePath)) {
          unlink($imagePath);
        }
      }

      $em->remove($photo);
      $em->flush();
      $this->addFlash('success', 'Photo supprimée avec succès.');
    }

    return $this->redirectToRoute('admin_photo_index');
  }
}

