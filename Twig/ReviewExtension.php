<?php

namespace Pixel\ReviewBundle\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Pixel\ReviewBundle\Entity\Review;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\Environment;

class ReviewExtension extends AbstractExtension
{
    private EntityManagerInterface $entityManager;
    private Environment $environment;

    public function __construct(EntityManagerInterface $entityManager, Environment $environment)
    {
        $this->entityManager = $entityManager;
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction("get_latest_reviews_html", [$this, "getLatestReviewsHtml"], ["is_safe" => ["html"]]),
            new TwigFunction("get_latest_reviews", [$this, "getLatestReviews"], ["is_safe" => ["html"]])
        ];
    }

    public function getLatestReviewsHtml(int $limit = 3)
    {
        $reviews = $this->entityManager->getRepository(Review::class)->getLatestReviews($limit);
        return $this->environment->render("@Review/twig/reviews.html.twig", ["reviews" => $reviews]);
    }

    public function getLatestReviews(int $limit = 3)
    {
        return $this->entityManager->getRepository(Review::class)->getLatestReviews($limit);
    }
}
