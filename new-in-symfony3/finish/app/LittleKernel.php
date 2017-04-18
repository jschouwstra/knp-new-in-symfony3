<?php

use Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class LittleKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new SensioFrameworkExtraBundle()
        ];
    }

    public function helloSymfony($version)
    {
        return new Response('Hi Symfony version '.$version);
    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $routes->add('/hello/symfony/{version}', 'kernel:helloSymfony');

        $routes->import(__DIR__.'/../src/AppBundle/Controller', '/', 'annotation');
    }

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        $c->loadFromExtension('framework', [
            'secret' => 'micr0',
            'templating' => ['engines' => ['twig']],
            'assets' => []
        ]);
    }
}
