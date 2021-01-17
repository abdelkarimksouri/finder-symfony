<?php

namespace App\DataFixtures;

use App\Entity\ProfilUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProfilUserFixtures extends Fixture implements OrderedFixtureInterface,ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
   public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 5; $i++) {

            $profilUser = new ProfilUser();
            $profilUser->setFirstName('kss'.$i);
            $profilUser->setLastName('abdel'.$i);
            $profilUser->setDdn(new \DateTime());
            $profilUser->setPhoneNumber(12235);
            $profilUser->setHeight(176,5);
            $profilUser->setWeight('7'.$i);
            $profilUser->setAddress($this->getReference('address_'.$i));
            $this->addReference('profil_'.$i, $profilUser);

            $manager->persist($profilUser);

        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}