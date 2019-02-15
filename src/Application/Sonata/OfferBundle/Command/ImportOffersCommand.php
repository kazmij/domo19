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

class ImportOffersCommand extends ContainerAwareCommand
{

    use LockableTrait;

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('import:offers')
            // the short description shown while running "php bin/console list"
            ->setDescription('Import offers.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to import offers from XML file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(0);
        ini_set('memory_limit', -1);

        $output->writeln('START');

        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();
        $importer = $container->get('application_sonata_offer.importer');
        $importManager = $container->get('application_sonata_offer.manager.offer_import');
        $imports = $importManager->findBy([
            'status' => 'new'
        ]);

        $output->writeln('Found ' . count($imports) . ' files to import.');

        foreach ($imports as $importObject) {
            $output->writeln('Import #' . $importObject->getId());
            try {
                $importer->import($importObject);
                $importObject->setStatus('completed');
                $output->writeln('Import #' . $importObject->getId() . ' completed');
                $output->writeln('');
                $output->writeln('###########################################################');
            } catch(\Exception $e) {
                if($importObject->getErrorsCount() >= OfferImport::MAX_ERRORS_COUNT) {
                    $importObject->setStatus('error');
                } else {
                    $importObject->setErrorsCount((int) $importObject->getErrorsCount() + 1);
                }
                $importObject->setError($e->__toString());
                $output->writeln('Import #' . $importObject->getId() . ' error: ' . $e->getMessage());
                $output->writeln('');
                $output->writeln('###########################################################');
            }

            $em->flush();
        }

        # Add new files to queue
        $offersPath = $container->hasParameter('offers_files_path') ? $container->getParameter('offers_files_path') : false;
        if ($offersPath) {
            $qb = $em->createQueryBuilder();
            $admin = $em->getRepository('\Application\Sonata\UserBundle\Entity\User')
                ->createQueryBuilder('u')
                ->where($qb->expr()->like('u.roles', $qb->expr()->literal('%ROLE_SUPER_ADMIN%')))
                ->getQuery()
                ->getOneOrNullResult();

            if ($admin) {
                $finder = new Finder();
                $files = $finder->in($offersPath)->files()->name('/\.(xml|zip)$/')->size('>0');
                /* @var $file \Symfony\Component\Finder\SplFileInfo */
                foreach ($files as $file) {
                    $fileName = $file->getFilename();
                    $imports = $importManager->findByFileName($fileName);
                    if (!$imports) {
                        $media = new Media();
                        $media->setName($fileName);
                        $media->setEnabled(true);
                        $media->setBinaryContent($file->getRealPath());
                        $media->setProviderName('sonata.media.provider.file');
                        $media->setContext('offer');
                        $em->persist($media);
                        $import = new OfferImport();
                        $import->setStatus('new');
                        $import->setImportFile($media);
                        $import->setAssignedTo($admin);
                        $import->setCreatedBy($admin);
                        $em->persist($import);

                        $em->flush();
                        $output->writeln('File ' . $offersPath . $file . ' added to queue!');
                    }
                }
            }
        }

        $output->writeln('END');
    }
}