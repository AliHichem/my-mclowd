<?php
namespace App\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MC\UserBundle\Entity\Client,
    MC\UserBundle\Entity\Contractor,
    MC\UserBundle\Entity\Manager;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = new Client();
        $user->setUsername('client');
        $user->setEmail('client@mclowd.com');
        $user->setPlainPassword('client');
        $user->setEnabled(true);
        $this->container->get('fos_user.user_manager')->updateUser($user);
        
        $user2 = new Contractor();
        $user2->setUsername('contractor');
        $user2->setEmail('contractor@mclowd.com');
        $user2->setPlainPassword('contractor');
        $user2->setEnabled(true);
        $this->container->get('fos_user.user_manager')->updateUser($user2);

        $user3 = new Manager();
        $user3->setUsername('admin');
        $user3->setEmail('admin@mclowd.com');
        $user3->setPlainPassword('admin');
        $user3->setEnabled(true);
        $this->container->get('fos_user.user_manager')->updateUser($user3);
        
    }

    public function getOrder()
    {
        return 1;
    }
}