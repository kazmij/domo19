<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\MainBundle\Form\Type;

use Application\Sonata\MainBundle\Model\Email;
use Application\Sonata\OfferBundle\Service\ImportOffers;
use Doctrine\ORM\EntityRepository;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


class BuyType extends AbstractType
{
    use ContainerAwareTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('areaFrom', TextType::class, [
                'label' => 'Wymiary działki od',
                'required' => false,
                'attr' => [
                    'class' => '',
                    'placeholder' => 'od',
                ]
            ])
            ->add('areaTo', TextType::class, [
                'label' => 'Wymiary działki do',
                'required' => false,
                'attr' => [
                    'class' => '',
                    'placeholder' => 'do',
                ]
            ])
            ->add('usableAreaFrom', TextType::class, [
                'label' => 'Powierzchnia użytkowa od',
                'required' => false,
                'attr' => [
                    'class' => '',
                    'placeholder' => 'od',
                ]
            ])
            ->add('usableAreaTo', TextType::class, [
                'label' => 'Powierzchnia użytkowa do',
                'required' => false,
                'attr' => [
                    'class' => '',
                    'placeholder' => 'do',
                ]
            ])
            ->add('additionalBasement', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'Piwnica',
                'empty_data' => null,
                'choices' => [
                    'Tak' => 1,
                    'Nie' => 0
                ],
                'attr' => [
                    'placeholder' => 'Piwnica',
                    'class' => ''
                ]
            ])
            ->add('additionalGarage', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'Garaż',
                'empty_data' => null,
                'choices' => [
                    'Tak' => 1,
                    'Nie' => 0
                ],
                'attr' => [
                    'placeholder' => 'Garaż',
                    'class' => ''
                ]
            ])
            ->add('floor', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'Kondygnacja',
                'empty_data' => null,
                'choices' => [
                    0, 1, 2
                ],
                'attr' => [
                    'class' => ''
                ]
            ])
            ->add('angleOfInclination', TextType::class, [
                'label' => 'Kąt nachylenia dachu',
                'required' => false,
                'attr' => [
                    'class' => 'hidden'
                ]
            ])
            ;

    }
}
