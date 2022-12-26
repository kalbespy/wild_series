<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{

    public const PROGRAMS = [
        ['Title' => 'Le bureau', 'Synopsis' => "Du papier et des hommes", 'poster' => 'theoffice-63a048ebb140b628168251.jpg', 'country' => 'France', 'year' => '2000', 'Category' => 'Comedie', 'owner' => 'user@wildseries.com',],
        ['Title' => 'Mauvaise cassure', 'Synopsis' => "De la meth et des hommes", 'poster' => 'breakinbad-63a048fb1b647448077818.jpeg', 'country' => 'France', 'year' => '2000', 'Category' => 'Aventure', 'owner' => 'admin@mwildseries.com',],
        ['Title' => 'Le fil', 'Synopsis' => "Des gangs et des policiers", 'poster' => 'thewire-63a04908ccfd2368511803.jpg', 'country' => 'US', 'year' => '2000', 'Category' => 'Action', 'owner' => 'user@wildseries.com',],
        ['Title' => 'Malcolm au milieu', 'Synopsis' => "Des enfants et des bêtises", 'poster' => 'why-malcolm-63a0491685e26936626708.webp', 'country' => 'France', 'year' => '2000', 'Category' => 'Comedie', 'owner' => 'admin@mwildseries.com',],
        ['Title' => 'Jeu des trônes', 'Synopsis' => "Des dragons et des hommes", 'poster' => 'got-63a048c304820937366371.jpeg', 'country' => 'France', 'year' => '2000', 'Category' => 'Fantaisie', 'owner' => 'user@wildseries.com',],
        ['Title' => 'Communauté', 'Synopsis' => "Des étudiants et du fun", 'poster' => 'community-63a04922185da099013197.jpg', 'country' => 'France', 'year' => '2000', 'Category' => 'Comedie', 'owner' => 'admin@mwildseries.com',],
        ['Title' => 'Blouses', 'Synopsis' => "Des étudiants et des patients", 'poster' => 'scrubs-63a04931ab509104301837.jpeg', 'country' => 'France', 'year' => '2000', 'Category' => 'Comedie', 'owner' => 'user@wildseries.com',],
        ['Title' => 'Parc du sud', 'Synopsis' => "Des enfants et des jurons", 'poster' => 'south-park-6123-63a0493e160f4708812701.jpg', 'country' => 'France', 'year' => '2000', 'Category' => 'Comedie', 'owner' => 'admin@mwildseries.com',],
        ['Title' => 'Des choses étranges', 'Synopsis' => "Des enfants et des monstres", 'poster' => 'strangerthings-639f154b6c478635877243.jpg', 'country' => 'US', 'year' => '2000', 'Category' => 'Comedie', 'owner' => 'user@wildseries.com',],
        ['Title' => 'Le pari de la reine', 'Synopsis' => "Une dame et des échecs", 'poster' => 'the-queens-gambit-639f155843907090507725.jpg', 'country' => 'US', 'year' => '2016', 'Category' => 'Comedie', 'owner' => 'admin@mwildseries.com',],
        ['Title' => 'L\'étendue', 'Synopsis' => "Des hommes et l'espace", 'poster' => 'the-expanse-639f15668446e594269390.jpeg', 'country' => 'France', 'year' => '2000', 'Category' => 'Comedie', 'owner' => 'user@wildseries.com',],
        ['Title' => 'Les aveuglants pâles', 'Synopsis' => "Des hommes et des magouilles", 'poster' => 'peakyfuckingblinders-639f157999511746369802.jpg', 'country' => 'France', 'year' => '2000', 'Category' => 'Comedie', 'owner' => 'admin@mwildseries.com',],
    ];

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::PROGRAMS as $key => $tvshow) {
            $program = new Program();
            $program->setTitle($tvshow['Title']);
            $program->setSynopsis($tvshow['Synopsis']);
            $program->setPoster($tvshow['poster']);
            $program->setCountry($tvshow['country']);
            $program->setYear($tvshow['year']);
            $program->setCategory($this->getReference('category_' . $tvshow['Category']));
            $program->setOwner($this->getReference($tvshow['owner']));
            $slug = $this->slugger->slug($program->getTitle());
            $program->setSlug($slug);
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
