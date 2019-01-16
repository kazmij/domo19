<?php

namespace Application\Sonata\OfferBundle\Command;

use Application\Sonata\MediaBundle\Entity\Media;
use Application\Sonata\OfferBundle\Entity\OfferImport;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Application\Sonata\ClassificationBundle\Entity\Category;
use Symfony\Component\Finder\Finder;

class UpdateSearchCommand extends ContainerAwareCommand
{

    use LockableTrait;

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('update:search')
            // the short description shown while running "php bin/console list"
            ->setDescription('Update search phrases.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you update search phrases for offers');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(0);
        ini_set('memory_limit', -1);

        $output->writeln('START');

        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();

        $offers = $container->get('application_sonata_offer.manager.offer')->findAll();
        foreach($offers as $offer) {
            $offer->setUpdates((int) $offer->getUpdates() + 1);
        }
        $em->flush();


        $output->writeln('END');
    }
}