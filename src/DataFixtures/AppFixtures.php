<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Service\Slugify;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    private $slugify; 

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        $categories = [];
        for ($i = 0; $i < 5; $i++) {
            $category = new Category;
            $category->setName($faker->word())
            ->setSlug($this->slugify->generate($category->getName()));
            $manager->persist($category);
            array_push($categories, $category);
        }

        for ($i = 0; $i < 20; $i++) {
            $article = new Article;
            $article->setTitle($faker->sentence())
            ->setSlug($this->slugify->generate($article->getTitle()))
            ->setContent($faker->paragraph(5))
            ->setCategory($faker->randomElement($categories))
            ->setImage('https://rickandmortyapi.com/api/character/avatar/4.jpeg');
            $manager->persist($article);
        }

        $manager->flush();
    }
}
