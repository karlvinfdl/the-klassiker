<?php

namespace App\Controller\Admin;

use App\Entity\Supplement;
use App\Form\SupplementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/supplements')]
class SupplementController extends AbstractController
{
  #[Route('/', name: 'admin_supplement_index')]
  public function index(EntityManagerInterface $em): Response
  {
    $supplements = $em->getRepository(Supplement::class)->findBy([], ['displayOrder' => 'ASC']);
    return $this->render('admin/supplement/index.html.twig', ['supplements' => $supplements]);
  }

  #[Route('/new', name: 'admin_supplement_new')]
  public function new(Request $request, EntityManagerInterface $em): Response
  {
    $supplement = new Supplement();
    $form = $this->createForm(SupplementType::class, $supplement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em->persist($supplement);
      $em->flush();
      $this->addFlash('success', 'Supplément ajouté.');
      return $this->redirectToRoute('admin_supplement_index');
    }

    return $this->render('admin/supplement/new.html.twig', ['form' => $form->createView()]);
  }

  #[Route('/{id}/edit', name: 'admin_supplement_edit')]
  public function edit(Request $request, Supplement $supplement, EntityManagerInterface $em): Response
  {
    $form = $this->createForm(SupplementType::class, $supplement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em->flush();
      $this->addFlash('success', 'Supplément modifié.');
      return $this->redirectToRoute('admin_supplement_index');
    }

    return $this->render('admin/supplement/edit.html.twig', ['form' => $form->createView(), 'supplement' => $supplement]);
  }

  #[Route('/{id}/delete', name: 'admin_supplement_delete')]
  public function delete(Request $request, Supplement $supplement, EntityManagerInterface $em): Response
  {
    if ($this->isCsrfTokenValid('delete' . $supplement->getId(), $request->request->get('_token'))) {
      $em->remove($supplement);
      $em->flush();
      $this->addFlash('success', 'Supplément supprimé.');
    }
    return $this->redirectToRoute('admin_supplement_index');
  }
}
