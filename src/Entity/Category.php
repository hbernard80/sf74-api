<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[UniqueEntity(
    fields: ['name'],
    message: 'category.name.unique'
)]
#[UniqueEntity(
    fields: ['slug'],
    message: 'category.slug.unique'
)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    #[Assert\NotBlank(message: 'category.name.not_blank')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'category.name.min',
        maxMessage: 'category.name.max'
    )]
    private ?string $name = null;

    #[ORM\Column(length: 120, unique: true)]
    #[Assert\NotBlank(message: 'category.slug.not_blank')]
    #[Assert\Length(
        min: 2,
        max: 120,
        minMessage: 'category.slug.min',
        maxMessage: 'category.slug.max'
    )]
    #[Assert\Regex(
        pattern: '/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
        message: 'category.slug.invalid'
    )]
    private ?string $slug = null;

    #[ORM\Column(length: 1000, nullable: true)]
    #[Assert\Length(
        max: 1000,
        maxMessage: 'category.description.max'
    )]
    private ?string $description = null;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Post::class)]
    private Collection $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = trim($name);

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description !== null ? trim($description) : null;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setCategory($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            if ($post->getCategory() === $this) {
                $post->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}