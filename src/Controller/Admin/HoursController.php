<?php

namespace App\Controller\Admin;

use App\Entity\OpeningHours;
use App\Form\OpeningHoursType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/hours')]
class HoursController extends AbstractController
{
  #[Route('/', name: 'admin_hours_index')]
  public function index(EntityManagerInterface $em): Response
  {
    $hours = $em->getRepository(OpeningHours::class)->findBy(
      [],
      ['displayOrder' => 'ASC']
    );

    return $this->render('admin/hours/index.html.twig', [
      'hours' => $hours,
    ]);
  }

  #[Route('/new', name: 'admin_hours_new')]
  public function new(Request $request, EntityManagerInterface $em): Response
  {
    $hours = new OpeningHours();
    $form = $this->createForm(OpeningHoursType::class, $hours);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em->persist($hours);
      $em->flush();

      $this->addFlash('success', 'Horaire créé avec succès.');

      return $this->redirectToRoute('admin_hours_index');
    }

    return $this->render('admin/hours/new.html.twig', [
      'form' => $form->createView(),
    ]);
  }

  #[Route('/{id}/edit', name: 'admin_hours_edit')]
  public function edit(Request $request, OpeningHours $hours, EntityManagerInterface $em): Response
  {
    $form = $this->createForm(OpeningHoursType::class, $hours);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em->flush();

      $this->addFlash('success', 'Horaire modifié avec succès.');

      return $this->redirectToRoute('admin_hours_index');
    }

    return $this->render('admin/hours/edit.html.twig', [
      'form' => $form->createView(),
      'hours' => $hours,
    ]);
  }

  #[Route('/{id}/delete', name: 'admin_hours_delete')]
  public function delete(Request $request, OpeningHours $hours, EntityManagerInterface $em): Response
  {
    $csrfToken = $request->request->get('_token');
    if ($this->isCsrfTokenValid('delete' . $hours->getId(), $csrfToken)) {
      $em->remove($hours);
      $em->flush();
      $this->addFlash('success', 'Horaire supprimé avec succès.');
    }

    return $this->redirectToRoute('admin_hours_index');
  }
}

