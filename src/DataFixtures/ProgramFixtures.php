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
        ['Title' => 'Le bureau', 'Synopsis' => "Du papier et des hommes", 'poster' => 'https://flxt.tmsimg.com/assets/p185008_b_h9_ai.jpg', 'country' => 'France', 'year' => '2000', 'Category' => 'Comedie',],
        ['Title' => 'Mauvaise cassure', 'Synopsis' => "De la meth et des hommes", 'poster' => 'https://www.pause-canap.com/media/wysiwyg/serie-breaking-bad.JPG', 'country' => 'France', 'year' => '2000', 'Category' => 'Aventure',],
        ['Title' => 'Le fil', 'Synopsis' => "Des gangs et des policiers", 'poster' => 'https://docs.imperium.plus/files/media-GRGQG-GFRXSGFP-QWSP-FMMLS-GFRXSGRXSP-LLPXR-GFRXSGRWLFLS-X-WPPRPWPGSF/w:LFPRFRX!h:X!q:LPRXFM/r:x!g:x!b:x!a:x/a6748ecbd3915aba603d532e8689eece.jpg', 'country' => 'US', 'year' => '2000', 'Category' => 'Action',],
        ['Title' => 'Malcolm au milieu', 'Synopsis' => "Des enfants et des bêtises", 'poster' => 'https://faroutmagazine.co.uk/static/uploads/2022/03/Why-Malcolm-in-the-Middle-was-a-seminal-comedy-1.jpg', 'country' => 'France', 'year' => '2000', 'Category' => 'Comedie',],
        ['Title' => 'Jeu des trônes', 'Synopsis' => "Des dragons et des hommes", 'poster' => 'https://images1.persgroep.net/rcs/8vW-YV_W-zLO35Oa9-AntoAZFjU/diocontent/202364172/_fitwidth/426?appId=038a353bad43ac27fd436dc5419c256b&quality=0.8', 'country' => 'France', 'year' => '2000', 'Category' => 'Fantaisie',],
        ['Title' => 'Communauté', 'Synopsis' => "Des étudiants et du fun", 'poster' => 'https://m.media-amazon.com/images/I/91kbpBO5hhL._RI_.jpg', 'country' => 'France', 'year' => '2000', 'Category' => 'Comedie',],
        ['Title' => 'Blouses', 'Synopsis' => "Des étudiants et des patients", 'poster' => 'https://i.blogs.es/643f56/scrubs_hwhc/1366_2000.jpeg', 'country' => 'France', 'year' => '2000', 'Category' => 'Comedie',],
        ['Title' => 'Parc du sud', 'Synopsis' => "Des enfants et des jurons", 'poster' => 'https://sfractus-images.cleo.media/unsafe/0x400:1056x994/2000x0/images/South-Park-6123.jpg', 'country' => 'France', 'year' => '2000', 'Category' => 'Comedie',],
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
