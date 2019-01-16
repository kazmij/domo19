<?php

declare(strict_types = 1);

namespace Application\Sonata\UserBundle\Admin\Entity;

use Application\Sonata\MediaBundle\Form\Type\ImageType;
use Application\Sonata\UserBundle\Entity\Group;
use Doctrine\ORM\EntityRepository;
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
use Sonata\AdminBundle\Form\Type\ModelListType;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Callback;
use Sonata\AdminBundle\Admin\FieldDescriptionInterface;

class ConsultantAdmin extends BaseUserAdmin
{

    protected $baseRouteName = 'application_sonata_user_consultant_admin';

    protected $baseRoutePattern = 'consultant';

    const ADMIN_CODE = 'application_sonata_user.admin.director';

    /**
     * {@inheritdoc}
     */
    public function getFormBuilder()
    {
        $this->formOptions['data_class'] = $this->getClass();

        $options = $this->formOptions;

        $formBuilder = $this->getFormContractor()->getFormBuilder($this->getUniqid(), $options);

        $this->defineFormBuilder($formBuilder);

        return $formBuilder;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper): void
    {

        $directorUserGroup = Group::GROUP_DIRECTOR_ID;

        $genderOptions = [
            'choices' => call_user_func([$this->getUserManager()->getClass(), 'getGenderList']),
            'required' => true,
            'translation_domain' => $this->getTranslationDomain(),
        ];

        // define group zoning
        $formMapper
            ->tab('User')
            ->with('Profile', ['class' => 'col-md-6'])->end()
            ->with('General', ['class' => 'col-md-6'])->end()
            ->end();

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
            ->with('Profile');

        if (!$this->getUser()->isDirector()) {
            $formMapper->add('assignedToUsers', null, [
                'required' => true,
                'multiple' => true,
                'by_reference' => false,
                'constraints' => [
                    new Count(['min' => 1])
                ],
                'query_builder' => function (EntityRepository $repository) use ($directorUserGroup) {
                    return $repository->createQueryBuilder('c')
                        ->join('c.groups', 'g')
                        ->andWhere('g.id = :group')
                        ->setParameters([
                            'group' => $directorUserGroup
                        ]);
                }
            ]);
        }

        $formMapper
            ->add('firstname', null, ['required' => true])
            ->add('lastname', null, ['required' => true])
            ->add('profilePicture', ImageType::class, [
                'required' => true
            ])
            ->add('gender', ChoiceType::class, $genderOptions)
            ->add('phone', null, ['required' => true])
            ->add('province', null, [
                'placeholder' => ' --Select province--'
            ])
            ->add('city', null, ['required' => true])
            ->add('website', UrlType::class, ['required' => false])
            ->add('biography', TextType::class, ['required' => false])
            ->end()
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper) : void
    {
        $datagridMapper
            ->add('id')
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('username');

        if (!$this->getUser()->isDirector()) {
            $datagridMapper->add('assignedToUsers');
        }

        $datagridMapper
            ->add('city')
            ->add('phone')
            ->add('province')
            ->add('gender')
            ->add('website')
            ->add('biography')
            ->add('createdAt')
            ->add('updatedAt');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('fullName')
            ->add('email')
            ->add('username');

        if (!$this->getUser()->isDirector()) {
            $listMapper->add('assignedToUsers', null, [
                'template' => '@ApplicationSonataUser/Admin/Fields/list_many_to_many_user.html.twig'
            ]);
        }

        $listMapper
            ->add('phone')
            ->add('province')
            ->add('city')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $loggedUser = $this->getUser();

        if ($loggedUser) {
            if ($loggedUser->isDirector() || $loggedUser->isConsultant()) {
                $query->join($query->getRootAliases()[0] . '.assignedToUsers', 'atu');
                $query->andWhere(
                    $query->expr()->eq('atu.id', $loggedUser->getId())
                );
            }
        }

        $query->join($query->getRootAliases()[0] . '.groups', 'gr');
        $query->andWhere(
            $query->expr()->eq('gr.id', ':group')
        );
        $query->setParameter('group', Group::GROUP_CONSULTANT_ID);

        return $query;
    }

    protected function configureShowFields(ShowMapper $showMapper) : void
    {
        $showMapper
            ->add('id')
            ->add('fullName')
            ->add('email')
            ->add('username');

        if (!$this->getUser()->isDirector()) {
            $showMapper->add('assignedToUsers', null, [
                'template' => '@ApplicationSonataUser/Admin/Fields/show_many_to_many_user.html.twig'
            ]);
        }

        $showMapper
            ->add('city')
            ->add('phone')
            ->add('province')
            ->add('gender')
            ->add('website')
            ->add('biography')
            ->add('createdAt')
            ->add('updatedAt');
    }

    public function prePersist($object)
    {
        parent::prePersist($object);

        $container = $this->getConfigurationPool()->getContainer();
        $group = $container->get('sonata.user.orm.group_manager')->findGroupBy(['id' => Group::GROUP_CONSULTANT_ID]);

        if($this->getUser()->isDirector()) {
            $object->addAssignedToUser($this->getUser());
        }

        $object->addGroup($group);
    }

    public function preUpdate($object): void
    {
        parent::preUpdate($object);

        $container = $this->getConfigurationPool()->getContainer();
        $group = $container->get('sonata.user.orm.group_manager')->findGroupBy(['id' => Group::GROUP_CONSULTANT_ID]);

        if($this->getUser()->isDirector()) {
            $object->addAssignedToUser($this->getUser());
        }

        $object->addGroup($group);
    }

    /**
     * @return User
     */
    public function getUser()
    {
        $token = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken();

        if(!$token) {
            return new \Application\Sonata\UserBundle\Entity\User();
        }

        $loggedUser = $token->getUser();

        return $loggedUser;
    }

    public function attachAdminClass(FieldDescriptionInterface $fieldDescription)
    {
        $pool = $this->getConfigurationPool();

        $adminCode = $fieldDescription->getOption('admin_code');

        if (null !== $adminCode) {
            $admin = $pool->getAdminByAdminCode($adminCode);
        } else {
            if ($fieldDescription->getFieldName() == 'assignedToUsers') {
                $admin = $pool->getAdminByAdminCode(DirectorAdmin::ADMIN_CODE);
            } else {
                $admin = $pool->getAdminByClass($fieldDescription->getTargetEntity());
            }
        }

        if (!$admin) {
            return;
        }

        if ($this->hasRequest()) {
            $admin->setRequest($this->getRequest());
        }

        $fieldDescription->setAssociationAdmin($admin);
    }
}
