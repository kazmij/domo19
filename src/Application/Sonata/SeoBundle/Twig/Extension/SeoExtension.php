<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\SeoBundle\Twig\Extension;

use Application\Sonata\OfferBundle\Entity\Offer;
use Application\Sonata\PageBundle\Entity\Page;
use Sonata\SeoBundle\Seo\SeoPageInterface;
use Sonata\SeoBundle\Twig\Extension\SeoExtension as BaseSeoExtension;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Twig\Environment;

class SeoExtension extends BaseSeoExtension
{

    use ContainerAwareTrait;

    /**
     * @return string
     */
    public function getTitle($page = null)
    {
        $titles = [];

        if ($page) {
            $site = $page->getSite();
            if ($site->getTitle()) {
                $titles[] = $site->getTitle();
            }

            if ($page->getTitle()) {
                $titles[] = $page->getTitle();
            }
        }

        if (empty($titles)) {
            $titles[] = $this->page->getTitle();
        }

        return sprintf('<title>%s</title>', strip_tags(implode(' - ', $titles)));
    }

    /**
     * @return string
     */
    public function getMetadatas($page = null)
    {
        if ($page) {
            $metaDescription = $page->getMetaDescription();
            $metaKeywords = $page->getMetaDescription();
            $site = $page->getSite();
            $metaSiteDescription = $site->getMetaDescription();
            $metaSiteKeywords = $site->getMetaDescription();

            $pageMetas = $this->page->getMetas();

            if (isset($pageMetas['description'])) {
                if ($metaDescription) {
                    $pageMetas['description'] = $metaDescription;
                } elseif ($metaSiteDescription) {
                    $pageMetas['description'] = $metaSiteDescription;
                }
            }

            if (isset($pageMetas['keywords'])) {
                if ($metaKeywords) {
                    $pageMetas['keywords'] = $metaKeywords;
                } elseif ($metaSiteKeywords) {
                    $pageMetas['keywords'] = $metaSiteKeywords;
                }
            }

            $this->page->setMetas($pageMetas);

//            $liip = $this->container->get('liip_imagine.data.manager');
//            $defaultImage = $liip->getDefaultImageUrl('facebook');
//            $imagineFilter = $this->container->get('liip_imagine.service.filter');
//            $imgUrl = $imagineFilter->getUrlOfFilteredImage($defaultImage, 'facebook');
//            if ($imgUrl) {
//                $this->page->addMeta('property', 'og:image', $imgUrl);
//            }
//
//            $request = $this->container->get('request_stack')->getCurrentRequest();
//            $this->page->addMeta('property', 'og:url', $request->getUri());
//
//            if ($request->attributes->get('_route') == 'application_sonata_offer_view') {
//                $offerManager = $this->container->get('application_sonata_offer.manager.offer');
//                /* @var $offer Offer */
//                $offer = $offerManager->find($request->get('id'));
//                if ($offer) {
//                    $this->page->addMeta('property', 'og:title', $offer->getOfferFullName());
//                    $this->page->addMeta('property', 'og:description', $offer->getDescription());
//                    if ($offer->getGallery() && $offer->getGallery()->getGalleryHasMedias()) {
//                        $metas = $this->page->getMetas();
//                        if (isset($metas['property']['og:image'])) {
//                            unset($metas['property']['og:image']);
//                            $this->page->setMetas($metas);
//                        }
//                        foreach ($offer->getGallery()->getGalleryHasMedias() as $galleryHasMedia) {
//                            $photo = $galleryHasMedia->getMedia();
//                            /* @var $provider \Sonata\MediaBundle\Provider\ImageProvider */
//                            $provider = $this->container->get($photo->getProviderName());
//                            $tmpImagePathRel = $provider->generatePublicUrl($photo, 'reference');
//                            if ($tmpImagePathRel) {
//                                $imgUrl = $imagineFilter->getUrlOfFilteredImage($tmpImagePathRel, 'facebook');
//                                $this->page->addMeta('property_image', $photo->getId(), $imgUrl);
//                            }
//                        }
//                    }
//                }
//            }
        }

        $html = '';

        foreach ($this->page->getMetas() as $type => $metas) {
            if ($type === 'property_image') {
                foreach ((array)$metas as $name => $meta) {
                    list($content, $extras) = $meta;

                    if (!empty($content)) {
                        $html .= sprintf("<meta %s=\"%s\" content=\"%s\" />\n",
                            'property',
                            'og:image',
                            $this->normalize($content)
                        );
                        $html .= sprintf("<meta %s=\"%s\" content=\"%s\" />\n",
                            'property',
                            'og:image:width',
                            1200
                        );
                        $html .= sprintf("<meta %s=\"%s\" content=\"%s\" />\n",
                            'property',
                            'og:image:height',
                            630
                        );
                    } else {
                        $html .= sprintf("<meta %s=\"%s\" />\n",
                            'property',
                            'og:image'
                        );
                    }
                }
            } else {
                foreach ((array)$metas as $name => $meta) {
                    list($content, $extras) = $meta;

                    if (!empty($content)) {
                        $html .= sprintf("<meta %s=\"%s\" content=\"%s\" />\n",
                            $type,
                            $this->normalize($name),
                            $this->normalize($content)
                        );
                    } else {
                        $html .= sprintf("<meta %s=\"%s\" />\n",
                            $type,
                            $this->normalize($name)
                        );
                    }
                }
            }
        }

        return $html;
    }

    /**
     * @param string $string
     *
     * @return mixed
     */
    private function normalize($string)
    {
        return htmlentities(strip_tags($string), ENT_COMPAT, $this->encoding);
    }

}
