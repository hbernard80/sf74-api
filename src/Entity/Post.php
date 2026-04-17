<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\Timestampable;
use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
#[UniqueEntity(
    fields: ['slug'],
    message: 'post.slug.unique'
)]
class Post
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'post.title.not_blank')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'post.title.min',
        maxMessage: 'post.title.max'
    )]
    private ?string $title = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: 'post.slug.not_blank')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'post.slug.min',
        maxMessage: 'post.slug.max'
    )]
    #[Assert\Regex(
        pattern: '/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
        message: 'post.slug.invalid'
    )]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'post.content.not_blank')]
    #[Assert\Length(
        min: 10,
        minMessage: 'post.content.min'
    )]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'post.category.not_null')]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'post.author.not_null')]
    private ?User $author = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = trim($title);

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = strtolower(trim($slug));

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = trim($content);

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function __toString(): string
    {
        return $this->title ?? '';
    }
}