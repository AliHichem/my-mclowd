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
        
        $category = new JobCategory();
        $category->setId(2); // tree nodes need an id to construct path.
        $category->setChildOf($root);
        $category->setName('IT/Telecomunication');


        $child = new JobCategory;
        $child->setId(3);
        $child->setChildOf($category);
        $child->setName('Programming');
        $child2 = new JobCategory;
        $child2->setId(4);
        $child2->setChildOf($category);
        $child2->setName('Web design');

        $manager->persist($category);
        $manager->persist($child);
        $manager->persist($child2);
        
        
        $manager->flush();

        unset($child, $child2, $category);

        $category = new JobCategory();
        $category->setId(5); // tree nodes need an id to construct path.
        $category->setChildOf($root);        
        $category->setName('Legal');
        $child = new JobCategory;
        $child->setId(6);
        $child->setChildOf($category);
        $child->setName('Tax Law');
        $child2 = new JobCategory;
        $child2->setId(7);
        $child2->setChildOf($category);
        $child2->setName('Bankrupcy');


        $manager->persist($child);
        $manager->persist($child2);
        $manager->persist($category);
        $manager->flush();
        
    }

    public function getOrder()
    {
        return 2;
    }
}