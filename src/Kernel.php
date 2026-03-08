<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
  use MicroKernelTrait;

  public function registerBundles(): iterable
  {
    $contents = require $this->getProjectDir() . '/config/bundles.php';
    foreach ($contents as $class => $envs) {
      if ($envs[$this->environment] ?? $envs['all'] ?? false) {
        yield new $class();
      }
    }
  }

  public function getProjectDir(): string
  {
    return \dirname(__DIR__);
  }

  protected function configureContainer(\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $container): void
  {
    $container->import('../config/{packages}/*.yaml');

    if (is_file($file = \dirname(__DIR__) . '/config/services.yaml')) {
      $container->import($file);
    }
  }

  protected function configureRoutes(\Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator $routes): void
  {
    $routes->import('../config/{routes}/*.yaml');

    if (is_file($file = \dirname(__DIR__) . '/config/routes.yaml')) {
      $routes->import($file);
    }
  }
}

