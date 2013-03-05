<?php
namespace App\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Entity\HearSource;

class LoadHearSourceFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $sources = array(
            'Google',
            'Yahoo!',
            'Bing',
            'Other search engine',
            'A friend',
            'A coworker',
            'Internet ad',
            'TV ad',
            'Radio ad'
        );
        foreach ($sources as $name) {
            $source = new HearSource();
            $source->setName($name);
            $manager->persist($source);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }
}