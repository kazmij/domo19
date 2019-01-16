<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\PageBundle\Block;

use Knp\Menu\FactoryInterface;
use Knp\Menu\Provider\MenuProviderInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\PageBundle\CmsManager\CmsManagerSelectorInterface;
use Sonata\PageBundle\Model\PageInterface;
use Sonata\SeoBundle\Block\Breadcrumb\BaseBreadcrumbMenuBlockService;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormTypeInterface;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\PageBundle\Block\BreadcrumbBlockService as BaseBreadcrumbBlockService;

/**
 * BlockService for homepage breadcrumb.
 *
 * @author Sylvain Deloux <sylvain.deloux@ekino.com>
 */
class BreadcrumbBlockService extends BaseBreadcrumbBlockService
{

    /**
     * @return array
     */
    protected function getFormSettingsKeys()
    {
        $choiceOptions = [
            'required' => false,
            'label' => 'form.label_url',
            'choice_translation_domain' => 'SonataBlockBundle',
        ];

        // choice_as_value options is not needed in SF 3.0+
        if (method_exists(FormTypeInterface::class, 'setDefaultOptions')) {
            $choiceOptions['choices_as_values'] = true;
        }

        $this->menuRegistry->add('Main menu');

        $choiceOptions['choices'] = array_flip($this->menuRegistry->getAliasNames());

        return [
            ['title', TextType::class, [
                'required' => false,
                'label' => 'form.label_title',
            ]],
            ['cache_policy', ChoiceType::class, [
                'label' => 'form.label_cache_policy',
                'choices' => ['public', 'private'],
            ]],
            ['menu_name', ChoiceType::class, $choiceOptions],
            ['safe_labels', CheckboxType::class, [
                'required' => false,
                'label' => 'form.label_safe_labels',
            ]],
            ['current_class', TextType::class, [
                'required' => false,
                'label' => 'form.label_current_class',
            ]],
            ['first_class', TextType::class, [
                'required' => false,
                'label' => 'form.label_first_class',
            ]],
            ['last_class', TextType::class, [
                'required' => false,
                'label' => 'form.label_last_class',
            ]],
            ['menu_class', TextType::class, [
                'required' => false,
                'label' => 'form.label_menu_class',
            ]],
            ['children_class', TextType::class, [
                'required' => false,
                'label' => 'form.label_children_class',
            ]],
            ['menu_template', TextType::class, [
                'required' => false,
                'label' => 'form.label_menu_template',
            ]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'include_homepage_link' => true,
            'title' => $this->getName(),
            'cache_policy' => 'public',
            'template' => '@SonataBlock/Block/block_core_menu.html.twig',
            'menu_name' => '',
            'safe_labels' => true,
            'current_class' => 'active',
            'first_class' => 'first',
            'last_class' => 'last',
            'current_uri' => '/',
            'menu_class' => 'list-group',
            'children_class' => 'list-group-item',
            'menu_template' => '@SonataSeo/Block/breadcrumb.html.twig',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {

    }

}
