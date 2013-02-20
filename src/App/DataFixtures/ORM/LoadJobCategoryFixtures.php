<?php
namespace App\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Entity\JobCategory;

class LoadJobCategoryFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $root = new JobCategory;
        $root->setId(1);
        $root->setName('Root');    
        
        $manager->persist($root);
        $manager->flush();
        $id = 2;
        foreach (['Accounting', 'Bookkeeping', 'Data entry', 'Concierge', 'Tax', 'Audits'] as $name) {
            $category = new JobCategory();
            $category->setId($id); // tree nodes need an id to construct path.
            $category->setChildOf($root);
            $category->setName($name);
            ++$id;
            $manager->persist($category);
        }


        $manager->flush();
        
    }

    public function getOrder()
    {
        return 2;
    }
}