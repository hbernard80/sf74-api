<?php
// Commande : symfony console doctrine:fixtures:load

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

final class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private SluggerInterface $slugger,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {
            $post = new Post();

            $title = $faker->sentence(6);

            $createdAtMutable = $faker->dateTimeBetween('-6 months', 'now');
            $createdAt = \DateTimeImmutable::createFromMutable($createdAtMutable);

            $updatedAtMutable = $faker->dateTimeBetween($createdAtMutable, 'now');
            $updatedAt = \DateTimeImmutable::createFromMutable($updatedAtMutable);

            $categoryIndex = random_int(0, count(CategoryFixtures::CATEGORIES) - 1);
            /** @var Category $category */
            $category = $this->getReference('category_' . $categoryIndex, Category::class);

             $userIndex = random_int(0, 9);
             
            $post->setTitle($title);
            $post->setSlug($this->slugger->slug($title)->lower()->toString());
            $post->setContent($faker->paragraphs(3, true));
            $post->setCreatedAt($createdAt);
            $post->setUpdatedAt($updatedAt);
            $post->setCategory($category);
            $post->setAuthor($this->getReference('user_' . $userIndex, User::class));
            
            $manager->persist($post);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}