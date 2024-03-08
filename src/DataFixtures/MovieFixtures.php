<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $movie = new Movie();
        $movie->setTitle('How to train yout dragon');
        $movie->setReleaseYear('2012');
        $movie->setDescription('Movie about training your dragon');
        $movie->setImagePath('https://external-content.duckduckgo.com/iu/?u=http%3A%2F%2Fwww.hdwallpapers.in%2Fwalls%2Fhow_to_train_your_dragon_2_poster-wide.jpg&f=1&nofb=1&ipt=e91e52153904393c177f24f8dd83056f48b637d3700e09831f1f7efe5aa0c902&ipo=images');
        $movie->addActor($this->getReference('actor_1'));
        $movie->addActor($this->getReference('actor_2'));
        $manager->persist($movie);
        $movie2 = new Movie();
        $movie2->setTitle('Batman');
        $movie2->setReleaseYear('2008');
        $movie2->setDescription('Movie about a bilionare punshin poor people');
        $movie2->setImagePath('https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fimages.hdqwalls.com%2Fdownload%2Fbatman-arkham-origins-2021-5k-qw-2560x1440.jpg&f=1&nofb=1&ipt=75d1ccf2fb2e0a268a05a53ff8e1437448a56b57a5ccc4c6702aef810c8680a2&ipo=images');
        $movie2->addActor($this->getReference('actor_3'));
        $movie2->addActor($this->getReference('actor_4'));
        $manager->persist($movie2);

        $manager->flush();
    }
}