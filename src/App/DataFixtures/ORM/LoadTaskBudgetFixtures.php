<?php
namespace App\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Entity\TaskBudget;

class LoadTaskBudgetFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $hourly = [
            '$15-25 per hour',
            '$25-30 per hour',
            '$30-45 per hour',
            '$45+ per hour'
        ];
        foreach ($hourly as $value) {
            $tb = new TaskBudget;
            $tb
                ->setName($value)
                ->setType(TaskBudget::TYPE_HOURLY)
            ;
            $manager->persist($tb);
        }

        $fixed = [
            'Less than $100',
            'Between $100 and $250',
            'Between $250 and $500',
            '$500+'            
        ];
        foreach ($fixed as $value) {
            $tb = new TaskBudget;
            $tb
                ->setName($value)
                ->setType(TaskBudget::TYPE_FIXED)
            ;
            $manager->persist($tb);
        }


        $manager->flush();
        
    }

    public function getOrder()
    {
        return 3;
    }
}