<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

final class CategoryFixtures extends Fixture implements FixtureGroupInterface
{
    public const CATEGORIES = [
        'Culture',
        'Economie',
        'Faits divers',
        'France',
        'International',
        'People',
        'Politique',
        'Sports',
        'Tech',
    ];

    public function __construct(
        private SluggerInterface $slugger,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        foreach (self::CATEGORIES as $index => $name) {
            $category = new Category();

            $category->setName($name);
            $category->setSlug($this->slugger->slug($name)->lower()->toString());

            // Description nullable : on n'en met pas à chaque fois
            if ($faker->boolean(50)) {
                $category->setDescription($faker->paragraphs(2, true));
            }

            $manager->persist($category);

            $this->addReference('category_' . $index, $category);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['category'];
    }
}