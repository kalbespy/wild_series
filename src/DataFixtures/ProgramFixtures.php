<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{

    public const PROGRAMS = [
        ['Title' => 'The Office', 'Synopsis' => "Du papier et des hommes", 'poster' => 'assets/images/imgserie.jpg', 'country' => 'France', 'year' => '2000', 'Category' => 'Comedie',],
        ['Title' => 'Breaking bad', 'Synopsis' => "De la meth et des hommes", 'country' => 'France', 'year' => '2000', 'Category' => 'Aventure',],
        ['Title' => 'The Wire', 'Synopsis' => "Des gangs et des policiers", 'country' => 'France', 'year' => '2000', 'Category' => 'Action',],
        ['Title' => 'Malcolm', 'Synopsis' => "Des enfants et des bêtises", 'country' => 'France', 'year' => '2000', 'Category' => 'Comedie',],
        ['Title' => 'Game of thrones', 'Synopsis' => "Des dragons et des hommes", 'country' => 'France', 'year' => '2000', 'Category' => 'Fantaisie',],
        ['Title' => 'Community', 'Synopsis' => "Des étudiants et du fun", 'country' => 'France', 'year' => '2000', 'Category' => 'Comedie',],
        ['Title' => 'Scrubs', 'Synopsis' => "Des étudiants et des blouses", 'country' => 'France', 'year' => '2000', 'Category' => 'Comedie',],
        ['Title' => 'South Park', 'Synopsis' => "Des enfants et des jurons", 'country' => 'France', 'year' => '2000', 'Category' => 'Comedie',],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::PROGRAMS as $key => $tvshow) {
            $program = new Program();
            $program->setTitle($tvshow['Title']);
            $program->setSynopsis($tvshow['Synopsis']);
            $program->setCountry($tvshow['country']);
            $program->setYear($tvshow['year']);
            $program->setCategory($this->getReference('category_' . $tvshow['Category']));
            $manager->persist($program);
            $this->addReference('program_' . $key, $program);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            CategoryFixtures::class,
        ];
    }
}
