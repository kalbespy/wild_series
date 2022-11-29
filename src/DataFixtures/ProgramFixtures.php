<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{

    public const PROGRAMS =[
        ['Title' => 'The Office', 'Synopsis' => "Du papier et des hommes", 'Category' => 'Comedie',],
        ['Title' => 'Breaking bad', 'Synopsis' => "De la meth et des hommes", 'Category' => 'Aventure',],
        ['Title' => 'The Wire', 'Synopsis' => "Des gangs et des policiers", 'Category' => 'Action',],
        ['Title' => 'Malcolm', 'Synopsis' => "Des enfants turbulants", 'Category' => 'Comedie',],
        ['Title' => 'Game of thrones', 'Synopsis' => "Des dragons et des hommes", 'Category' => 'Fantaisie',],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::PROGRAMS as $key => $tvshow) {
            $program = new Program();
            $program->setTitle($tvshow['Title']);
            $program->setSynopsis($tvshow['Synopsis']);
            $program->setCategory($this->getReference('category_' . $tvshow['Category']));
            $manager->persist($program);        
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
