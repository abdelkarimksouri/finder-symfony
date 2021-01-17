<?php

namespace App\DataFixtures;

use App\Entity\Message;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MessageFixtures extends Fixture implements OrderedFixtureInterface,ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 4 ; $i++) {
            $j = $i+1;
            $message = new Message();
            $message->setSender($this->getReference('user_'.$i));
            $message->setReceived($this->getReference('user_'.$j));
            dump($this->getReference('user_'.$j));
            die;
            $message->setBody('Voilà je vais envoyer mon premier test message');
            $message->setDateSent(new \DateTime());
            $message->setArchived(0);
            $message->setArchievedAt(new \DateTime());
            

            $manager->persist($message);

        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 6;
    }
}