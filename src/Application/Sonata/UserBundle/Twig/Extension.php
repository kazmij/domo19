<?php


namespace Application\Sonata\UserBundle\Twig;

use Application\Sonata\UserBundle\Admin\Entity\DirectorAdmin;
use Application\Sonata\UserBundle\Entity\Group;
use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Extension extends \Twig_Extension
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function getFilters()
    {
        return array();
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getAdminForUser', [$this, 'getAdminForUser'])
        );
    }

    public function getAdminForUser(User $user, $defaultAdmin)
    {
        $groupManager = $this->container->get('sonata.user.orm.group_manager');
        $groupDirector = $groupManager->findGroupBy(['id' => Group::GROUP_DIRECTOR_ID]);
        $groupConsultant = $groupManager->findGroupBy(['id' => Group::GROUP_CONSULTANT_ID]);

        if ($groupDirector && $user->hasGroup($groupDirector->getName())) {
            return $this->container->get('application_sonata_user.admin.director');
        } elseif ($groupConsultant && $user->hasGroup($groupConsultant->getName())) {
            return $this->container->get('application_sonata_user.admin.consultant');
        } elseif($user->hasRole('ROLE_SUPER_ADMIN')) {
            return $this->container->get('sonata.user.admin.user');
        } else {
            return $defaultAdmin;
        }
    }
}