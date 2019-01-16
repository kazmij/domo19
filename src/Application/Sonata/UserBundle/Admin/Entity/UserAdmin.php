<?php

declare(strict_types = 1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Admin\Entity;

use Application\Sonata\MediaBundle\Form\Type\ImageType;
use Sonata\UserBundle\Admin\Entity\UserAdmin as BaseUserAdmin;
use FOS\UserBundle\Model\UserManagerInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Sonata\UserBundle\Form\Type\SecurityRolesType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sonata\MediaBundle\Form\Type\MediaType;

class UserAdmin extends BaseUserAdmin
{

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper): void
    {
        $container = $this->getConfigurationPool()->getContainer();
        $token = $container->get('security.token_storage')->getToken();
        $user = $token ? $token->getUser() : null;

        if(!$user) {
            return;
        }

        if ($user->hasRole('ROLE_SUPER_ADMIN')) {

            parent::configureFormFields($formMapper);
        } else {

            if ($user->getId() !== $formMapper->getAdmin()->getSubject()->getId()) {
                throw new AccessDeniedException();
            }

            // define group zoning
            $formMapper
                ->tab('User')
                ->with('Profile', ['class' => 'col-md-6'])->end()
                ->with('General', ['class' => 'col-md-6'])->end()
                ->with('Social', ['class' => 'col-md-6'])->end()
                ->end();

            $now = new \DateTime();

            $genderOptions = [
                'choices' => call_user_func([$this->getUserManager()->getClass(), 'getGenderList']),
                'required' => true,
                'translation_domain' => $this->getTranslationDomain(),
            ];

            // NEXT_MAJOR: Remove this when dropping support for SF 2.8
            if (method_exists(FormTypeInterface::class, 'setDefaultOptions')) {
                $genderOptions['choices_as_values'] = true;
            }

            $formMapper
                ->tab('User')
                ->with('General')
                ->add('username')
                ->add('email')
                ->add('plainPassword', RepeatedType::class, array(
                    'required' => (!$this->getSubject() || null === $this->getSubject()->getId()),
                    'type' => PasswordType::class,
                    'options' => array(
                        'translation_domain' => 'FOSUserBundle',
                        'attr' => array(
                            'autocomplete' => 'new-password',
                        ),
                    ),
                    'first_options' => array('label' => 'form.new_password'),
                    'second_options' => array('label' => 'form.new_password_confirmation'),
                    'invalid_message' => 'fos_user.password.mismatch',
                ))
                ->end()
                ->with('Profile')
                ->add('dateOfBirth', DatePickerType::class, [
                    'years' => range(1900, $now->format('Y')),
                    'dp_min_date' => '1-1-1900',
                    'dp_max_date' => $now->format('c'),
                    'required' => false,
                ])
                ->add('firstname', null, ['required' => false])
                ->add('lastname', null, ['required' => false])
                ->add('profilePicture', ImageType::class, [

                ])
                ->add('website', UrlType::class, ['required' => false])
                ->add('biography', TextType::class, ['required' => false])
                ->add('gender', ChoiceType::class, $genderOptions)
                ->add('locale', LocaleType::class, ['required' => false])
                ->add('timezone', TimezoneType::class, ['required' => false])
                ->add('phone', null, ['required' => false])
                ->end()
                ->with('Social')
                ->add('facebookUid', null, ['required' => false])
                ->add('facebookName', null, ['required' => false])
                ->add('twitterUid', null, ['required' => false])
                ->add('twitterName', null, ['required' => false])
                ->add('gplusUid', null, ['required' => false])
                ->add('gplusName', null, ['required' => false])
                ->end()
                ->end();
        }
    }
}
