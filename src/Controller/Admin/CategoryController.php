<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/category')]
class CategoryController extends AbstractController
{
  #[Route('/', name: 'admin_category_index')]
  public function index(EntityManagerInterface $em): Response
  {
    $categories = $em->getRepository(Category::class)->findBy(
      [],
      ['displayOrder' => 'ASC']
    );

    return $this->render('admin/category/index.html.twig', [
      'categories' => $categories,
    ]);
  }

  #[Route('/new', name: 'admin_category_new')]
  public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
  {
    $category = new Category();
    $form = $this->createForm(CategoryType::class, $category);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // Générer le slug automatiquement si non fourni
      if (!$category->getSlug()) {
        $category->setSlug($slugger->slug($category->getName())->lower());
      }

      $em->persist($category);
      $em->flush();

      $this->addFlash('success', 'Catégorie créée avec succès.');

      return $this->redirectToRoute('admin_category_index');
    }

    return $this->render('admin/category/new.html.twig', [
      'form' => $form->createView(),
    ]);
  }

  #[Route('/{id}/edit', name: 'admin_category_edit')]
  public function edit(Request $request, Category $category, EntityManagerInterface $em, SluggerInterface $slugger): Response
  {
    $form = $this->createForm(CategoryType::class, $category);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // Mettre à jour le slug si le nom a changé
      if (!$category->getSlug()) {
        $category->setSlug($slugger->slug($category->getName())->lower());
      }

      $em->flush();

      $this->addFlash('success', 'Catégorie modifiée avec succès.');

      return $this->redirectToRoute('admin_category_index');
    }

    return $this->render('admin/category/edit.html.twig', [
      'form' => $form->createView(),
      'category' => $category,
    ]);
  }

  #[Route('/{id}/toggle', name: 'admin_category_toggle')]
  public function toggle(Category $category, EntityManagerInterface $em): Response
  {
    $category->setIsActive(!$category->isIsActive());
    $em->flush();

    $status = $category->isIsActive() ? 'activée' : 'désactivée';
    $this->addFlash('success', "Catégorie $status avec succès.");

    return $this->redirectToRoute('admin_category_index');
  }

  #[Route('/{id}/delete', name: 'admin_category_delete')]
  public function delete(Request $request, Category $category, EntityManagerInterface $em): Response
  {
    $csrfToken = $request->request->get('_token');
    if ($this->isCsrfTokenValid('delete' . $category->getId(), $csrfToken)) {
      $em->remove($category);
      $em->flush();
      $this->addFlash('success', 'Catégorie supprimée avec succès.');
    }

    return $this->redirectToRoute('admin_category_index');
  }
}

