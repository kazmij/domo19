<?php


namespace Application\Sonata\MainBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Block
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getBlockBySlug($slug)
    {
        $manager = $this->container->get('sonata.page.manager.block');

        return $manager->findOneBy(['alias' => $slug]);
    }
}