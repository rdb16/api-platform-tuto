<?php

namespace App\Entity;

use App\Entity\Category;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PostRepository;
use App\Controller\PostCountController;
use App\Controller\PostPublishController;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:collection']],
    denormalizationContext: ['groups' => ['write:Post']],
    paginationItemsPerPage: 2,
    collectionOperations: [
        'get',
        'post' => [
            'validation_groups' => ['create:Post']
        ],
        'quantityOfPosts' => [
            'method' => 'GET',
            'path' => '/posts/count',
            'controller' => PostCountController::class
        ]
    ],
    itemOperations: [
        'put',
        'delete',
        'get' => [
            'normalization_context' => ['groups' => ['read:collection', 'read:item', 'read:Post']]
        ],
        'publish' => [
            'method' => 'POST',
            'path' => '/posts/{id}/publish',
            'controller' => PostPublishController::class
        ]
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'title' => 'partial'])]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read:collection'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[
        Groups(['read:collection', 'write:Post']),
        Length(min: 5, groups: ['create:Post'])
    ]

    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    #[
        Groups(['read:collection', 'write:Post']),
        Length(min: 5)
    ]
    private $slug;

    #[ORM\Column(type: 'text')]
    #[Groups(['read:item', 'write:Post'])]
    private $content;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['read:item'])]
    private $createdAt;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['read:item'])]
    private $updatedAt;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'posts', cascade: ['all'])]
    #[Groups(['read:item', 'write:Post'])]
    private $category;

    #[ORM\Column(type: 'boolean', options: ['default' => '0'])]
    #[Groups(['read:collection'])]
    private $online = false;

    public static function validationGroups(self $post): array
    {
        return ['create:Post'];
    }

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function isOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(bool $online): self
    {
        $this->online = $online;

        return $this;
    }
}
