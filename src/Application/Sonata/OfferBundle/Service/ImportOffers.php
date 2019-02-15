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
    const OFFER_MEDIA_CONTEXT = 'offer';

    public static $CATEGORIES_MAP = [
        'parterowe' => [
            OfferAttribute::PODDASZE_ATTRIBUTE_KEY => 0,
            OfferAttribute::PIETRO_ATTRIBUTE_KEY => 0
        ],
        'z-poddaszem' => [
            OfferAttribute::PODDASZE_ATTRIBUTE_KEY => 1
        ],
        'pietrowe' => [
            OfferAttribute::PIETRO_ATTRIBUTE_KEY => [
                'condition' => '>',
                'value' => 0
            ]
        ],
        'male' => [
            OfferAttribute::POW_UZYTKOWA_ATTRIBUTE_KEY => [
                'condition' => '<=',
                'value' => 100
            ]
        ],
        'srednie' => [
            OfferAttribute::POW_UZYTKOWA_ATTRIBUTE_KEY => [
                'condition' => 'between',
                'values' => [
                    0 => 100,
                    1 => 150
                ]
            ]
        ],
        'duze' => [
            OfferAttribute::POW_UZYTKOWA_ATTRIBUTE_KEY => [
                'condition' => 'between',
                'values' => [
                    0 => 150,
                    1 => 200
                ]
            ]
        ],
        'wille' => [
            OfferAttribute::POW_UZYTKOWA_ATTRIBUTE_KEY => [
                'condition' => '>',
                'value' => 200
            ]
        ],
        'tradycyjne' => [
            OfferAttribute::OPIS_PROJEKTU_ATTRIBUTE_KEY => [
                'condition' => 'match',
                'value' => 'tradycyjny'
            ]
        ],
        'nowoczesne' => [
            OfferAttribute::OPIS_PROJEKTU_ATTRIBUTE_KEY => [
                'condition' => 'match',
                'value' => 'nowoczesny'
            ]
        ],
        'drewniane' => [
            'condition' => 'or',
            OfferAttribute::TECHNOLOGIA_ATTRIBUTE_KEY => [
                'condition' => 'match',
                'value' => 'drewniany'
            ],
            OfferAttribute::OPIS_TECHNOLOGIA_ATTRIBUTE_KEY => [
                'condition' => 'match',
                'value' => 'drewniany'
            ]
        ],
        'zabudowa-blizniacza' => [
            OfferAttribute::OPIS_PROJEKTU_ATTRIBUTE_KEY => [
                'condition' => 'match',
                'value' => 'bliźniacza'
            ]
        ],
        'tanie-w-budowie' => [
            OfferAttribute::KOSZT_OGOLNY_ATTRIBUTE_KEY => [
                'condition' => '<=',
                'value' => 300000
            ]
        ],
        'na-waska-dzialke' => [
            OfferAttribute::MIN_SZEROKOSC_DZIALKI_ATTRIBUTE_KEY => [
                'condition' => '<=',
                'value' => 16
            ]
        ]
    ];


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
        $this->em = $this->container->get('doctrine')->getManager();
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

        if (!in_array($file->getMimeType(), [
            'application/zip',
            'application/xml'
        ])) {
            throw new InvalidArgumentException('Invalid file mime type. Must be application/zip or application/xml instead of ' . $file->getMimeType());
        }

        if (in_array($file->getMimeType(), [
            'application/zip'
        ])) {
            $importFiles = $this->unzipAndValidateFile($filePath);
        } else {
            $importFiles['offers'][] = $filePath;
        }

        if (count($importFiles['offers'])) {
            foreach ($importFiles['offers'] as $offerFilePath) {
                $this->importOffers($offerImport, $offerFilePath, isset($importFiles['images']) ? $importFiles['images'] : []);
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
    public function importOffers(OfferImport $offerImport, $filePath, array $images = [])
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

        if ($rootContextCategories) {
            $rootCategory = $rootContextCategories[0];
        } else {
            throw new InvalidArgumentException('Root context category is not set!');
        }

        $authorUser = $container->get('security.token_storage')->getToken() ? $container->get('security.token_storage')->getToken()->getUser() : null;
        if (!$authorUser) {
            $authorUser = $this->userManager->find(self::DEFAULT_AUTHOR_ID);
        }

        $xx = 0;
        foreach ($xml->projekt as $offer) {
            $output->write('.');
//            $xx++;
//            if ($xx >= 10) break;

            $offerObject = null;
            if (isset($offer->id_klienta)) {
                $offerObject = $this->offerManager->findOneBy(['externalId' => (string)$offer->id_klienta]);
            }

            if (!$offerObject) {
                $offerObject = new Offer();
                if (isset($offer->id_klienta)) {
                    $offerObject->setExternalId((string)$offer->id_klienta);
                }
            }
            //set offer to import relation
            $offerObject->addImport($offerImport);
            $offerImport->addOffer($offerObject);

            $offerObject->setUpdatedBy($authorUser);
            $offerObject->setAssignedTo($offerImport->getAssignedTo() ? $offerImport->getAssignedTo() : $authorUser);

            if (!$offerObject->getId()) {
                $offerObject->setCreatedBy($offerImport->getCreatedBy() ? $offerImport->getCreatedBy() : $authorUser);
                $this->em->persist($offerObject);
            }

            foreach ($offer->children() as $attributeName => $child) {
//                if (!strlen((string)$child) && !in_array($attributeName, [
//                        OfferAttribute::WIZUALIZACJE_ATTRIBUTE_KEY,
//                        OfferAttribute::ELEWACJE_ATTRIBUTE_KEY,
//                        OfferAttribute::USYTUOWANIE_ATTRIBUTE_KEY,
//                        OfferAttribute::RZUT_PARTER_ATTRIBUTE_KEY,
//                        OfferAttribute::RZUT_PIETRO_ATTRIBUTE_KEY,
//
//
//                        OfferAttribute::RZUT_PODDASZE_ATTRIBUTE_KEY,
//                        OfferAttribute::RZUTY_INNE_ATTRIBUTE_KEY
//                    ])) { //empty attribute - remove if exist and skip it
//                    if ($offerObject->getId()) {
//                        $categoryAttribute = $this->getCategoryAttribute($attributeName, $child);
//                        $offerAttributes = $offerObject->getAttributes()->filter(function ($entry) use ($categoryAttribute) {
//                            return $entry->getAttribute() ? ($entry->getAttribute()->getId() == $categoryAttribute->getId()) : false;
//                        });
//
//                        if ($offerAttributes->count() > 0 && $offerAttributes->first()) {
//                            $this->em->remove($offerAttributes->first());
//                            $this->em->flush();
//                        }
//                    }
//                    continue;
//                }

                $isPhotoAttribute = in_array($attributeName, [
                    OfferAttribute::WIZUALIZACJE_ATTRIBUTE_KEY,
                    OfferAttribute::ELEWACJE_ATTRIBUTE_KEY,
                    OfferAttribute::USYTUOWANIE_ATTRIBUTE_KEY,
                    OfferAttribute::RZUT_PARTER_ATTRIBUTE_KEY,
                    OfferAttribute::RZUT_PIETRO_ATTRIBUTE_KEY,
                    OfferAttribute::RZUT_PODDASZE_ATTRIBUTE_KEY,
                    OfferAttribute::RZUTY_INNE_ATTRIBUTE_KEY
                ]);

                if ($isPhotoAttribute) {
                    $categoryPhotoAttribute = $this->getCategoryAttribute($attributeName, $child);
                    $attributeName = OfferAttribute::GALLERY_ATTRIBUTE_KEY;
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


                    $pictures = [];
                    if (isset($child->link)) {
                        $pictures = (array)$child->link;
                        $tmp = [];
                        foreach ($pictures as $picture) {
                            $tmp[basename((string)$picture)] = (string)$picture;
                        }
                        $pictures = $tmp;
                    } else {
                        $imageUrl = (string)$child;
                        if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                            $pictures[basename($imageUrl)] = $imageUrl;
                        }
                    }

                    if ($pictures) {
                        $picturesKeys = array_keys($pictures);

                        if ($offerAttribute->getGallery()) {
                            $gallery = $offerAttribute->getGallery();
                            $galleryHasMedias = $gallery->getGalleryHasMedias();

                            foreach ($galleryHasMedias as $galleryHasMedia) {
                                $mediaImage = $galleryHasMedia->getMedia();

                                if (!in_array($mediaImage->getName(), $picturesKeys)) {
                                    if ($galleryHasMedia->getMedia()) {
                                        $this->em->remove($galleryHasMedia->getMedia());
                                    }
                                    $this->em->remove($galleryHasMedia);
                                } else {
                                    if (isset($picturesKeys[$mediaImage->getName()])) {
                                        unset($picturesKeys[$mediaImage->getName()]);
                                    }
                                }
                            }

                            foreach ($pictures as $imageName => $imageUrl) {
                                if (@getimagesize($imageUrl)) {
                                    $mediaImage = $this->mediaManager->findOneBy([
                                        'name' => $imageName,
                                        'context' => $context->getId(),
                                        'providerName' => 'sonata.media.provider.image'
                                    ]);
                                    if (!$mediaImage) {
                                        $imageUrlTmp = tempnam(sys_get_temp_dir(), 'offer_' . time()) . '.jpg';
                                        $fp = fopen($imageUrlTmp, "w");
                                        fwrite($fp, file_get_contents($imageUrl));
                                        fclose($fp);

                                        if (!filesize($imageUrlTmp)) {
                                            continue;
                                        }

                                        $mediaImage = new Media();
                                        $mediaImage->setEnabled(true);
                                        $mediaImage->setName($imageName);
                                        $mediaImage->setProviderName('sonata.media.provider.image');
                                        $mediaImage->setBinaryContent($imageUrlTmp);
                                        $mediaImage->setContext($context);
                                        $mediaImage->setCategory($categoryPhotoAttribute);
                                        $this->em->persist($mediaImage);
                                    }
                                    $galleryHasMedia = new GalleryHasMedia();
                                    $galleryHasMedia->setEnabled(true);
                                    $galleryHasMedia->setGallery($gallery);
                                    $galleryHasMedia->setPosition(count($gallery->getGalleryHasMedias()));
                                    $galleryHasMedia->setMedia($mediaImage);
                                    $mediaImage->addGalleryHasMedia($galleryHasMedia);
                                    $gallery->addGalleryHasMedia($galleryHasMedia);

                                    $this->em->persist($galleryHasMedia);
                                }
                            }
                        } else {
                            $gallery = new Gallery();
                            $gallery->setEnabled(true);
                            $gallery->setContext(self::OFFER_MEDIA_CONTEXT);
                            $gallery->setName('Offer ' . $offerObject->getExternalId());

                            $positionKey = 0;
                            foreach ($pictures as $imageName => $imageUrl) {
                                if (@getimagesize($imageUrl)) {
                                    $mediaImage = $this->mediaManager->findOneBy([
                                        'name' => $imageName,
                                        'context' => $context->getId(),
                                        'providerName' => 'sonata.media.provider.image'
                                    ]);

                                    if (!$mediaImage) {
                                        $imageUrlTmp = tempnam(sys_get_temp_dir(), 'offer_' . time()) . '.jpg';
                                        $fp = fopen($imageUrlTmp, "w");
                                        fwrite($fp, file_get_contents($imageUrl));
                                        fclose($fp);

                                        if (!filesize($imageUrlTmp)) {
                                            continue;
                                        }

                                        $mediaImage = new Media();
                                        $mediaImage->setEnabled(true);
                                        $mediaImage->setName($imageName);
                                        $mediaImage->setContext($context);
                                        $mediaImage->setCategory($categoryPhotoAttribute);
                                        $mediaImage->setProviderName('sonata.media.provider.image');
                                        $mediaImage->setBinaryContent($imageUrlTmp);
                                        $this->em->persist($mediaImage);
                                    }
                                    $galleryHasMedia = new GalleryHasMedia();
                                    $galleryHasMedia->setEnabled(true);
                                    $galleryHasMedia->setGallery($gallery);
                                    $galleryHasMedia->setPosition($positionKey);
                                    $galleryHasMedia->setMedia($mediaImage);
                                    $mediaImage->addGalleryHasMedia($galleryHasMedia);
                                    $gallery->addGalleryHasMedia($galleryHasMedia);

                                    $this->em->persist($galleryHasMedia);
                                    $positionKey++;
                                }
                                $this->em->persist($gallery);
                            }
                        }

                        $offerAttribute->setGallery($gallery);
                    }
                } else { //Normal attributes
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

                    if($child->count()) {
                        $childArr = $this->getChildArr($child);
                        $attributeValue = json_encode($childArr);
                    } else {
                        $attributeValue = (string)$child;
                    }

                    if (!$offerAttribute->getAttributeValue()) {
                        if (is_numeric($attributeValue)) {
                            $offerAttribute->setValueNumeric($attributeValue);
                            $offerAttribute->setValue(null);
                        } else {
                            $offerAttribute->setValue($attributeValue);
                            $offerAttribute->setValueNumeric(null);
                        }
                    }
                }

                if (!$offerAttribute->getId()) {
                    $this->em->persist($offerAttribute);
                }

                $this->setOfferCategories($offerObject);

                $this->em->flush();
            }
        }
    }

    private function getChildArr($node, $result = []) {
        if($node->count()) {
            foreach($node->children() as $key => $value) {
                if($value->count()) {
                    $result[] = $this->getChildArr($value);
                } else {
                    $result[$value->getName()] = (string) $value;
                }
            }
        } else {
            $result[$node->getName()] = (string) $node;
        }

        return $result;
    }

    public function setOfferCategories(Offer $offer)
    {
        $categoryManager = $this->container->get('sonata.classification.manager.category');

        if (!($offer->getAttributeValue(OfferAttribute::PODDASZE_ATTRIBUTE_KEY) && $offer->getAttributeValue(OfferAttribute::PIETRO_ATTRIBUTE_KEY))) {
            $category = $categoryManager->findOneBy(['customName' => Offer::CATEGORY_PARTEROWE]);
            if ($category) {
                if(!$offer->getCategories()->contains($category)) {
                    $offer->addCategory($category);
                }
            }
        }

        if ($offer->getAttributeValue(OfferAttribute::PODDASZE_ATTRIBUTE_KEY)) {
            $category = $categoryManager->findOneBy(['customName' => Offer::CATEGORY_Z_PODDASZEM]);
            if ($category) {
                if(!$offer->getCategories()->contains($category)) {
                    $offer->addCategory($category);
                }
            }
        }

        if ($offer->getAttributeValue(OfferAttribute::PIETRO_ATTRIBUTE_KEY)) {
            $category = $categoryManager->findOneBy(['customName' => Offer::CATEGORY_PIETROWE]);
            if ($category) {
                if(!$offer->getCategories()->contains($category)) {
                    $offer->addCategory($category);
                }
            }
        }

        if ($offer->getAttributeValue(OfferAttribute::POW_UZYTKOWA_ATTRIBUTE_KEY) <= 100) {
            $category = $categoryManager->findOneBy(['customName' => Offer::CATEGORY_MALE]);
            if ($category) {
                if(!$offer->getCategories()->contains($category)) {
                    $offer->addCategory($category);
                }
            }
        }

        if ($offer->getAttributeValue(OfferAttribute::POW_UZYTKOWA_ATTRIBUTE_KEY) > 100 && $offer->getAttributeValue(OfferAttribute::POW_UZYTKOWA_ATTRIBUTE_KEY) <= 150) {
            $category = $categoryManager->findOneBy(['customName' => Offer::CATEGORY_SREDNIE]);
            if ($category) {
                if(!$offer->getCategories()->contains($category)) {
                    $offer->addCategory($category);
                }
            }
        }

        if ($offer->getAttributeValue(OfferAttribute::POW_UZYTKOWA_ATTRIBUTE_KEY) > 150 && $offer->getAttributeValue(OfferAttribute::POW_UZYTKOWA_ATTRIBUTE_KEY) <= 200) {
            $category = $categoryManager->findOneBy(['customName' => Offer::CATEGORY_DUZE]);
            if ($category) {
                if(!$offer->getCategories()->contains($category)) {
                    $offer->addCategory($category);
                }
            }
        }

        if (preg_match('/tradycyjn/im', $offer->getAttributeValue(OfferAttribute::OPIS_PROJEKTU_ATTRIBUTE_KEY))) {
            $category = $categoryManager->findOneBy(['customName' => Offer::CATEGORY_TRADYCYJNE]);
            if ($category) {
                if(!$offer->getCategories()->contains($category)) {
                    $offer->addCategory($category);
                }
            }
        }

        if (preg_match('/nowoczesn/im', $offer->getAttributeValue(OfferAttribute::OPIS_PROJEKTU_ATTRIBUTE_KEY))) {
            $category = $categoryManager->findOneBy(['customName' => Offer::CATEGORY_NOWOCZESNE]);
            if ($category) {
                if(!$offer->getCategories()->contains($category)) {
                    $offer->addCategory($category);
                }
            }
        }

        if (preg_match('/drewnian/im', $offer->getAttributeValue(OfferAttribute::OPIS_TECHNOLOGIA_ATTRIBUTE_KEY)) ||
            preg_match('/drewnian/im', $offer->getAttributeValue(OfferAttribute::TECHNOLOGIA_ATTRIBUTE_KEY))) {
            $category = $categoryManager->findOneBy(['customName' => Offer::CATEGORY_DREWNIANE]);
            if ($category) {
                if(!$offer->getCategories()->contains($category)) {
                    $offer->addCategory($category);
                }
            }
        }

        if (preg_match('/bliźniacz/im', $offer->getAttributeValue(OfferAttribute::OPIS_PROJEKTU_ATTRIBUTE_KEY))) {
            $category = $categoryManager->findOneBy(['customName' => Offer::CATEGORY_ZABUDOWA_BLIZNIACZA]);
            if ($category) {
                if(!$offer->getCategories()->contains($category)) {
                    $offer->addCategory($category);
                }
            }
        }

        if ($offer->getAttributeValue(OfferAttribute::KOSZT_OGOLNY_ATTRIBUTE_KEY) <= 300000) {
            $category = $categoryManager->findOneBy(['customName' => Offer::CATEGORY_TANIE_W_BUDOWIE]);
            if ($category) {
                if(!$offer->getCategories()->contains($category)) {
                    $offer->addCategory($category);
                }
            }
        }

        if ($offer->getAttributeValue(OfferAttribute::MIN_SZEROKOSC_DZIALKI_ATTRIBUTE_KEY) <= 16) {
            $category = $categoryManager->findOneBy(['customName' => Offer::CATEGORY_NA_WASKA_DZIALKE]);
            if ($category) {
                if(!$offer->getCategories()->contains($category)) {
                    $offer->addCategory($category);
                }
            }
        }

        //@toDO garaż!!!!!!!!
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
            $categoryAttribute->setCustomName($attributeName);
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