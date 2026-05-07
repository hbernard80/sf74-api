<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\Timestampable;
use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attributes\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['post:read']],
    denormalizationContext: ['groups' => ['post:write']],
    order: ['createdAt' => 'DESC'],
    paginationItemsPerPage: 10,
    paginationMaximumItemsPerPage: 50,
    paginationClientItemsPerPage: true
)]
#[ApiFilter(SearchFilter::class, properties: [
    'title' => 'partial',
    'slug' => 'partial',
    'category.id' => 'exact',
    'author.id' => 'exact',
    'publicationStatus' => 'exact',
])]
#[ApiFilter(OrderFilter::class, properties: [
    'id',
    'title',
    'slug',
    'createdAt',
    'updatedAt',
    'publicationStatus',
], arguments: ['orderParameterName' => 'order'])]
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
    #[Groups(['post:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'post.title.not_blank')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'post.title.min',
        maxMessage: 'post.title.max'
    )]
    #[Groups(['post:read', 'post:write'])]
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
    #[Groups(['post:read', 'post:write'])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'post.content.not_blank')]
    #[Assert\Length(
        min: 10,
        minMessage: 'post.content.min'
    )]
    #[Groups(['post:read', 'post:write'])]
    private ?string $content = null;

    #[ORM\Column(enumType: PostPublicationStatus::class)]
    #[Groups(['post:read', 'post:write'])]
    private PostPublicationStatus $publicationStatus = PostPublicationStatus::DRAFT;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'post.category.not_null')]
    #[Groups(['post:read', 'post:write'])]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'post.author.not_null')]
    #[Groups(['post:read'])]
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

    public function getPublicationStatus(): PostPublicationStatus
    {
        return $this->publicationStatus;
    }

    public function setPublicationStatus(PostPublicationStatus $publicationStatus): static
    {
        $this->publicationStatus = $publicationStatus;

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