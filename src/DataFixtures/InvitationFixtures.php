<?php

namespace App\DataFixtures;

use App\Entity\Invitation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class InvitationFixtures  extends Fixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;



    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

   public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 5; $i++) {

            $invitation = new Invitation();
            $invitation->setSender($this->getReference('user_'.$i));
            $invitation->setReceived($this->getReference('user_'.$i));
            $invitation->setDateSent(new \DateTime());
            $invitation->setStatus(0);
           
            $manager->persist($invitation);

        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }
}