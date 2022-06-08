<?php

namespace Pixel\ReviewBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Sulu\Bundle\CategoryBundle\Entity\CategoryInterface;
use Sulu\Bundle\MediaBundle\Entity\MediaInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="review")
 * @ORM\Entity(repositoryClass="Pixel\ReviewBundle\Repository\ReviewRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Review
{
    public const RESOURCE_KEY = "reviews";
    public const LIST_KEY = "reviews";
    public const FORM_KEY = "review_details";
    public const SECURITY_CONTEXT = "review.reviews";

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Expose()
     */
    private string $name;

    /**
     * @ORM\Column(type="date_immutable")
     * @Serializer\Expose()
     */
    private \DateTimeImmutable $date;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     */
    private int $rating;

    /**
     * @ORM\ManyToOne(targetEntity=CategoryInterface::class)
     * @Serializer\Expose()
     */
    private CategoryInterface $platform;

    /**
     * @ORM\ManyToOne(targetEntity=CategoryInterface::class)
     * @Serializer\Expose()
     */
    private CategoryInterface $category;

    /**
     * @ORM\ManyToOne(targetEntity=MediaInterface::class)
     * @Serializer\Expose()
     */
    private ?MediaInterface $clientImage = null;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Serializer\Expose()
     */
    private ?bool $isActive;

    /**
     * @var Collection<string, ReviewTranslation>
     * @ORM\OneToMany(targetEntity="Pixel\ReviewBundle\Entity\ReviewTranslation", mappedBy="review", cascade={"ALL"}, indexBy="locale")
     * @Serializer\Exclude()
     */
    private $translations;

    /**
     * @var string
     */
    private string $locale = "fr";

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param \DateTimeImmutable $date
     */
    public function setDate(\DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     */
    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    /**
     * @return CategoryInterface
     */
    public function getPlatform(): CategoryInterface
    {
        return $this->platform;
    }

    /**
     * @param CategoryInterface $platform
     */
    public function setPlatform(CategoryInterface $platform): void
    {
        $this->platform = $platform;
    }

    /**
     * @return CategoryInterface
     */
    public function getCategory(): CategoryInterface
    {
        return $this->category;
    }

    /**
     * @param CategoryInterface $category
     */
    public function setCategory(CategoryInterface $category): void
    {
        $this->category = $category;
    }

    /**
     * @return MediaInterface|null
     */
    public function getClientImage(): ?MediaInterface
    {
        return $this->clientImage;
    }

    /**
     * @param MediaInterface|null $clientImage
     */
    public function setClientImage(?MediaInterface $clientImage): void
    {
        $this->clientImage = $clientImage;
    }

    /**
     * @return bool|null
     */
    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    /**
     * @param bool|null $isActive
     */
    public function setIsActive(?bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    protected function createTranslation(string $locale): ReviewTranslation
    {
        $translation = new ReviewTranslation($this, $this->locale);
        $this->translations->set($locale, $translation);
        return $translation;
    }

    protected function getTranslation(string $locale): ?ReviewTranslation
    {
        if(!$this->translations->containsKey($locale)){
            return null;
        }
        return $this->translations->get($locale);
    }

    protected function getTranslations(): array
    {
        return $this->translations->toArray();
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @Serializer\VirtualProperty(name="message")
     * @return string|null
     */
    public function getMessage(): ?string
    {
        $translation = $this->getTranslation($this->locale);
        if(!$translation){
            return null;
        }
        return $translation->getMessage();
    }

    public function setMessage(string $message): self
    {
        $translation = $this->getTranslation($this->locale);
        if(!$translation){
            $translation = $this->createTranslation($this->locale);
        }
        $translation->setMessage($message);
        return $this;
    }
}
