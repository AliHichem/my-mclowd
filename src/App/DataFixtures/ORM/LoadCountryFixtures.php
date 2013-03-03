<?php
namespace App\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Entity\Country;

class LoadCountryFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        // Countries list from http://blog.plsoucy.com/wp-content/uploads/2012/04/countries.sql_.txt
        $countries = json_decode(
            file_get_contents(dirname(__FILE__).'/../DATA/countries.json')
        );
        foreach ($countries as $code => $name) {
            $country = new Country();
            $country->setName($name);
            $country->setCode($code);
            $manager->persist($country);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}