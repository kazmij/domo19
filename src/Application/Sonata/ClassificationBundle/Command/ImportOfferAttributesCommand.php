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

class ImportOfferAttributesCommand extends ContainerAwareCommand
{

    use LockableTrait;

    const CONTEXT = 'offer';

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('classification:offer-attributes')

            ->addArgument('pathToFile', InputArgument::REQUIRED, 'Absolute file path to import attributes')

            // the short description shown while running "php bin/console list"
            ->setDescription('Import offers attributes.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to import offer attributes from example file.')
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

        $xml = simplexml_load_file($filePath);
        $container = $this->getContainer();
        $categoryManager = $container->get('sonata.classification.manager.category');
        $contextManager = $container->get('sonata.classification.manager.context');
        $context = $contextManager->find(self::CONTEXT);
        $rootContextCategories = $categoryManager->getRootCategoriesForContext($context);
        $em = $container->get('doctrine.orm.default_entity_manager');

        if($rootContextCategories) {
            $rootCategory = $rootContextCategories[0];
        } else {
            $output->writeln('Root context category is not set!');
            exit;
        }

        if(isset($xml->offer[0])) {
            $position = 0;
            foreach ($xml->offer[0]->children() as $attributeName => $child) {
                $createNew = false;
                $category = $categoryManager->findOneBy([
                    'slug' => Urlizer::urlize($attributeName, '-'),
                    'context' => self::CONTEXT
                ]);
                if(!$category) {
                    $category = new Category();
                    $createNew = true;
                }
                $category->setName($attributeName);
                $category->setSlug(Urlizer::urlize($attributeName, '-'));
                $category->setParent($rootCategory);
                $category->setContext($context);
                $category->setPosition($position);
                $category->setEnabled(true);

                if($child->attributes()) {
                    if(isset($child->attributes()->dictionary)) {
                        $dictionaryName = $child->attributes()->dictionary;
                        $category->setCustomName($dictionaryName);
                    }
                }

                if($createNew) {
                    $em->persist($category);
                }

                $em->flush();

                $position++;

                $output->writeln('Attribute ' . $attributeName . ' has been imported.');
            }

            $output->writeln('All attributes have been imported.');

        } else {
            $output->writeln('It is not valid XMl file... you must provide valid XML file');
            exit;
        }
    }
}