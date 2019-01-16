<?php

namespace Application\Sonata\ClassificationBundle\Command;

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

class ImportOfferDefinitionsCommand extends ContainerAwareCommand
{

    use LockableTrait;

    const CONTEXT = 'offer_definitions';

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('classification:offer-definitions')

            ->addArgument('pathToFile', InputArgument::REQUIRED, 'Absolute file path to import definitions')

            // the short description shown while running "php bin/console list"
            ->setDescription('Import offers definitions.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to import offer definitions from example file.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
         $filePath = $input->getArgument('pathToFile');

        if(!file_exists($filePath)) {
            $output->writeln('File is not found... you must set valid XML file path');
            exit;
        }

        $file = new File($filePath);

        if($file->getMimeType() !== 'application/xml' && $file->getMimeType() !== 'text/xml') {
            $output->writeln('It is not XMl file... you must set valid XML file path');
            exit;
        }

        $this->getContainer()->get('application_sonata_offer.importer')->importDefinitions($filePath);
    }
}