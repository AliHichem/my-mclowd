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
        
        $category = new JobCategory();
        $category->setId(1); // tree nodes need an id to construct path.
        $category->setName('IT/Telecomunication');
        $child = new JobCategory;
        $child->setId(2);
        $child->setChildOf($category);
        $child->setName('Programming');
        $child2 = new JobCategory;
        $child2->setId(3);
        $child2->setChildOf($category);
        $child2->setName('Web design');

        $manager->persist($child);
        $manager->persist($child2);
        $manager->persist($category);
        $manager->flush();

        unset($child, $child2, $category);

        $category = new JobCategory();
        $category->setId(4); // tree nodes need an id to construct path.
        $category->setName('Legal');
        $child = new JobCategory;
        $child->setId(5);
        $child->setChildOf($category);
        $child->setName('Tax Law');
        $child2 = new JobCategory;
        $child2->setId(6);
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