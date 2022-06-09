<?php

namespace Pixel\ReviewBundle\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Pixel\ReviewBundle\Entity\Review;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\Environment;

class ReviewExtension extends AbstractExtension
{
    private EntityManagerInterface $entityManager;
    private Environment $environment;
    private RequestStack $request;

    public function __construct(EntityManagerInterface $entityManager, Environment $environment, RequestStack $request)
    {
        $this->entityManager = $entityManager;
        $this->environment = $environment;
        $this->request = $request;
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
        $reviews = $this->entityManager->getRepository(Review::class)->getLatestReviews($limit, $this->request->getMainRequest()->getLocale());
        return $this->environment->render("@Review/twig/reviews.html.twig", ["reviews" => $reviews]);
    }

    public function getLatestReviews(int $limit = 3)
    {
        return $this->entityManager->getRepository(Review::class)->getLatestReviews($limit, $this->request->getMainRequest()->getLocale());
    }
}
