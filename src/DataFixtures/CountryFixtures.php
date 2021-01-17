<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use ParseCsv\Csv;

class CountryFixtures  extends Fixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var string
     */
    private $file;


    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->file = $container->getParameter('kernel.project_dir') . '/public/files/country_iso.csv';
    }

    public function load(ObjectManager $manager)
    {
        $countries = $this->parseCSV();
        $i = 0;
        if (count($countries) > 0) {
            foreach($countries as $data) {
                $country = new Country();
                $country->setCountryCode($data[1]);
                $country->setCountryName($data[0]);
                
                $manager->persist($country);
                $this->addReference('country_'.$i, $country);

                $i++;
            }
            $manager->flush();
        }
    }

    public function parseCSV()
    {
        $data = [];

        if(!file_exists($this->file) || !is_readable($this->file)){
            return $data;
        }

        if (($handle = fopen($this->file, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE)
            {
                $data[] = $row;
            }
            fclose($handle);
        }

        return $data;

    }

    public function getOrder()
    {
        return 1;
    }
}