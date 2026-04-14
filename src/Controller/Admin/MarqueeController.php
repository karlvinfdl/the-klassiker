<?php

namespace App\Controller\Admin;

use App\Entity\MarqueeItem;
use App\Form\MarqueeItemType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/marquee')]
#[IsGranted('ROLE_ADMIN')]
class MarqueeController extends AbstractController
{
  #[Route('/', name: 'admin_marquee_index')]
  public function index(EntityManagerInterface $em): Response
  {
    $items = $em->getRepository(MarqueeItem::class)->findBy([], ['displayOrder' => 'ASC']);

    return $this->render('admin/marquee/index.html.twig', ['items' => $items]);
  }

  #[Route('/new', name: 'admin_marquee_new')]
  public function new(Request $request, EntityManagerInterface $em): Response
  {
    $item = new MarqueeItem();
    $form = $this->createForm(MarqueeItemType::class, $item);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em->persist($item);
      $em->flush();
      $this->addFlash('success', 'Élément ajouté au bandeau.');

      return $this->redirectToRoute('admin_marquee_index');
    }

    return $this->render('admin/marquee/new.html.twig', ['form' => $form->createView()]);
  }

  #[Route('/{id}/edit', name: 'admin_marquee_edit')]
  public function edit(Request $request, MarqueeItem $item, EntityManagerInterface $em): Response
  {
    $form = $this->createForm(MarqueeItemType::class, $item);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em->flush();
      $this->addFlash('success', 'Élément modifié.');

      return $this->redirectToRoute('admin_marquee_index');
    }

    return $this->render('admin/marquee/edit.html.twig', ['form' => $form->createView(), 'item' => $item]);
  }

  #[Route('/{id}/delete', name: 'admin_marquee_delete')]
  public function delete(Request $request, MarqueeItem $item, EntityManagerInterface $em): Response
  {
    if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
      $em->remove($item);
      $em->flush();
      $this->addFlash('success', 'Élément supprimé.');
    }

    return $this->redirectToRoute('admin_marquee_index');
  }
}
