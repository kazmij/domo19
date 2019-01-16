<?php

namespace Application\Sonata\MediaBundle\Serializer;

use Application\Sonata\MediaBundle\Entity\Media;

use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Context;
use Sonata\MediaBundle\Provider\ImageProvider;
use Sonata\MediaBundle\Provider\FileProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class MediaSerializationHandler
{
    /**
     * @var ImageProvider
     */
    private $imageProvider;

    /**
     * @var FileProvider
     */
    private $fileProvider;

    public function __construct(ImageProvider $imageProvider, FileProvider $fileProvider, \JMS\Serializer\Serializer $jmsSerializer, RequestStack $requestStack)
    {
        $this->imageProvider = $imageProvider;
        $this->fileProvider = $fileProvider;
        $this->jmsSerializer = $jmsSerializer;
        $this->request = $requestStack->getCurrentRequest();
    }

    public function serializeMedia(JsonSerializationVisitor $visitor, Media $media, array $type, Context $context)
    {

        switch ($media->getProviderName()) {
            case 'sonata.media.provider.file':
                $serialization = $this->serializeFile($media);
                break;

            case 'sonata.media.provider.image':
                $serialization = $this->serializeImage($media);
                break;

            default:
                throw new \RuntimeException("Serialization media provider not recognized");
        }

        if ($visitor->getRoot() === null) {
            $visitor->setRoot($serialization);
        }

        return $serialization;
    }

    private function serializeImage(Media $media)
    {
        // here you can provide one ore more URLs based on your SonataMedia configuration
        // you can also add some more properties coming from the media entity based on your needs (e.g. authorName, description, copyright etc)
        return [
            "id" => $media->getId(),
            "name" => $media->getName(),
            "description" => $media->getDescription(),
            "type" => $media->getContentType(),
            "url" => [
                "orig" => $this->imageProvider->generatePublicUrl($media, "reference"),
                "small" => $this->imageProvider->generatePublicUrl($media, "default_small"),
                "big" => $this->imageProvider->generatePublicUrl($media, "default_big"),
            ]
        ];
    }

    private function serializeFile(Media $media)
    {
        return [
            "id" => $media->getId(),
            "name" => $media->getName(),
            "description" => $media->getDescription(),
            "type" => $media->getContentType(),
            "size" => $media->getSize(),
            "url" => $this->fileProvider->generatePublicUrl($media, 'reference')
        ];
    }
}