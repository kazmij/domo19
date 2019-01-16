<?php

namespace Application\Sonata\OfferBundle\Service;


use Application\Sonata\ClassificationBundle\Entity\Category;
use Application\Sonata\MediaBundle\Entity\Gallery;
use Application\Sonata\MediaBundle\Entity\GalleryHasMedia;
use Application\Sonata\MediaBundle\Entity\Media;
use Application\Sonata\OfferBundle\Entity\Offer;
use Application\Sonata\OfferBundle\Entity\OfferAttribute;
use Application\Sonata\OfferBundle\Entity\OfferAttributeManager;
use Application\Sonata\OfferBundle\Entity\OfferImport;
use Application\Sonata\OfferBundle\Entity\OfferManager;
use Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Gedmo\Sluggable\Util\Urlizer;
use Sonata\ClassificationBundle\Entity\CategoryManager;
use Sonata\ClassificationBundle\Entity\ContextManager;
use Sonata\MediaBundle\Entity\MediaManager;
use Sonata\MediaBundle\Provider\FileProvider;
use Sonata\UserBundle\Entity\UserManager;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Process\Exception\InvalidArgumentException;

/**
 * Class ImportOffers
 * @package Application\Sonata\OfferBundle\Service
 */
class ImportOffers
{
    const CONTEXT_DEFINITIONS = 'offer_definitions';
    const CONTEXT_ATTRIBUTES = 'offer';
    const DEFAULT_AUTHOR_ID = 2;
    const PICTURES_ATTRIBUTE_NAME = 'pictures';
    const OFFER_MEDIA_CONTEXT = 'offer';

    /**
     * @var CategoryManager
     */
    private $categoryManager;

    /**
     * @var ContextManager
     */
    private $contextManager;

    /**
     * @var OfferManager
     */
    private $offerManager;

    /**
     * @var OfferAttributeManager
     */
    private $offerAttributeManager;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var MediaManager
     */
    private $mediaManager;

    /**
     * @var EntityManager
     */
    private $em;

    use ContainerAwareTrait;

    /**
     * Custom serrvice constructor
     */
    public function _construct()
    {
        $this->categoryManager = $this->container->get('sonata.classification.manager.category');
        $this->contextManager = $this->container->get('sonata.classification.manager.context');
        $this->offerManager = $this->container->get('application_sonata_offer.manager.offer');
        $this->mediaManager = $this->container->get('sonata.media.manager.media');
        $this->offerAttributeManager = $this->container->get('application_sonata_offer.manager.offer_attribute');
        $this->userManager = $this->container->get('sonata.user.manager.user');
        $this->em = $this->container->get('doctrine.orm.default_entity_manager');
    }

    /**
     * Import definitions and offers
     *
     * @param $filePath
     * @throws \Exception
     */
    public function import(OfferImport $offerImport): void
    {

        if (!$offerImport->getImportFile()) {
            throw new InvalidArgumentException('File is not set in import!');
        }
        $media = $offerImport->getImportFile();
        /* @var $provider FileProvider */
        $provider = $this->container->get($media->getProviderName());
        $filesystem = $this->container->get('sonata.media.adapter.filesystem.local');
        $filePath = $filesystem->getDirectory() . '/' . $provider->generatePath($media) . '/' . $media->getProviderReference();

        if (!file_exists($filePath)) {
            throw new FileNotFoundException();
        }

        $file = new File($filePath);

        if ($file->getMimeType() !== 'application/zip' && $file->getMimeType() !== 'text/zip') {
            throw new InvalidArgumentException('Invalid file mime type. Must be application/zip instead of ' . $file->getMimeType());
        }

        $importFiles = $this->unzipAndValidateFile($filePath);

        if ($importFiles['definition']) {
            $this->importDefinitions($importFiles['definition']);
        }

        if (count($importFiles['offers'])) {
            foreach ($importFiles['offers'] as $offerFilePath) {
                $this->importOffers($offerImport, $offerFilePath, $importFiles['images']);
            }
        }
    }

    /**
     * Import offers from XML file
     *
     * @param $filePath
     * @param array $images
     * @param User|null $assignedTo
     */
    public function importOffers(OfferImport $offerImport, $filePath, array $images, User $assignedTo = null)
    {
        if (!file_exists($filePath)) {
            throw new InvalidArgumentException('Offers file is not exist!');
        }

        $file = new File($filePath);

        if ($file->getMimeType() !== 'application/xml' && $file->getMimeType() !== 'text/xml') {
            throw new InvalidArgumentException('Offers file has invalid format!');
        }

        $output = new ConsoleOutput();
        $xml = simplexml_load_file($filePath);
        $container = $this->container;
        $context = $this->contextManager->find(self::CONTEXT_ATTRIBUTES);
        $rootContextCategories = $this->categoryManager->getRootCategoriesForContext($context);
        $em = $container->get('doctrine.orm.default_entity_manager');

        if ($rootContextCategories) {
            $rootCategory = $rootContextCategories[0];
        } else {
            throw new InvalidArgumentException('Root context category is not set!');
        }

        $authorUser = $container->get('security.token_storage')->getToken() ? $container->get('security.token_storage')->getToken()->getUser() : null;
        if (!$authorUser) {
            $authorUser = $this->userManager->find(self::DEFAULT_AUTHOR_ID);
        }

        foreach ($xml->offer as $offer) {
            $offerObject = null;
            if (isset($offer->id)) {
                $offerObject = $this->offerManager->findOneBy(['externalId' => (string)$offer->id]);
            }

            if (!$offerObject) {
                $offerObject = new Offer();
                if (isset($offer->id)) {
                    $offerObject->setExternalId((string)$offer->id);
                }
            }
            //set offer to import relation
            $offerObject->addImport($offerImport);
            $offerImport->addOffer($offerObject);

            $offerObject->setUpdatedBy($authorUser);
            $offerObject->setAssignedTo($offerImport->getAssignedTo() ? $offerImport->getAssignedTo() : $authorUser);

            if (!$offerObject->getId()) {
                $offerObject->setCreatedBy($offerImport->getCreatedBy() ? $offerImport->getCreatedBy() : $authorUser);
                $em->persist($offerObject);
            }

            foreach ($offer->children() as $attributeName => $child) {
                if (!strlen((string)$child) && $attributeName !== self::PICTURES_ATTRIBUTE_NAME) { //empty attribute - remove if exist and skip it
                    if ($offerObject->getId()) {
                        $categoryAttribute = $this->getCategoryAttribute($attributeName, $child);
                        $offerAttributes = $offerObject->getAttributes()->filter(function ($entry) use ($categoryAttribute) {
                            return $entry->getAttribute() ? ($entry->getAttribute()->getId() == $categoryAttribute->getId()) : false;
                        });

                        if ($offerAttributes->count() > 0 && $offerAttributes->first()) {
                            $this->em->remove($offerAttributes->first());
                            $this->em->flush();
                        }
                    }
                    continue;
                }

                if($attributeName == OfferAttribute::OFFER_ACTION && (string) $child == 'delete') {
                    if($offerObject->getId()) {
                        $this->em->remove($offerObject);
                    }
                    continue 2;
                }

                $categoryAttribute = $this->getCategoryAttribute($attributeName, $child);
                $offerAttribute = null;
                if ($offerObject->getId()) {
                    $offerAttribute = $this->offerAttributeManager->findOneBy([
                        'offer' => $offerObject->getId(),
                        'attribute' => $categoryAttribute->getId()
                    ]);
                }
                if (!$offerAttribute) {
                    $offerAttribute = new OfferAttribute();
                    $offerAttribute->setAttribute($categoryAttribute);
                    $offerAttribute->setOffer($offerObject);
                    $offerObject->addAttribute($offerAttribute);
                }

                if ($attributeName == self::PICTURES_ATTRIBUTE_NAME) {
                    $pictures = (array)$child;
                    $pictures = isset($pictures['picture']) ? $pictures['picture'] : [];
                    if (!is_array($pictures)) {
                        $pictures = array($pictures);
                    }
                    $picturesKeys = array_flip($pictures);

                    if ($pictures) {
                        if ($offerAttribute->getGallery()) {
                            $gallery = $offerAttribute->getGallery();
                            $galleryHasMedias = $gallery->getGalleryHasMedias();

                            foreach ($galleryHasMedias as $galleryHasMedia) {
                                $mediaImage = $galleryHasMedia->getMedia();

                                if (!in_array($mediaImage->getName(), array_keys($images))) {
                                    if($galleryHasMedia->getMedia()) {
                                        $this->em->remove($galleryHasMedia->getMedia());
                                    }
                                    $this->em->remove($galleryHasMedia);
                                } else {
                                    if (isset($picturesKeys[$mediaImage->getName()])) {
                                        unset($picturesKeys[$mediaImage->getName()]);
                                    }
                                }
                            }

                            foreach ($images as $imageKey => $image) {
                                if (isset($picturesKeys[$imageKey])) {
                                    $mediaImage = $this->mediaManager->findOneBy([
                                        'name' => $imageKey,
                                        'context' => $context->getId(),
                                        'providerName' => 'sonata.media.provider.image'
                                    ]);
                                    if (!$mediaImage) {
                                        $mediaImage = new Media();
                                        $mediaImage->setEnabled(true);
                                        $mediaImage->setName($imageKey);
                                        $mediaImage->setProviderName('sonata.media.provider.image');
                                        $mediaImage->setBinaryContent($image);
                                        $mediaImage->setContext($context);
                                        $mediaImage->setCategory($rootCategory);
                                    }
                                    $galleryHasMedia = new GalleryHasMedia();
                                    $galleryHasMedia->setEnabled(true);
                                    $galleryHasMedia->setGallery($gallery);
                                    $galleryHasMedia->setPosition(count($gallery->getGalleryHasMedias()));
                                    $galleryHasMedia->setMedia($mediaImage);
                                    $mediaImage->addGalleryHasMedia($galleryHasMedia);
                                    $gallery->addGalleryHasMedia($galleryHasMedia);

                                    $this->em->persist($mediaImage);
                                    $this->em->persist($galleryHasMedia);
                                }
                            }
                        } else {
                            $gallery = new Gallery();
                            $gallery->setEnabled(true);
                            $gallery->setContext(self::OFFER_MEDIA_CONTEXT);
                            $gallery->setName('Offer ' . $offerObject->getExternalId());

                            foreach ($pictures as $positionKey => $picture) {
                                if (isset($images[$picture])) {
                                    $mediaImage = $this->mediaManager->findOneBy([
                                        'name' => $picture,
                                        'context' => $context->getId(),
                                        'providerName' => 'sonata.media.provider.image'
                                    ]);

                                    if (!$mediaImage) {
                                        $mediaImage = new Media();
                                        $mediaImage->setEnabled(true);
                                        $mediaImage->setName($picture);
                                        $mediaImage->setContext($context);
                                        $mediaImage->setCategory($rootCategory);
                                        $mediaImage->setProviderName('sonata.media.provider.image');
                                        $mediaImage->setBinaryContent($images[$picture]);
                                    }
                                    $galleryHasMedia = new GalleryHasMedia();
                                    $galleryHasMedia->setEnabled(true);
                                    $galleryHasMedia->setGallery($gallery);
                                    $galleryHasMedia->setPosition($positionKey);
                                    $galleryHasMedia->setMedia($mediaImage);
                                    $mediaImage->addGalleryHasMedia($galleryHasMedia);
                                    $gallery->addGalleryHasMedia($galleryHasMedia);

                                    $this->em->persist($mediaImage);
                                    $this->em->persist($galleryHasMedia);
                                }
                            }
                            $this->em->persist($gallery);
                        }

                        $offerAttribute->setGallery($gallery);
                    }
                } else {
                    $attributeValue = (string)$child;
                    if ($child->attributes()) {
                        if (isset($child->attributes()->dictionary)) {
                            $dictionary = (string)$child->attributes()->dictionary;

                            $categoryAttributeValue = $this->getCategoryAttributeValue($dictionary, $attributeValue);
                            if ($categoryAttributeValue) {
                                $offerAttribute->setAttributeValue($categoryAttributeValue);
                            }
                        }
                    }
                    if (!$offerAttribute->getAttributeValue()) {
                        if(is_numeric($attributeValue)) {
                            $offerAttribute->setValueNumeric($attributeValue);
                            $offerAttribute->setValue(null);
                        } else {
                            $offerAttribute->setValue($attributeValue);
                            $offerAttribute->setValueNumeric(null);
                        }
                    }
                }

                if (!$offerAttribute->getId()) {
                    $em->persist($offerAttribute);
                }
            }

            $em->flush();
        }
    }

    /**
     * Get category attribute value or create this category and its value object
     *
     * @param $dictionary
     * @param $attributeValue
     * @param null $customKey
     * @return Category|mixed
     */
    public function getCategoryAttributeValue($dictionary, $attributeValue, $customKey = null)
    {
        $categoryValue = $this->categoryManager->findOneBy([
            'slug' => Urlizer::urlize($dictionary, '-'),
            'context' => self::CONTEXT_DEFINITIONS
        ]);

        $context = $this->contextManager->find(self::CONTEXT_DEFINITIONS);

        if (!$categoryValue) {
            $categoryValue = new Category();
            $rootContextCategories = $this->categoryManager->getRootCategoriesForContext($context);

            $rootCategory = null;
            if ($rootContextCategories) {
                $rootCategory = $rootContextCategories[0];
                $categoryValue->setParent($rootCategory);
            }

            $categoryValue->setName($dictionary);
            $categoryValue->setSlug(Urlizer::urlize($dictionary, '-'));
            $categoryValue->setParent($rootCategory);
            $categoryValue->setContext($context);
            $categoryValue->setPosition($rootCategory ? count($rootCategory->getChildren()) : 1);
            $categoryValue->setEnabled(true);

            $this->em->persist($categoryValue);
            $this->em->flush($categoryValue);
        }
        $contextDefinition = self::CONTEXT_DEFINITIONS;
        $values = $categoryValue->getChildren()->filter(function ($entry) use ($dictionary, $contextDefinition, $attributeValue) {
            return $entry->getCustomKey() == $attributeValue;
        });

        if (!$values->count()
        ) {
            $value = new Category();
            $value->setName($attributeValue);
            $value->setParent($categoryValue);
            $value->setContext($context);
            $value->setPosition(count($categoryValue->getChildren()));
            $value->setEnabled(true);
            $value->setCustomName(Urlizer::urlize($dictionary, '-'));
            if ($customKey) {
                $value->setCustomKey($customKey);
            }
            $this->em->persist($value);
            $this->em->flush($value);
        } else {
            $value = $values->first();
        }

        return $value;

    }

    /**
     * Get categoty attribute
     *
     * @param $attributeName
     * @param $node
     * @return Category|null|object
     */
    public function getCategoryAttribute($attributeName, $node)
    {
        $categoryAttribute = $this->categoryManager->findOneBy([
            'slug' => Urlizer::urlize($attributeName, '-'),
            'context' => self::CONTEXT_ATTRIBUTES
        ]);

        if (!$categoryAttribute) {
            $context = $this->contextManager->find(self::CONTEXT_ATTRIBUTES);
            $rootContextCategories = $this->categoryManager->getRootCategoriesForContext($context);
            $categoryAttribute = new Category();
            $categoryAttribute->setName($attributeName);
            $categoryAttribute->setSlug(Urlizer::urlize($attributeName, '-'));
            if ($rootContextCategories) {
                $rootCategory = $rootContextCategories[0];
                $categoryAttribute->setParent($rootCategory);
            }
            $categoryAttribute->setContext($context);
            $categoryAttribute->setPosition(isset($rootCategory) ? count($rootCategory->getChildren()) : 1);
            $categoryAttribute->setEnabled(true);

            if ($node->attributes()) {
                if (isset($node->attributes()->dictionary)) {
                    $dictionaryName = $node->attributes()->dictionary;
                    $categoryAttribute->setCustomName($dictionaryName);
                }
            }

            $this->em->persist($categoryAttribute);
            $this->em->flush($categoryAttribute);
        }


        return $categoryAttribute;
    }

    /**
     * Import dictionary definitions
     *
     * @param $filePath
     * @return bool
     */
    public function importDefinitions($filePath)
    {
        if (!file_exists($filePath)) {
            throw new InvalidArgumentException('Definition file is not exist!');
        }

        $file = new File($filePath);

        if ($file->getMimeType() !== 'application/xml' && $file->getMimeType() !== 'text/xml') {
            throw new InvalidArgumentException('Definition file has invalid format!');
        }

        $xml = simplexml_load_file($filePath);
        $container = $this->container;
        $context = $this->contextManager->find(self::CONTEXT_DEFINITIONS);
        $rootContextCategories = $this->categoryManager->getRootCategoriesForContext($context);
        $em = $container->get('doctrine.orm.default_entity_manager');

        $output = new ConsoleOutput();

        if ($rootContextCategories) {
            $rootCategory = $rootContextCategories[0];
        } else {
            throw new InvalidArgumentException('Root context category is not set!');
        }

        if ($xml->children()) {
            $position = 0;
            foreach ($xml->children() as $attributeName => $child) {
                $createNew = false;
                $category = $this->categoryManager->findOneBy([
                    'slug' => Urlizer::urlize($attributeName, '-'),
                    'context' => self::CONTEXT_DEFINITIONS
                ]);
                if (!$category) {
                    $category = new Category();
                    $createNew = true;
                }
                $category->setName($attributeName);
                $category->setSlug(Urlizer::urlize($attributeName, '-'));
                $category->setParent($rootCategory);
                $category->setContext($context);
                $category->setPosition($position);
                $category->setEnabled(true);

                if ($createNew) {
                    $em->persist($category);
                }

                if ($child->children()) {
                    $childPosition = 0;
                    foreach ($child->children() as $valueName => $value) {
                        $createNewValue = false;
                        $customKey = null;
                        $categoryValue = null;
                        if ($value->attributes()) {
                            if (isset($value->attributes()->key)) {
                                $customKey = $value->attributes()->key;
                                $categoryValue = $this->categoryManager->findOneBy([
                                    'name' => (string)$value,
                                    'parent' => $category->getId(),
                                    'customName' => Urlizer::urlize($attributeName, '-'),
                                    'customKey' => $customKey,
                                    'context' => self::CONTEXT_DEFINITIONS
                                ]);
                            }
                        }

                        if (!$categoryValue) {
                            $categoryValue = $this->categoryManager->findOneBy([
                                'name' => (string)$value,
                                'parent' => $category->getId(),
                                'customName' => Urlizer::urlize($attributeName, '-'),
                                'context' => self::CONTEXT_DEFINITIONS
                            ]);
                        }

                        if (!$categoryValue) {
                            $categoryValue = new Category();
                            $createNewValue = true;
                        }

                        $categoryValue->setName($value);
                        $categoryValue->setParent($category);
                        $categoryValue->setContext($context);
                        $categoryValue->setPosition($childPosition);
                        $categoryValue->setEnabled(true);
                        $categoryValue->setCustomName(Urlizer::urlize($attributeName, '-'));
                        if ($customKey) {
                            $categoryValue->setCustomKey($customKey);
                        }


                        if ($createNewValue) {
                            $em->persist($categoryValue);
                        }
                    }
                }

                $em->flush();

                $position++;

                $output->writeln('Definitions ' . $attributeName . ' has been imported.');
            }

            $output->writeln('All attributes definitions have been imported or updated.');

        } else {
            $output->writeln('It is not valid XMl file... you must provide valid XML file');
        }

        return true;
    }

    /**
     * Unzip file to temp location
     *
     * @param $filePath
     * @return array
     * @throws \Exception
     */
    private function unzipAndValidateFile($filePath)
    {
        $zip = new \ZipArchive();
        chmod($filePath, 0775);
        $zip->open($filePath);

        $tmpPath = sys_get_temp_dir() . '/' . hash('sha256', basename($filePath) . microtime());

        $extracted = $zip->extractTo($tmpPath);

        if ($extracted) {
            $results = [
                'definition' => null,
                'offers' => [],
                'images' => []
            ];
            if (is_dir($tmpPath)) {
                mb_internal_encoding("UTF-8");
                $files = glob($tmpPath . '/*');
                foreach ($files as $file) {
                    $fileObject = new File($file);
                    $mimeType = $fileObject->getMimeType();

                    if (preg_match('/^image\//im', $mimeType)) {
                        $results['images'][basename($file)] = $file;
                    } elseif ($mimeType === 'application/xml' || $mimeType === 'text/xml') {
                        if ($fileObject->getBasename() == 'definitions.xml') {
                            $results['definition'] = $file;
                        } else {
                            $results['offers'][] = $file;
                        }
                    }

                }
            }

            if (!count($results['offers'])) {
                throw new \Exception('There are not any offers in ZIP file!!');
            }

            return $results;
        } else {
            throw new \Exception('Could not unzip this file!');
        }

    }
}