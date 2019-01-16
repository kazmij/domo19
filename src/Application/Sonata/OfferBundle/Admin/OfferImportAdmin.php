<?php

namespace Application\Sonata\OfferBundle\Admin;

use Application\Sonata\ProductBundle\Entity\Product;
use Application\Sonata\UserBundle\Admin\Entity\DirectorAdmin;
use Application\Sonata\UserBundle\Entity\Group;
use Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\FormatterBundle\Form\Type\FormatterType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Application\Sonata\MediaBundle\Form\Type\ZipType;
use Sonata\AdminBundle\Admin\FieldDescriptionInterface;

class OfferImportAdmin extends AbstractAdmin
{

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('checkImport', $this->getRouterIdParameter() . '/check-import');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('createdBy')
            ->add('assignedTo')
            ->add('status')
            ->add('importFile')
            ->add('createdAt')
            ->add('updatedAt');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('offersCount', null, [
                'class' => 'offersCount'
            ])
            ->add('createdBy', null, [
                'template' => '@ApplicationSonataUser/Admin/Fields/list_many_to_one_user.html.twig'
            ])
            ->add('assignedTo', null, [
                'template' => '@ApplicationSonataUser/Admin/Fields/list_many_to_one_user.html.twig'
            ])
            ->add('importFile')
            ->add('status', null, [
                'template' => '@ApplicationSonataOffer/Admin/Fields/status.html.twig'
            ])
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

        if ($this->getUser()->isConsultant()) {
            $query->leftJoin($query->getRootAliases()[0] . '.createdBy', 'cb');
            $query->leftJoin($query->getRootAliases()[0] . '.assignedTo', 'at');
            $query->andWhere(
                $query->expr()->orX(
                    $query->expr()->eq('cb.id', ':user'),
                    $query->expr()->eq('at.id', ':user')
                )
            );
            $query->setParameter('user', $this->getUser()->getId());
        } elseif ($this->getUser()->isDirector()) {
            $query->leftJoin($query->getRootAliases()[0] . '.createdBy', 'cb');
            $query->leftJoin('cb.assignedToUsers', 'cba');
            $query->leftJoin($query->getRootAliases()[0] . '.assignedTo', 'at');
            $query->leftJoin('at.assignedToUsers', 'ata');
            $query->andWhere(
                $query->expr()->orX(
                    $query->expr()->eq('cb.id', ':user'),
                    $query->expr()->eq('at.id', ':user'),
                    $query->expr()->eq('cba.id', ':user'),
                    $query->expr()->eq('ata.id', ':user')
                )
            );
            $query->setParameter('user', $this->getUser()->getId());
        }

        return $query;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $container = $this->getConfigurationPool()->getContainer();
        $loggedUser = $this->getUser();

        if (!$this->getUser()->isConsultant()) {
            $formMapper
                ->add('assignedTo', null, [
                    'query_builder' => function (EntityRepository $repository) use ($loggedUser) {
                        if ($loggedUser->hasRole('ROLE_SUPER_ADMIN')) {
                            return $repository->createQueryBuilder('u');
                        } else {
                            return $repository->createQueryBuilder('u')
                                ->join('u.assignedToUsers', 'ru')
                                ->andWhere('ru.id = :user')
                                ->setParameters([
                                    'user' => $loggedUser->getId()
                                ]);
                        }
                    }
                ]);
        }

        $formMapper->add('importFile', ZipType::class, [
            'context' => 'offer',
            'required' => (!$this->getSubject() || null === $this->getSubject()->getId()),
        ]);
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('createdBy', null, [
                'template' => '@ApplicationSonataUser/Admin/Fields/show_many_to_one_user.html.twig'
            ])
            ->add('assignedTo', null, [
                'template' => '@ApplicationSonataUser/Admin/Fields/show_many_to_one_user.html.twig'
            ])
            ->add('status')
            ->add('importFile')
            ->add('createdAt')
            ->add('updatedAt');
    }

    /**
     * @return User
     */
    public function getUser()
    {
        $token = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken();

        if (!$token) {
            return new \Application\Sonata\UserBundle\Entity\User();
        }

        $loggedUser = $token->getUser();

        return $loggedUser;
    }

    public function prePersist($object)
    {
        $object->setCreatedBy($this->getUser());

        if (!$object->getAssignedTo()) {
            $object->setAssignedTo($this->getUser());
        }
    }

    public function attachAdminClass(FieldDescriptionInterface $fieldDescription)
    {
        $pool = $this->getConfigurationPool();

        $adminCode = $fieldDescription->getOption('admin_code');

        if (null !== $adminCode) {
            $admin = $pool->getAdminByAdminCode($adminCode);
        } else {
            if ($fieldDescription->getTargetEntity() === User::class) {
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
