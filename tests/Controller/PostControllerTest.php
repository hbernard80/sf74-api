<?php

namespace App\Tests\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class PostControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;

    /** @var EntityRepository<Post> */
    private EntityRepository $postRepository;
    private string $path = '/post/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->postRepository = $this->manager->getRepository(Post::class);

        foreach ($this->postRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Post index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'post[title]' => 'Testing',
            'post[slug]' => 'Testing',
            'post[content]' => 'Testing',
            'post[created_at]' => 'Testing',
            'post[updated_at]' => 'Testing',
            'post[category]' => 'Testing',
        ]);

        self::assertResponseRedirects('/post');

        self::assertSame(1, $this->postRepository->count([]));

        $this->markTestIncomplete('This test was generated');
    }

    public function testShow(): void
    {
        $fixture = new Post();
        $fixture->setTitle('My Title');
        $fixture->setSlug('My Title');
        $fixture->setContent('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setCategory('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Post');

        // Use assertions to check that the properties are properly displayed.
        $this->markTestIncomplete('This test was generated');
    }

    public function testEdit(): void
    {
        $fixture = new Post();
        $fixture->setTitle('Value');
        $fixture->setSlug('Value');
        $fixture->setContent('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setCategory('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'post[title]' => 'Something New',
            'post[slug]' => 'Something New',
            'post[content]' => 'Something New',
            'post[created_at]' => 'Something New',
            'post[updated_at]' => 'Something New',
            'post[category]' => 'Something New',
        ]);

        self::assertResponseRedirects('/post');

        $fixture = $this->postRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getSlug());
        self::assertSame('Something New', $fixture[0]->getContent());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getCategory());

        $this->markTestIncomplete('This test was generated');
    }

    public function testRemove(): void
    {
        $fixture = new Post();
        $fixture->setTitle('Value');
        $fixture->setSlug('Value');
        $fixture->setContent('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setCategory('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/post');
        self::assertSame(0, $this->postRepository->count([]));

        $this->markTestIncomplete('This test was generated');
    }
}
