<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $nbProgram = count(ProgramFixtures::PROGRAMS);

        for ($i = 0; $i < $nbProgram; $i++) {
            for ($j = 1; $j < 6; $j++) {
                for ($k = 1; $k < 11; $k++) {
                    $episode = new Episode();
                    $episode->setTitle($faker->text(30, true));
                    $episode->setNumber($k);
                    $episode->setSynopsis($faker->paragraphs(1, true));
                    $episode->setDuration($faker->numberBetween(10, 90));
                    $episode->setSeason($this->getReference('program_' . $i . '_season_' . $j));
                    $slug = $this->slugger->slug($episode->getTitle());
                    $episode->setSlug($slug);
                    $manager->persist($episode);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }
}
