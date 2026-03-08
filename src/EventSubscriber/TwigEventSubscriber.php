<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
  private Environment $twig;
  private UrlGeneratorInterface $urlGenerator;

  public function __construct(Environment $twig, UrlGeneratorInterface $urlGenerator)
  {
    $this->twig = $twig;
    $this->urlGenerator = $urlGenerator;
  }

  public static function getSubscribedEvents(): array
  {
    return [
      'kernel.request' => 'onKernelRequest',
    ];
  }

  public function onKernelRequest(RequestEvent $event): void
  {
    $request = $event->getRequest();

    // Get the current route name from the request attributes
    $currentRoute = $request->get('_route');

    // Add current_route to all Twig templates
    $this->twig->addGlobal('current_route', $currentRoute ?? '');
  }
}

