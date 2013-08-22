<?php
namespace App\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MC\UserBundle\Entity\Client,
    MC\UserBundle\Entity\Contractor,
    MC\UserBundle\Entity\Manager,
    MC\UserBundle\Entity\ContractorTask;

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
        
        
        $this->addReference('contractor_reference', $user2);
        
        
        $task1 = new ContractorTask();
        $task1->setName("Some task");
        $task1->setPrice(200);
        $task1->setUser($this->getReference("contractor_reference"));
        
        $manager->persist($task1);
        $manager->flush();
        
        $task2 = new ContractorTask();
        $task2->setName("Some task 2");
        $task2->setPrice(500);
        $task2->setUser($this->getReference("contractor_reference"));
        
        $manager->persist($task2);
        $manager->flush();
        
        $task3 = new ContractorTask();
        $task3->setName("Some task 3");
        $task3->setPrice(255);
        $task3->setUser($this->getReference("contractor_reference"));
        
        $manager->persist($task3);
        $manager->flush();
        
        
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