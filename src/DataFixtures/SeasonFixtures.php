<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public const SEASONS = [
        ['program' => 1, 'number' => 1, 'year' => 2002, 'description' => 'C\'est la saison 1 de The Office'],
        ['program' => 1, 'number' => 2, 'year' => 2003, 'description' => 'C\'est la saison 2 de The Office'],
        ['program' => 1, 'number' => 3, 'year' => 2004, 'description' => 'C\'est la saison 3 de The Office'],
        ['program' => 1, 'number' => 4, 'year' => 2005, 'description' => 'C\'est la saison 4 de The Office'],
        ['program' => 1, 'number' => 5, 'year' => 2006, 'description' => 'C\'est la saison 5 de The Office'],
        ['program' => 3, 'number' => 1, 'year' => 1998, 'description' => 'C\'est la saison 1 de The Wire'],
        ['program' => 3, 'number' => 2, 'year' => 1999, 'description' => 'C\'est la saison 2 de The Wire'],
        ['program' => 3, 'number' => 3, 'year' => 2000, 'description' => 'C\'est la saison 3 de The Wire'],
        ['program' => 2, 'number' => 1, 'year' => 2010, 'description' => 'C\'est la saison 1 de Breaking Bad'],
        ['program' => 4, 'number' => 1, 'year' => 2003, 'description' => 'C\'est la saison 1 de Malcolm'],
        ['program' => 6, 'number' => 1, 'year' => 2014, 'description' => 'C\'est la saison 1 de Community'],
        ['program' => 7, 'number' => 1, 'year' => 2002, 'description' => 'C\'est la saison 1 de Scrubs'],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::SEASONS as $key => $programSeason) {
            $season = new Season();
            $season->setProgram($this->getReference('program_' . $programSeason['program']));
            $season->setNumber($programSeason['number']);
            $season->setYear($programSeason['year']);
            $season->setDescription($programSeason['description']);
            $manager->persist($season);
            $this->addReference('season_' . $key, $season);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            ProgramFixtures::class,
        ];
    }
}
