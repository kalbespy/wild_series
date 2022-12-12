<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $nbProgram = count(ProgramFixtures::PROGRAMS);

        for ($i = 0; $i < 10; $i++) {
            $actor = new Actor();
            $actor->setName($faker->firstname() . " " . $faker->lastname());
            for ($j = 0; $j < 3; $j++) {
                $actor->addProgram($this->getReference('program_' . $faker->numberBetween(1, $nbProgram - 1)));
            }
            $manager->persist($actor);
            $this->addReference($actor->getName(), $actor);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            ProgramFixtures::class,
        ];
    }
}
