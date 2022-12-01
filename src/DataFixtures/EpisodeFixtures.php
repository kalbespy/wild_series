<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public const EPISODES = [
        ['season' => 1, 'title' => 'episode 1', 'number' => 1, 'synopsis' => 'Eppisode 1 de season id 1'],
        ['season' => 1, 'title' => 'episode 2', 'number' => 2, 'synopsis' => 'Eppisode 2 de season id 1'],
        ['season' => 1, 'title' => 'episode 3', 'number' => 3, 'synopsis' => 'Eppisode 3 de season id 1'],
        ['season' => 2, 'title' => 'episode 1', 'number' => 1, 'synopsis' => 'Eppisode 1 de season id 2'],
        ['season' => 2, 'title' => 'episode 2', 'number' => 2, 'synopsis' => 'Eppisode 2 de season id 2'],
        ['season' => 3, 'title' => 'episode 1', 'number' => 1, 'synopsis' => 'Eppisode 1 de season id 3'],
        ['season' => 3, 'title' => 'episode 2', 'number' => 2, 'synopsis' => 'Eppisode 2 de season id 3'],
        ['season' => 3, 'title' => 'episode 3', 'number' => 3, 'synopsis' => 'Eppisode 3 de season id 3'],
        ['season' => 4, 'title' => 'episode 1', 'number' => 1, 'synopsis' => 'Eppisode 1 de season id 4'],
        ['season' => 4, 'title' => 'episode 2', 'number' => 2, 'synopsis' => 'Eppisode 2 de season id 4'],
        ['season' => 4, 'title' => 'episode 3', 'number' => 3, 'synopsis' => 'Eppisode 3 de season id 4'],
        ['season' => 4, 'title' => 'episode 4', 'number' => 4, 'synopsis' => 'Eppisode 4 de season id 4'],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::EPISODES as $key => $programEpisode) {
            $episode = new Episode();
            $episode->setSeason($this->getReference('season_' . $programEpisode['season']));
            $episode->setTitle($programEpisode['number']);
            $episode->setNumber($programEpisode['number']);
            $episode->setSynopsis($programEpisode['synopsis']);
            $manager->persist($episode);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            SeasonFixtures::class,
        ];
    }
}
