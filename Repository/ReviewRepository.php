<?php

namespace Pixel\ReviewBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Pixel\ReviewBundle\Entity\Review;
use Sulu\Component\SmartContent\Orm\DataProviderRepositoryInterface;
use Sulu\Component\SmartContent\Orm\DataProviderRepositoryTrait;

class ReviewRepository extends EntityRepository implements DataProviderRepositoryInterface
{
    use DataProviderRepositoryTrait;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new ClassMetadata(Review::class));
    }

    public function create(string $locale): Review
    {
        $review = new Review();
        $review->setLocale($locale);
        return $review;
    }

    public function save(Review $review): void
    {
        $this->getEntityManager()->persist($review);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id, string $locale): ?Review
    {
        $review = $this->find($id);
        if(!$review){
            return null;
        }
        $review->setLocale($locale);
        return $review;
    }

    public function getLatestReviews(int $limit)
    {
        $query = $this->createQueryBuilder("r")
            ->where("r.isActive = 1")
            ->orderBy("r.date", "desc")
            ->setMaxResults($limit);
        return $query->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function appendJoins(QueryBuilder $queryBuilder, $alias, $locale)
    {
        $queryBuilder->addSelect("category")->leftJoin($alias . ".category", "category");
        //$queryBuilder->addSelect("platform")->leftJoin($alias . ".platform", "platform");
    }

    public function appendCategoriesRelation(QueryBuilder $queryBuilder, $alias)
    {
        return $alias . ".category";
    }
}
