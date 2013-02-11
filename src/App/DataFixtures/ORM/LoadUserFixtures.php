<?php
namespace App\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MC\UserBundle\Entity\Client,
    MC\UserBundle\Entity\Contractor;

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
        $this->container->get('fos_user.user_manager')->updateUser($user);
        
    }

    public function getOrder()
    {
        return 1;
    }
}