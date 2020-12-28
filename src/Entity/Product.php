<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $createAt;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private string $title;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private string $content;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private int $stock;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private string $reference;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private string $brand;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private string $model;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private string $camera;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private int $screenSize;


    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->createAt = new \DateTimeImmutable();
    }


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreateAt(): ?DateTimeInterface
    {
        return $this->createAt;
    }

    /**
     * @param DateTimeInterface $createAt
     * @return $this
     */
    public function setCreateAt(DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getStock(): ?int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     * @return $this
     */
    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getReference(): ?int
    {
        return $this->reference;
    }

    /**
     * @param int $reference
     * @return $this
     */
    public function setReference(int $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBrand(): ?string
    {
        return $this->brand;
    }

    /**
     * @param string $brand
     * @return $this
     */
    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * @param string $model
     * @return $this
     */
    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCamera(): ?string
    {
        return $this->camera;
    }

    /**
     * @param string $camera
     * @return $this
     */
    public function setCamera(string $camera): self
    {
        $this->camera = $camera;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getScreenSize(): ?int
    {
        return $this->screenSize;
    }

    /**
     * @param int $screenSize
     * @return $this
     */
    public function setScreenSize(int $screenSize): self
    {
        $this->screenSize = $screenSize;

        return $this;
    }
}
