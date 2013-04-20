<?php
namespace App\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Entity\Setting;

class LoadSettingFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $mclowdCharge = new Setting();
        $mclowdCharge->setSettingname('mclowd_charge')
                ->setSettingvalue('8.75'); // define how you want, can be 8.75 can be 0.0875 as percents

        $manager->persist($mclowdCharge);
        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }
}
