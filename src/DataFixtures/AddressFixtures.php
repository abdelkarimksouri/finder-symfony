<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AddressFixtures extends Fixture implements OrderedFixtureInterface,ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 5; $i++) {

            $address = new Address();
            $address->setStreetNumber('12'.$i);
            $address->setStreetName('Fixture-Paris'.$i.' Creteil');
            $address->setStreetComplementary('Nogent-sur-Marne');
            $address->setZipCode('9400'.$i);
            $address->setLongitude('1233.54'.$i);
            $address->setLatitude('33.0000'.$i);
            $address->setCity("Paris");
            $address->setCountry($this->getReference('country_'.$i));
            $this->addReference('address_'.$i, $address);

            $manager->persist($address);

        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}